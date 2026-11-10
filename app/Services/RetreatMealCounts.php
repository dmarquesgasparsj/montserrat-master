<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\Retreat;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class RetreatMealCounts
{
    public function registrations(Retreat $retreat): Builder
    {
        return Registration::whereCanceledAt(null)
            ->whereEventId($retreat->id)
            ->whereIn('role_id', [config('polanco.participant_role_id.retreatant'), config('polanco.participant_role_id.ambassador')])
            ->whereStatusId(config('polanco.registration_status_id.registered'));
    }

    public function forRetreat(Retreat $retreat, ?Carbon $day = null): array
    {
        $start = $retreat->start_date->copy();
        $end = $retreat->end_date->copy();
        $mealTimes = config('polanco.meal_times');
        $days = [];
        for ($date = $day?->copy() ?? $start->copy()->startOfDay(); $date->lte($day ?? $end); $date->addDay()) {
            $days[$date->toDateString()] = array_fill_keys(array_keys($mealTimes), 0);
        }

        // Older date-only records represent a whole final day.
        if ($end->isStartOfDay()) {
            $end->endOfDay();
        }

        foreach ($this->registrations($retreat)->get(['id', 'arrived_at', 'departed_at']) as $registration) {
            $arrival = $registration->arrived_at?->copy() ?? $start;
            $departure = $registration->departed_at?->copy() ?? $end;
            if ($arrival->isStartOfDay() && $arrival->isSameDay($start)) {
                $arrival = $start;
            }
            if ($registration->departed_at && $departure->isStartOfDay()) {
                $departure = $departure->isSameDay($end) ? $end : $departure->endOfDay();
            }

            foreach ($days as $date => $meals) {
                foreach ($mealTimes as $meal => $time) {
                    if (Carbon::parse($date.' '.$time)->betweenIncluded($arrival, $departure)) {
                        $days[$date][$meal]++;
                    }
                }
            }
        }

        return $days;
    }
}
