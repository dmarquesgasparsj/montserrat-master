<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
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
    public function schedule(int|string|null $ymd = null)
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

        $registrations_start = \App\Models\Registration::with('room', 'room.location', 'retreatant', 'retreat')->whereNull('canceled_at')->where('room_id', '>', 0)->whereHas('retreat', function ($query) use ($dts) {
            $query->where('start_date', '>=', $dts[0])->where('start_date', '<=', $dts[30]);
        })->get();
        $registrations_end = \App\Models\Registration::with('room', 'room.location', 'retreatant', 'retreat')->whereNull('canceled_at')->where('room_id', '>', 0)->whereHas('retreat', function ($query) use ($dts) {
            $query->where('end_date', '>=', $dts[0])->where('start_date', '<=', $dts[0]);
        })->get();
	
#	dd($registrations_start,$registrations_end);
        // create matrix of rooms and dates
        foreach ($rooms as $room) {
            foreach ($dts as $dt) {
                $m[$room->id][$dt->toDateString()]['status'] = 'A';
                $m[$room->id][$dt->toDateString()]['registration_id'] = null;
                $m[$room->id][$dt->toDateString()]['retreatant_id'] = null;
                $m[$room->id][$dt->toDateString()]['retreatant_name'] = null;

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

        /*
         * for each registration, get the number of days
         * for each day, check if the status is set (in other words it is in the room schedule matrix)
         * if it is in the matrix update the status to reserved
         */

        foreach ($registrations_start as $registration) {
            $start_time = $registration->retreat->start_date->hour + (($registration->retreat->start_date->minute / 100));
            $end_time = $registration->retreat->end_date->hour + (($registration->retreat->end_date->minute / 100));
            $numdays = ( (int) $registration->retreat->start_date->diffInDays($registration->retreat->end_date));
            $numdays = ($start_time > $end_time) ? $numdays + 1 : $numdays;
            for ($i = 0; $i <= $numdays; $i++) {
                $matrixdate = $registration->retreat->start_date->copy()->addDays($i);
                if (array_key_exists($matrixdate->toDateString(), $m[$registration->room_id])) {
                    $m[$registration->room_id][$matrixdate->toDateString()]['status'] = 'R';
                    if (! empty($registration->arrived_at) && empty($registration->departed_at)) {
                        $m[$registration->room_id][$matrixdate->toDateString()]['status'] = 'O';
                    }
                    $m[$registration->room_id][$matrixdate->toDateString()]['registration_id'] = $registration->id;
                    $m[$registration->room_id][$matrixdate->toDateString()]['retreatant_id'] = $registration->contact_id;
                    $m[$registration->room_id][$matrixdate->toDateString()]['retreatant_name'] = $registration->retreatant->display_name;
                    $m[$registration->room_id][$matrixdate->toDateString()]['retreat_name'] = $registration->retreat_name;

                    /* For now just handle marking the room as reserved with a URL to the registration and name in the title when hovering over it
                     * I am thinking about using diffInDays to see if the retreatant arrived on the day that we are looking at or sooner
                     * If they have not yet arrived then the first day should be reserved but not occupied.
                     * Occupied will be the same link to the registration.
                     */
                }
            }
        }
        foreach ($registrations_end as $registration) {
            $start_time = $registration->retreat->start_date->hour + (($registration->retreat->start_date->minute / 100));
            $end_time = $registration->retreat->end_date->hour + (($registration->retreat->end_date->minute / 100));
            $numdays = ( (int) $registration->retreat->start_date->diffInDays($registration->retreat->end_date));
            $numdays = ($start_time > $end_time) ? $numdays + 1 : $numdays;
            for ($i = 0; $i <= $numdays; $i++) {
                $matrixdate = $registration->retreat->start_date->copy()->addDays($i);
                if (array_key_exists($matrixdate->toDateString(), $m[$registration->room_id])) {
                    $m[$registration->room_id][$matrixdate->toDateString()]['status'] = 'R';
                    if (! empty($registration->arrived_at) && empty($registration->departed_at)) {
                        $m[$registration->room_id][$matrixdate->toDateString()]['status'] = 'O';
                    }
                    $m[$registration->room_id][$matrixdate->toDateString()]['registration_id'] = $registration->id;
                    $m[$registration->room_id][$matrixdate->toDateString()]['retreatant_id'] = $registration->contact_id;
                    $m[$registration->room_id][$matrixdate->toDateString()]['retreatant_name'] = $registration->retreatant->display_name;
                    $m[$registration->room_id][$matrixdate->toDateString()]['retreat_name'] = $registration->retreat_name;

                    /* For now just handle marking the room as reserved with a URL to the registration and name in the title when hovering over it
                     * I am thinking about using diffInDays to see if the retreatant arrived on the day that we are looking at or sooner
                     * If they have not yet arrived then the first day should be reserved but not occupied.
                     * Occupied will be the same link to the registration.
                     */
                }
	    }
#	    dd($start_time, $end_time, $numdays, $matrixdate, $m);
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

    public function moveReservation(Request $request)
    {
        $this->authorize('update-registration');
        $registration = \App\Models\Registration::with('retreat')->findOrFail($request->input('registration_id'));
        $room_id = $request->input('room_id');

        $conflict = \App\Models\Registration::where('room_id', $room_id)
            ->where('id', '<>', $registration->id)
            ->whereNull('canceled_at')
            ->whereHas('retreat', function ($query) use ($registration) {
                $query->where('start_date', '<=', $registration->retreat->end_date)
                    ->where('end_date', '>=', $registration->retreat->start_date);
            })->exists();

        if ($conflict) {
            return response()->json([
                'status' => 'error',
                'message' => 'Room not available for the selected dates',
            ], 409);
        }

        $registration->room_id = $room_id;
        $registration->save();

        return response()->json(['status' => 'ok']);
    }

    public function createReservation(Request $request)
    {
        $this->authorize('create-registration');

        $data = $request->validate([
            'room_id' => 'required|integer|exists:rooms,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'contact_id' => 'required|integer|exists:contact,id',
            'event_id' => 'required|integer|exists:event,id',
        ]);

        $registration = new \App\Models\Registration();
        $registration->room_id = $data['room_id'];
        $registration->contact_id = $data['contact_id'];
        $registration->event_id = $data['event_id'];
        $registration->status_id = config('polanco.registration_status_id.registered');
        $registration->role_id = config('polanco.participant_role_id.retreatant');
        $registration->register_date = now();
        $registration->arrived_at = Carbon::parse($data['start_date'])->startOfDay();
        $registration->departed_at = Carbon::parse($data['end_date'])->startOfDay();
        $registration->save();

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
