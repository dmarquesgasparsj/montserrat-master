<!DOCTYPE html>
<html>
<head>
    <title>Confirmación de Reserva</title>
</head>
<body>
    <p>
        Estimado/a {{ $reservation->retreatant->first_name }},<br><br>
        Su reserva ha sido confirmada para el retiro #{{ $reservation->event_idnumber }} que inicia el {{ $reservation->retreat_start_date->format('j \d\e F \d\e Y') }}.
    </p>
</body>
</html>
