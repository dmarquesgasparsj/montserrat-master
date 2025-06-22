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

  $.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
  });

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
      });
    }
  });

  // Drag to select available dates for new reservations
  let selecting = false;
  let startCell = null;
  let selectedCells = [];

  $('.room-cell.table-success')
    .on('mousedown', function (e) {
      selecting = true;
      startCell = this;
      selectedCells = [this];
      $('.room-cell.table-success').removeClass('table-info');
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
    const startDate = $(selectedCells[0]).data('date');
    const endDate = $(selectedCells[selectedCells.length - 1]).data('date');

    $.ajax({
      url: '/rooms/create-reservation',
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      data: {
        room_id: roomId,
        start_date: startDate,
        end_date: endDate
      },
      complete: function () {
        $(selectedCells).removeClass('table-info');
        selecting = false;
      }
    });
  });
});
