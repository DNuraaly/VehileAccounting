<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property string $auto_number
 * @property integer $generated_number
 * @property $created_at
 * @property $updated_at
 */


class CarPlate extends Model
{
    protected $guarded = ['id'];
}
