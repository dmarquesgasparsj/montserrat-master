@extends('template')
@section('content')
<div class="row">
	<div class="col-lg-12 text-center">
                <div class="text-danger">
                        <p>{{ __('messages.opps') }}</p>
                        <img src="images/503.png" alt="503 Error">
                </div>
	</div>
</div>
@stop
