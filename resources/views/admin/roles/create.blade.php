@extends('template')
@section('content')

<div class="row bg-cover">
    <div class="col-lg-12">
        <h1>{{ __('messages.create_role_title') }}</h1>
    </div>
    <div class="col-lg-12">
        {{ html()->form('POST', url('admin/role'))->open() }}
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        {{ html()->label(__('messages.name'), 'name') }}
                        {{ html()->text('name')->class('form-control') }}
                    </div>
                    <div class="col-lg-3 col-md-4">
                        {{ html()->label(__('messages.display_name'), 'display_name') }}
                        {{ html()->text('display_name')->class('form-control') }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        {{ html()->label(__('messages.description'), 'description') }}
                        {{ html()->textarea('description')->class('form-control')->rows(3) }}
                    </div>
                </div>
            </div>
            <div class="row text-center">
                <div class="col-lg-12">
                    {{ html()->submit(__('messages.add_role_button'))->class('btn btn-outline-dark') }}
                </div>
            </div>
        {{ html()->form()->close() }}
    </div>
</div>
@stop