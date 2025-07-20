<div class="modal fade" id="reservationModal" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservationModalLabel">Add Reservation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ html()->form('POST', route('rooms.create-reservation'))->id('reservationForm')->open() }}
                    @isset($rooms)
                        <div class="form-group">
                            {{ html()->label('Room', 'reservation-room-id') }}
                            {{ html()->select('room_id', $rooms, old('room_id'))->class('form-control select2')->id('reservation-room-id') }}
                        </div>
                    @else
                        {{ html()->hidden('room_id')->id('reservation-room-id') }}
                    @endisset
                    <div class="form-group">
                        {{ html()->label('Start Date', 'reservation-start-date') }}
                        {{ html()->text('start_date', old('start_date'))->class('form-control flatpickr-date')->id('reservation-start-date') }}
                    </div>
                    <div class="form-group">
                        {{ html()->label('End Date', 'reservation-end-date') }}
                        {{ html()->text('end_date', old('end_date'))->class('form-control flatpickr-date')->id('reservation-end-date') }}
                    </div>
                    <div class="form-group">
                        {{ html()->label('Guest', 'reservation-contact-id') }}
                        {{ html()->select('contact_id', $retreatants, old('contact_id'))->class('form-control select2')->id('reservation-contact-id') }}
                    </div>
                    <div class="form-group">
                        {{ html()->label('Retreat', 'reservation-event-id') }}
                        {{ html()->select('event_id', $retreats, old('event_id'))->class('form-control select2')->id('reservation-event-id') }}
                    </div>
                {{ html()->form()->close() }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="reservationForm">Save</button>
            </div>
        </div>
    </div>
</div>
