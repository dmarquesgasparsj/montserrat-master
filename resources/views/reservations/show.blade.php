@extends('template')
@section('content')
<div class="row bg-cover">
    <div class="col-lg-12">
        <h2>
            Reservation #{{ $reservation->id }}
            <span class="options">
                <a href="{{ route('reservations.edit', $reservation->id) }}">
                    {{ html()->img(asset('images/edit.png'), 'Edit')->class('btn btn-light') }}
                </a>
            </span>
        </h2>
    </div>
    <div class="col-lg-12">
        <p><strong>Guest:</strong> {!! $reservation->retreatant->contact_link_full_name ?? 'N/A' !!}</p>
        <p><strong>Retreat:</strong>
            @if($reservation->retreat)
                <a href="{{ url('retreat/'.$reservation->event_id) }}">{{ $reservation->retreat->title }}</a>
            @else
                N/A
            @endif
        </p>
        <p><strong>Room:</strong> {{ optional($reservation->room)->name }}</p>
        <p><strong>Start:</strong> {{ optional($reservation->arrived_at)->format('M d, Y') }}</p>
        <p><strong>End:</strong> {{ optional($reservation->departed_at)->format('M d, Y') }}</p>
    </div>
</div>
@endsection
