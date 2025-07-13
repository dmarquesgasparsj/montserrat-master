<?php

namespace Tests\Feature\Http\Controllers;

use PHPUnit\Framework\Attributes\Test;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class RoomstateControllerTest extends TestCase
{
    use withFaker;

    #[Test]
    public function checkout_marks_room_needs_cleaning(): void
    {
        $user = $this->createUserWithPermission('update-registration');
        $retreat = \App\Models\Retreat::factory()->create([
            'start_date' => Carbon::now()->subDays(3),
            'end_date' => Carbon::now()->subDay(),
        ]);
        $room = \App\Models\Room::factory()->create(['status' => 'A']);
        $registration = \App\Models\Registration::factory()->create([
            'event_id' => $retreat->id,
            'canceled_at' => null,
            'arrived_at' => Carbon::now()->subDays(2),
            'departed_at' => null,
            'room_id' => $room->id,
        ]);

        $this->actingAs($user)->get(route('retreat.checkout', ['id' => $retreat->id]));
        $room->refresh();

        $this->assertEquals('C', $room->status);
        $this->assertDatabaseHas('roomstates', [
            'room_id' => $room->id,
            'statusto' => 'C',
        ]);
    }

    #[Test]
    public function update_marks_room_clean(): void
    {
        $user = $this->createUserWithPermission('update-room');
        $room = \App\Models\Room::factory()->create(['status' => 'C']);

        $response = $this->actingAs($user)->put(route('roomstate.update', $room->id));

        $room->refresh();
        $response->assertRedirect(route('roomstate.index'));
        $this->assertEquals('A', $room->status);
        $this->assertDatabaseHas('roomstates', [
            'room_id' => $room->id,
            'statusto' => 'A',
        ]);
    }
}
