<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'order';

    protected $guarded = ['created_at', 'updated_at'];

    public function serviceItem()
    {
        return $this->belongsTo('App\Models\Service', 'service_id', 'id')
            ->select(['id', 'uuid', 'technician_id', 'title', 'thumb_min', 'tour_price', 'sold_count']);
    }

    public function technician()
    {
        return $this->belongsTo('App\Models\Technician', 'technician_id', 'id')
            ->select(['id', 'uuid', 'name', 'avatar']);
    }
}
