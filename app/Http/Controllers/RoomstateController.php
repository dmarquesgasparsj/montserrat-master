<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Roomstate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class RoomstateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of rooms that need cleaning.
     */
    public function index(): View
    {
        $this->authorize('show-room');
        $rooms = Room::with('location')->whereStatus('C')->get();

        return view('roomstates.index', compact('rooms'));   //
    }

    /**
     * Store a newly created room state and update the room status.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('update-room');
        $data = $request->validate([
            'room_id' => 'required|integer|exists:rooms,id',
            'status' => 'required|string|max:1',
        ]);

        $room = Room::findOrFail($data['room_id']);
        $from = $room->status;
        $room->status = $data['status'];
        $room->save();

        Roomstate::create([
            'room_id' => $room->id,
            'statechange_at' => now(),
            'statusfrom' => $from,
            'statusto' => $data['status'],
        ]);

        return Redirect::back();
    }

    /**
     * Update the specified room status to cleaned ("A").
     */
    public function update(int $room_id): RedirectResponse
    {
        $this->authorize('update-room');
        $room = Room::findOrFail($room_id);
        $from = $room->status;
        $room->status = 'A';
        $room->save();

        Roomstate::create([
            'room_id' => $room->id,
            'statechange_at' => now(),
            'statusfrom' => $from,
            'statusto' => 'A',
        ]);

        return Redirect::route('roomstate.index');
    }
}

