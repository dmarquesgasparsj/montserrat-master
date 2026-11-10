<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Registration;
use App\Services\RoomAssignments;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Contact;
use App\Models\Retreat;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationConfirmation;
use DateTime;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('show-room');
        // TODO: consider eager loading building name and sorting on room.location.name
        $rooms = \App\Models\Room::with('location')->get();
        $roomsort = $rooms->sortBy(function ($building) {
            return sprintf('%-12s%s', $building->location->name, $building->name);
        });
        $location_ids = \App\Models\Room::distinct()->pluck('location_id');
        $locations = \App\Models\Location::whereIn('id', $location_ids)
            ->whereNotIn('type', ['Chapel', 'Dining Room'])
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('rooms.index', compact('roomsort', 'locations'));   //
    }

    public function index_location($location_id = null): View
    {
        $this->authorize('show-room');

        $location_ids = \App\Models\Room::distinct()->pluck('location_id');
        $locations = \App\Models\Location::whereIn('id', $location_ids)
            ->whereNotIn('type', ['Chapel', 'Dining Room'])
            ->orderBy('name')
            ->pluck('name', 'id');

        $rooms = \App\Models\Room::with('location')->whereLocationId($location_id)->get();
        $roomsort = $rooms->sortBy(function ($room) {
            return sprintf('%-12s%s', $room->location->name, $room->name);
        });

        return view('rooms.index', compact('roomsort', 'locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create-room');
        $locations = \App\Models\Location::orderby('name')->pluck('name', 'id');
        $floors = $this->get_floors();

        return view('rooms.create', compact('locations', 'floors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request): RedirectResponse
    {
        $this->authorize('create-room');

        $room = new \App\Models\Room;
        $room->location_id = $request->input('location_id');
        $room->name = $request->input('name');
        $room->description = $request->input('description');
        $room->notes = $request->input('notes');
        $room->access = $request->input('access');
        $room->type = $request->input('type');
        $room->occupancy = $request->input('occupancy');
        $room->status = $request->input('status');
        $room->floor = $request->input('floor');
        $room->save();

        flash('Room: <a href="'.url('/room/'.$room->id).'">'.$room->name.'</a> added')->success();

        return Redirect::action([self::class, 'index']);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $this->authorize('show-room');
        $room = \App\Models\Room::findOrFail($id);
        $building = \App\Models\Room::findOrFail($id)->location;
        $room->building = $building->name;

        return view('rooms.show', compact('room')); //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $this->authorize('update-room');
        $locations = \App\Models\Location::orderby('name')->pluck('name', 'id');
        $floors = $this->get_floors();
        $room = \App\Models\Room::findOrFail($id);

        return view('rooms.edit', compact('room', 'locations', 'floors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, int $id): RedirectResponse
    {
        $this->authorize('update-room');

        $room = \App\Models\Room::findOrFail($request->input('id'));
        $room->location_id = $request->input('location_id');
        $room->name = $request->input('name');
        $room->description = $request->input('description');
        $room->notes = $request->input('notes');
        $room->access = $request->input('access');
        $room->type = $request->input('type');
        $room->occupancy = $request->input('occupancy');
        $room->status = $request->input('status');
        $room->floor = $request->input('floor');
        $room->save();

        flash('Room: <a href="'.url('/room/'.$room->id).'">'.$room->name.'</a> updated')->success();

        return Redirect::action([self::class, 'index']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->authorize('delete-room');
        $room = \App\Models\Room::findOrFail($id);

        \App\Models\Room::destroy($id);

        flash('Room: '.$room->name.' deleted')->warning()->important();

        return Redirect::action([self::class, 'index']);
    }

    /**
     * Generate an array of floors.
     *
     * @return array
     */
    public function get_floors()
    {
        $floors = collect([]);
        $max_floors = config('polanco.rooms.max_floors');
        $floors->prepend('N/A', 0);
        for ($x = 1; $x <= $max_floors; $x++) {
            $floors->put($x, $x);
        }

        return $floors;
    }

    /**
     * Display the room schedules for a particular month/year - default this month.
     *
     * @return \Illuminate\Http\Response
     */
    public function schedule(RoomAssignments $assignments, int|string|null $ymd = null)
    {
        $this->authorize('show-room');
        if ((! isset($ymd)) or ($ymd == 0)) {
            $dt = Carbon::now();
        } else {
            $ymd = $this->hyphenate_date($ymd);
            if (! $dt = Carbon::parse($ymd)) {
                return view('404');
            }
        }
        $upcoming = clone $dt;
        $previous_dt = clone $dt;
        $m = [];
        $prev_path = url('rooms/'.$previous_dt->subDays(31)->format('Ymd'));
        $previous_link = '<a href="'.$prev_path.'">&#171;</a>';
        $dts[0] = $dt;
        //dd($dts);
        for ($i = 1; $i <= 31; $i++) {
            $dts[$i] = clone $upcoming->addDay();
        }

        $next_path = url('rooms/'.$upcoming->format('Ymd'));
        $next_link = '<a href="'.$next_path.'">&#187;</a>';

        $rooms = \App\Models\Room::with('location')->get();
        $roomsort = $rooms->sortBy(function ($room) {
            return sprintf('%-12s%s', $room->location_id, $room->name);
        });

        $retreats = \App\Models\Retreat::select(DB::raw('CONCAT(idnumber, "-", title, " (",DATE_FORMAT(start_date,"%m-%d-%Y"),")") as description'), 'id')
            ->whereBetween('start_date', [$dts[0], $dts[31]])
            ->where('end_date', '>=', Carbon::today())
            ->where('is_active', 1)
            ->orderBy('start_date')
            ->pluck('description', 'id');
        $retreats->prepend('Unassigned', 0);

        $retreatants = \App\Models\Contact::whereContactType(config('polanco.contact_type.individual'))
            ->orderBy('sort_name')
            ->pluck('sort_name', 'id');
        $retreatants->prepend('Unassigned', 0);

        $registrations = $assignments->between($dts[0], $dts[31]->copy()->addDay())
            ->whereIn('room_id', $rooms->modelKeys())
            ->with('retreatant', 'retreat')
            ->get();

        // create matrix of rooms and dates
        foreach ($rooms as $room) {
            foreach ($dts as $dt) {
                $m[$room->id][$dt->toDateString()]['status'] = 'A';
                $m[$room->id][$dt->toDateString()]['registrations'] = [];

                $m[$room->id]['room'] = $room->name;
                $m[$room->id]['building'] = $room->location->name;
                $m[$room->id]['occupancy'] = $room->occupancy;
            }

            // highlight rooms that are marked cleaning or maintenance until cleared
            if (in_array($room->status, ['C', 'M'])) {
                foreach ($dts as $dt) {
                    if ($m[$room->id][$dt->toDateString()]['status'] === 'A') {
                        $m[$room->id][$dt->toDateString()]['status'] = $room->status;
                    }
                }
            }
        }

        foreach ($registrations as $registration) {
            [$start, $end] = $registration->roomNights();
            for ($night = $start->max($dts[0]->copy()->startOfDay())->copy(); $night->lt($end) && $night->lte($dts[31]); $night->addDay()) {
                $key = $night->toDateString();
                $cell = &$m[$registration->room_id][$key];
                $occupied = $registration->arrived_at && ! $registration->departed_at;
                $cell['status'] = $occupied || $cell['status'] === 'O' ? 'O' : 'R';
                $cell['registrations'][] = $registration;
                unset($cell);
            }
        }

        return view('rooms.sched2', compact('dts', 'roomsort', 'm', 'previous_link', 'next_link', 'retreats', 'retreatants'));
    }

    /**
     * Hyphenates an 8 digit number to yyyy-mm-dd
     * Ensures dashes added to create hyphenated string prior to parsing date if unhyphanted
     * If already hyphenated and valid format of yyyy-mm-dd returns hyphanated string
     * Helps address issue #448
     *
     * @return string $hyphenated_date
     */
    public function hyphenate_date(int|string $unhyphenated_date)
    {
        if ((strpos($unhyphenated_date, '-') == 0) && (strlen($unhyphenated_date) == 8) && is_numeric($unhyphenated_date)) {
            $hyphenated_date = substr($unhyphenated_date, 0, 4).'-'.substr($unhyphenated_date, 4, 2).'-'.substr($unhyphenated_date, 6, 2);

            return $hyphenated_date;
        } else {
            if ($this->validateDate($unhyphenated_date)) { //already hyphenated
                $hyphenated_date = $unhyphenated_date;

                return $hyphenated_date;
            } else {
                return null;
            }
        }
    }

    public function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);

        return $d && $d->format($format) == $date;
    }

    public function moveReservation(Request $request, RoomAssignments $assignments)
    {
        $this->authorize('update-registration');
        $data = $request->validate([
            'registration_id' => 'required|integer',
            'room_id' => 'required|integer|exists:rooms,id',
        ]);

        try {
            $assignments->transaction(function () use ($data, $assignments) {
                $registration = Registration::with('retreat')->findOrFail($data['registration_id']);
                $registration->room_id = $data['room_id'];
                if ($registration->isDirty('room_id')) {
                    $assignments->check($registration);
                    $registration->save();
                }
            });
        } catch (ValidationException $error) {
            return response()->json(['status' => 'error', 'message' => $error->getMessage()], 409);
        }

        return response()->json(['status' => 'ok']);
    }

    public function createReservation(Request $request, RoomAssignments $assignments)
    {
        $this->authorize('create-registration');

        $data = $request->validate([
            'room_id' => 'required|integer|exists:rooms,id',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after:start_date',
            'contact_id' => 'required|integer|exists:contact,id',
            'event_id' => 'required|integer|exists:event,id',
        ]);

        $registration = new Registration;
        $registration->room_id = $data['room_id'];
        $registration->contact_id = $data['contact_id'];
        $registration->event_id = $data['event_id'];
        $registration->status_id = config('polanco.registration_status_id.registered');
        $registration->role_id = config('polanco.participant_role_id.retreatant');
        $registration->register_date = now();
        $registration->room_starts_on = $data['start_date'];
        $registration->room_ends_on = $data['end_date'];

        $assignments->transaction(function () use ($registration, $assignments) {
            $assignments->check($registration);
            $registration->save();
        });

        if (! empty($registration->retreatant->email_primary_text) && $registration->contact->do_not_email == 0) {
            try {
                Mail::to($registration->retreatant->email_primary_text)->queue(new ReservationConfirmation($registration));
            } catch (\Exception $e) {
                // ignore mail failures during reservation creation
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'ok', 'registration_id' => $registration->id]);
        }

        flash('Reservation created')->success();

        return redirect()->route('rooms', Carbon::parse($data['start_date'])->format('Y-m-d'));
    }
}
