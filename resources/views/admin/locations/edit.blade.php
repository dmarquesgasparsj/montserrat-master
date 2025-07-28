@extends('template')
@section('content')
<div class="row bg-cover">
    <div class="col-lg-12">
        <h1>{{ __('messages.edit_location_title') }} {!! $location->name !!}</h1>
    </div>
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <h2>{{ __('messages.locations_index_title') }}</h2>
            </div>
            <div class="col-lg-12">
                {{ html()->form('PUT', route('location.update', [$location->id]))->open() }}
                {{ html()->hidden('id', $location->id) }}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-3 col-md-4">
                                        {{ html()->label(__('messages.name'), 'name') }}
                                        {{ html()->text('name', $location->name)->class('form-control') }}
                                    </div>
                                    <div class="col-lg-3 col-md-4">
                                        {{ html()->label(__('messages.label'), 'label') }}
                                        {{ html()->text('label', $location->label)->class('form-control') }}
                                    </div>
                                    <div class="col-lg-3 col-md-4">
                                        {{ html()->label(__('messages.type'), 'type') }}
                                        {{ html()->select('type', $location_types, $location->type)->class('form-control') }}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        {{ html()->label(__('messages.description'), 'description') }}
                                        {{ html()->textarea('description', $location->description)->class('form-control')->rows(3) }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4">
                                        {{ html()->label(__('messages.latitude_label'), 'latitude') }}
                                        {{ html()->text('latitude', number_format($location->latitude, 8))->class('form-control') }}
                                    </div>
                                    <div class="col-lg-3 col-md-4">
                                        {{ html()->label(__('messages.longitude_label'), 'longitude') }}
                                        {{ html()->text('longitude', number_format($location->longitude, 8))->class('form-control') }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4">
                                        {{ html()->label(__('messages.occupancy_label'), 'occupancy') }}
                                        {{ html()->text('occupancy', $location->occupancy)->class('form-control') }}
                                    </div>
                                    <div class="col-lg-3 col-md-4">
                                        {{ html()->label(__('messages.room_label'), 'room_id') }}
                                        {{ html()->select('room_id', $rooms, $location->room_id)->class('form-control') }}
                                    </div>
                                    <div class="col-lg-3 col-md-4">
                                        {{ html()->label(__('messages.parent_label'), 'parent_id') }}
                                        {{ html()->select('parent_id', $parents, $location->parent_id)->class('form-control') }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        {{ html()->label(__('messages.notes'), 'notes') }}
                                        {{ html()->textarea('notes', $location->notes)->class('form-control')->rows(3) }}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            {{ html()->input('image', 'btnSave')->class('btn btn-outline-dark')->attribute('src', asset('images/save.png')) }}
                        </div>
                    </div>
                {{ html()->form()->close() }}
            </div>
        </div>
    </div>
</div>
@stop
