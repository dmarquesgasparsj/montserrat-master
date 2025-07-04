@extends('template')
@section('content')
<div class="row">
	<div class="col-lg-12 text-center">
		<h1>{{ __('messages.welcome_polanco_long') }}</h1>
		<p><a href="https://en.wikipedia.org/wiki/Juan_Alfonso_de_Polanco" target="_blank">{{ __('messages.polanco') }}</a> {{ __('messages.polanco_description') }}</p>
		<p><a href="https://www.liturgia.pt/liturgiadiaria/" target="_blank">{{ __('messages.todays_readings') }}</a></p>
<!--		<p>{!! $quote !!}</p>  -->
                <div class="responsiveCal">
                        <iframe src="https://calendar.google.com/calendar/embed?wkst=2&amp;bgcolor=%23FFFFFF&amp;src={{ rawurlencode(config('google-calendar.calendar_id')) }}&amp;color=%23711616&amp;ctz=America%2FChicago" style="border:solid 1px #777" width="800" height="600" frameborder="0" scrolling="no"></iframe>
                </div>
	</div>
</div>
@stop
