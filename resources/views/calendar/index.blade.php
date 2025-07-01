@extends('template')
@section('content')
<section class="section-padding">
    <div class="container">
        <div id="calendar"></div>
    </div>
</section>
@endsection
<script src="{{ url(mix('dist/calendar.js')) }}"></script>

