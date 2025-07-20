<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Reservation extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $fillable = [
        'room_id',
        'registration_id',
        'retreatant_id',
        'retreat_id',
        'group_id',
        'start',
        'end',
        'notes',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'start' => 'datetime',
            'end' => 'datetime',
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'registration_id', 'id');
    }

    public function retreatant(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'retreatant_id', 'id');
    }

    public function retreat(): BelongsTo
    {
        return $this->belongsTo(Retreat::class, 'retreat_id', 'id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
}
