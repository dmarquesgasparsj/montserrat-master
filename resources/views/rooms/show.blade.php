@extends('template')
@section('content')

<section class="section-padding">
    <div class="jumbotron text-left">
        <div class="panel panel-default">
            <div class="panel-heading">
                    <span>
                        <h2>
                            @can('update-room')
                                <a href="{{url('room/'.$room->id.'/edit')}}">{{ $room->building }} - {{ __('messages.room_table_room_header') }} #{!! $room->name !!}</a>
                            @else
                                {{ $room->building }} - {{ __('messages.room_table_room_header') }} #{{$room->name}}
                            @endCan
                        </h2>
                    </span>
                    <span class="back"><a href={{ action([\App\Http\Controllers\RoomController::class, 'index']) }}>{{ html()->img(asset('images/room.png'), __('messages.room_index_title'))->attribute('title', __('messages.room_index_title'))->class('btn btn-primary') }}</a></span></h1>
                </div>
                <div class='row'>
                    <div class='col-md-2'><strong>{{ __('messages.building_label') }} </strong>{{ $room->building}}</div>
                </div><div class="clearfix"> </div>
                <div class='row'>
                    <div class='col-md-3'><strong>{{ __('messages.name_label') }} </strong>{{ $room->name}}</div>
                </div><div class="clearfix"> </div>
                <div class='row'>
                    <div class='col-md-6'><strong>{{ __('messages.description_label') }} </strong>{{ $room->description}}</div>
                </div><div class="clearfix"> </div>
                <div class='row'>
                    <div class='col-md-6'><strong>{{ __('messages.notes_label') }} </strong>{{ $room->notes}}</div>
                </div><div class="clearfix"> </div>
                <div class='row'>
                    <div class='col-md-3'><strong>{{ __('messages.floor_label') }} </strong>{{ $room->floor}}</div>
                    <div class='col-md-3'><strong>{{ __('messages.access_label') }} </strong>{{ $room->access}}</div>
                    <div class='col-md-3'><strong>{{ __('messages.type_label') }} </strong>{{ $room->type}}</div>
                    <div class='col-md-3'><strong>{{ __('messages.occupancy_label') }} </strong>{{ $room->occupancy}}</div>
                    <div class='col-md-3'><strong>{{ __('messages.status_label') }} </strong>{{ $room->status}}</div>
                </div><div class="clearfix"> </div>
                <div class='row'>
                    @can('update-room')
                        <div class='col-md-1'>
                            <a href="{{ action([\App\Http\Controllers\RoomController::class, 'edit'], $room->id) }}" class="btn btn-info">{{ html()->img(asset('images/edit.png'), 'Edit')->attribute('title', "Edit") }}</a>
                        </div>
                    @endCan
                    @can('delete-room')
                        <div class='col-md-1'>
                            {{ html()->form('DELETE', route('room.destroy', [$room->id]))->attribute('onsubmit', 'return ConfirmDelete()')->open() }}
                            {{ html()->input('image', 'btnDelete')->class('btn btn-danger')->attribute('title', 'Delete')->attribute('src', asset('images/delete.png')) }}
                            {{ html()->form()->close() }}
                        </div>
                    @endCan
                    <div class="clearfix"> </div>
                </div>
            </div>
        </div>
    </section>
@stop
