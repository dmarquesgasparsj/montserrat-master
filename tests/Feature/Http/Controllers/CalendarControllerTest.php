<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Retreat;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class CalendarControllerTest extends TestCase
{
    #[Test]
    public function index_displays_view(): void
    {
        $user = $this->createUserWithPermission('show-retreat');
        $response = $this->actingAs($user)->get(route('calendar'));
        $response->assertOk();
        $response->assertViewIs('calendar.index');
    }

    #[Test]
    public function events_returns_json(): void
    {
        $user = $this->createUserWithPermission('show-retreat');
        $events = Retreat::factory()->count(2)->create();
        $response = $this->actingAs($user)->getJson(route('api.events'));
        $response->assertOk();
        $response->assertJsonStructure([
            ['id', 'title', 'start', 'end']
        ]);
        $response->assertJsonFragment(['id' => $events[0]->id]);
    }
}
