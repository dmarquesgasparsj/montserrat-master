@extends('template')
@section('content')
<div class="row">
  <div class="col-lg-12 text-center">
    <h1>{{ __('messages.welcome_polanco') }}</h1>
    <p><a href="https://en.wikipedia.org/wiki/Juan_Alfonso_de_Polanco" target="_blank">{{ __('messages.polanco') }}</a> {{ __('messages.polanco_description') }}</p>
    <p><a href="https://bible.usccb.org/bible/readings/" target="_blank">{{ __('messages.todays_readings') }}</a></p>
<!--    <p>{!! $quote !!}</p> -->
  </div>
</div>
@stop
