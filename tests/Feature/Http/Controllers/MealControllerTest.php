<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Meal;
use App\Models\Retreat;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class MealControllerTest extends TestCase
{
    use WithFaker;

    #[Test]
    public function index_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('show-meal');
        $meal = Meal::factory()->create();

        $response = $this->actingAs($user)->get(route('meal.index'));

        $response->assertOk();
        $response->assertViewIs('meals.index');
        $response->assertViewHas('meals');
        $response->assertSeeText('Meals');
    }

    #[Test]
    public function create_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('create-meal');

        $response = $this->actingAs($user)->get(route('meal.create'));

        $response->assertOk();
        $response->assertViewIs('meals.create');
        $response->assertViewHas('retreats');
        $response->assertSeeText('Create meal');
    }

    #[Test]
    public function store_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('create-meal');
        $retreat = Retreat::factory()->create();

        $response = $this->actingAs($user)->post(route('meal.store'), [
            'retreat_id' => $retreat->id,
            'meal_date' => now()->format('Y-m-d'),
            'meal_type' => 'Lunch',
            'vegetarian_count' => 1,
            'gluten_free_count' => 2,
            'dairy_free_count' => 3,
        ]);

        $response->assertRedirect(action([\App\Http\Controllers\MealController::class, 'index']));
        $response->assertSessionHas('flash_notification');

        $this->assertDatabaseHas('meals', [
            'retreat_id' => $retreat->id,
            'meal_type' => 'Lunch',
        ]);
    }

    #[Test]
    public function show_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('show-meal');
        $meal = Meal::factory()->create();

        $response = $this->actingAs($user)->get(route('meal.show', [$meal]));

        $response->assertOk();
        $response->assertViewIs('meals.show');
        $response->assertViewHas('meal');
        $response->assertSeeText('Meal details');
    }

    #[Test]
    public function edit_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('update-meal');
        $meal = Meal::factory()->create();

        $response = $this->actingAs($user)->get(route('meal.edit', [$meal]));

        $response->assertOk();
        $response->assertViewIs('meals.edit');
        $response->assertViewHas('meal');
        $response->assertViewHas('retreats');
        $response->assertSeeText('Edit meal');
    }

    #[Test]
    public function update_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('update-meal');
        $meal = Meal::factory()->create();

        $response = $this->actingAs($user)->put(route('meal.update', [$meal]), [
            'id' => $meal->id,
            'retreat_id' => $meal->retreat_id,
            'meal_date' => now()->format('Y-m-d'),
            'meal_type' => 'Dinner',
            'vegetarian_count' => 2,
            'gluten_free_count' => 0,
            'dairy_free_count' => 0,
        ]);

        $response->assertRedirect(action([\App\Http\Controllers\MealController::class, 'show'], $meal->id));
        $response->assertSessionHas('flash_notification');

        $this->assertDatabaseHas('meals', [
            'id' => $meal->id,
            'meal_type' => 'Dinner',
        ]);
    }

    #[Test]
    public function destroy_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('delete-meal');
        $meal = Meal::factory()->create();

        $response = $this->actingAs($user)->delete(route('meal.destroy', [$meal]));

        $response->assertSessionHas('flash_notification');
        $response->assertRedirect(action([\App\Http\Controllers\MealController::class, 'index']));
        $this->assertSoftDeleted($meal);
    }

    #[Test]
    public function report_returns_an_ok_response(): void
    {
        $user = $this->createUserWithPermission('show-meal');
        $meal = Meal::factory()->create();

        $response = $this->actingAs($user)->get(route('report.meal', $meal->retreat_id));

        $response->assertOk();
        $response->assertViewIs('meals.report');
        $response->assertViewHas('retreat');
        $response->assertViewHas('meals');
    }
}
