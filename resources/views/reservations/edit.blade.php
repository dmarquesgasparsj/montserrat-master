@extends('template')
@section('content')
<div class="row bg-cover">
    <div class="col-lg-12">
        <h2>Edit Reservation #{{ $reservation->id }}</h2>
    </div>
    <div class="col-lg-12">
        {{ html()->form('PUT', route('registration.update', [$reservation->id]))->open() }}
            @include('reservations.form', ['reservation' => $reservation])
            <div class="row">
                <div class="col-lg-12 mt-4">
                    {{ html()->submit('Update Reservation')->class('btn btn-primary') }}
                </div>
            </div>
        {{ html()->form()->close() }}
    </div>
</div>
@endsection
