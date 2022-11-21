<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    protected $table = 'technician';

    protected $guarded = ['created_at', 'updated_at'];

    public function getPhoneAttribute($mobile)
    {
        return substr_replace($mobile, '****', 3, 4);
    }

    public function getWechatAttribute($wechat)
    {
        return substr_replace($wechat, '****', 2);
    }

    public function shop()
    {
        return $this->hasOne('App\Models\Shop', 'id', 'shop_id')
            ->select(['id', 'uuid', 'title']);
    }

    public function labels()
    {
        return $this->belongsToMany('App\Models\Label', 'technician_label', 'technician_id', 'label_id')
            ->orderBy('style')
            ->orderBy('id');
    }

    /**
     * 根据用户ID集合 查询技师信息
     *
     * @author z00455118 <zhenhuaixiu@huawei.com>
     *
     * @param $idArr
     *
     * @return mixed
     */
    public static function getByIdArr($idArr)
    {
        return Technician::whereIn('id', $idArr)->get(['id', 'uuid', 'name', 'avatar']);
    }
}
