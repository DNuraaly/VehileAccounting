<?php

namespace App\Models\Car;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property $id
 * @property string $auto_number
 * @property integer $generated_number
 * @property $created_at
 * @property $updated_at
 */


class Car extends Model
{
    protected $guarded = ['id'];

    public function brand():belongsTo
    {
        return $this->belongsTo(CarBrand::class, 'car_brand_id', 'id');
    }

    public function model():belongsTo
    {
        return $this->belongsTo(CarModel::class, 'car_model_id', 'id');
    }

    public function ownerHistories(): HasMany {
        return $this->hasMany(CarOwnership::class, 'car_id', 'id');
    }
}
