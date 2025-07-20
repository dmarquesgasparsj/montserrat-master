<?php

namespace App\Http\Controllers;

use App\Mail\ReservationConfirmation;
use App\Models\Registration;
use App\Models\Touchpoint;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function send_confirmation_email($id): RedirectResponse
    {
        $this->authorize('update-registration');
        $reservation = Registration::findOrFail($id);
        $current_user = Auth::user();
        $primary_email = $reservation->retreatant->email_primary_text;

        if (! empty($primary_email) && $reservation->contact->do_not_email == 0) {
            if ($reservation->remember_token == null) {
                $reservation->remember_token = Str::random(60);
                $reservation->save();
            }

            $touchpoint = new Touchpoint();
            $touchpoint->person_id = $reservation->contact_id;
            $touchpoint->staff_id = $current_user->contact_id ?? config('polanco.self.id');
            $touchpoint->touched_at = Carbon::now();
            $touchpoint->type = 'Email';

            try {
                Mail::to($primary_email)->queue(new ReservationConfirmation($reservation));
                $touchpoint->notes = 'Reservation confirmation email sent.';
                flash('Confirmation email sent to '.$reservation->contact_sort_name)->success();
            } catch (\Exception $e) {
                $touchpoint->notes = 'Reservation confirmation email failed: '.$e->getMessage();
                flash('Confirmation email failed to send. See <a href="/touchpoint/'.$touchpoint->id.'">touchpoint</a> for details.')->warning();
            }

            $touchpoint->save();
        } else {
            flash('Confirmation email not sent because the guest does not appear to have a primary email address or has requested NOT to receive emails.')->warning();
        }

        return redirect('registration/'.$reservation->id);
    }
}
