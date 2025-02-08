<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * @property $id
 * @property string $full_name
 * @property $created_at
 * @property $updated_at
 */
class Owner extends Model
{
    protected $guarded = ['id'];
}
