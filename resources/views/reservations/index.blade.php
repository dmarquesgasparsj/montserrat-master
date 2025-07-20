@extends('template')
@section('content')
<div class="row bg-cover">
    <div class="col-lg-12">
        <h2>
            Reservations
            <span class="options">
                <a href="#" data-toggle="modal" data-target="#reservationModal">
                    {{ html()->img(asset('images/create.png'), 'Add Reservation')->attribute('title', 'Add Reservation')->class('btn btn-light') }}
                </a>
            </span>
        </h2>
        <p class="lead">{{ $reservations->total() }} records</p>
    </div>
    <div class="col-lg-12 table-responsive-lg">
        @if ($reservations->isEmpty())
            <div class="col-lg-12 text-center py-5">
                <p>No reservations found!</p>
            </div>
        @else
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Guest</th>
                        <th>Retreat</th>
                        <th>Room</th>
                        <th>Start</th>
                        <th>End</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $reservation)
                    <tr>
                        <td><a href="{{ route('reservations.show', $reservation->id) }}">{{ $reservation->id }}</a></td>
                        <td>{!! $reservation->retreatant->contact_link_full_name ?? 'N/A' !!}</td>
                        <td>
                            @if($reservation->retreat)
                                <a href="{{ url('retreat/'.$reservation->event_id) }}">{{ $reservation->retreat->title }}</a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ optional($reservation->room)->name }}</td>
                        <td>{{ optional($reservation->arrived_at)->format('M d, Y') }}</td>
                        <td>{{ optional($reservation->departed_at)->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                    {{ $reservations->links() }}
                </tbody>
            </table>
        @endif
    </div>
</div>
@include('reservations.modal')
@endsection
