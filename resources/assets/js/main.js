import flatpickr from "flatpickr";

$(document).ready(function() {
  var dateOptions = {
    altInput: true,
    altFormat: "F j, Y",
    dateFormat: "Y-m-d"
  }

  var dateTimeOptions = {
    enableTime: true,
    altInput: true,
    altFormat: "F j, Y h:i K",
    dateFormat: "Y-m-d H:i"
  }
  var timeOptions = {
    enableTime: true,
    allowInput: true,
    noCalendar: true,
    altInput: true,
    dateFormat: "H:i",
    time_24hr: true
  }

  $('.select2').select2(); // Apply select2
  $('.flatpickr-date').flatpickr(dateOptions); // Apply flatpickr
  $('.flatpickr-date-time').flatpickr(dateTimeOptions);
  $('.flatpickr-time').flatpickr(timeOptions);

  const reservationModal = $('#reservationModal');
  const roomInput = $('#reservation-room-id');
  const startInput = $('#reservation-start-date');
  const endInput = $('#reservation-end-date');
  let wasSelecting = false;

  reservationModal.on('shown.bs.modal', function () {
    wasSelecting = false;
  });

  $.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
  });

  function checkoutAfter(date) {
    const checkout = new Date(date + 'T00:00:00Z');
    checkout.setUTCDate(checkout.getUTCDate() + 1);
    return checkout.toISOString().slice(0, 10);
  }

  function showReservationError(xhr) {
    const response = xhr.responseJSON;
    window.alert(response?.message || xhr.statusText);
  }

  // Enable drag & drop of reservations on the room schedule
  $('.reservation').draggable({
    revert: 'invalid',
    helper: 'clone'
  });

  $('.room-cell').droppable({
    accept: '.reservation',
    hoverClass: 'table-primary',
    drop: function (event, ui) {
      const registrationId = ui.draggable.data('registration-id');
      const roomId = $(this).data('room-id');
      const date = $(this).data('date');
      $.ajax({
        url: '/rooms/move-reservation',
        method: 'POST',
        data: {
          registration_id: registrationId,
          room_id: roomId,
          date: date
        }
      })
        .done(() => window.location.reload())
        .fail(showReservationError);
    }
  });

  // Drag to select available dates for new reservations
  let selecting = false;
  let startCell = null;
  let selectedCells = [];

  // Click on a single available cell to open reservation modal
  $('.room-cell.room-available').on('click', function (event) {
    if (selecting || wasSelecting || $(event.target).closest('a').length) return;
    const roomId = $(this).data('room-id');
    const date = $(this).data('date');
    roomInput.val(roomId);
    startInput[0]._flatpickr.setDate(date, true);
    endInput[0]._flatpickr.setDate(checkoutAfter(date), true);
    reservationModal.modal('show');
  });

  $('.room-cell.room-available')
    .on('mousedown', function (e) {
      if ($(e.target).closest('a').length) return;
      selecting = true;
      startCell = this;
      selectedCells = [this];
      $('.room-cell.room-available').removeClass('table-info');
      $(this).addClass('table-info');
      e.preventDefault();
    })
    .on('mouseover', function () {
      if (selecting && $(this).data('room-id') === $(startCell).data('room-id')) {
        $(this).addClass('table-info');
        selectedCells.push(this);
      }
    });

  $(document).on('mouseup', function () {
    if (!selecting || selectedCells.length === 0) {
      selecting = false;
      return;
    }

    const roomId = $(startCell).data('room-id');
    const nights = selectedCells.map(cell => $(cell).data('date')).sort();
    const startDate = nights[0];
    const endDate = checkoutAfter(nights[nights.length - 1]);

    roomInput.val(roomId);
    startInput[0]._flatpickr.setDate(startDate, true);
    endInput[0]._flatpickr.setDate(endDate, true);
    reservationModal.modal('show');
    wasSelecting = true;

    $(selectedCells).removeClass('table-info');
    selecting = false;
  });

  $('#reservationForm').on('submit', function (e) {
    e.preventDefault();
    $.post({
      url: $(this).attr('action'),
      data: $(this).serialize(),
      success: function () {
        reservationModal.modal('hide');
        window.location.reload();
      },
      error: showReservationError
    });
  });
});
