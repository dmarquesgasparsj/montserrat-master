<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meal extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'retreat_id',
        'meal_date',
        'meal_type',
        'vegetarian_count',
        'gluten_free_count',
        'dairy_free_count',
    ];

    protected function casts(): array
    {
        return [
            'meal_date' => 'datetime',
        ];
    }

    public function retreat(): BelongsTo
    {
        return $this->belongsTo(Retreat::class);
    }
}
