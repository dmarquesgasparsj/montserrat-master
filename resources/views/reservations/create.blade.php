@extends('template')
@section('content')
<div class="row bg-cover">
    <div class="col-lg-12">
        <h2>Create Reservation</h2>
    </div>
    <div class="col-lg-12">
        {{ html()->form('POST', route('rooms.create-reservation'))->open() }}
            @include('reservations.form')
            <div class="row">
                <div class="col-lg-12 mt-4">
                    {{ html()->submit('Save Reservation')->class('btn btn-primary') }}
                </div>
            </div>
        {{ html()->form()->close() }}
    </div>
</div>
@endsection
