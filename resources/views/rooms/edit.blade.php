@extends('template')
@section('content')

<div class="jumbotron text-left">
    <h1>{{ __('messages.edit_room_title') }} {!! $room->id !!}</h1>
    {{ html()->form('PUT', route('room.update', [$room->id]))->open() }}
    {{ html()->hidden('id', $room->id) }}
    <div class="form-group">
        {{ html()->label(__('messages.building_id_label'), 'location_id')->class('col-md-1') }}
        {{ html()->select('location_id', $locations, $room->location_id)->class('col-md-2') }}
    </div><div class="clearfix"> </div>

    <div class="form-group">
        {{ html()->label(__('messages.name_label'), 'name')->class('col-md-1') }}
        {{ html()->text('name', $room->name)->class('col-md-2') }}
    </div>
    <div class="clearfix"> </div>
    <div class="form-group">
        {{ html()->label(__('messages.description_label'), 'description')->class('col-md-1') }}
        {{ html()->textarea('description', $room->description)->class('col-md-5')->rows('3') }}
    </div>
    <div class="form-group">
        {{ html()->label(__('messages.notes_label'), 'notes')->class('col-md-1') }}
        {{ html()->textarea('notes', $room->notes)->class('col-md-5')->rows('3') }}
    </div>
    <div class="clearfix"> </div>
    <div class="form-group">
        {{ html()->label(__('messages.floor_label'), 'floor')->class('col-md-1') }}
        {{ html()->select('floor', $floors, $room->floor)->class('col-md-2') }}

        {{ html()->label(__('messages.access_label'), 'access')->class('col-md-1') }}
        {{ html()->text('access', $room->access)->class('col-md-1') }}

        {{ html()->label(__('messages.type_label'), 'type')->class('col-md-1') }}
        {{ html()->text('type', $room->type)->class('col-md-1') }}

        {{ html()->label(__('messages.occupancy_label'), 'occupancy')->class('col-md-1') }}
        {{ html()->text('occupancy', $room->occupancy)->class('col-md-1') }}

        {{ html()->label(__('messages.status_label'), 'status')->class('col-md-1') }}
        {{ html()->text('status', $room->status)->class('col-md-1') }}
    </div>
    <div class="clearfix"> </div>

    <div class="form-group">
        {{ html()->input('image', 'btnSave')->class('btn btn-primary')->attribute('src', asset('images/save.png')) }}
    </div>
    {{ html()->form()->close() }}
</div>
@stop
