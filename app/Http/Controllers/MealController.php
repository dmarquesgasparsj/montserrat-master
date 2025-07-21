<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMealRequest;
use App\Http\Requests\UpdateMealRequest;
use App\Models\Meal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class MealController extends Controller
{
    public function store(StoreMealRequest $request): RedirectResponse
    {
        $meal = Meal::create($request->validated());

        return Redirect::action([self::class, 'show'], $meal->id);
    }

    public function update(UpdateMealRequest $request, Meal $meal): RedirectResponse
    {
        $meal->update($request->validated());

        return Redirect::action([self::class, 'show'], $meal->id);
    }

    // Dummy show method for redirects
    public function show(Meal $meal)
    {
        return view('meals.show', compact('meal'));
    }
}
