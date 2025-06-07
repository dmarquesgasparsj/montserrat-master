@extends('template')
@section('content')
<div class="row">
	<div class="col-lg-12 text-center">
                <div class="text-danger"> {{ __('messages.oops_404_message') }}<br />
                        {{ html()->img(asset('images/404.png'), '404 Error')->attribute('title', '404 Error') }}
                </div>
	</div>
</div>
@stop