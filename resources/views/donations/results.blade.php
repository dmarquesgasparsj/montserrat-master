@extends('template')
@section('content')

<section class="section-padding">
    <div class="jumbotron text-left">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>
                    <span class="grey">{{$donations->total()}} {{ __('messages.results_found') }}</span>
                    <span class="search"><a href={{ action([\App\Http\Controllers\DonationController::class, 'search']) }}>{{ html()->img(asset('images/search.png'), __('messages.new_search'))->attribute('title', __('messages.new_search'))->class('btn btn-link') }}</a></span>
                </h1>
                <p class="lead">${{number_format($all_donations->sum('payments_paid'),2)}} paid of ${{number_format($all_donations->sum('donation_amount'),2) }} pledged
                  @if ($all_donations->sum('donation_amount') > 0)
                    ({{number_format(($all_donations->sum('payments_paid')/$all_donations->sum('donation_amount'))*100,0)}}%)
                  @endif
                </p>
            </div>
            @if ($donations->isEmpty())
            <p>{{ __('messages.oops_no_contacts_message') }}</p>
            @else
            <table class="table table-striped table-bordered table-hover">
                <caption>
                    <h2>{{ __('messages.donations') }}</h2>
                </caption>
                <thead>
                    <tr>
                        <th>{{ __('messages.date') }}</th>
                        <th>{{ __('messages.donor') }}</th>
                        <th>{{ __('messages.description') }}</th>
                        <th>{{ __('messages.amount') }}</th>
                        <th>{{ __('messages.event') }}</th>
                        <th>{{ __('messages.notes') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donations as $donation)
                    <tr>
                        <td><a href="{{ URL('donation/'. $donation->donation_id) }}">{{ date('M d, Y g:i A', strtotime($donation->donation_date)) }}</a></td>
                        <td>{!! $donation->contact->contact_link_full_name ?? __('messages.unknown_contact') !!} </td>
                        <td>{{ $donation->donation_description }} </td>
                        <td>{{ '$'.$donation->donation_amount }}</td>
                        <td>{!! $donation->retreat_link !!}</td>
                        <td>{{ $donation->Notes }}</td>
                    </tr>
                    @endforeach
                    {{ $donations->links() }}
                </tbody>
            </table>
            @endif
        </div>
    </div>
</section>
@stop
