<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPaymentType extends Model
{
    protected $table = 'order_payment_type';

    protected $guarded = ['id', 'created_at', 'updated_at'];

}
