"""Exercise the isolated Soutelo pilot through its HTTP routes."""
import datetime
import html.parser
import http.cookiejar
import json
import os
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


def main():
    cookies = http.cookiejar.CookieJar()
    browser = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cookies))

    with browser.open(BASE_URL + "/rooms", timeout=10) as response:
        assert urllib.parse.urlsplit(response.url).path == "/login", "Guest can access rooms"
        login = PilotPage(response.read().decode())
    assert login.token, "Missing login CSRF token"

    credentials = urllib.parse.urlencode({
        "_token": login.token,
        "email": os.environ["SOUTELO_PILOT_EMAIL"],
        "password": os.environ["SOUTELO_PILOT_PASSWORD"],
    }).encode()
    with browser.open(BASE_URL + "/login", credentials, timeout=15) as response:
        assert urllib.parse.urlsplit(response.url).path == "/rooms", "Login did not reach rooms"
        grid = PilotPage(response.read().decode())

    with browser.open(BASE_URL + "/report/meal_summary/PILOT-1", timeout=10) as response:
        report = PilotPage(response.read().decode())

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

    monday = sunday + datetime.timedelta(days=1)
    with ThreadPoolExecutor(max_workers=2) as pool:
        attempts = [pool.submit(reserve, room_one, sunday, monday) for _ in range(2)]
        statuses = sorted(attempt.result()[0] for attempt in attempts)
    assert statuses == [200, 422], statuses
    print("Room capacity, moves, checkout and concurrent bookings passed.")


if __name__ == "__main__":
    main()
