@extends('template')
@section('content')
  <div class="row">
  	<div class="col-lg-12 text-center">
        <div class="text-danger">
          {{ __('messages.corrupt_polanco_error', ['admin' => config('polanco.admin_name')]) }}<br />
        </div>
        {{ html()->img(asset('images/error.png'), __('messages.system_error'))->attribute('title', __('messages.system_error')) }}
        <p>{!! __('messages.corrupt_polanco_report', ['email' => config('polanco.admin_email'), 'admin' => config('polanco.admin_name')]) !!}</p>
    </div>
  </div>
@stop
