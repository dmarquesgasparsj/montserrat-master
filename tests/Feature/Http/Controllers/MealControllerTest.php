<?php

namespace Tests\Feature\Http\Controllers;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\MealController
 */
final class MealControllerTest extends TestCase
{
    #[Test]
    public function store_uses_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\MealController::class,
            'store',
            \App\Http\Requests\StoreMealRequest::class
        );
    }

    #[Test]
    public function update_uses_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\MealController::class,
            'update',
            \App\Http\Requests\UpdateMealRequest::class
        );
    }
}
