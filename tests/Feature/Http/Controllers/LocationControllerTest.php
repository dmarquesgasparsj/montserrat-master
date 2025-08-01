<?php

namespace Tests\Feature\Http\Controllers;

use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\LocationController
 */
final class LocationControllerTest extends TestCase
{
    // use DatabaseTransactions;
    use withFaker;

    #[Test]
    public function create_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('create-location');

        $response = $this->actingAs($user)->get(route('location.create'));

        $response->assertOk();
        $response->assertViewIs('admin.locations.create');
        $response->assertSeeText(__('messages.create_location_title'));
        $response->assertViewHas('parents');
        $response->assertViewHas('rooms');
        $response->assertViewHas('location_types');
    }

    #[Test]
    public function destroy_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('delete-location');
        $location = \App\Models\Location::factory()->create();

        $response = $this->actingAs($user)->delete(route('location.destroy', [$location]));
        $response->assertSessionHas('flash_notification');

        $response->assertRedirect(action([\App\Http\Controllers\LocationController::class, 'index']));
        $this->assertSoftDeleted($location);
    }

    #[Test]
    public function edit_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('update-location');
        $location = \App\Models\Location::factory()->create();

        $response = $this->actingAs($user)->get(route('location.edit', [$location]));

        $response->assertOk();
        $response->assertViewIs('admin.locations.edit');
        $response->assertViewHas('location');
        $response->assertViewHas('location_types');
        $response->assertViewHas('parents');
        $response->assertViewHas('rooms');
        $response->assertSeeText(__('messages.edit_location_title'));

        $this->assertTrue($this->findFieldValueInResponseContent('name', $location->name, 'text', $response->getContent()));
        $this->assertTrue($this->findFieldValueInResponseContent('label', $location->label, 'text', $response->getContent()));
        $this->assertTrue($this->findFieldValueInResponseContent('type', $location->type, 'select', $response->getContent()));
        $this->assertTrue($this->findFieldValueInResponseContent('description', $location->description, 'textarea', $response->getContent()));
        $this->assertTrue($this->findFieldValueInResponseContent('latitude', number_format($location->latitude, 8), 'text', $response->getContent()));
        $this->assertTrue($this->findFieldValueInResponseContent('longitude', number_format($location->longitude, 8), 'text', $response->getContent()));
        $this->assertTrue($this->findFieldValueInResponseContent('occupancy', $location->occupancy, 'text', $response->getContent()));
        $this->assertTrue($this->findFieldValueInResponseContent('room_id', $location->room_id, 'select', $response->getContent()));
        $this->assertTrue($this->findFieldValueInResponseContent('parent_id', $location->parent_id, 'select', $response->getContent()));
        $this->assertTrue($this->findFieldValueInResponseContent('notes', $location->notes, 'textarea', $response->getContent()));
    }

    #[Test]
    public function index_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('show-location');

        $response = $this->actingAs($user)->get(route('location.index'));

        $response->assertOk();
        $response->assertViewIs('admin.locations.index');
        $response->assertViewHas('locations');
        $response->assertViewHas('location_types');
        $response->assertSeeText(__('messages.locations_index_title'));
    }

    #[Test]
    public function index_type_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('show-location');

        $location = \App\Models\Location::factory()->create();

        $number_locations = $this->faker->numberBetween(2, 10);
        $locations = \App\Models\Location::factory()->count($number_locations)->create([
            'type' => $location->type,
            'deleted_at' => null,
        ]);

        $response = $this->actingAs($user)->get('admin/location/type/'.$location->type);
        $results = $response->viewData('locations');
        $response->assertOk();
        $response->assertViewIs('admin.locations.index');
        $response->assertViewHas('locations');
        $response->assertViewHas('location_types');
        $response->assertSeeText(__('messages.locations_index_title'));
        $response->assertSeeText($location->type);
        $this->assertGreaterThan($number_locations, $results->count());
    }

    #[Test]
    public function show_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('show-location');
        $location = \App\Models\Location::factory()->create();

        $response = $this->actingAs($user)->get(route('location.show', [$location]));

        $response->assertOk();
        $response->assertViewIs('admin.locations.show');
        $response->assertViewHas('location');
        $response->assertViewHas('children');
        $response->assertSeeText(__('messages.location_details_title'));
    }

    #[Test]
    public function store_returns_an_ok_response(): void
    {   // $this->withoutExceptionHandling();
        $user = $this->createUserWithPermission('create-location');

        $location_name = $this->faker->word();
        $location_description = $this->faker->sentence(7, true);
        $location_type = $this->faker->randomElement(config('polanco.locations_type'));

        $response = $this->actingAs($user)->post(route('location.store'), [
            'name' => $location_name,
            'label' => $location_name,
            'description' => $location_description,
            'type' => $location_type,
            'latitude' => $this->faker->randomFloat($nbMaxDecimals = 6, $min = -90, $max = 90),
            'longitude' => $this->faker->randomFloat($nbMaxDecimals = 6, $min = -180, $max = 180),
            'occupancy' => $this->faker->numberBetween(0, 100),
        ]);

        $response->assertRedirect(action([\App\Http\Controllers\LocationController::class, 'index']));
        $response->assertSessionHas('flash_notification');

        $this->assertDatabaseHas('locations', [
            'name' => $location_name,
            'description' => $location_description,
            'type' => $location_type,
        ]);
    }

    #[Test]
    public function update_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('update-location');

        $location = \App\Models\Location::factory()->create();

        $location_name = $this->faker->word();
        $location_description = $this->faker->sentence(7, true);
        $location_type = $this->faker->randomElement(config('polanco.locations_type'));

        $original_location_name = $location->name;
        $new_location_name = 'New '.$this->faker->words(2, true);

        $response = $this->actingAs($user)->put(route('location.update', [$location]), [
            'id' => $location->id,
            'name' => $new_location_name,
            'description' => $this->faker->sentence(7, true),
            'type' => $location_type,
        ]);

        $response->assertRedirect(action([\App\Http\Controllers\LocationController::class, 'show'], $location->id));
        $response->assertSessionHas('flash_notification');

        $updated = \App\Models\Location::findOrFail($location->id);

        $this->assertEquals($updated->name, $new_location_name);
        $this->assertNotEquals($updated->name, $original_location_name);
    }

    // test cases...
}
