<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventSearchRequest;
use App\Http\Requests\RoomUpdateRetreatRequest;
use App\Http\Requests\StoreRetreatRequest;
use App\Http\Requests\UpdateRetreatRequest;
use App\Models\Registration;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Storage;

class RetreatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->authorize('show-retreat');
        // do once in controller to reduce excessive number of checks on blade
        $permission_checks = ['show-retreat', 'show-event-contract', 'show-event-schedule', 'show-event-evaluation'];
        foreach ($permission_checks as $permission_check => $permission) {
            $results[$permission] = Auth::user()->can($permission);
        }

        $defaults = [];
        $defaults['type'] = 'Retreat';
        $event_types = \App\Models\EventType::whereIsActive(1)->orderBy('name')->pluck('id', 'name');

        $retreats = \App\Models\Retreat::whereDate('end_date', '>=', date('Y-m-d'))->orderBy('start_date', 'asc')->with('retreatmasters.contact', 'innkeepers.contact', 'assistants.contact')->withCount('retreatants')->paginate(25, ['*'], 'retreats');
        $oldretreats = \App\Models\Retreat::whereDate('end_date', '<', date('Y-m-d'))->orderBy('start_date', 'desc')->with('retreatmasters.contact', 'innkeepers.contact', 'assistants.contact')->withCount('retreatants')->paginate(25, ['*'], 'oldretreats');

        return view('retreats.index', compact('retreats', 'oldretreats', 'defaults', 'event_types', 'results'));   //
    }

    public function index_type($event_type_id): View
    {
        $this->authorize('show-retreat');
        $permission_checks = ['show-retreat', 'show-event-contract', 'show-event-schedule', 'show-event-evaluation'];
        foreach ($permission_checks as $permission_check => $permission) {
            $results[$permission] = Auth::user()->can($permission);
        }
        $event_types = \App\Models\EventType::whereIsActive(1)->orderBy('name')->pluck('id', 'name');
        $event_type = \App\Models\EventType::findOrFail($event_type_id);
        $defaults = [];
        $defaults['type'] = $event_type->label;

        $retreats = \App\Models\Retreat::whereEventTypeId($event_type_id)->whereDate('end_date', '>=', date('Y-m-d'))->orderBy('start_date', 'asc')->with('retreatmasters.contact', 'innkeepers.contact', 'assistants.contact')->withCount('retreatants')->paginate(25, ['*'], 'retreats');
        $oldretreats = \App\Models\Retreat::whereEventTypeId($event_type_id)->whereDate('end_date', '<', date('Y-m-d'))->orderBy('start_date', 'desc')->with('retreatmasters.contact', 'innkeepers.contact', 'assistants.contact')->withCount('retreatants')->paginate(25, ['*'], 'oldretreats');

        return view('retreats.index', compact('retreats', 'oldretreats', 'defaults', 'event_types', 'results'));   //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create-retreat');

        $retreat_house = \App\Models\Contact::with('retreat_directors.contact_b', 'retreat_innkeepers.contact_b', 'retreat_assistants.contact_b', 'retreat_ambassadors.contact_b')->findOrFail(config('polanco.self.id'));
        $event_types = \App\Models\EventType::whereIsActive(1)->orderBy('name')->pluck('name', 'id');
        $is_active[0] = 'Canceled';
        $is_active[1] = 'Active';

        // initialize null arrays for innkeeper, assistant, director and ambassador dropdowns
        $i = [];
        $a = [];
        $d = [];
        $c = [];

        foreach ($retreat_house->retreat_innkeepers as $innkeeper) {
            $i[$innkeeper->contact_id_b] = $innkeeper->contact_b->sort_name;
        }
        if (! null == $i) {
            asort($i);
            $i = [0 => 'N/A'] + $i;
        }

        foreach ($retreat_house->retreat_directors as $director) {
            $d[$director->contact_id_b] = $director->contact_b->sort_name;
        }
        if (! null == $d) {
            asort($d);
            $d = [0 => 'N/A'] + $d;
        }

        foreach ($retreat_house->retreat_assistants as $assistant) {
            $a[$assistant->contact_id_b] = $assistant->contact_b->sort_name;
        }
        if (! null == $a) {
            asort($a);
            $a = [0 => 'N/A'] + $a;
        }

        foreach ($retreat_house->retreat_ambassadors as $ambassador) {
            $c[$ambassador->contact_id_b] = $ambassador->contact_b->sort_name;
        }
        if (! null == $c) {
            asort($c);
            $c = [0 => 'N/A'] + $c;
        }

        $next_idnumber = \App\Models\Retreat::nextIdnumber();

        return view('retreats.create', compact('d', 'i', 'a', 'c', 'event_types', 'is_active', 'next_idnumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRetreatRequest $request): RedirectResponse
    {
        $this->authorize('create-retreat');

        $retreat = new \App\Models\Retreat;

        $retreat->idnumber = \App\Models\Retreat::nextIdnumber();
        $retreat->start_date = $request->input('start_date');
        $retreat->end_date = $request->input('end_date');
        $retreat->title = $request->input('title');
        $retreat->description = $request->input('description');
        $retreat->event_type_id = $request->input('event_type');
        $retreat->is_active = $request->input('is_active');
        $retreat->max_participants = $request->input('max_participants');
        // TODO: find a way to tag silent retreats, perhaps with event_type_id - for now disabled
        //$retreat->silent = $request->input('silent');
        // amount will be related to default_fee_id?
        //$retreat->amount = $request->input('amount');
        // attending should be calculated based on retreat participants
        //$retreat->attending = $request->input('attending');
        //$retreat->year = $request->input('year');

        //$retreat->chapel = $request->input('chapel');
        //$retreat->points_room= $request->input('points_room');
        //$retreat->dining_room= $request->input('dining_room');


        $directors = $request->input('directors');
        $innkeepers = $request->input('innkeepers');
        $assistants = $request->input('assistants');
        $ambassadors = $request->input('ambassadors');
        $retreat->save();

        if (empty($directors) or (in_array(0, $directors) && count($directors) == 1)) {
            // nothing to store
        } else {
            foreach ($directors as $director) {
                $this->add_participant($director, $retreat->id, config('polanco.participant_role_id.director'));
            }
        }

        if (empty($innkeepers) or (in_array(0, $innkeepers) && count($innkeepers) == 1)) {
            // nothing to store
        } else {
            foreach ($innkeepers as $innkeeper) {
                $this->add_participant($innkeeper, $retreat->id, config('polanco.participant_role_id.innkeeper'));
            }
        }

        if (empty($assistants) or (in_array(0, $assistants) && count($assistants) == 1)) {
            // nothing to store
        } else {
            foreach ($assistants as $assistant) {
                $this->add_participant($assistant, $retreat->id, config('polanco.participant_role_id.assistant'));
            }
        }

        if (empty($ambassadors) or (in_array(0, $ambassadors) && count($ambassadors) == 1)) {
            // nothing to store
        } else {
            foreach ($ambassadors as $ambassador) {
                $this->add_participant($ambassador, $retreat->id, config('polanco.participant_role_id.ambassador'));
            }
        }
        flash('Retreat: <a href="'.url('/retreat/'.$retreat->id).'">'.$retreat->title.'</a> added')->success();

        return Redirect::action([self::class, 'index']); //
    }
    /**
     * Add a participant to an event with a given participant role.
     *
     * @return bool
     */
    public function add_participant(int $contact_id, int $event_id, int $participant_role_id)
    {
        if ($contact_id > 0 && $event_id > 0) { //avoid inserting bad data
            $participant = \App\Models\Registration::updateOrCreate([
                'contact_id' => $contact_id,
                'event_id' => $event_id,
                'status_id' => config('polanco.registration_status_id.registered'),
                'role_id' => $participant_role_id,
            ]);
            if (! isset($participant->register_date)) {
                $participant->register_date = now();
            }
            if (! isset($participant->notes)) {
                $participant->notes = 'Automatically registered by Polanco as '.$participant->participant_role_name;
            }
            $participant->save();

            return true;
        } else {
            return false;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id, $status = null): View
    {
        $this->authorize('show-retreat');
        $retreat = \App\Models\Retreat::with('retreatmasters.contact', 'innkeepers.contact', 'assistants.contact', 'ambassadors.contact')->findOrFail($id);
        $attachments = \App\Models\Attachment::whereEntity('event')->whereEntityId($id)->whereFileTypeId(config('polanco.file_type.event_attachment'))->get();

        switch ($status) {
            case 'active':
                $registrations = \App\Models\Registration::select('participant.*', 'contact.sort_name')->
                  leftjoin('contact', 'participant.contact_id', '=', 'contact.id')->
                  orderBy('contact.sort_name')->
                  whereEventId($id)->
                  whereNull('canceled_at')->
                  withCount('retreatant_events')->
                  paginate(50);
                break;
            case 'canceled':
                $registrations = \App\Models\Registration::select('participant.*', 'contact.sort_name')->
                  leftjoin('contact', 'participant.contact_id', '=', 'contact.id')->
                  orderBy('contact.sort_name')->
                  whereEventId($id)->
                  whereNotNull('canceled_at')->
                  withCount('retreatant_events')->
                  paginate(50);
                break;
            case 'confirmed':
                $registrations = \App\Models\Registration::select('participant.*', 'contact.sort_name')->
                  leftjoin('contact', 'participant.contact_id', '=', 'contact.id')->
                  orderBy('contact.sort_name')->whereEventId($id)->
                  whereNotNull('registration_confirm_date')->
                  withCount('retreatant_events')->
                  paginate(50);
                break;
            case 'unconfirmed':
                $registrations = \App\Models\Registration::select('participant.*', 'contact.sort_name')->
                  leftjoin('contact', 'participant.contact_id', '=', 'contact.id')->
                  orderBy('contact.sort_name')->whereEventId($id)->
                  whereNull('registration_confirm_date')->
                  withCount('retreatant_events')->
                  paginate(50);
                break;
            case 'arrived':
                $registrations = \App\Models\Registration::select('participant.*', 'contact.sort_name')->
                  leftjoin('contact', 'participant.contact_id', '=', 'contact.id')->
                  orderBy('contact.sort_name')->
                  whereEventId($id)->
                  whereNotNull('arrived_at')->
                  withCount('retreatant_events')->
                  paginate(50);
                break;
            case 'dawdler':
                $registrations = \App\Models\Registration::select('participant.*', 'contact.sort_name')->
                  leftjoin('contact', 'participant.contact_id', '=', 'contact.id')->
                  orderBy('contact.sort_name')->
                  whereEventId($id)->
                  whereNull('arrived_at')->
                  whereNull('canceled_at')->
                  withCount('retreatant_events')->
                  paginate(50);
                break;
            case 'departed':
                $registrations = \App\Models\Registration::select('participant.*', 'contact.sort_name')->
                  leftjoin('contact', 'participant.contact_id', '=', 'contact.id')->
                  orderBy('contact.sort_name')->
                  whereEventId($id)->
                  whereNotNull('departed_at')->
                  withCount('retreatant_events')->
                  paginate(50);
                break;
            case 'retreatants':
                $registrations = \App\Models\Registration::select('participant.*', 'contact.sort_name')->
                  leftjoin('contact', 'participant.contact_id', '=', 'contact.id')->
                  orderBy('participant.register_date')->
                  whereEventId($id)->
                  whereNull('canceled_at')->
                  whereRoleId(config('polanco.participant_role_id.retreatant'))->
                  withCount('retreatant_events')->
                  paginate(50);
                break;
            default: //all
                $registrations = \App\Models\Registration::select('participant.*', 'contact.sort_name')->
                  leftjoin('contact', 'participant.contact_id', '=', 'contact.id')->
                  orderBy('contact.sort_name')->
                  whereEventId($id)->
                  withCount('retreatant_events')->
                  paginate(50);
                break;
        }
        return view('retreats.show', compact('retreat', 'registrations', 'status', 'attachments')); //
    }

    public function show_waitlist($id): View
    {
        $this->authorize('show-retreat');
        $retreat = \App\Models\Retreat::with('retreatmasters.contact', 'innkeepers.contact', 'assistants.contact', 'ambassadors.contact')->findOrFail($id);
        $registrations = \App\Models\Registration::where('event_id', '=', $id)->whereStatusId(config('polanco.registration_status_id.waitlist'))->with('retreatant.parish')->orderBy('register_date', 'ASC')->get();

        return view('retreats.waitlist', compact('retreat', 'registrations')); //
    }

    public function get_event_by_id_number($id_number, $status = null)
    {
        $this->authorize('show-retreat');
        $retreat = \App\Models\Retreat::with('retreatmasters.contact', 'innkeepers.contact', 'assistants.contact', 'ambassadors.contact')->whereIdnumber($id_number)->firstOrFail();

        return $this->show($retreat->id, $status);
    }

    /**
     * Show the form for editing the specified resource.
     */
    //public function edit($id)
    //{
    //   $retreats = \App\Models\Retreat::();
    //   return view('retreats.edit',compact('retreats'));
    //  }
    public function edit(int $id): View
    {
        $this->authorize('update-retreat');
        //get this retreat's information
        $retreat = \App\Models\Retreat::with('retreatmasters.contact', 'assistants.contact', 'innkeepers.contact', 'ambassadors.contact')->findOrFail($id);
        $event_types = \App\Models\EventType::whereIsActive(1)->orderBy('name')->pluck('name', 'id');
        $is_active[0] = 'Canceled';
        $is_active[1] = 'Active';

        //create lists of retreat directors, innkeepers, and assistants from relationship to retreat house
        $retreat_house = \App\Models\Contact::with('retreat_directors.contact_b', 'retreat_innkeepers.contact_b', 'retreat_assistants.contact_b', 'retreat_ambassadors.contact_b')->findOrFail(config('polanco.self.id'));

        // initialize null arrays for innkeeper, assistant, director and ambassador dropdowns

        $options = ['innkeepers' => [], 'directors' => [], 'assistants' => [], 'ambassadors' => []];

        foreach ($retreat_house->retreat_innkeepers as $innkeeper) {
            $options['innkeepers'][$innkeeper->contact_id_b] = $innkeeper->contact_b->sort_name;
        }
        if (! null == $options['innkeepers']) {
            asort($options['innkeepers']);
            $options['innkeepers'] = [0 => 'N/A'] + $options['innkeepers'];
        }

        foreach ($retreat_house->retreat_directors as $director) {
            $options['directors'][$director->contact_id_b] = $director->contact_b->sort_name;
        }
        if (! null == $options['directors']) {
            asort($options['directors']);
            $options['directors'] = [0 => 'N/A'] + $options['directors'];
        }

        foreach ($retreat_house->retreat_assistants as $assistant) {
            $options['assistants'][$assistant->contact_id_b] = $assistant->contact_b->sort_name;
        }
        if (! null == $options['assistants']) {
            asort($options['assistants']);
            $options['assistants'] = [0 => 'N/A'] + $options['assistants'];
        }

        foreach ($retreat_house->retreat_ambassadors as $ambassador) {
            $options['ambassadors'][$ambassador->contact_id_b] = $ambassador->contact_b->sort_name;
        }
        if (! null == $options['ambassadors']) {
            asort($options['ambassadors']);
            $options['ambassadors'] = [0 => 'N/A'] + $options['ambassadors'];
        }

        /* prevent losing former retreatmasters, innkeeper, assistant, or ambassador when editing retreat
         * loop through currently assigned retreatmasters, innkeeper, assistant, and ambassador assignments
         * verify that they are currently in appropriate array as defined above (d, i, a or c)
         * if not found in the array then add them and resort the array adding 'former' to the end of their name
         * so that they visually standout on the dropdown list as being a former member of that group
         */

        foreach ($retreat->retreatmasters as $director) {
            if (! Arr::has($options['directors'], $director->contact->id)) {
                $options['directors'][$director->contact->id] = $director->contact->sort_name.' (former)';
                asort($options['directors']);
            }
        }

        foreach ($retreat->innkeepers as $innkeeper) {
            if (! Arr::has($options['innkeepers'], $innkeeper->contact->id)) {
                $options['innkeepers'][$innkeeper->contact->id] = $innkeeper->contact->sort_name.' (former)';
                asort($options['innkeepers']);
            }
        }

        foreach ($retreat->assistants as $assistant) {
            if (! Arr::has($options['assistants'], $assistant->contact->id)) {
                $options['assistants'][$assistant->contact->id] = $assistant->contact->sort_name.' (former)';
                asort($options['assistants']);
            }
        }

        foreach ($retreat->ambassadors as $ambassador) {
            if (! Arr::has($options['ambassadors'], $ambassador->contact->id)) {
                $options['ambassadors'][$ambassador->contact->id] = $ambassador->contact->sort_name.' (former)';
                asort($options['ambassadors']);
            }
        }

        return view('retreats.edit', compact('retreat', 'options', 'event_types', 'is_active'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRetreatRequest $request, int $id): RedirectResponse
    {
        $this->authorize('update-retreat');

        $retreat = \App\Models\Retreat::findOrFail($request->input('id'));
        $retreat->idnumber = $request->input('idnumber');
        $retreat->start_date = $request->input('start_date');
        $retreat->end_date = $request->input('end_date');
        $retreat->title = $request->input('title');
        $retreat->description = $request->input('description');
        $retreat->event_type_id = $request->input('event_type');
        $retreat->is_active = $request->input('is_active');
        $retreat->max_participants = $request->input('max_participants');
        $retreat->save();


        //$retreat->chapel = $request->input('chapel');
        //$retreat->points_room= $request->input('points_room');
        //$retreat->dining_room= $request->input('dining_room');



        if ($request->file('contract') !== null) {
            $description = 'Contract for '.$retreat->idnumber.'-'.$retreat->title;
            $attachment = new AttachmentController;
            $attachment->update_attachment($request->file('contract'), 'event', $retreat->id, 'contract', $description);
        }

        if ($request->file('schedule') !== null) {
            $description = 'Schedule for '.$retreat->idnumber.'-'.$retreat->title;
            $attachment = new AttachmentController;
            $attachment->update_attachment($request->file('schedule'), 'event', $retreat->id, 'schedule', $description);
        }

        if ($request->file('evaluations') !== null) {
            $description = 'Evaluations for '.$retreat->idnumber.'-'.$retreat->title;
            $attachment = new AttachmentController;
            $attachment->update_attachment($request->file('evaluations'), 'event', $retreat->id, 'evaluations', $description);
        }

        if ($request->file('group_photo') !== null) {
            $description = 'Group photo for '.$retreat->idnumber.'-'.$retreat->title;
            $attachment = new AttachmentController;
            $attachment->update_attachment($request->file('group_photo'), 'event', $retreat->id, 'group_photo', $description);
        }

        if ($request->file('event_attachment') !== null) {
            $description = $request->input('event_attachment_description');
            $attachment = new AttachmentController;
            $attachment->update_attachment($request->file('event_attachment'), 'event', $retreat->id, 'event_attachment', $description);
        }

        $directors = $request->input('directors');
        $existing_directors = $retreat->retreatmasters()->pluck('contact_id');
        $removed_directors = $existing_directors->diff($directors);

        if (! empty($directors)) {
            foreach ($directors as $director) {
                $this->add_participant($director, $retreat->id, config('polanco.participant_role_id.director'));
            }
        }
        if (! $removed_directors->isEmpty()) {
            $retreat->retreatmasters()->whereIn('contact_id', $removed_directors)->delete();
        }

        $innkeepers = $request->input('innkeepers');
        $existing_innkeepers = $retreat->innkeepers()->pluck('contact_id');
        $removed_innkeepers = $existing_innkeepers->diff($innkeepers);
        if (! empty($innkeepers)) {
            foreach ($innkeepers as $innkeeper) {
                $this->add_participant($innkeeper, $retreat->id, config('polanco.participant_role_id.innkeeper'));
            }
        }
        if (! $removed_innkeepers->isEmpty()) {
            $retreat->innkeepers()->whereIn('contact_id', $removed_innkeepers)->delete();
        }

        $assistants = $request->input('assistants');
        $existing_assistants = $retreat->assistants()->pluck('contact_id');
        $removed_assistants = $existing_assistants->diff($assistants);
        if (! empty($assistants)) {
            foreach ($assistants as $assistant) {
                $this->add_participant($assistant, $retreat->id, config('polanco.participant_role_id.assistant'));
            }
        }
        if (! $removed_assistants->isEmpty()) {
            $retreat->assistants()->whereIn('contact_id', $removed_assistants)->delete();
        }

        $ambassadors = $request->input('ambassadors');
        $existing_ambassadors = $retreat->ambassadors()->pluck('contact_id');
        $removed_ambassadors = $existing_ambassadors->diff($ambassadors);
        if (! empty($ambassadors)) {
            foreach ($ambassadors as $ambassador) {
                $this->add_participant($ambassador, $retreat->id, config('polanco.participant_role_id.ambassador'));
            }
        }
        if (! $removed_ambassadors->isEmpty()) {
            $retreat->ambassadors()->whereIn('contact_id', $removed_ambassadors)->delete();
        }


        flash('Retreat: <a href="'.url('/retreat/'.$retreat->id).'">'.$retreat->title.'</a> updated')->success();

        return Redirect::action([self::class, 'show'], $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->authorize('delete-retreat');
        $retreat = \App\Models\Retreat::findOrFail($id);

        \App\Models\Retreat::destroy($id);

        flash('Retreat: '.$retreat->title.' deleted')->warning()->important();

        return Redirect::action([self::class, 'index']);
    }

    public function assign_rooms($id): View
    {
        $this->authorize('update-registration');
        //get this retreat's information
        $retreat = \App\Models\Retreat::with('retreatmasters.contact', 'assistants.contact', 'innkeepers.contact', 'ambassadors.contact')->findOrFail($id);
        $registrations = \App\Models\Registration::where('event_id', '=', $id)->with('retreatant.parish')->orderBy('register_date', 'DESC')->whereStatusId(config('polanco.registration_status_id.registered'))->get();
        $rooms = \App\Models\Room::orderby('name')->pluck('name', 'id');
        $rooms->prepend('Unassigned', '');

        return view('retreats.assign_rooms', compact('retreat', 'registrations', 'rooms'));
    }

    public function edit_payments($id): View
    {
        $this->authorize('update-payment');
        //get this retreat's information
        $retreat = \App\Models\Retreat::findOrFail($id);
        $registrations = \App\Models\Registration::where('event_id', '=', $id)->whereCanceledAt(null)->with('retreatant.parish', 'donation')->orderBy('register_date', 'DESC')->get();
        $payment_description = config('polanco.payment_method');
        $donation_description = \App\Models\DonationType::active()->orderby('name')->pluck('name', 'name');
        $donation_description->prepend('Unassigned', '');

        return view('retreats.payments.edit', compact('retreat', 'registrations', 'donation_description', 'payment_description'));
    }

    public function show_payments($id): View
    {
        $this->authorize('show-payment');
        $retreat = \App\Models\Retreat::findOrFail($id);
        $registrations = \App\Models\Registration::where('event_id', '=', $id)->whereCanceledAt(null)->with('retreatant.parish', 'donation')->orderBy('register_date', 'DESC')->get();

        return view('retreats.payments.show', compact('retreat', 'registrations'));
    }

    public function checkout($id): RedirectResponse
    {
        /* checkout all registrations for a retreat where the arrived_at is not NULL and the departed is NULL for a particular event */
        // TODO: consider also checking to see if the arrived_at time is empty and if it is put in the retreat start time
        $this->authorize('update-registration');
        $retreat = \App\Models\Retreat::findOrFail($id); //verifies that it is a valid retreat id
        $registrations = \App\Models\Registration::whereEventId($id)
            ->whereCanceledAt(null)
            ->whereNotNull('arrived_at')
            ->whereNull('departed_at')
            ->get();

        foreach ($registrations as $registration) {
            $registration->departed_at = $registration->retreat_end_date ?? now();
            $registration->save();
        }

        flash('Retreatants for '.$retreat->title.' successfully checked out')->success();

        return Redirect::action([self::class, 'show'], $retreat->id);
    }

    public function checkin($id): RedirectResponse
    {
        /* checkout all registrations for a retreat where the arrived_at is not NULL and the departed is NULL for a particular event */
        $this->authorize('update-registration');
        $retreat = \App\Models\Retreat::findOrFail($id); //verifies that it is a valid retreat id
        $registrations = \App\Models\Registration::whereEventId($id)->whereCanceledAt(null)->whereDepartedAt(null)->whereNull('arrived_at')->get();
        foreach ($registrations as $registration) {
            $registration->arrived_at = $registration->retreat_start_date;
            $registration->save();
        }

        flash('Retreatants for '.$retreat->title.' successfully checked in')->success();

        return Redirect::action([self::class, 'show'], $retreat->id);
    }

    public function room_update(RoomUpdateRetreatRequest $request): RedirectResponse
    {
        $this->authorize('update-registration');

        if ($request->input('registrations') !== null) {
            foreach ($request->input('registrations') as $key => $value) {
                $registration = \App\Models\Registration::findOrFail($key);
                if (! isset($event_id)) {
                    $event_id = $registration->event_id;
                }
                $registration->room_id = $value;
                $registration->save();
            }
        }
        if ($request->input('notes') !== null) {
            foreach ($request->input('notes') as $key => $value) {
                $registration = \App\Models\Registration::findOrFail($key);
                if (! isset($event_id)) {
                    $event_id = $registration->event_id;
                }
                $registration->notes = $value;
                $registration->save();
            }
        }
        if (isset($event_id)) {
            $retreat = \App\Models\Retreat::findOrFail($event_id);
            flash('Room assignments for '.$retreat->title.' successfully assigned')->success();

            return Redirect::action([self::class, 'show'], $event_id);
        } else { // this should never really happen as it means an event registration did not have an event_id; unit test will assume returning to retreat.show blade
            return Redirect::action([self::class, 'index']);
        }
    }


    public function event_room_list($event_id): View
    {
        // get buildings for which there are assigned rooms
        // for each building initialize array of all rooms in that building
        // for each registration add contact sort_name to room
        // view room_lists
        // TODO: write unit tests for this method
        $this->authorize('show-registration');
        $event = \App\Models\Retreat::findOrFail($event_id);
        $registrations = \App\Models\Registration::whereEventId($event_id)->whereNull('canceled_at')->with('room')->get();
        $room_ids = \App\Models\Registration::whereEventId($event_id)->whereNull('canceled_at')->pluck('room_id');
        $location_ids = \App\Models\Room::whereIn('id', $room_ids)->pluck('location_id')->unique();
        $building_ids = \App\Models\Location::whereIn('id', $location_ids)->pluck('id');
        $results = [];
        foreach ($building_ids as $building) {
            $building_rooms = \App\Models\Room::whereLocationId($building)->with('location')->orderBy('name')->get();
            foreach ($building_rooms as $room) {
                $results[$room->location->name][$room->floor][$room->name] = '';
            }
        }

        foreach ($registrations as $registration) {
            if ($registration->room_id > 0) {
                // if the registered retreatant is not an individual person - for example a contract group organization then use the registration note for the name of the retreatant
                if ($registration->retreatant->contact_type == config('polanco.contact_type.individual')) {
                    if ($results[$registration->room->location->name][$registration->room->floor][$registration->room->name] == '') {
                        $results[$registration->room->location->name][$registration->room->floor][$registration->room->name] = $registration->retreatant->sort_name;
                    } else {
                        $results[$registration->room->location->name][$registration->room->floor][$registration->room->name] .= ' & '.$registration->retreatant->sort_name;
                    }
                } else {
                    // if there is no note; default back to the sort_name of the contact
                    if (isset($registration->notes)) {
                        if ($results[$registration->room->location->name][$registration->room->floor][$registration->room->name] == '') {
                            $results[$registration->room->location->name][$registration->room->floor][$registration->room->name] = $registration->notes;
                        } else {
                            $results[$registration->room->location->name][$registration->room->floor][$registration->room->name] .= ' & '.$registration->notes;
                        }
                    } else {
                        $results[$registration->room->location->name][$registration->room->floor][$registration->room->name] .= $registration->retreatant->sort_name;
                    }
                }
            }
        }

        return view('retreats.roomlist', compact('results', 'event'));
    }

    public function event_namebadges($event_id, $role = null): View
    {
        // for each registration add contact sort_name to namebadge
        // TODO: write unit tests for this method
        $this->authorize('show-registration');
        $event = \App\Models\Retreat::findOrFail($event_id);
        switch ($role) {
            case 'retreatant': $role = config('polanco.participant_role_id.retreatant');
                break;
            case 'director': $role = config('polanco.participant_role_id.director');
                break;
            case 'innkeeper': $role = config('polanco.participant_role_id.innkeeper');
                break;
            case 'assistant': $role = config('polanco.participant_role_id.assistant');
                break;
            case 'ambassador':
                $role = config('polanco.participant_role_id.ambassador');
                break;
            case 'all': $role = null;
                break;
            default: $role = config('polanco.participant_role_id.retreatant');
        }
        if (is_null($role)) {
            $registrations = \App\Models\Registration::whereEventId($event_id)->whereNull('canceled_at')->get();
        } else {
            $registrations = \App\Models\Registration::whereEventId($event_id)->whereNull('canceled_at')->whereRoleId($role)->get();
        }

        $results = [];
        foreach ($registrations as $registration) {
            // if the registered retreatant is not an individual person - for example a contract group organization then use the registration note for the name of the retreatant
            if ($registration->retreatant->contact_type == config('polanco.contact_type.individual')) {
                $name = isset($registration->retreatant->nick_name) ? $registration->retreatant->nick_name : $registration->retreatant->first_name;
                $results[$registration->id] = $name.' '.$registration->retreatant->last_name;
                $registration->badgename = $registration->retreatant->sort_name;
            } else {
                // if there is no note; default back to the sort_name of the contact
                if (isset($registration->notes)) {
                    $results[$registration->id] = $registration->notes;
                    $registration->badgename = $registration->notes;
                } else {
                    $results[$registration->id] = $registration->retreatant->first_name.' '.$registration->retreatant->last_name;
                    $registration->badgename = $registration->retreatant->sort_name;
                }
            }
        }
        asort($results);
        $cresults = collect($results);
        $registrations->sortBy('badgename')->all();

        return view('retreats.namebadges', compact('cresults', 'event'));
    }

    public function event_tableplacards($event_id): View
    {
        // for each registration add contact sort_name to namebadge
        // TODO: write unit tests for this method
        $this->authorize('show-registration');
        $event = \App\Models\Retreat::findOrFail($event_id);
        $registrations = \App\Models\Registration::whereEventId($event_id)->whereNull('canceled_at')->whereStatusId(config('polanco.registration_status_id.registered'))->get();

        $results = [];
        foreach ($registrations as $registration) {
            // if the registered retreatant is not an individual person - for example a contract group organization then use the registration note for the name of the retreatant
            if ($registration->retreatant->contact_type == config('polanco.contact_type.individual')) {
                $results[$registration->id] = $registration->retreatant->prefix_name.' '.$registration->retreatant->first_name.' '.$registration->retreatant->last_name;
                $registration->sortname = $registration->retreatant->sort_name;
            } else {
                // if there is no note; default back to the sort_name of the contact
                if (isset($registration->notes)) {
                    $results[$registration->id] = $registration->notes;
                    $registration->sortname = $registration->notes;
                } else {
                    $results[$registration->id] = $registration->retreatant->prefix_name.' '.$registration->retreatant->first_name.' '.$registration->retreatant->last_name;
                    $registration->sortname = $registration->retreatant->sort_name;
                }
            }
        }
        asort($results);
        $cresults = collect($results);
        $registrations->sortBy('sortname')->all();

        return view('retreats.tableplacards', compact('cresults', 'event'));
    }

    public function search(): View
    {
        $this->authorize('show-retreat');
        $event_types = \App\Models\EventType::whereIsActive(true)->orderBy('label')->pluck('label', 'id');
        $event_types->prepend('N/A', '');

        return view('retreats.search', compact('event_types'));
    }

    public function results(EventSearchRequest $request): View
    {
        $this->authorize('show-retreat');

        if (! empty($request)) {
            $events = \App\Models\Retreat::filtered($request)->orderBy('idnumber')->paginate(25, ['*'], 'events');
            $events->appends($request->except('page'));
        } else {
            $events = \App\Models\Retreat::orderBy('idnumber')->paginate(25, ['*'], 'events');
        }

        return view('retreats.results', compact('events'));
    }





    
}
