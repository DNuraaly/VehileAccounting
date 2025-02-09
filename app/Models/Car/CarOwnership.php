<?php

namespace App\Models\Car;

use App\Models\Owner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property $id
 * @property $created_at
 * @property $updated_at
 */

class CarOwnership extends Model
{
    protected $guarded = ['id'];

    public function car():BelongsTo {
        return $this->belongsTo(Car::class);
    }

    public function owner():BelongsTo {
        return $this->belongsTo(Owner::class);
    }
}
