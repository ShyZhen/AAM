<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    //
    protected $table = 'setting';

    protected $fillable = [
        'namespace', 'key', 'value'
    ];

    public function getValueAttribute($value)
    {
        if (json_decode($value)) {
            return json_decode($value);
        }
        return $value;
    }

}
