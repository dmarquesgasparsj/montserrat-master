@extends('template')
@section('content')



    <section class="section-padding">
        <div class="jumbotron text-left">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>
                    <span class="grey">{{ __('messages.room_schedules_for', ['start' => $dts[0]->format('F d, Y'), 'end' => $dts[31]->format('F d, Y')]) }}</span>
                    </div>

                @if (empty($dts))
                    <p>{{ __('messages.yikes_nothing_to_schedule_message') }}</p>
                @else
                <table border="1" class="table">
                        <caption><h2>{{ __('messages.legend_title') }}
                            <span style="background-color:#dff0d8">{{ __('messages.legend_available') }}</span>;
                            <span style="background-color:#fcf8e3">{{ __('messages.legend_reserved') }}</span>;
                            <span style="background-color:#fcf8e3">{{ __('messages.legend_occupied') }}</span>;
                            <span style="background-color:#f2dede">{{ __('messages.legend_cleaning_needed') }}</span>;
                            <span style="background-color:#f2dede">{{ __('messages.legend_maintenance_required') }}</span>
                        </h2></caption>
                    <thead>
                        <tr>
                            <th>{{ __('messages.room_table_building_header') }}</th>
                            <th>{{ __('messages.room_table_room_header') }}#</th>
                            @foreach($dts as $dt)
                            <th>{{$dt->day}}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @if ($roomsort->isEmpty())
                            <p>{{ __('messages.yikes_no_rooms_message') }}</p>
                        @else

                            @foreach($roomsort as $room)

                            <tr>
                                <td>{{$room->location->name}}</td>
                                <td>{{$room->name}}</td>
                                 @foreach($dts as $dt)
                                 @if ($dt->day == 21)
                                    <td class="warning">R</td>
                                 @elseif ($dt->day == 22 ?? $room->name == "104") 
                                    <td class="danger">M</td>
                                 @elseif ($dt->day == 23)
                                    <td class="warning">O</td>
                                 @elseif ($dt->day == 25)
                                    <td class="danger">C</td>
                                 @else
                                    <td class="success">A</td>
                                 @endif
                            @endforeach
                            </tr>

                            @endforeach
                        @endif
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </section>
@stop
