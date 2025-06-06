@extends('template')
@section('content')

    <section class="section-padding">
        <div class="jumbotron text-left">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>
                    <span class="grey">{{$persons->total()}} {{ __('messages.results_found') }}</span>
                    <span class="search"><a href={{ action([\App\Http\Controllers\SearchController::class, 'search']) }}>{{ html()->img(asset('images/search.png'), __('messages.new_search'))->attribute('title', __('messages.new_search'))->class('btn btn-link') }}</a></span></h1>
                </div>
                @if ($persons->isEmpty())
                    <p>{{ __('messages.oops_no_contacts_message') }}</p>
                @else
                <table class="table table-striped table-bordered table-hover"><caption><h2>{{ __('messages.contacts') }}</h2></caption>
                    <thead>
                        <tr>
                            <th>{{ __('messages.picture') }}</th>
                            <th>{{ __('messages.name') }}</th>
                            <th>{{ __('messages.address_city') }}</th>
                            <th>{{ __('messages.home_phone') }}</th>
                            <th>{{ __('messages.cell_phone') }}</th>
                            <th>{{ __('messages.email') }}</th>
                            <th>{{ __('messages.parish_city') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($persons as $person)
                        <tr>
                            <td>{!!$person->avatar_small_link!!}</td>
                            <td>{!!$person->contact_link_full_name!!}</td>
                            <td>
                                @if($person->do_not_mail)
                                    <div class="alert alert-warning alert-important"><strong>{{ __('messages.do_not_mail') }}</strong></div>
                                @endIf
                                {!!$person->address_primary_google_map!!}
                            </td>
                            <td>
                                @if($person->do_not_phone)
                                    <div class="alert alert-warning alert-important"><strong>{{ __('messages.do_not_call') }}</strong></div>
                                @endIf
                                @if($person->do_not_sms)
                                    <div class="alert alert-warning alert-important"><strong>{{ __('messages.do_not_text') }}</strong></div>
                                @endIf
                                @foreach($person->phones as $phone)
                                @if (($phone->location_type_id==1) and ($phone->phone_type=="Phone"))
                                <a href="tel:{{ $phone->phone }}">{{ $phone->phone }}</a>
                                @endif
                                @endforeach

                            <td>

                                @if($person->do_not_phone)
                                    <div class="alert alert-warning alert-important"><strong>{{ __('messages.do_not_call') }}</strong></div>
                                @endIf
                                @if($person->do_not_sms)
                                    <div class="alert alert-warning alert-important"><strong>{{ __('messages.do_not_text') }}</strong></div>
                                @endIf
                                @foreach($person->phones as $phone)
                                @if (($phone->location_type_id==1) and ($phone->phone_type=="Mobile"))
                                <a href="tel:{{ $phone->phone }}">{{ $phone->phone }}</a>
                                @endif
                                @endforeach
                            </td>
                            <td>

                                @if($person->do_not_email)
                                    <div class="alert alert-warning alert-important"><strong>{{ __('messages.do_not_email') }}</strong></div>
                                @endIf
                                @foreach($person->emails as $email)
                                @if ($email->is_primary)
                                <a href="mailto:{{ $email->email }}">{{ $email->email }}</a>
                                @endif
                                @endforeach
                            </td>
                            <td>
                                {!! $person->parish_link !!}
                            </td>
                        </tr>
                        @endforeach
                    {{ $persons->links() }}
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </section>
@stop
