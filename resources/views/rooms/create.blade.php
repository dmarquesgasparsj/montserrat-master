@extends('template')
@section('content')

<section class="section-padding">
    <div class="jumbotron text-left">
        <h2><strong>{{ __('messages.create_room_title') }}</strong></h2>
        {{ html()->form('POST', url('room'))->class('form-horizontal panel')->open() }}
        <div class="form-group">

            {{ html()->label(__('messages.location_label'), 'location_id')->class('col-md-1') }}
            {{ html()->select('location_id', $locations, 0)->class('col-md-2') }}

        </div>
        <div class="clearfix"> </div>
        <div class="form-group">
            {{ html()->label(__('messages.name_label'), 'name')->class('col-md-1') }}
            {{ html()->text('name')->class('col-md-2') }}
        </div>
        <div class="clearfix"> </div>
        <div class="form-group">
            {{ html()->label(__('messages.description_label'), 'description')->class('col-md-1') }}
            {{ html()->textarea('description')->class('col-md-5')->rows('3') }}
        </div>
        <div class="clearfix"> </div>
        <div class="form-group">
            {{ html()->label(__('messages.notes_label'), 'notes')->class('col-md-1') }}
            {{ html()->textarea('notes')->class('col-md-5')->rows('3') }}
        </div>
        <div class="clearfix"> </div>
        <div class="form-group">
            {{ html()->label(__('messages.floor_label'), 'floor')->class('col-md-1') }}
            {{ html()->select('floor', $floors, 0)->class('col-md-2') }}
            {{ html()->label(__('messages.access_label'), 'access')->class('col-md-1') }}
            {{ html()->text('access')->class('col-md-2') }}
            {{ html()->label(__('messages.type_label'), 'type')->class('col-md-1') }}
            {{ html()->text('type')->class('col-md-2') }}
            {{ html()->label(__('messages.occupancy_label'), 'occupancy')->class('col-md-1') }}
            {{ html()->text('occupancy', 1)->class('col-md-1') }}
            {{ html()->label(__('messages.status_label'), 'status')->class('col-md-1') }}
            {{ html()->text('status')->class('col-md-1') }}
        </div>
        <div class="clearfix"> </div>

        <div class="col-md-1">
            <div class="form-group">
                {{ html()->submit(__('messages.add_room_button'))->class('btn btn-primary') }}
            </div>
        </div>
        <div class="clearfix"> </div>
        {{ html()->form()->close() }}
    </div>
</section>

@stop
