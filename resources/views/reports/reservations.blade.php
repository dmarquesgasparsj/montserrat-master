@extends('report')
@section('content')
<h2>Reservations</h2>
@if($reservations->isNotEmpty())
<table width="100%">
    <tr>
        <th style="width:30%">Retreatant</th>
        <th style="width:15%">Room</th>
        <th style="width:30%">Retreat</th>
        <th style="width:25%">Status</th>
    </tr>
    @foreach($reservations as $reservation)
    <tr>
        <td>{{ $reservation->retreatant->full_name }}</td>
        <td>{{ $reservation->room_name }}</td>
        <td>{{ $reservation->retreat_name }}</td>
        <td>{{ $reservation->participant_status }}</td>
    </tr>
    @endforeach
</table>
@else
<p>No reservations found.</p>
@endif
@endsection
