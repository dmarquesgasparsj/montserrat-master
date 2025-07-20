<?php

namespace App\Http\Controllers;


use App\Models\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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


    public function index(): JsonResponse
    {
        return response()->json(Reservation::all());
    }

    public function create(): JsonResponse
    {
        return response()->json(['status' => 'ok']);
    }

    public function store(Request $request): RedirectResponse
    {
        $reservation = Reservation::create($request->all());

        return redirect()->route('reservation.show', $reservation);
    }

    public function show(Reservation $reservation): JsonResponse
    {
        return response()->json($reservation);
    }

    public function edit(Reservation $reservation): JsonResponse
    {
        return response()->json($reservation);
    }

    public function update(Request $request, Reservation $reservation): RedirectResponse
    {
        $reservation->update($request->all());

        return redirect()->route('reservation.show', $reservation);
    }

    public function destroy(Reservation $reservation): RedirectResponse
    {
        $reservation->delete();

        return redirect()->route('reservation.index');

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
