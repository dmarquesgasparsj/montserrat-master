@extends('template')
@section('content')

<div class="row bg-cover">
    <div class="col-lg-12">
        <h2>{{ __('messages.retreat_create_title') }}</h2>
    </div>
    <div class="col-lg-12">
        {{ html()->form('POST', url('retreat'))->open() }}
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-3">
                        {{ html()->label('ID#:', 'idnumber') }}
                        {{ html()->text('idnumber', $next_idnumber)->class('form-control')->attribute('readonly', true) }}
                    </div>
                    <div class="col-lg-3">
                        {{ html()->label('Title: ', 'title') }}
                        {{ html()->text('title')->class('form-control') }}
                    </div>
                    <div class="col-lg-3">
                        {{ html()->label('Type:', 'event_type') }}
                        {{ html()->select('event_type', $event_types, config('polanco.event_type.ignatian'))->class('form-control') }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        {{ html()->label(__('messages.start_label'), 'start_date') }}
                        {{ html()->text('start_date')->id('start_date')->class('form-control flatpickr-date-time bg-white') }}
                    </div>
                    <div class="col-lg-3">
                        {{ html()->label(__('messages.end_label'), 'end_date') }}
                        {{ html()->text('end_date')->id('end_date')->class('form-control flatpickr-date-time bg-white') }}
                    </div>
                    <div class="col-lg-3">
                        {{ html()->label(__('messages.canceled_label'), 'is_active') }}
                        {{ html()->select('is_active', $is_active, 1)->class('form-control') }}
                    </div>
                    <div class="col-lg-3"> 
                            {{ html()->label(__('messages.participants_label'), 'max_participants') }}
                            {{ html()->text('max_participants', 20)->class('form-control') }}
                    </div>
                </div>
                <div class="row"> <!-- Adicionar Implementar aqui uma lista definida nos buildings -->
                    <div class="col-lg-3">
                        {{ html()->label('Chapel:', 'Chapel') }}
                        {{ html()->select('Chapel_id', ['Santo Inácio' => 'Santo Inácio', 'São José' => 'São José', 'Room to give points' => 'Room to give points'], null)->class('form-control') }}
                    </div>
                    <div class="col-lg-3">
                        {{ html()->label('Dinning Room:', 'dinning_room_id') }}
                        {{ html()->select('Chapel_id', ['Sala de Refeiçoes A' => 'Sala de Refeiçoes A', 'Sala de Refeiçoes B' => 'Sala de Refeiçoes B', 'Room to give points' => 'Room to give points'], null)->class('form-control') }}
                    </div>
                    <div class="col-lg-3">
                        {{ html()->label('Points Room :', 'points_room_id') }}
                        {{ html()->select('Chapel_id', ['Chapel' => 'Sala A', 'Dinning Room' => 'Sala B', 'Room to give points' => 'Room to give points'], null)->class('form-control') }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-9">
                        {{ html()->label('Description:', 'description') }}
                        {{ html()->textarea('description')->class('form-control')->rows('3') }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        {{ html()->label('Director(s):', 'directors') }}
                        {{ html()->multiselect('directors[]', $d, 0)->id('directors')->class('form-control select2') }}
                    </div>
                    <div class="col-lg-3">
                        {{ html()->label('Innkeeper(s):', 'innkeeper_ids') }}
                        {{ html()->multiselect('innkeepers[]', $i, 0)->id('innkeepers')->class('form-control select2') }}
                    </div>
                    <div class="col-lg-3">
                        {{ html()->label('Assistant(s):', 'assistant_ids') }}
                        {{ html()->multiselect('assistants[]', $a, 0)->id('assistants')->class('form-control select2') }}
                    </div>
                    <div class="col-lg-3">
                        {{ html()->label('Ambassador(s):', 'ambassadors') }}
                        {{ html()->multiselect('ambassadors[]', $c, 0)->id('ambassadors')->class('form-control select2') }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 mt-5">
                    {{ html()->submit('Add Retreat')->class('btn btn-light') }}
                </div>
            </div>
        {{ html()->form()->close() }}
    </div>
</div>
@stop
