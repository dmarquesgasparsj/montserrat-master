@extends('template')
@section('content')

<div class="row bg-cover">
    <div class="col-lg-12">
        <h1>AGC FY {{$total['year']}} Index - ({{$donations->total()}} donations)</h1>
        <p class="lead">${{number_format($total['paid'],2)}} of ${{number_format($total['pledged'],2)}} ({{number_format($total['percent'],0)}}%)</p>
        {{ $donations->links() }}
    </div>
    <div class="col-lg-12">
        @if ($donations->isEmpty())
            <div class="text-center">
                <p>It is an impoverished new world, there are no AGC donations!</p>
            </div>
        @else
            <table class="table table-bordered table-striped table-hover table-responsive">
                <thead>
                    <tr>
                        <th>Donation Date</th>
                        <th>Donor (Household name)</th>
                        <th>Description</th>
                        <th>Amount (Paid/Pledged)</th>
                        @if (is_null($total['unthanked']))
                            <th>Acknowledged (<a href='{{url("agc/".$total['year']."?unthanked=1")}}'>Show unthanked</a>)</th>
                        @else
                            <th>Acknowledged (<a href='{{url("agc/".$total['year'])}}'>Show all</a>)</th>
                        @endIf
                    </tr>
                </thead>
                <tbody>
                    @foreach($donations as $donation)
                        <tr>
                            <td><a href='{{url("donation/".$donation->donation_id)}}'"> {{ date('M d, Y', strtotime($donation->donation_date)) }} </a></td>
                            <td>{!! $donation->contact->contact_link_full_name ?? __('messages.unknown_contact') !!} ({{$donation->contact->agc_household_name}}) </td>
                            <td>{{ $donation->donation_description }} </td>
                            <td>${{number_format($donation->payments_paid,2)}}/${{ number_format($donation->donation_amount,2) }}</td>
                            <td>
                                @if(isset($donation['Thank You']))
                                    <a href='{{url("donation/".$donation->donation_id."/agc_acknowledge") }}'>{{$donation['Thank You']}}</a>
                                @else
                                    @if ($donation->percent_paid >= 100)
                                        <a href='{{ url("donation/".$donation->donation_id."/agc_acknowledge") }}'><img src='{{ url("/images/letter.png") }}' alt="{{ __('messages.print_acknowledgement') }}" title="{{ __('messages.print_acknowledgement') }}"></a>
                                    @else
                                        Awaiting full payment
                                    @endIf
                                @endIf
                                <a href='{{ url("person/".$donation->contact_id."/envelope?size=10&name=household&logo=0") }}'><img src='{{ url("/images/envelope.png") }}' alt="{{ __('messages.print_envelope') }}" title="{{ __('messages.print_envelope') }}"></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $donations->links() }}
        @endif
    </div>
</div>
@stop
