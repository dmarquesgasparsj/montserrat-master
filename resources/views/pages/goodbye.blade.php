@extends('template')
@section('content')
	<div class="row">
		<div class="col-lg-12 text-center">
			<div class="text-info"> 
                                <p>{{ __('messages.goodbye_message') }}</p>
                                <img src="images/goodbye.png" alt="{{ __('messages.goodbye_alt') }}">
			</div>
		</div>
	</div>
@stop