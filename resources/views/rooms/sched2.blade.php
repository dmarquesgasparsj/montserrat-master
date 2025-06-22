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
