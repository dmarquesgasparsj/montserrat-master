<?php

namespace Tests\Feature\Http\Controllers;

use PHPUnit\Framework\Attributes\Test;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Mail;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\RoomController
 */
final class RoomControllerTest extends TestCase
{
    // use DatabaseTransactions;
    use withFaker;

    #[Test]
    public function create_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('create-room');

        $response = $this->actingAs($user)->get(route('room.create'));

        $response->assertOk();
        $response->assertViewIs('rooms.create');
        $response->assertViewHas('locations');
        $response->assertViewHas('floors');
        $response->assertSeeText(__('messages.create_room_title'));
    }

    #[Test]
    public function destroy_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('delete-room');
        $room = \App\Models\Room::factory()->create();
        $contact = \App\Models\Contact::factory()->create();
        $event = \App\Models\Retreat::factory()->create();

        $response = $this->actingAs($user)->delete(route('room.destroy', [$room]));

        $response->assertSessionHas('flash_notification');
        $response->assertRedirect(action([\App\Http\Controllers\RoomController::class, 'index']));
        $this->assertSoftDeleted($room);
    }

    #[Test]
    public function edit_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('update-room');
        $room = \App\Models\Room::factory()->create();

        $response = $this->actingAs($user)->get(route('room.edit', [$room]));

        $response->assertOk();
        $response->assertViewIs('rooms.edit');
        $response->assertViewHas('room');
        $response->assertViewHas('locations');
        $response->assertViewHas('floors');
        $room_data = $response->viewData('room');
        $this->assertEquals($room_data->description, $room->description);
        $response->assertSeeText(__('messages.edit_room_title'));

        $this->assertTrue($this->findFieldValueInResponseContent('location_id', $room->location_id, 'select', $response->getContent()));
        $this->assertTrue($this->findFieldValueInResponseContent('floor', $room->floor, 'select', $response->getContent()));
        $this->assertTrue($this->findFieldValueInResponseContent('name', $room->name, 'text', $response->getContent()));
        $this->assertTrue($this->findFieldValueInResponseContent('description', $room->description, 'textarea', $response->getContent()));
        $this->assertTrue($this->findFieldValueInResponseContent('notes', $room->notes, 'textarea', $response->getContent()));
        $this->assertTrue($this->findFieldValueInResponseContent('access', $room->access, 'text', $response->getContent()));
        $this->assertTrue($this->findFieldValueInResponseContent('type', $room->type, 'text', $response->getContent()));
        $this->assertTrue($this->findFieldValueInResponseContent('occupancy', $room->occupancy, 'text', $response->getContent()));
        $this->assertTrue($this->findFieldValueInResponseContent('status', $room->status, 'text', $response->getContent()));

        /*
        {!! Form::select('location_id', $locations, $room->location_id, ['class' => 'col-md-2']) !!}
        {!! Form::select('floor', $floors, $room->floor, ['class' => 'col-md-2']) !!}
        {!! Form::text('name', $room->name, ['class' => 'col-md-2']) !!}
        {!! Form::textarea('description', $room->description, ['class' => 'col-md-5', 'rows'=>'3']) !!}
        {!! Form::textarea('notes', $room->notes, ['class' => 'col-md-5', 'rows'=>'3']) !!}
        {!! Form::text('access', $room->access, ['class' => 'col-md-1']) !!}
        {!! Form::text('type', $room->type, ['class' => 'col-md-1']) !!}
        {!! Form::text('occupancy', $room->occupancy, ['class' => 'col-md-1']) !!}
        {!! Form::text('status', $room->status, ['class' => 'col-md-1']) !!}
         */
    }

    #[Test]
    public function index_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('show-room');

        $response = $this->actingAs($user)->get(route('room.index'));

        $response->assertOk();
        $response->assertViewIs('rooms.index');
        $response->assertViewHas('roomsort');
        $response->assertSeeText(__('messages.room_index_title'));
    }

    #[Test]
    public function index_location_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('show-room');

        $room = \App\Models\Room::factory()->create();

        $number_rooms = $this->faker->numberBetween(2, 10);
        $rooms = \App\Models\Room::factory()->count($number_rooms)->create([
            'location_id' => $room->location_id,
            'deleted_at' => null,
        ]);

        $response = $this->actingAs($user)->get('room/location/'.$room->location_id);
        $results = $response->viewData('roomsort');
        $response->assertOk();
        $response->assertViewIs('rooms.index');
        $response->assertViewHas('roomsort');
        $response->assertViewHas('locations');
        $response->assertSeeText(__('messages.room_index_title'));
        $this->assertGreaterThan($number_rooms, $results->count());
    }

    #[Test]
    public function schedule_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('show-room');

        $response = $this->actingAs($user)->get(route('rooms'));

        $response->assertOk();
        $response->assertViewIs('rooms.sched2');
        $response->assertViewHas('roomsort');
        $response->assertViewHas('dts');
        $response->assertViewHas('m');
        $response->assertViewHas('previous_link');
        $response->assertViewHas('next_link');
        $response->assertViewHas('retreats');
        $response->assertViewHas('retreatants');
        $dts = $response->viewData('dts');
        $response->assertSeeText(__('messages.room_schedules_for', ['start' => $dts[0]->format('F d, Y'), 'end' => $dts[31]->format('F d, Y')]));
    }

    #[Test]
    public function schedule_with_hyphenated_date_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('show-room');
        $yesterday = Carbon::now()->subDay()->toDateString();

        $response = $this->actingAs($user)->get(route('rooms', ['ymd' => $yesterday]));

        $response->assertOk();
        $response->assertViewIs('rooms.sched2');
        $response->assertViewHas('roomsort');
        $response->assertViewHas('dts');
        $response->assertViewHas('m');
        $response->assertViewHas('previous_link');
        $response->assertViewHas('next_link');
        $dts = $response->viewData('dts');
        $response->assertSeeText(__('messages.room_schedules_for', ['start' => $dts[0]->format('F d, Y'), 'end' => $dts[31]->format('F d, Y')]));
    }

    #[Test]
    public function schedule_with_unhyphenated_date_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('show-room');
        $yesterday = Carbon::now()->subDay()->toDateString();
        // remove hyphens
        $yesterday = str_replace('-', '', $yesterday);

        $response = $this->actingAs($user)->get(route('rooms', ['ymd' => $yesterday]));

        $response->assertOk();
        $response->assertViewIs('rooms.sched2');
        $response->assertViewHas('roomsort');
        $response->assertViewHas('dts');
        $response->assertViewHas('m');
        $response->assertViewHas('previous_link');
        $response->assertViewHas('next_link');
        $dts = $response->viewData('dts');
        $response->assertSeeText(__('messages.room_schedules_for', ['start' => $dts[0]->format('F d, Y'), 'end' => $dts[31]->format('F d, Y')]));
    }

    #[Test]
    public function schedule_contains_reservation_modal(): void
    {
        $user = $this->createUserWithPermission('show-room');

        $response = $this->actingAs($user)->get(route('rooms'));

        $response->assertOk();
        $response->assertViewHas('retreats');
        $response->assertViewHas('retreatants');
        $response->assertSee('id="reservationModal"', false);
        $response->assertSee('id="reservationForm"', false);
        $response->assertSee('name="contact_id"', false);
        $response->assertSee('name="event_id"', false);
    }

    #[Test]
    public function show_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('show-room');
        $room = \App\Models\Room::factory()->create();

        $response = $this->actingAs($user)->get(route('room.show', [$room]));

        $response->assertOk();
        $response->assertViewIs('rooms.show');
        $response->assertViewHas('room');
        $response->assertSeeText($room->description);
    }

    #[Test]
    public function store_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('create-room');

        $location = \App\Models\Location::factory()->create();
        $name = 'New '.$this->faker->lastName().' Suite';
        $description = $this->faker->catchPhrase();

        $response = $this->actingAs($user)->post(route('room.store'), [
            'location_id' => $location->id,
            'floor' => $this->faker->numberBetween($min = 1, $max = 2),
            'name' => $name,
            'description' => $description,
            'notes' => $this->faker->sentence(),
            'access' => $this->faker->word(),
            'type' => $this->faker->word(),
            'occupancy' => $this->faker->randomDigitNotNull(),
            'status' => $this->faker->word(),
        ]);

        $response->assertSessionHas('flash_notification');
        $response->assertRedirect(action([\App\Http\Controllers\RoomController::class, 'index']));
        $this->assertDatabaseHas('rooms', [
            'name' => $name,
            'description' => $description,
            'location_id' => $location->id,
        ]);
    }

    #[Test]
    public function store_validates_with_a_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\RoomController::class,
            'store',
            \App\Http\Requests\StoreRoomRequest::class
        );
    }

    #[Test]
    public function update_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('update-room');
        $room = \App\Models\Room::factory()->create();

        $original_description = $room->description;
        $new_location = \App\Models\Location::factory()->create();
        $new_name = 'Renovated '.$this->faker->lastName().' Suite';
        $new_description = $this->faker->catchPhrase();

        $response = $this->actingAs($user)->put(route('room.update', [$room]), [
            'id' => $room->id,
            'location_id' => $new_location->id,
            'name' => $new_name,
            'description' => $new_description,
        ]);

        $room->refresh();
        $response->assertSessionHas('flash_notification');
        $response->assertRedirect(action([\App\Http\Controllers\RoomController::class, 'index']));
        $this->assertEquals($new_description, $room->description);
        $this->assertNotEquals($original_description, $room->description);
    }

    #[Test]
    public function update_validates_with_a_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\RoomController::class,
            'update',
            \App\Http\Requests\UpdateRoomRequest::class
        );
    }

    #[Test]
    public function move_reservation_updates_the_room(): void
    {
        $user = $this->createUserWithPermission('update-registration');
        $roomA = \App\Models\Room::factory()->create();
        $roomB = \App\Models\Room::factory()->create();
        $registration = \App\Models\Registration::factory()->create([
            'room_id' => $roomA->id,
        ]);

        $response = $this->actingAs($user)->post(route('rooms.move-reservation'), [
            'registration_id' => $registration->id,
            'room_id' => $roomB->id,
            'date' => now()->toDateString(),
        ]);

        $response->assertJson(['status' => 'ok']);
        $registration->refresh();
        $this->assertEquals($roomB->id, $registration->room_id);
    }

    #[Test]

    public function create_reservation_creates_a_registration(): void
    {
        $user = $this->createUserWithPermission('create-registration');
        $room = \App\Models\Room::factory()->create();
        $contact = \App\Models\Contact::factory()->create();
        $event = \App\Models\Retreat::factory()->create();

        $start = now()->toDateString();
        $end = now()->addDay()->toDateString();

        $response = $this->actingAs($user)->postJson(route('rooms.create-reservation'), [
            'room_id' => $room->id,
            'contact_id' => $contact->id,
            'event_id' => $event->id,
            'start_date' => $start,
            'end_date' => $end,
        ]);

        $response->assertJson(['status' => 'ok']);
        $this->assertDatabaseHas('participant', [
            'room_id' => $room->id,
        ]);

    }

    #[Test]
    public function create_reservation_queues_a_confirmation_email(): void
    {
        Mail::fake();

        $user = $this->createUserWithPermission('create-registration');
        $room = \App\Models\Room::factory()->create();
        $contact = \App\Models\Contact::factory()->create();
        \App\Models\Email::factory()->create([
            'contact_id' => $contact->id,
            'is_primary' => 1,
        ]);
        $event = \App\Models\Retreat::factory()->create();

        $start = now()->toDateString();
        $end = now()->addDay()->toDateString();

        $this->actingAs($user)->postJson(route('rooms.create-reservation'), [
            'room_id' => $room->id,
            'contact_id' => $contact->id,
            'event_id' => $event->id,
            'start_date' => $start,
            'end_date' => $end,
        ])->assertJson(['status' => 'ok']);

        Mail::assertQueued(\App\Mail\ReservationConfirmation::class);

    }

    public function move_reservation_returns_error_on_conflict(): void
    {
        $user = $this->createUserWithPermission('update-registration');

        $retreat = \App\Models\Retreat::factory()->create([
            'start_date' => now()->addDays(1),
            'end_date' => now()->addDays(3),
        ]);

        $roomA = \App\Models\Room::factory()->create();
        $roomB = \App\Models\Room::factory()->create();

        $registrationA = \App\Models\Registration::factory()->create([
            'room_id' => $roomA->id,
            'event_id' => $retreat->id,
        ]);

        $registrationB = \App\Models\Registration::factory()->create([
            'room_id' => $roomB->id,
            'event_id' => $retreat->id,
        ]);

        $response = $this->actingAs($user)->post(route('rooms.move-reservation'), [
            'registration_id' => $registrationB->id,
            'room_id' => $roomA->id,
            'date' => now()->addDay()->toDateString(),
        ]);

        $response->assertStatus(409);
        $response->assertJson(['status' => 'error']);
        $registrationB->refresh();
        $this->assertEquals($roomB->id, $registrationB->room_id);

    }

    #[Test]
    public function schedule_filters_retreats_by_end_date(): void
    {
        Carbon::setTestNow(Carbon::create(2025, 7, 15));
        $user = $this->createUserWithPermission('show-room');

        $past = \App\Models\Retreat::factory()->create([
            'start_date' => Carbon::create(2025, 7, 1),
            'end_date' => Carbon::create(2025, 7, 5),
        ]);
        $future = \App\Models\Retreat::factory()->create([
            'start_date' => Carbon::create(2025, 7, 20),
            'end_date' => Carbon::create(2025, 7, 22),
        ]);

        $response = $this->actingAs($user)->get(route('rooms', ['ymd' => '2025-07-01']));

        $response->assertOk();
        $retreats = $response->viewData('retreats');
        $this->assertTrue($retreats->has($future->id));
        $this->assertFalse($retreats->has($past->id));

        Carbon::setTestNow();
    }

    // test cases...
}
