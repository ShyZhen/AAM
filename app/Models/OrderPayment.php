<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $table = 'order_payment';

    protected $guarded = ['id', 'created_at', 'updated_at'];

}
