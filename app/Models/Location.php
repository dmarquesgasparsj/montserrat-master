<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Location extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'location_id', 'id');
    }

    public function parent(): HasOne
    {
        return $this->hasOne(self::class, 'id', 'parent_id');
    }

    public function getParentLabelAttribute()
    {
        if (isset($this->parent->label)) {
            return $this->parent->label;
        } else {
            return 'N/A';
        }
    }

    public function getParentNameAttribute()
    {
        if (isset($this->parent->name)) {
            return $this->parent->name;
        } else {
            return 'N/A';
        }
    }
}
