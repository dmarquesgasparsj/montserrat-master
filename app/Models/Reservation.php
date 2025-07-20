<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'room_id', 'registration_id', 'retreatant_id', 'retreat_id', 'group_id',
        'start', 'end', 'notes',

    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);

    }

    public function registration(): BelongsTo
    {

        return $this->belongsTo(Registration::class);
    }

    public function retreatant(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'retreatant_id');

    }

    public function retreat(): BelongsTo
    {

        return $this->belongsTo(Retreat::class, 'retreat_id');

    }
}
