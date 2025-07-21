<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMealRequest;
use App\Http\Requests\UpdateMealRequest;
use App\Models\Meal;
use App\Models\Retreat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MealController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $this->authorize('show-meal');
        $meals = Meal::with('retreat')->orderBy('meal_date')->get();

        return view('meals.index', compact('meals'));   //
    }

    public function create(): View
    {
        $this->authorize('create-meal');
        $retreats = Retreat::orderBy('start_date')->pluck('title', 'id');

        return view('meals.create', compact('retreats'));   //
    }

    public function store(StoreMealRequest $request): RedirectResponse
    {
        $this->authorize('create-meal');
        $meal = Meal::create($request->validated());

        flash('Meal added')->success();

        return Redirect::action([self::class, 'index']);
    }

    public function show(int $id): View
    {
        $this->authorize('show-meal');
        $meal = Meal::with('retreat')->findOrFail($id);

        return view('meals.show', compact('meal'));   //
    }

    public function edit(int $id): View
    {
        $this->authorize('update-meal');
        $meal = Meal::findOrFail($id);
        $retreats = Retreat::orderBy('start_date')->pluck('title', 'id');

        return view('meals.edit', compact('meal', 'retreats'));   //
    }

    public function update(UpdateMealRequest $request, int $id): RedirectResponse
    {
        $this->authorize('update-meal');
        $meal = Meal::findOrFail($id);
        $meal->update($request->validated());

        flash('Meal updated')->success();

        return Redirect::action([self::class, 'show'], $meal->id);
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->authorize('delete-meal');
        $meal = Meal::findOrFail($id);
        Meal::destroy($id);

        flash('Meal deleted')->warning()->important();

        return Redirect::action([self::class, 'index']);
    }

    public function report(int $retreat_id): View
    {
        $this->authorize('show-meal');
        $retreat = Retreat::findOrFail($retreat_id);
        $meals = Meal::where('retreat_id', $retreat_id)->orderBy('meal_date')->get();

        return view('meals.report', compact('retreat', 'meals'));   //
    }
}
