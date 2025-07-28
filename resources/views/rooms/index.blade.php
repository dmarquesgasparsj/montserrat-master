@extends('template')
@section('content')



    <section class="section-padding">
        <div class="jumbotron text-left">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>
                    <span class="grey">{{ __('messages.room_index_title') }}</span>
                        @can('room-create')
                            <span class="create">
                                <a href={{ action([\App\Http\Controllers\RoomController::class, 'create']) }}>{{ html()->img(asset('images/create.png'), __('messages.add_room_button'))->attribute('title', __('messages.add_room_button'))->class('btn btn-primary') }}</a>
                            </span>
                        @endCan
                    </h1>

                    <select class="location-select select2" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                        <option value="">{{ __('messages.filter_by_building_option') }}</option>
                        <option value="{{url('room')}}">{{ __('messages.all_buildings_option') }}</option>
                        @foreach($locations as $key=>$location)
                        <option value="{{url('room/location/'.$key)}}">{{$location}}</option>
                        @endforeach
                    </select>

                </div>
                @if ($roomsort->isEmpty())
                    <p> {{ __('messages.no_rooms_house_message') }}</p>
                @else
                <table class="table table-bordered table-striped table-hover"><caption><h2>{{ __('messages.room_caption') }}</h2></caption>
                    <thead>
                        <tr>
                            <th>{{ __('messages.room_table_room_header') }}</th>
                            <th>{{ __('messages.room_table_building_header') }}</th>
                            <th>{{ __('messages.room_table_status_header') }}</th>
                       </tr>
                    </thead>
                    <tbody>
                        @foreach($roomsort as $room)
                        <tr>
                            <td><a href="room/{{$room->id}}">{{ $room->name }}</a></td>
                            <td>{{ $room->location->name}}</td>
                            <td>{{ $room->status }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </section>
@stop
