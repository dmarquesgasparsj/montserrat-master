@extends('template')
@section('content')

<div class="row bg-cover">
    <div class="col-lg-12 text-center">
        <h2>{{ __('messages.room_schedules_for', ['start' => $dts[0]->format('F d, Y'), 'end' => $dts[31]->format('F d, Y')]) }}</h2>
        <h2>
            {!!$previous_link!!}
            {{$dts[0]->format('F d, Y')}} - {{$dts[31]->format('F d, Y')}} 
            {!!$next_link!!}
        </h2>
        <p class="lead">
            <span class="table-success">{{ __('messages.legend_available') }}</span>
            <span class="table-warning">{{ __('messages.legend_reserved') }}</span>
            <span class="table-info">{{ __('messages.legend_occupied') }}</span>
            <span class="table-danger">{{ __('messages.legend_cleaning_needed') }}</span>
            <span class="table-secondary">{{ __('messages.legend_maintenance_required') }}</span>
        </p>
    </div>

    <div class="col-lg-12 text-center">
        @if (empty($m))
            
                <p>{{ __('messages.yikes_nothing_to_schedule_message') }}</p>
            </div>
        @else
            <table class="table-sm table-bordered table-hover mx-auto">
                <thead>
                    <tr>
                        <th scope="col">{{ __('messages.room_table_room_header') }}</th>
                        @foreach($dts as $dt)
                        <th scope="col">{{$dt->day}}</th>
                        @endforeach
                    </tr>                   
                </thead>
                <tbody>
                    @if ($roomsort->isEmpty())
                        <p>{{ __('messages.yikes_no_rooms_message') }}</p>
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
                                    {{ html()->a(url('registration/' . $m[$room->id][$dt->toDateString()]['registration_id']), $m[$room->id][$dt->toDateString()]['retreatant_name'])->attribute('title', $m[$room->id][$dt->toDateString()]['retreat_name'] . ' (' . $m[$room->id][$dt->toDateString()]['retreatant_name'] . ')')->attribute('class', 'reservation')->attribute('data-registration-id', $m[$room->id][$dt->toDateString()]['registration_id']) }}
                                @elseif ($status == 'R')
                                <td class="table-warning room-cell" data-room-id="{{$room->id}}" data-date="{{$dt->toDateString()}}">
                                    {{ html()->a(url('registration/' . $m[$room->id][$dt->toDateString()]['registration_id']), $m[$room->id][$dt->toDateString()]['retreatant_name'])->attribute('title', $m[$room->id][$dt->toDateString()]['retreat_name'] . ' (' . $m[$room->id][$dt->toDateString()]['retreatant_name'] . ')')->attribute('class', 'reservation')->attribute('data-registration-id', $m[$room->id][$dt->toDateString()]['registration_id']) }}
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
@include('reservations.modal')

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
