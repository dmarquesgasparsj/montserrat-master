@extends('template')
@section('content')

    <section class="section-padding">
        <div class="jumbotron text-left">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>
                        <span class="grey">{{ $page_title }}</span>
                    </h1>
                    <div class="col-lg-3 col-md-4">
                        <select class="custom-select" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                            <option value="">Display dashboard for ...</option>
                                <option value="{{url('dashboard/agc')}}">AGC</option>
                                <option value="{{url('dashboard/events')}}">Events</option>
                                <option value="{{url('dashboard/description')}}">Donation descriptions</option>
                                <option value="{{url('dashboard/statistics')}}">Statistics</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
