"""Exercise the isolated Soutelo pilot through its HTTP routes."""
import base64
import datetime
import hashlib
import hmac
import html.parser
import http.cookiejar
import json
import os
import struct
import time
import urllib.error
import urllib.parse
import urllib.request
from concurrent.futures import ThreadPoolExecutor


BASE_URL = "http://127.0.0.1:8088"


class PilotPage(html.parser.HTMLParser):
    def __init__(self, document):
        super().__init__()
        self.token = None
        self.rooms = []
        self.options = {}
        self.account_forms = {}
        self.meals = {}
        self.select = None
        self.day = None
        self.meal = None
        self.feed(document)

    def handle_starttag(self, tag, attrs):
        attrs = dict(attrs)
        if tag == "input" and attrs.get("name") == "_token":
            self.token = attrs["value"]
        if tag == "meta" and attrs.get("name") == "csrf-token":
            self.token = attrs["content"]
        if tag == "td" and attrs.get("data-room-id") not in [None, *self.rooms]:
            self.rooms.append(attrs["data-room-id"])
        if tag == "form" and "data-account-email" in attrs:
            self.account_forms[attrs["data-account-email"]] = urllib.parse.urlsplit(attrs["action"]).path
        if tag == "select":
            self.select = attrs.get("id")
        if tag == "option" and self.select and attrs.get("value", "").isdigit():
            if int(attrs["value"]) > 0:
                self.options.setdefault(self.select, []).append(attrs["value"])
        if tag == "tr" and "data-day" in attrs:
            self.day = datetime.date.fromisoformat(attrs["data-day"])
            self.meals[self.day] = {}
        if tag == "td":
            self.meal = attrs.get("data-meal")

    def handle_endtag(self, tag):
        if tag == "select":
            self.select = None
        if tag == "td":
            self.meal = None
        if tag == "tr":
            self.day = None

    def handle_data(self, data):
        if self.day and self.meal and data.strip():
            self.meals[self.day][self.meal] = int(data)


def post_json(path, data, headers):
    request = urllib.request.Request(
        BASE_URL + path,
        data=json.dumps(data).encode(),
        headers=headers,
        method="POST",
    )
    try:
        with urllib.request.urlopen(request, timeout=20) as response:
            return response.status, json.load(response)
    except urllib.error.HTTPError as error:
        return error.code, json.load(error)


def browser_session():
    cookies = http.cookiejar.CookieJar()
    browser = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cookies))
    return browser, cookies


def login_account(browser, email, password, code=""):
    with browser.open(BASE_URL + "/login", timeout=10) as response:
        page = PilotPage(response.read().decode())
    assert page.token, "Missing login CSRF token"

    credentials = urllib.parse.urlencode({
        "_token": page.token,
        "email": email,
        "password": password,
        "code": code,
    }).encode()
    return browser.open(BASE_URL + "/login", credentials, timeout=15)


def totp(secret):
    step = int(time.time()) // 30
    key = base64.b32decode(secret)
    digest = hmac.new(key, struct.pack(">Q", step), hashlib.sha1).digest()
    offset = digest[-1] & 15
    value = struct.unpack_from(">I", digest, offset)[0] & 0x7fffffff
    return f"{value % 1000000:06d}"


def expect_status(browser, path, expected, headers=None):
    request = urllib.request.Request(BASE_URL + path, headers=headers or {"Accept": "application/json"})
    try:
        with browser.open(request, timeout=10) as response:
            status = response.status
    except urllib.error.HTTPError as error:
        status = error.code
    assert status == expected, (path, status, expected)


def main():
    browser, cookies = browser_session()
    for path in ("/rooms", "/api/events", "/person/1", "/registration/confirm/fake", "/pilot/exportar-reservas"):
        expect_status(browser, path, 401)
    expect_status(browser, "/login", 403, {"Host": "private.example.test", "X-Forwarded-Proto": "https"})

    with browser.open(BASE_URL + "/rooms", timeout=10) as response:
        assert urllib.parse.urlsplit(response.url).path == "/login", "Guest can access rooms"

    admin_email = os.environ["SOUTELO_PILOT_EMAIL"]
    admin_password = os.environ["SOUTELO_PILOT_PASSWORD"]
    with login_account(browser, admin_email, admin_password) as response:
        assert urllib.parse.urlsplit(response.url).path == "/login", "Admin logged in without MFA"
    code = totp(os.environ["SOUTELO_PILOT_TOTP_SECRET"])
    with login_account(browser, admin_email, admin_password, code) as response:
        assert urllib.parse.urlsplit(response.url).path == "/rooms", "Login did not reach rooms"
        assert "no-store" in response.headers["Cache-Control"]
        grid = PilotPage(response.read().decode())
    second_browser, _ = browser_session()
    with login_account(second_browser, admin_email, admin_password, code) as response:
        assert urllib.parse.urlsplit(response.url).path == "/login", "Admin code was accepted twice"
    expect_status(browser, "/registration/confirm/fake", 403)
    expect_status(browser, "/admin/phpinfo", 403)

    staff_sessions = {}
    for role, home in (
        ("reception", "/rooms"), ("kitchen", "/pilot/cozinha"), ("housekeeping", "/pilot/limpeza")
    ):
        staff, _ = browser_session()
        with login_account(staff, role + "@example.test", os.environ["SOUTELO_PILOT_ACCOUNT_PASSWORD"]) as response:
            assert urllib.parse.urlsplit(response.url).path == home, (role, response.url)
            content = response.read().decode()
            if role != "reception":
                assert "Participante de teste" not in content
                for path in ("/person/1", "/rooms", "/api/events", "/contact/1/attachment/test.pdf", "/report/meal_summary/PILOT-1"):
                    expect_status(staff, path, 403)
        expect_status(staff, "/pilot/exportar-reservas", 403)
        expect_status(staff, "/pilot/contas", 403)
        if role == "reception":
            for path in ("/calendar", "/retreat", "/search"):
                expect_status(staff, path, 200)
        staff_sessions[role] = staff

    with browser.open(BASE_URL + "/pilot/exportar-reservas", timeout=10) as response:
        assert "attachment" in response.headers["Content-Disposition"]
        assert "Quarto,Retiro,Chegada" in response.read().decode()
    with browser.open(BASE_URL + "/pilot/contas", timeout=10) as response:
        accounts = PilotPage(response.read().decode())
    change = urllib.parse.urlencode({"_token": accounts.token, "role": "housekeeping"}).encode()
    with browser.open(BASE_URL + accounts.account_forms["kitchen@example.test"], change, timeout=10) as response:
        audit = response.read().decode()
        assert "Perfil alterado" in audit and admin_email in audit
    expect_status(staff_sessions["kitchen"], "/pilot/cozinha", 401)
    print("Guest restrictions, role permissions, MFA replay protection, export and session revocation passed.")

    with browser.open(BASE_URL + "/report/meal_summary/PILOT-1", timeout=10) as response:
        report = PilotPage(response.read().decode())

    with browser.open(BASE_URL + "/api/events", timeout=10) as response:
        events = json.load(response)
    assert any(event["title"] == "Grupo de teste 1" for event in events), events
    assert all(set(event) == {"id", "title", "start", "end"} for event in events)

    friday, saturday, sunday = sorted(report.meals)
    assert report.meals[friday] == {"Breakfast": 0, "Lunch": 0, "Snack": 0, "Dinner": 1}
    assert report.meals[saturday] == {"Breakfast": 1, "Lunch": 1, "Snack": 1, "Dinner": 1}
    assert report.meals[sunday] == {"Breakfast": 1, "Lunch": 0, "Snack": 0, "Dinner": 0}
    print("Meal service counts passed.")

    room_one, room_two = grid.rooms
    contact = grid.options["reservation-contact-id"][0]
    event = grid.options["reservation-event-id"][0]
    assert grid.token, "Missing grid CSRF token"
    headers = {
        "Accept": "application/json",
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": grid.token,
        "Cookie": "; ".join(f"{cookie.name}={cookie.value}" for cookie in cookies),
    }

    def reserve(room, start, end):
        return post_json("/rooms/create-reservation", {
            "room_id": room,
            "start_date": start.isoformat(),
            "end_date": end.isoformat(),
            "contact_id": contact,
            "event_id": event,
        }, headers)

    status, result = reserve(room_one, friday, sunday)
    assert status == 422 and "room_id" in result["errors"], result
    status, result = reserve(room_two, friday, sunday)
    assert status == 200, result
    registration = result["registration_id"]
    status, result = reserve(room_two, friday, sunday)
    assert status == 422 and "room_id" in result["errors"], result
    status, result = post_json("/rooms/move-reservation", {
        "room_id": room_one,
        "registration_id": registration,
    }, headers)
    assert status == 409, result
    status, result = post_json("/retreat/room_update", {
        "retreat_id": event,
        "registrations": {str(registration): room_one},
    }, headers)
    assert status == 422 and "room_id" in result["errors"], result

    monday = sunday + datetime.timedelta(days=1)
    with ThreadPoolExecutor(max_workers=2) as pool:
        attempts = [pool.submit(reserve, room_one, sunday, monday) for _ in range(2)]
        statuses = sorted(attempt.result()[0] for attempt in attempts)
    assert statuses == [200, 422], statuses
    print("Room capacity, moves, checkout and concurrent bookings passed.")


if __name__ == "__main__":
    main()
