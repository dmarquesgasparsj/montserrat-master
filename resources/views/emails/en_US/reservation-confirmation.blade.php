<!DOCTYPE html>
<html>
<head>
    <title>Reservation Confirmation</title>
</head>
<body>
    <p>
        Dear {{ $reservation->retreatant->first_name }},<br><br>
        Your reservation has been confirmed for retreat #{{ $reservation->event_idnumber }} starting on {{ $reservation->retreat_start_date->format('F j, Y') }}.
    </p>
</body>
</html>
