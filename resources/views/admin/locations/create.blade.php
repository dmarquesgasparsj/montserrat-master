@extends('template')
@section('content')

<div class="row bg-cover">
    <div class="col-lg-12">
        <h1>{{ __('messages.create_location_title') }}</h1>
    </div>
    <div class="col-lg-12">
        {{ html()->form('POST', url('admin/location'))->open() }}
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        {{ html()->label(__('messages.name'), 'name') }}
                        {{ html()->text('name')->class('form-control') }}
                    </div>
                    <div class="col-lg-3 col-md-4">
                        {{ html()->label(__('messages.label'), 'label') }}
                        {{ html()->text('label')->class('form-control') }}
                    </div>
                    <div class="col-lg-3 col-md-4">
                        {{ html()->label(__('messages.type'), 'type') }}
                        {{ html()->select('type', $location_types)->class('form-control') }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        {{ html()->label(__('messages.description'), 'description') }}
                        {{ html()->textarea('description')->class('form-control')->rows(3) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        {{ html()->label(__('messages.latitude_label'), 'latitude') }}
                        {{ html()->text('latitude')->class('form-control') }}
                    </div>
                    <div class="col-lg-3 col-md-4">
                        {{ html()->label(__('messages.longitude_label'), 'longitude') }}
                        {{ html()->text('longitude')->class('form-control') }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        {{ html()->label(__('messages.occupancy_label'), 'occupancy') }}
                        {{ html()->text('occupancy')->class('form-control') }}
                    </div>
                    <div class="col-lg-3 col-md-4">
                        {{ html()->label(__('messages.room_label'), 'room_id') }}
                        {{ html()->select('room_id', $rooms)->class('form-control') }}
                    </div>
                    <div class="col-lg-3 col-md-4">
                        {{ html()->label(__('messages.parent_label'), 'parent_id') }}
                        {{ html()->select('parent_id', $parents)->class('form-control') }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        {{ html()->label(__('messages.notes'), 'notes') }}
                        {{ html()->textarea('notes')->class('form-control')->rows(3) }}
                    </div>
                </div>
            </div>
            <div class="row text-center">
                <div class="col-lg-12">
                    {{ html()->submit(__('messages.add_location_button'))->class('btn btn-outline-dark') }}
                </div>
            </div>
        {{ html()->form()->close() }}
    </div>
</div>
@stop
