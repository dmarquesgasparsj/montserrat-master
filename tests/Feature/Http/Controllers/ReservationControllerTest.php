<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Reservation;
use App\Models\Registration;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class ReservationControllerTest extends TestCase
{
    use WithFaker;

    #[Test]
    public function index_returns_an_ok_response(): void
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create();

        $response = $this->actingAs($user)->get(route('reservation.index'));

        $response->assertOk();
        $response->assertJsonFragment(['id' => $reservation->id]);
    }

    #[Test]
    public function store_creates_a_new_reservation(): void
    {
        $user = User::factory()->create();
        $room = Room::factory()->create();
        $registration = Registration::factory()->create();

        $response = $this->actingAs($user)->post(route('reservation.store'), [
            'room_id' => $room->id,
            'registration_id' => $registration->id,
            'start' => now()->toDateTimeString(),
            'end' => now()->addHour()->toDateTimeString(),
            'notes' => 'test notes',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reservations', [
            'room_id' => $room->id,
            'registration_id' => $registration->id,
            'notes' => 'test notes',
        ]);
    }

    #[Test]
    public function edit_returns_an_ok_response(): void
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create();

        $response = $this->actingAs($user)->get(route('reservation.edit', $reservation));

        $response->assertOk();
        $response->assertJsonFragment(['id' => $reservation->id]);
    }

    #[Test]
    public function destroy_deletes_reservation(): void
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create();

        $response = $this->actingAs($user)->delete(route('reservation.destroy', $reservation));

        $response->assertRedirect(route('reservation.index'));
        $this->assertSoftDeleted($reservation);
    }
}
