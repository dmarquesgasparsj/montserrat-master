@extends('template')
@section('content')

<div class="row bg-cover">
    <div class="col-lg-12">
        <h1>
            @can('update-retreat')
            Retreat {{ html()->a(url('retreat/' . $retreat->id . '/edit'), $retreat->title . ' (' . $retreat->idnumber . ')') }}
            @else
            Retreat {{$retreat->title.' ('.$retreat->idnumber.')'}}
            @endCan
        </h1>
    </div>
    <div class="col-lg-12">
        {{ html()->a(url('#registrations'), __('messages.registrations_button'))->class('btn btn-outline-dark') }}
        @can('create-touchpoint')
            {{ html()->a(url(action([\App\Http\Controllers\TouchpointController::class, 'add_retreat'], $retreat->id)), 'Retreat touchpoint')->class('btn btn-outline-dark') }}
        @endCan
        @can('show-registration')
            <select class="custom-select col-3" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                <option value="">{{ __('messages.print_option') }}</option>
                <option value="{{url('retreat/'.$retreat->id.'/namebadges/all')}}">{{ __('messages.print_namebadges') }}</option>
                <option value="{{url('report/retreatrosterphone/'.$retreat->idnumber)}}">{{ __('messages.print_phone_roster') }}</option>
                <option value="{{url('report/retreatroster/'.$retreat->idnumber)}}">{{ __('messages.print_roster') }}</option>
                <option value="{{url('report/retreatlisting/'.$retreat->idnumber)}}">{{ __('messages.print_listing') }}</option>
                <option value="{{url('report/retreatantinfo/'.$retreat->idnumber)}}">{{ __('messages.print_info_sheets') }}</option>
                <option value="{{url('retreat/'.$retreat->id.'/roomlist')}}">{{ __('messages.print_room_list') }}</option>
                <option value="{{url('retreat/'.$retreat->id.'/tableplacards')}}">{{ __('messages.print_table_placards') }}</option>
                <option value="{{url('report/meal_summary/'.$retreat->idnumber)}}">{{ __('messages.print_meal_summary') }}</option>
                <option value="{{url('report/retreatregistrations/'.$retreat->idnumber)}}">{{ __('messages.print_registrations') }}</option>
                @can('show-donation')
                    <option value="{{url('report/finance/retreatdonations/'.$retreat->idnumber)}}">{{ __('messages.print_donations') }}</option>
                @endCan

            </select>
        @endCan
    </div>
    <div class="col-lg-12 mt-3">
        <h2>{{ __('messages.details_title') }}</h2>
        <div class="row">
            <div class="col-lg-4 col-md-6 ">
                <span class="font-weight-bold">{{ __('messages.id_label') }} </span>{{ $retreat->idnumber}} <br>
                <span class="font-weight-bold">{{ __('messages.start_label') }} </span>{{ date('F j, Y g:i A', strtotime($retreat->start_date)) }} <br>
                <span class="font-weight-bold">{{ __('messages.end_label') }} </span>{{ date('F j, Y g:i A', strtotime($retreat->end_date)) }} <br>
                <span class="font-weight-bold">{{ __('messages.title_label') }} </span>{{ $retreat->title}} <br>
                <span class="font-weight-bold">{{ __('messages.participants_label') }} </span>{{ $retreat->participant_count}} out of {{$retreat->max_participants}}
                @if ($retreat->max_participants > 0)
                    ({{number_format((($retreat->participant_count / $retreat->max_participants)*100),0).'% Capacity'}})
                @endIf
                <br>
                @if ($retreat->retreatant_waitlist_count > 0)
                ({{ html()->a(url('retreat/' . $retreat->id . '/waitlist'), $retreat->retreatant_waitlist_count) }}) <br>
                @endif
            </div>
            <div class="col-lg-4 col-md-6 ">
                <span class="font-weight-bold">{{ __('messages.description_label') }} </span>
                @if (!$retreat->description)
                    N/A
                @else
                    {{ $retreat->description }}
                @endif
                <br>

                <span class="font-weight-bold">{{ __('messages.director_label') }} </span>
                @if ($retreat->retreatmasters->isEmpty())
                    N/A <br>
                @else
                    @foreach($retreat->retreatmasters as $retreatmaster)
                        {!!$retreatmaster->contact_link_full_name!!}
                    @endforeach
                @endif

                <span class="font-weight-bold">{{ __('messages.innkeeper_label') }} </span>
                @if ($retreat->innkeepers->isEmpty())
                    N/A <br>
                @else
                    @foreach($retreat->innkeepers as $innkeeper)
                        {!!$innkeeper->contact_link_full_name!!}
                    @endforeach
                @endif

                <span class="font-weight-bold">{{ __('messages.assistant_label') }} </span>
                @if ($retreat->assistants->isEmpty())
                    N/A <br>
                @else
                    @foreach($retreat->assistants as $assistant)
                        {!!$assistant->contact_link_full_name!!}
                    @endforeach
                @endif

                <span class="font-weight-bold">{{ __('messages.ambassador_label') }} </span>
                @if ($retreat->ambassadors->isEmpty())
                    N/A <br>
                @else
                    @foreach($retreat->ambassadors as $ambassador)
                        {!!$ambassador->contact_link_full_name!!}
                    @endforeach
                @endif
            </div>

            <div class="col-lg-4 col-md-6">
                <span class="font-weight-bold">{{ __('messages.event_type_label') }} </span>{{ $retreat->retreat_type}} <br>
                <span class="font-weight-bold">{{ __('messages.status_label') }} </span>{{ $retreat->is_active == 0 ? __('messages.status_canceled') : __('messages.status_active') }} <br>
                @can('show-donation')
                    <span class="font-weight-bold">{{ __('messages.donations_label') }} </span>
                    {{ html()->a(url('report/finance/retreatdonations/'.$retreat->idnumber),
                        ($retreat->donations_pledged_sum)>0 ? '$'.number_format($retreat->donations_pledged_sum,2) : '$'.number_format(0,2))
                    }}
                    @can('update-donation')
                        @if ($retreat->hasDeposits && $retreat->end_date < now())
                            ({{ html()->a(url('donation/process_deposits/' . $retreat->id), "Process Retreat Deposits") }})
                        @endIf
                    @endCan
                    <br>
                @endCan
                <span class="font-weight-bold">{{ __('messages.last_updated_label') }} </span>{{ $retreat->updated_at?->format('F j, Y g:i A')}}<br>

            </div>

            <div class="col-lg-12">
                <h2>{{ __('messages.attachments_title') }}</h2>
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        @can('show-event-contract')
                        {!!$retreat->retreat_contract_link!!}
                        @endCan
                    </div>
                    <div class="col-lg-4 col-md-6">
                        @can('show-event-schedule')
                        {!!$retreat->retreat_schedule_link!!}
                        @endCan
                    </div>
                    <div class="col-lg-4 col-md-6">
                        @can('show-event-evaluation')
                        {!!$retreat->retreat_evaluations_link!!}
                        @endCan
                    </div>

                    @can('show-event-attachment')
                    @if ($attachments->isEmpty())
                    <p>{{ __('messages.no_attachments_message') }}</p>
                    @else
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Uploaded date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attachments->sortByDesc('upload_date') as $file)
                                <tr>
                                    <td><a href="{{url('retreat/'.$retreat->id.'/attachment/'.$file->uri)}}">{{ $file->uri }}</a></td>
                                    <td><a href="{{url('attachment/'.$file->id)}}">{{$file->description}}</a></td>
                                    <td>{{ $file->upload_date}}</td>
                                </tr>
                                @endforeach
                        </tbody>
                    </table>
                    @endif
                    @endCan
                </div>
            </div>

            @can('show-event-group-photo')
            @if (Storage::has('event/'.$retreat->id.'/group_photo.jpg'))
            <div class="col-lg-12">
                <h2>{{ __('messages.group_photo_title') }}</h2>
                <img src="{{url('retreat/'.$retreat->id).'/photo'}}" class="img" style="padding:5px; width:75%">
            </div>
            @endif
            @endCan

        </div>
    </div>

    <div class="col-lg-12">
        <div class="row">
            <div class="col-6 text-right">
                @can('update-retreat')
                <a href="{{ action([\App\Http\Controllers\RetreatController::class, 'edit'], $retreat->id) }}" class="btn btn-info">{{ html()->img(asset('images/edit.png'), 'Edit')->attribute('title', "Edit") }}</a>
                @endCan
            </div>
            <div class="col-6 text-left">
                @can('delete-retreat')
                {{ html()->form('DELETE', route('retreat.destroy', [$retreat->id]))->attribute('onsubmit', 'return ConfirmDelete()')->open() }}
                {{ html()->input('image', 'btnDelete')->class('btn btn-danger')->attribute('title', 'Delete')->attribute('src', asset('images/delete.png')) }}
                {{ html()->form()->close() }}
                @endCan
            </div>
        </div>
    </div>
    <div class="col-lg-12 mt-3">
        <div class="row" id='registrations'>
            <div class="col-lg-12">
                <h2>
                    {{$registrations->total()}} Registrations
                    {{($status ? '('.ucfirst($status).')' : NULL) }} for
                    @can('update-retreat')
                    {{ html()->a(url('retreat/' . $retreat->id . '/edit'), $retreat->title . ' (' . $retreat->idnumber . ')') }}
                    @else
                    {{$retreat->title.' ('.$retreat->idnumber.')'}}
                    @endCan
                </h2>
                @can('create-registration')
                    {{ html()->a(url(action([\App\Http\Controllers\RegistrationController::class, 'register'], $retreat->id)), __('messages.register_retreatant_button'))->class('btn btn-outline-dark') }}
                @endCan
                @can('show-contact')
                    {{ html()->a(url($retreat->email_registered_retreatants), __('messages.email_registered_retreatants_button'))->class('btn btn-outline-dark') }}
                @endCan
                @can('update-registration')
                    {{ html()->a(url(action([\App\Http\Controllers\RetreatController::class, 'assign_rooms'], $retreat->id)), __('messages.assign_rooms_button'))->class('btn btn-outline-dark') }}
                    @if (($retreat->start_date <= now()) && ($retreat->end_date >= now()))
                        {{ html()->a(url(action([\App\Http\Controllers\RetreatController::class, 'checkin'], $retreat->id)), __('messages.checkin_button'))->class('btn btn-outline-dark') }}
                    @endIf

                    @if ($retreat->end_date < now())
                        {{ html()->a(url(action([\App\Http\Controllers\RetreatController::class, 'checkout'], $retreat->id)), __('messages.checkout_button'))->class('btn btn-outline-dark') }}
                    @endIf
                @endCan
                <select class="custom-select col-3" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                    <option value="">{{ __('messages.filter_registrations_option') }}</option>
                    <option value="{{url('retreat/'.$retreat->id)}}">{{ __('messages.all') }}</option>
                    <option value="{{url('retreat/'.$retreat->id.'/status/active')}}">{{ __('messages.status_active') }}</option>
                    <option value="{{url('retreat/'.$retreat->id.'/status/arrived')}}">{{ __('messages.status_arrived') }}</option>
                    <option value="{{url('retreat/'.$retreat->id.'/status/canceled')}}">{{ __('messages.status_canceled') }}</option>
                    <option value="{{url('retreat/'.$retreat->id.'/status/confirmed')}}">{{ __('messages.status_confirmed') }}</option>
                    <option value="{{url('retreat/'.$retreat->id.'/status/dawdler')}}">{{ __('messages.status_dawdler') }}</option>
                    <option value="{{url('retreat/'.$retreat->id.'/status/departed')}}">{{ __('messages.status_departed') }}</option>
                    <option value="{{url('retreat/'.$retreat->id.'/status/retreatants')}}">{{ __('messages.status_retreatants') }}</option>
                    <option value="{{url('retreat/'.$retreat->id.'/status/unconfirmed')}}">{{ __('messages.status_unconfirmed') }}</option>
                    </select>
            </div>
            <div class="col-lg-12 mt-3">
                @if ($registrations->isEmpty())
                <div class="text-center">
                    <p>{{ __('messages.no_registrations_message') }}</p>
                </div>
                @else
                    @can('show-registration')
                        {{ $registrations->links() }}
                        <table class="table table-bordered table-striped table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>Date Registered</th>
                                    <th>Picture</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Room</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Notes</th>
                                    <th>Parish</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @foreach($registrations as $registration)
                                        @if ($registration->status_id == config('polanco.registration_status_id.waitlist'))
                                            <tr class="warning">
                                        @else
                                            <tr>
                                        @endif
                                        <td id='registration-{{$registration->id}}'><a href="{{action([\App\Http\Controllers\RegistrationController::class, 'show'], $registration->id)}}">{{ date('F d, Y', strtotime($registration->register_date)) }} </a>
                                            {{$registration->participant_role_name}} [{{ $loop->index +1 }}]
                                        </td>
                                        <td> {!!$registration->retreatant->avatar_small_link!!} </td>
                                        @if ($registration->retreatant->is_free_loader)
                                            <td class='table-warning'  data-toggle="tooltip" data-placement="top" title="Possible Freeloader">
                                        @else
                                            <td>
                                        @endIf
                                            {!!$registration->retreatant->contact_link_full_name!!} ({{$registration->retreatant_events_count}})</td>
                                        <td>
                                            @can('update-registration')
                                                {!! $registration->registration_status_buttons!!}
                                            @else
                                                {!! $registration->registration_status!!}
                                            @endCan
                                        </td>
                                        <td>
                                            @if (empty($registration->room->name))
                                                N/A
                                            @else
                                                <a href="{{action([\App\Http\Controllers\RoomController::class, 'show'], $registration->room->id)}}">{{ $registration->room->name}}</a>
                                            @endif
                                        </td>
                                        <td><a href="mailto:{{ $registration->retreatant->email_primary_text }}?subject={{ rawurlencode($retreat->title . ": Followup") }}">{{ $registration->retreatant->email_primary_text }}</a></td>
                                        <td>
                                            {!!$registration->retreatant->phone_home_mobile_number!!}
                                        </td>
                                        <td>
                                            {{ $registration->notes }} <br />
                                            <span>
                                                Health:
                                            </span>
                                            {!! (!empty($registration->retreatant->note_health->note)) ? "<div class=\"alert alert-danger alert-important\">" . $registration->retreatant->note_health->note . "</div>" : null !!}
                                            <br />
                                            <span>
                                                Dietary:
                                            </span>
                                            {!! (!empty($registration->retreatant->note_dietary->note)) ? "<div class=\"alert alert-info alert-important\">" . $registration->retreatant->note_dietary->note . "</div>" : null !!}
                                        </td>
                                        <td>
                                            @if (empty($registration->retreatant->parish_name))
                                            N/A
                                            @else
                                            {!! $registration->retreatant->parish_link!!}
                                            @endif
                                        </td>
                                        </tr>
                                    @endforeach

                            </tbody>
                        </table>
                    {{ $registrations->links() }}
                    @endCan
                @endif
            </div>
        </div>
    </div>
</div>
@stop
