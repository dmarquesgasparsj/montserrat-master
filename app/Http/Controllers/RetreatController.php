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
use Spatie\GoogleCalendar\Event;
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

