@extends('template')
@section('content')

<div class="row bg-cover">
    <div class="col-lg-12 text-center">
        <h2>Room Schedules for</h2>
        <h2>
            {!!$previous_link!!}
            {{$dts[0]->format('F d, Y')}} - {{$dts[31]->format('F d, Y')}} 
            {!!$next_link!!}
        </h2>
        <p class="lead">
            <span class="table-success">Available</span>
            <span class="table-warning">Reserved</span>
            <span class="table-info">Occupied</span>
            <span class="table-danger">Cleaning Needed</span>
            <span class="table-secondary">Maintenance Required</span>
        </p>
    </div>

    <div class="col-lg-12 text-center">
        @if (empty($m))
            
                <p>Yikes, there is nothing to schedule!</p>
            </div>
        @else
            <table class="table-sm table-bordered table-hover mx-auto">
                <thead>
                    <tr>
                        <th scope="col">Room</th>
                        @foreach($dts as $dt)
                        <th scope="col">{{$dt->day}}</th>
                        @endforeach
                    </tr>                   
                </thead>
                <tbody>
                    @if ($roomsort->isEmpty())
                        <p> Yikes, there are no rooms!</p>
                    @else
                        
                        @foreach($roomsort as $room)

                        <tr>
                            <th scope="row">
                                <a href="{{url('room/'.$room->id)}}">{{$room->location->name}} {{$room->name}}</a>
                            </th>

                            @foreach($dts as $dt)
                                @php $status = $m[$room->id][$dt->toDateString()]['status']; @endphp
                                @if ($status == 'O')
                                <td class="table-info room-cell" data-room-id="{{$room->id}}" data-date="{{$dt->toDateString()}}">
                                    {{ html()->a(url('registration/' . $m[$room->id][$dt->toDateString()]['registration_id']), '&nbsp;')->attribute('title', $m[$room->id][$dt->toDateString()]['retreat_name'] . ' (' . $m[$room->id][$dt->toDateString()]['retreatant_name'] . ')')->attribute('class', 'reservation')->attribute('data-registration-id', $m[$room->id][$dt->toDateString()]['registration_id']) }}
                                @elseif ($status == 'R')
                                <td class="table-warning room-cell" data-room-id="{{$room->id}}" data-date="{{$dt->toDateString()}}">
                                    {{ html()->a(url('registration/' . $m[$room->id][$dt->toDateString()]['registration_id']), '&nbsp;')->attribute('title', $m[$room->id][$dt->toDateString()]['retreat_name'] . ' (' . $m[$room->id][$dt->toDateString()]['retreatant_name'] . ')')->attribute('class', 'reservation')->attribute('data-registration-id', $m[$room->id][$dt->toDateString()]['registration_id']) }}
                                @elseif ($status == 'C')
                                <td class="table-danger room-cell" data-room-id="{{$room->id}}" data-date="{{$dt->toDateString()}}">
                                @elseif ($status == 'M')
                                <td class="table-secondary room-cell" data-room-id="{{$room->id}}" data-date="{{$dt->toDateString()}}">
                                @else
                                <td class="table-success room-cell" data-room-id="{{$room->id}}" data-date="{{$dt->toDateString()}}">
                                @endif
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
    
                        
                    @endif
                </tbody>
            </table>
        @endif
    </div>
</div>

<!-- Reservation Modal -->
<div class="modal fade" id="reservationModal" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservationModalLabel">Add Reservation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ html()->form('POST', route('rooms.create-reservation'))->id('reservationForm')->open() }}
                    {{ html()->hidden('room_id')->id('reservation-room-id') }}
                    <div class="form-group">
                        {{ html()->label('Start Date', 'reservation-start-date') }}
                        {{ html()->text('start_date')->class('form-control flatpickr-date')->id('reservation-start-date') }}
                    </div>
                    <div class="form-group">
                        {{ html()->label('End Date', 'reservation-end-date') }}
                        {{ html()->text('end_date')->class('form-control flatpickr-date')->id('reservation-end-date') }}
                    </div>
                {{ html()->form()->close() }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="reservationForm">Save</button>
            </div>
        </div>
    </div>
</div>

    {{-- <section class="section-padding">
        <div class="jumbotron text-left">
            <div class="panel panel-default">
                <div class="panel-heading" style="text-align: center">
                    <h1>Room Schedules for</h1>
                    <h1>
                        {!!$previous_link!!}
                        {{$dts[0]->format('F d, Y')}} - {{$dts[31]->format('F d, Y')}}
                        {!!$next_link!!}
                    </h1>
                </div>

                @if (empty($m))
                    <p></p>
                @else
                
                @endif
            </div>
        </div>
    </section> --}}
@stop
