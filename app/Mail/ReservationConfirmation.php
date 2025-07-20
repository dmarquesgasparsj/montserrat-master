<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function build(): static
    {
        if ($this->reservation->contact->preferred_language == 'es_ES') {
            return $this->subject('Confirmaci\u00f3n de Reserva')
                ->replyTo('registration@montserratretreat.org')
                ->view('emails.es_ES.reservation-confirmation');
        }

        return $this->subject('Reservation Confirmation')
            ->replyTo('registration@montserratretreat.org')
            ->view('emails.en_US.reservation-confirmation');
    }
}
