<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'shop';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function getThumbsAttribute($value)
    {
        if (json_decode($value)) {
            return json_decode($value);
        }
        return $value;
    }

}
