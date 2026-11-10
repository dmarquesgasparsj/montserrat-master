<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\Room;
use Carbon\CarbonInterface;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RoomAssignments
{
    public function transaction(Closure $operation): mixed
    {
        return DB::transaction(function () use ($operation) {
            // Serialize the assignment paths, including bulk assignments and retreat date changes.
            Room::query()->orderBy('id')->lockForUpdate()->pluck('id');

            return $operation();
        });
    }

    public function between(CarbonInterface $start, CarbonInterface $end): Builder
    {
        return Registration::query()
            ->where('room_id', '>', 0)
            ->whereNull('canceled_at')
            ->where('status_id', '!=', config('polanco.registration_status_id.canceled'))
            ->whereHas('retreat')
            ->where(function (Builder $query) use ($start, $end) {
                $query->where(function (Builder $explicit) use ($start, $end) {
                    $explicit->where('room_starts_on', '<', $end->toDateString())
                        ->where('room_ends_on', '>', $start->toDateString());
                })->orWhere(function (Builder $legacy) use ($start, $end) {
                    $legacy->whereNull('room_starts_on')
                        ->whereHas('retreat', function (Builder $retreat) use ($start, $end) {
                            $retreat->whereDate('start_date', '<', $end->toDateString())
                                ->whereDate('end_date', '>=', $start->toDateString());
                        });
                });
            });
    }

    public function check(Registration $registration): void
    {
        if (! $registration->room_id || $registration->canceled_at
            || (int) $registration->status_id === (int) config('polanco.registration_status_id.canceled')) {
            return;
        }

        $room = Room::find($registration->room_id);
        if (! $room || $room->status === 'M') {
            throw ValidationException::withMessages(['room_id' => __('messages.room_unavailable')]);
        }

        [$start, $end] = $registration->roomNights();
        $capacity = max(1, (int) $room->occupancy);
        $existing = $this->between($start, $end)
            ->where('room_id', $room->id)
            ->when($registration->exists, fn (Builder $query) => $query->whereKeyNot($registration->id))
            ->with('retreat')
            ->get();
        $occupied = [];
        foreach ($existing as $other) {
            [$otherStart, $otherEnd] = $other->roomNights();
            for ($night = $otherStart->max($start)->copy(); $night->lt($otherEnd) && $night->lt($end); $night->addDay()) {
                $key = $night->toDateString();
                $occupied[$key] = ($occupied[$key] ?? 0) + 1;
                if ($occupied[$key] >= $capacity) {
                    throw ValidationException::withMessages(['room_id' => __('messages.room_unavailable')]);
                }
            }
        }
    }
}
