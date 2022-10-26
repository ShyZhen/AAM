<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $table = 'label';

    protected $guarded = ['id', 'created_at', 'updated_at'];

}
