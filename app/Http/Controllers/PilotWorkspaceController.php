<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Retreat;
use App\Models\Room;
use App\Models\Roomstate;
use App\Models\User;
use App\Services\PilotAccounts;
use App\Services\RetreatMealCounts;
use App\Services\RoomAssignments;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PilotWorkspaceController extends Controller
{
    public function home(Request $request): RedirectResponse
    {
        return redirect()->route(match (PilotAccounts::role($request->user())) {
            'admin', 'reception' => 'rooms',
            'kitchen' => 'pilot.kitchen',
            'housekeeping' => 'pilot.housekeeping',
        });
    }

    public function kitchen(Request $request, RetreatMealCounts $counts): View
    {
        $date = $request->validate(['date' => 'nullable|date_format:Y-m-d'])['date'] ?? today()->toDateString();
        $day = Carbon::parse($date);
        $totals = array_fill_keys(array_keys(config('polanco.meal_times')), 0);

        $retreats = Retreat::where('start_date', '<', $day->copy()->addDay())
            ->where('end_date', '>=', $day)->where('is_active', 1)->get();
        foreach ($retreats as $retreat) {
            foreach ($counts->forRetreat($retreat, $day)[$date] as $service => $number) {
                $totals[$service] += $number;
            }
        }

        $diets = Meal::whereDate('meal_date', $date)->whereIn('retreat_id', $retreats->modelKeys())
            ->selectRaw('meal_type, SUM(vegetarian_count) AS vegetarian, SUM(gluten_free_count) AS gluten_free, SUM(dairy_free_count) AS dairy_free')
            ->groupBy('meal_type')->get();

        return view('pilot.kitchen', compact('date', 'totals', 'diets'));
    }

    public function housekeeping(): View
    {
        $rooms = Room::with('location:id,name')->whereStatus('C')->orderBy('name')->get(['id', 'location_id', 'name']);

        return view('pilot.housekeeping', compact('rooms'));
    }

    public function markCleaned(Room $room): RedirectResponse
    {
        DB::transaction(function () use ($room) {
            $room = Room::whereKey($room->id)->lockForUpdate()->firstOrFail();
            abort_unless($room->status === 'C', 409);

            $room->status = 'A';
            $room->save();
            Roomstate::create([
                'room_id' => $room->id,
                'statechange_at' => now(),
                'statusfrom' => 'C',
                'statusto' => 'A',
            ]);
        });

        return redirect()->route('pilot.housekeeping');
    }

    public function accounts(): View
    {
        $this->authorize('superuser');
        $users = User::where('provider', 'soutelo-pilot')->with('roles')->orderBy('email')->get();
        $events = DB::table('pilot_security_events')
            ->leftJoin('users as actors', 'actors.id', '=', 'pilot_security_events.actor_user_id')
            ->leftJoin('users as targets', 'targets.id', '=', 'pilot_security_events.target_user_id')
            ->orderByDesc('pilot_security_events.id')->limit(30)
            ->get(['pilot_security_events.*', 'actors.email as actor_email', 'targets.email as target_email']);

        return view('pilot.accounts', compact('users', 'events'));
    }

    public function assignStaffRole(Request $request, User $user, PilotAccounts $accounts): RedirectResponse
    {
        $this->authorize('superuser');
        $role = $request->validate(['role' => 'required|in:reception,kitchen,housekeeping'])['role'];
        $accounts->assignStaffRole($request->user(), $user, $role);

        return redirect()->route('pilot.accounts');
    }

    public function exportReservations(Request $request, PilotAccounts $accounts, RoomAssignments $assignments): StreamedResponse
    {
        $this->authorize('superuser');
        $start = today();
        $end = $start->copy()->addDays(31);
        $bookings = $assignments->between($start, $end)->with('room:id,name', 'retreat')->get();
        $accounts->record('reservations_exported', $request->user()->id);

        return response()->streamDownload(function () use ($bookings, $start, $end) {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Quarto', 'Retiro', 'Chegada', 'Saída'], escape: '');
            foreach ($bookings as $booking) {
                [$arrival, $departure] = $booking->roomNights();
                if (! $booking->room || $arrival->gte($end) || $departure->lte($start)) {
                    continue;
                }

                fputcsv($output, [
                    $this->spreadsheetText($booking->room->name),
                    $this->spreadsheetText($booking->retreat->title),
                    $arrival->toDateString(),
                    $departure->toDateString(),
                ], escape: '');
            }
            fclose($output);
        }, 'reservas.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function spreadsheetText(string $value): string
    {
        return preg_match('/\A(?:[\t\r\n]|[[:space:]]*[=+@-])/', $value) ? "'".$value : $value;
    }
}
