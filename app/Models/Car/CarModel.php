<?php

namespace App\Models\Car;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property $id
 * @property integer $car_brand_id
 * @property string $title
 * @property string $description
 * @property $created_at
 * @property $updated_at
 */

class CarModel extends Model
{
    public function cars(): HasMany {
        return $this->hasMany(Car::class, 'car_model_id', 'id');
    }

    public function brand(): belongsTo {
        return $this->belongsTo(CarBrand::class, 'car_brand_id', 'id');
    }
}
