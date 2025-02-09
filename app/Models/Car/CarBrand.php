<?php

namespace App\Models\Car;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property $id
 * @property string $title
 * @property string $description
 * @property $created_at
 * @property $updated_at
 */

class CarBrand extends Model
{
    protected $table = 'car_brands';
    protected $guarded = ['id'];

    public function models(): HasMany {
        return $this->hasMany(CarModel::class);
    }
}
