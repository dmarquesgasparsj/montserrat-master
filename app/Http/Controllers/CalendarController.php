<?php

namespace App\Http\Controllers;

use App\Models\Retreat;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(): View
    {
        $this->authorize('show-retreat');
        return view('calendar.index');
    }

    public function events(): JsonResponse
    {
        $this->authorize('show-retreat');
        $events = Retreat::select('id', 'title', 'start_date as start', 'end_date as end')->get();
        return response()->json($events);
    }
}
