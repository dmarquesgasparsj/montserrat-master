<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): JsonResponse
    {
        return response()->json(Reservation::all());
    }

    public function create(): JsonResponse
    {
        return response()->json(['status' => 'ok']);
    }

    public function store(Request $request): RedirectResponse
    {
        $reservation = Reservation::create($request->all());

        return redirect()->route('reservation.show', $reservation);
    }

    public function show(Reservation $reservation): JsonResponse
    {
        return response()->json($reservation);
    }

    public function edit(Reservation $reservation): JsonResponse
    {
        return response()->json($reservation);
    }

    public function update(Request $request, Reservation $reservation): RedirectResponse
    {
        $reservation->update($request->all());

        return redirect()->route('reservation.show', $reservation);
    }

    public function destroy(Reservation $reservation): RedirectResponse
    {
        $reservation->delete();

        return redirect()->route('reservation.index');
    }
}
