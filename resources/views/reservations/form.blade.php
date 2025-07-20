<div class="form-group">
    <div class="row">
        <div class="col-lg-3">
            {{ html()->label('Room', 'reservation-room-id') }}
            {{ html()->select('room_id', $rooms, old('room_id', $reservation->room_id ?? null))->class('form-control select2')->id('reservation-room-id') }}
        </div>
        <div class="col-lg-3">
            {{ html()->label('Start Date', 'reservation-start-date') }}
            {{ html()->text('start_date', old('start_date', isset($reservation) ? optional($reservation->arrived_at)->toDateString() : null))->class('form-control flatpickr-date')->id('reservation-start-date') }}
        </div>
        <div class="col-lg-3">
            {{ html()->label('End Date', 'reservation-end-date') }}
            {{ html()->text('end_date', old('end_date', isset($reservation) ? optional($reservation->departed_at)->toDateString() : null))->class('form-control flatpickr-date')->id('reservation-end-date') }}
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3">
            {{ html()->label('Guest', 'reservation-contact-id') }}
            {{ html()->select('contact_id', $retreatants, old('contact_id', $reservation->contact_id ?? null))->class('form-control select2')->id('reservation-contact-id') }}
        </div>
        <div class="col-lg-3">
            {{ html()->label('Retreat', 'reservation-event-id') }}
            {{ html()->select('event_id', $retreats, old('event_id', $reservation->event_id ?? null))->class('form-control select2')->id('reservation-event-id') }}
        </div>
    </div>
</div>
