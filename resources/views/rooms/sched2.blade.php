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
                                @php
                                    $cell = $m[$room->id][$dt->toDateString()];
                                    $available = count($cell['registrations']) < max(1, (int) $room->occupancy)
                                        && ! in_array($room->status, ['C', 'M']);
                                    $color = match ($cell['status']) {
                                        'O' => 'info',
                                        'R' => 'warning',
                                        'C' => 'danger',
                                        'M' => 'secondary',
                                        default => 'success',
                                    };
                                @endphp
                                <td class="table-{{ $color }} room-cell{{ $available ? ' room-available' : '' }}" data-room-id="{{ $room->id }}" data-date="{{ $dt->toDateString() }}">
                                    @foreach ($cell['registrations'] as $registration)
                                        <div>
                                            <a class="reservation" data-registration-id="{{ $registration->id }}"
                                               href="{{ url('registration/'.$registration->id) }}" title="{{ $registration->retreat_name }}">
                                                {{ $registration->retreatant?->display_name }}
                                            </a>
                                        </div>
                                    @endforeach
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
