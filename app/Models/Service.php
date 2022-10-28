<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'service';

    protected $guarded = ['created_at', 'updated_at'];

    public function getThumbsAttribute($value)
    {
        if (json_decode($value)) {
            return json_decode($value);
        }
        return $value;
    }

    public function technician()
    {
        return $this->belongsTo('App\Models\Technician')
            ->select(['id', 'uuid', 'name', 'avatar']);
    }

}
