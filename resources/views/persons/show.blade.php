@extends('template')
@section('content')

<div class="row bg-cover">
    <div class="col-lg-12">
        <div class="row text-center">
            <div class="col-lg-12">
                {!!$person->avatar_large_link!!}
            </div>

            @if ($person->is_free_loader) 
                <div class="col-lg-12 bg-warning"  data-toggle="tooltip" data-placement="top" title="Possible freeloader">
            @else
                <div class="col-lg-12">
            @endIf
                @can('update-contact')
                    <h1><span class="font-weight-bold"><a href="{{url('person/'.$person->id.'/edit')}}">{{ $person->full_name }}</a></span></h1>
                @else
                    <h1><span class="font-weight-bold">{{ $person->full_name }}</span></h1>
                @endCan
            </div>

            <div class="col-lg-12">
                {{ html()->a(url('#contact_info'), 'Contact')->class('m-1 btn btn-outline-dark') }}
                {{ html()->a(url('#demographics'), 'Demographics')->class('m-1 btn btn-outline-dark') }}
                {{ html()->a(url('#groups'), 'Groups')->class('m-1 btn btn-outline-dark') }}
                {{ html()->a(url('#notes'), 'Notes')->class('m-1 btn btn-outline-dark') }}
                @can('show-relationship'){{ html()->a(url('#relationships'), 'Relationships')->class('m-1 btn btn-outline-dark') }} @endCan
                @can('show-registration'){{ html()->a(url('#registrations'), 'Registrations')->class('m-1 btn btn-outline-dark') }} @endCan
                @can('show-touchpoint'){{ html()->a(url('#touchpoints'), 'Touchpoints')->class('m-1 btn btn-outline-dark') }} @endCan
                @can('show-attachment'){{ html()->a(url('#attachments'), 'Attachments')->class('m-1 btn btn-outline-dark') }} @endCan
                @can('show-donation') {{ html()->a(url('#donations'), 'Donations')->class('m-1 btn btn-outline-dark') }} @endCan
            </div>
            <div class="col-lg-12 mt-2">
                @can('show-group')
                @if ($person->is_board_member) <span><a href={{ action([\App\Http\Controllers\PersonController::class, 'boardmembers']) }}>{{ html()->img(asset('images/board.png'), __('messages.board_members_group'))->attribute('title', __('messages.board_members_group'))->class('m-1 btn btn-outline-dark') }}</a></span> @endIf
                @if ($person->is_ambassador) <span><a href={{ action([\App\Http\Controllers\PersonController::class, 'ambassadors']) }}>{{ html()->img(asset('images/ambassador.png'), 'Ambassador Group')->attribute('title', "Ambassadors Group")->class('m-1 btn btn-outline-dark') }}</a></span> @endIf
                @if ($person->is_staff) <span><a href={{ action([\App\Http\Controllers\PersonController::class, 'staff']) }}>{{ html()->img(asset('images/employee.png'), 'Staff Group')->attribute('title', "Employees Group")->class('m-1 btn btn-outline-dark') }}</a></span> @endIf
                @if ($person->is_steward) <span><a href={{ action([\App\Http\Controllers\PersonController::class, 'stewards']) }}>{{ html()->img(asset('images/steward.png'), 'Steward Group')->attribute('title', "Stewards Group")->class('m-1 btn btn-outline-dark') }}</a></span> @endIf
                @if ($person->is_volunteer) <span><a href={{ action([\App\Http\Controllers\PersonController::class, 'volunteers']) }}>{{ html()->img(asset('images/volunteer.png'), 'Volunteers Group')->attribute('title', "Volunteers Group")->class('m-1 btn btn-outline-dark') }}</a></span> @endIf
                @if ($person->is_retreat_director) <span><a href={{ action([\App\Http\Controllers\PersonController::class, 'directors']) }}>{{ html()->img(asset('images/director.png'), 'Retreat Directors Group')->attribute('title', "Directors Group")->class('m-1 btn btn-outline-dark') }}</a></span> @endIf
                @if ($person->is_retreat_innkeeper) <span><a href={{ action([\App\Http\Controllers\PersonController::class, 'innkeepers']) }}>{{ html()->img(asset('images/innkeeper.png'), 'Retreat Innkeepers Group')->attribute('title', "Innkeepers Group")->class('m-1 btn btn-outline-dark') }}</a></span> @endIf
                @if ($person->is_retreat_assistant) <span><a href={{ action([\App\Http\Controllers\PersonController::class, 'assistants']) }}>{{ html()->img(asset('images/assistant.png'), 'Retreat Assistants Group')->attribute('title', "Assistants Group")->class('m-1 btn btn-outline-dark') }}</a></span> @endIf
                @if ($person->is_bishop) <span><a href={{ action([\App\Http\Controllers\PersonController::class, 'bishops']) }}>{{ html()->img(asset('images/bishop.png'), 'Bishops Group')->attribute('title', "Bishop Group")->class('m-1 btn btn-outline-dark') }}</a></span> @endIf
                @if ($person->is_pastor) <span><a href={{ action([\App\Http\Controllers\PersonController::class, 'pastors']) }}>{{ html()->img(asset('images/pastor.png'), 'Pastors Group')->attribute('title', "Pastors Group")->class('m-1 btn btn-outline-dark') }}</a></span> @endIf
                @if ($person->is_priest) <span><a href={{ action([\App\Http\Controllers\PersonController::class, 'priests']) }}>{{ html()->img(asset('images/priest.png'), 'Priests Group')->attribute('title', "Priests Group")->class('m-1 btn btn-outline-dark') }}</a></span> @endIf
                @if ($person->is_deacon) <span><a href={{ action([\App\Http\Controllers\PersonController::class, 'deacons']) }}>{{ html()->img(asset('images/deacon.png'), 'Deacons Group')->attribute('title', "Deacons Group")->class('m-1 btn btn-outline-dark') }}</a></span> @endIf
                @if ($person->is_provincial) <span><a href={{ action([\App\Http\Controllers\PersonController::class, 'provincials']) }}>{{ html()->img(asset('images/provincial.png'), 'Provincials Group')->attribute('title', "Provincials Group")->class('m-1 btn btn-outline-dark') }}</a></span> @endIf
                @if ($person->is_superior) <span><a href={{ action([\App\Http\Controllers\PersonController::class, 'superiors']) }}>{{ html()->img(asset('images/superior.png'), 'Superiors Group')->attribute('title', "Superiors Group")->class('m-1 btn btn-outline-dark') }}</a></span> @endIf
                @if ($person->is_jesuit) <span><a href={{ action([\App\Http\Controllers\PersonController::class, 'jesuits']) }}>{{ html()->img(asset('images/jesuit.png'), 'Jesuits Group')->attribute('title', "Jesuits Group")->class('m-1 btn btn-outline-dark') }}</a></span> @endIf
                @endCan
            </div>
            <div class="col-lg-12 mt-2">
                @can('create-touchpoint')
                <span class="m-1 btn btn-outline-dark">
                    <a href={{ action([\App\Http\Controllers\TouchpointController::class, 'add'],$person->id) }}>Add Touchpoint</a>
                </span>
                @endCan
                @can('create-registration')
                <span class="m-1 btn btn-outline-dark">
                    <a href={{ action([\App\Http\Controllers\RegistrationController::class, 'add'],$person->id) }}>Add Registration</a>
                </span>
                @endCan
                 <span class="m-1 btn btn-outline-dark">
                    <a href={{ action([\App\Http\Controllers\PageController::class, 'contact_info_report'],$person->id) }}>Contact Info Report</a>
                </span>
                <span class="m-1 btn btn-outline-dark">
                    <a href={{ URL('person/'.$person->id.'/envelope?size=10&logo=0') }}><img src={{URL::asset('images/envelope.png')}} title="Print envelope" alt="Print envelope"></a>
                </span>
                <span class="m-1 btn btn-outline-dark">
                    <a href={{ URL('person/'.$person->id.'/envelope?size=9x6&logo=1') }}><img src={{URL::asset('images/envelope9x6.png')}} title="Print 9x6 envelope" alt="Print 9x6 envelope"></a>
                </span>
            </div>
        </div>
    </div>
    <div class="col-lg-12 mt-5">
        <div class="row">
            <div class="col-lg-6" id="basic_info">
                <h2>Basic Information</h2>
                <p>
                    <span class="font-weight-bold">Title: </span>{{ (!empty($person->prefix_name)) ? $person->prefix_name : null }}
                    <br><span class="font-weight-bold">First Name: </span>{{ (!empty($person->first_name)) ? $person->first_name : null }}
                    <br><span class="font-weight-bold">Middle Name: </span>{{ (!empty($person->middle_name)) ? $person->middle_name : null}}
                    <br><span class="font-weight-bold">Last Name: </span>{{ (!empty($person->last_name)) ? $person->last_name : null}}
                    <br><span class="font-weight-bold">Suffix: </span>{{$person->suffix_name}}
                    <br><span class="font-weight-bold">Nick name:</span> {{ (!empty($person->nick_name)) ? $person->nick_name : null }}
                    <br><span class="font-weight-bold">Display name: </span>{{ (!empty($person->display_name)) ? $person->display_name : null }}
                    <br><span class="font-weight-bold">Sort name: </span>{{ (!empty($person->sort_name)) ? $person->sort_name : null }}
                    <br><span class="font-weight-bold">AGC Household name: </span>{{ (!empty($person->agc_household_name)) ? $person->agc_household_name : null }}
                    <br><span class="font-weight-bold">Contact type: </span>{{ $person->contact_type_label }}
                    <br><span class="font-weight-bold">Subcontact type: </span>{{ $person->subcontact_type_label }}
                </p>
            </div>
            <div class="col-lg-6  alert alert-danger alert-important" id="safety_info">
                <h2>Emergency Contact Information</h2>
                <p>
                    <span class="font-weight-bold">Name: </span>{{ !empty($person->emergency_contact->name) ? $person->emergency_contact->name : 'N/A' }}
                    <br><span class="font-weight-bold">Relationship: </span>{{ !empty($person->emergency_contact->relationship) ? $person->emergency_contact->relationship : 'N/A' }}
                    <br><span class="font-weight-bold">Phone:</span> {{ !empty($person->emergency_contact->phone) ? $person->emergency_contact->phone : 'N/A' }}
                    <br><span class="font-weight-bold">Alt phone:</span> {{ !empty($person->emergency_contact->phone_alternate) ? $person->emergency_contact->phone_alternate: 'N/A' }}
                </p>
                <h2>Health and Dietary Information</h2>
                <p>
                    <span class="font-weight-bold">Health notes: </span>{{$person->note_health}}
                    <br><span class="font-weight-bold">Dietary notes: </span>{{$person->note_dietary}}
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 " id="contact_info">
                <h2>Contact Information</h2>
                <div class="row">
                    <strong>Preferred communication method: </strong> {{ config('polanco.preferred_communication_method.'.$person->preferred_communication_method) }}
                </div>
                <div class="row">
                    @if($person->do_not_phone)
                        <div class="alert alert-warning alert-important col-lg-12 col-lg-6 m-2" role="alert">
                            <span class="font-weight-bold">Do Not Call</span>
                        </div>
                    @endIf
                    @if($person->do_not_sms)
                        <div class="alert alert-warning alert-important col-lg-12 col-lg-6 m-2" role="alert">
                            <span class="font-weight-bold">Do Not Text</span>
                        </div>
                    @endIf
                    @if($person->do_not_mail)
                        <div class="alert alert-warning alert-important col-lg-12 col-lg-6 m-2" role="alert">
                            <span class="font-weight-bold">Do Not Mail</span>
                        </div>
                    @endIf
                    @if($person->do_not_email)
                        <div class="alert alert-warning alert-important col-lg-12 col-lg-6 m-2" role="alert">
                            <span class="font-weight-bold">Do Not Email</span>
                        </div>
                    @endIf
                </div>
                <div><h3>Address(es) [Primary: {{ $person->primary_address_location_name }}]</h3>
                @foreach($person->addresses as $address)
                    @if (!empty($address->street_address))
                        @if($address->is_primary) * @endIf
                        <span class="font-weight-bold">{{$address->location->display_name}}:</span>
                        <address class="d-inline">{!!$address->google_map!!}</address>
                        @can('delete-address')
                            {{ html()->form('DELETE', route('address.destroy', [$address->id]))->attribute('onsubmit', 'return ConfirmDelete()')->class('d-inline')->open() }}
                                <button type="submit" class="m-1 btn btn-outline-dark btn-sm"><i class="fas fa-trash"></i></button>
                            {{ html()->form()->close() }}
                        @endCan
                        <br>
                    @endif
                @endforeach
            </div>
                <div><h3>Phone(s) [Primary: {{ $person->primary_phone_location_name}} {{ $person->primary_phone_type }}]</h3>
                    @foreach($person->phones as $phone)
                        @if(!empty($phone->phone))
                            @if($phone->is_primary) * @endIf
                            <span class="font-weight-bold">{{$phone->location->display_name}} - {{$phone->phone_type}}: </span>
                            <a href="tel:{{$phone->phone}}{{$phone->phone_extension}}">{{$phone->phone}}{{$phone->phone_extension}}</a>
                            <br>
                        @endif
                    @endforeach
                </div>
                <div><h3>Email(s) [Primary: {{ $person->primary_email_location_name }}]</h3>
                    @foreach($person->emails as $email)
                        @if(!empty($email->email))
                            @if($email->is_primary) * @endIf
                            <span class="font-weight-bold">{{$email->location->display_name}} - Email: </span><a href="mailto:{{$email->email}}">{{$email->email}}</a>
                            <br>
                        @endif
                    @endforeach
                </div>
                <div><h3>Website(s)</h3>
                @foreach($person->websites as $website)
                    @if(!empty($website->url))
                        <span class="font-weight-bold">{{$website->website_type}} - URL: </span><a href="{{$website->url}}" target="_blank">{{$website->url}}</a>
                        <br>
                    @endif
                @endforeach
                </div>
            </div>
            <div class="col-lg-6" id="demographics">
                <h2>Demographics</h2>
                <p>
                    <span class="font-weight-bold">Gender: </span>{{ !empty($person->gender_name) ? $person->gender_name : 'N/A' }}
                    <br><span class="font-weight-bold">Birth Date: </span> {{ !empty($person->birth_date) ? $person->birth_date->format('F d, Y') : 'N/A' }}
                    <br><span class="font-weight-bold">Religion: </span> {{ !empty($person->religion_id) ? $person->religion_name : 'N/A' }}
                    <br><span class="font-weight-bold">Occupation: </span> {{ !empty($person->occupation_id) ? $person->occupation_name : 'N/A' }}
                    <br><span class="font-weight-bold">Ethnicity: </span> {{ !empty($person->ethnicity_id) ? $person->ethnicity_name : 'N/A' }}
                    <br><span class="font-weight-bold">Parish: </span> {!! !empty($person->parish_link) ? $person->parish_link : 'N/A' !!}
                    <br><span class="font-weight-bold">Preferred Language: </span> {{ !empty($person->preferred_language) ? $person->preferred_language_label : 'N/A' }}
                    <br><span class="font-weight-bold">Languages: </span>
                    @if(!empty(array_filter((array)$person->languages)))
                        <ul>
                            @foreach($person->languages as $language)
                                <li>{{$language->label}}</li>
                            @endforeach
                        </ul>
                    @else
                        N/A
                    @endif
                </p>
            </div>
            <div class="col-lg-6 " id="other_info">
                <h2>Other</h2>
                <p>
                    <span class="font-weight-bold">Referral sources: </span>
                    @if(!empty(array_filter((array)$person->referrals)))
                    <ul>
                        @foreach($person->referrals as $referral)
                            <li>{{$referral->name}}</li>
                        @endforeach
                    </ul>
                    @else
                        N/A
                    @endif
                    <br>
                    <span class="font-weight-bold">Deceased: </span>
                    @if ($person->is_deceased)
                        Yes
                    @else
                        No
                    @endIf
                    <br>
                    <span class="font-weight-bold">Deceased Date: </span>
                    @if (!empty($person->deceased_date))
                        {{date('F d, Y', strtotime($person->deceased_date))}}
                    @else
                        N/A
                    @endif
                </p>
            </div>
            @can('show-group')
            <div class="col-lg-6 " id="groups">
                <h2>Groups</h2>
                @if(!empty(array_filter((array)$person->groups)))
                    <ul>
                    @foreach($person->groups as $group)
                        <li><a href="../group/{{ $group->group_id}}">{{ $group->group->name }}</a></li>
                    @endforeach
                    </ul>
                @else
                    This person does not belong to any groups.
                @endif
            </div>
            @endCan
            <div class="col-lg-6 " id="notes">
                <h2>Notes</h2>
                <p><span class="font-weight-bold">General: </span> {!! $person->note_contact ? $person->note_contact : 'N/A' !!}
                <br><span class="font-weight-bold">Room Preference: </span> {!! $person->note_room_preference ? $person->note_room_preference : 'N/A' !!}</p>
            </div>

            @can('show-relationship')
            <div class="col-lg-6 " id="relationships">
                <h2>Relationships ({{ $person->a_relationships->count() + $person->b_relationships->count() }})</h2>
                <ul>
                    @foreach($person->a_relationships as $a_relationship)
                    <li>
                        @can('delete-relationship')
                            {{ html()->form('DELETE', route('relationship.destroy', [$a_relationship->id]))->attribute('onsubmit', 'return ConfirmDelete()')->open() }}
                                {!!$person->contact_link_full_name!!} is {{ $a_relationship->is_former }} {{ $a_relationship->relationship_type->label_a_b }} {!! $a_relationship->contact_b->contact_link_full_name !!}
                                <button type="submit" class="m-1 btn btn-outline-dark btn-sm"><i class="fas fa-trash"></i></button>
                            {{ html()->form()->close() }}
                        @else
                            {!!$person->contact_link_full_name!!} is {{ $a_relationship->relationship_type->label_a_b }} {!! $a_relationship->contact_b->contact_link_full_name !!}
                        @endCan
                    </li>
                    @endforeach

                    @foreach($person->b_relationships as $b_relationship)
                    <li>
                        @can('delete-relationship')
                            {{ html()->form('DELETE', route('relationship.destroy', [$b_relationship->id]))->attribute('onsubmit', 'return ConfirmDelete()')->open() }}
                                {!!$person->contact_link_full_name!!} is {{ $b_relationship->is_former }} {{ $b_relationship->relationship_type->label_b_a }} {!! $b_relationship->contact_a->contact_link_full_name!!}
                                <button type="submit" class="m-1 btn btn-outline-dark btn-sm"><i class="fas fa-trash"></i></button>
                            {{ html()->form()->close() }}
                        @else
                            {!!$person->contact_link_full_name!!} is {{ $b_relationship->relationship_type->label_b_a }} {!! $b_relationship->contact_a->contact_link_full_name!!}
                        @endCan
                    </li>
                    @endforeach
                </ul>
                @can('create-relationship')
                <div class = "border card border-secondary form-group">
                {{ html()->form('POST', route('relationship_type.addme', ))->open() }}
                    <div class = "card-title p-2 m-1 h4">
                        Create a New Relationship
                    </div>
                    <div class="card-body p-2 m-1">
                        <div class="row">
                            <div class="col-lg-4">
                                {{ html()->label('Relationship: ', 'relationship_type_name')->class('font-weight-bold') }}
                                {{ html()->select('relationship_type_name', $relationship_types)->class('form-control') }}
                                {{ html()->hidden('contact_id', $person->id) }}
                            </div>
                            <div class="col-lg-4">
                                {{ html()->label('Alternate name: ', 'relationship_filter_alternate_name')->class('font-weight-bold') }}
                                {{ html()->text('relationship_filter_alternate_name')->class('form-control') }}
                            </div>
                            <div class="col-lg-4">
                            {{ html()->submit('Create')->class('m-1 btn btn-primary') }}
                            {{ html()->form()->close() }}
                            </div>
                        </div>
                    </div>
                </div>
                @endCan
            </div>
            @endCan
            @can('show-registration')
            <div class="col-lg-12  mt-3" id="registrations">
                <h2>Retreat Participation ({{$registrations->total()}})</h2>
                {{ $registrations->links() }}
                @foreach($registrations->sortByDesc('retreat_start_date') as $registration)
                    <div class="p-3 mb-2 rounded {{ $registration->canceled_at ? 'bg-warning' : 'bg-light'}}">
                        {!!$registration->event_link!!} ({{date('F j, Y', strtotime($registration->retreat_start_date))}} - {{date('F j, Y', strtotime($registration->retreat_end_date))}}) - <u>{{$registration->participant_role_name}}</u> ({{$registration->participant_status}})
                        <a href="{{ url('registration/'.$registration->id) }}">
                            View Registration
                        </a>
                        [{{ $registration->source ? $registration->source : 'N/A' }}]
                    </div>
                @endforeach
            </div>
            @endCan
            @can('show-touchpoint')
            <div class="col-lg-12  mt-3" id="touchpoints">
                <h2>Touchpoints ({{ $touchpoints->total() }})</h2>
                @can('create-touchpoint')
                <button class="m-1 btn btn-outline-dark"><a href={{ action([\App\Http\Controllers\TouchpointController::class, 'add'],$person->id) }}>Add Touchpoint</a></button>
                @endCan
                @if ($touchpoints->isEmpty())
                    <p>There are no touchpoints for this person.</p>
                @else
                    <table class="table table-striped table-responsive-lg">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Contacted by</th>
                                <th>Type of contact</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($touchpoints->sortByDesc('touched_at') as $touchpoint)
                            <tr>
                                <td><a href="{{url('touchpoint/'.$touchpoint->id)}}">{{ $touchpoint->touched_at }}</a></td>
                                <td>{!! $touchpoint->staff->contact_link_full_name ?? __('messages.unknown_staff_member') !!}</td>
                                <td>{{ $touchpoint->type }}</td>
                                <td>{{ $touchpoint->notes }}</td>
                            </tr>
                            @endforeach
                            {{ $touchpoints->links() }}
                        </tbody>
                    </table>
                @endif
            </div>
            @endCan
            @can('show-attachment')
            <div class="col-lg-12  mt-3" id="attachments">
                <h2>Attachments ({{ $files->count() }})</h2>
                @if ($files->isEmpty())
                    <p>There are no attachments for this person.</p>
                @else
                    <table class="table table-striped table-bordered table-hover table-responsive-md">
                        <thead>
                            <tr>
                                <th>File Name</th>
                                <th>Description
                                <th>Uploaded date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($files->sortByDesc('upload_date') as $file)
                            <tr>
                                <td><a href="{{url('contact/'.$person->id.'/attachment/'.$file->uri)}}">{{ $file->uri }}</a></td>
                                <td><a href="{{url('attachment/'.$file->id)}}">{{$file->description_text}}</a></td>
                                <td>{{ $file->upload_date}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            @endCan
            @can('show-donation')
            <div class="col-lg-12 mt-3" id="donations">

              <h2>
                  {{$person->donations->count('donation_id') }} Donation(s) for {{ $person->display_name }}
                      - ${{$person->donations->sum('payments_paid')}} paid of
                  ${{$person->donations->sum('donation_amount') }} pledged
                  @if ($person->donations->sum('donation_amount') > 0)
                  [{{($person->donations->sum('payments_paid') / $person->donations->sum('donation_amount'))*100}}%]
                  @endif
              </h2>
                @can('create-donation')
                    {{ html()->a(url(route('donation.add', [$person->id])), 'Add donation')->class('m-1 btn btn-outline-dark') }}
                @endCan
                {{ html()->a(url(action([\App\Http\Controllers\PageController::class, 'eoy_acknowledgment'], $person->id)), 'EOY Acknowledgment')->class('m-1 btn btn-outline-dark') }}

                @if ($donations->isEmpty())
                    <p>No donations for this person!</p>
                @else
                    <table class="table table-striped table-responsive-lg">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Paid/Pledged [%]</th>
                                <th>Terms</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($donations->sortByDesc('donation_date') as $donation)
                            <tr>
                                <td><a href="{{url('donation/'.$donation->donation_id)}}"> {{ $donation->donation_date_formatted}} </a></td>
                                <td> {{ $donation->donation_description.': #'.$donation->retreat?->idnumber }}</td>

                                @if ($donation->donation_amount - $donation->payments->sum('payment_amount') > 0.001)
                                  <td class="alert alert-warning alert-important" style="padding:0px;">
                                @endIf
                                @if ($donation->donation_amount - $donation->payments->sum('payment_amount') < -0.001)
                                  <td class="alert alert-danger alert-important" style="padding:0px;">
                                @endIf
                                @if (abs($donation->donation_amount - $donation->payments->sum('payment_amount')) < 0.001)
                                  <td>
                                @endIf

                                ${{number_format($donation->payments->sum('payment_amount'),2)}}
                                    / ${{number_format($donation->donation_amount,2) }}
                                    [{{$donation->percent_paid}}%]
                                </td>

                                <td> {{ $donation->terms }}</td>
                                <td> {{ $donation->Notes }}</td>
                            </tr>
                            @endforeach
                            {{ $donations->links() }}
                        </tbody>
                    </table>
                @endif
            </div>
            @endCan
        </div>
        <div class="row" id="commands">
          <div class='col-lg-6  mt-4 mb-4'>
            @can('update-contact')
                <a href="{{ action([\App\Http\Controllers\PersonController::class, 'edit'], $person->id) }}" class="m-1 btn btn-info mr-4">
                  {{ html()->img(asset('images/edit.png'), 'Edit')->attribute('title', "Edit") }}
                </a>
            @endCan

            @can('delete-contact')
                {{ html()->form('DELETE', route('person.destroy', [$person->id]))->attribute('onsubmit', 'return ConfirmDelete()')->class('d-inline')->open() }}
                {{ html()->input('image', 'btnDelete')->class('m-1 btn btn-danger')->attribute('title', 'Delete')->attribute('src', asset('images/delete.png')) }}
                {{ html()->form()->close() }}
            @endCan
        </div>
      </div>
</div>
</div>
@stop
