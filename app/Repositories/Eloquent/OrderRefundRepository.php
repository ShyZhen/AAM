<?php

namespace App\Repositories\Eloquent;

class OrderRefundRepository extends Repository
{
    /**
     * 实现抽象函数获取模型
     *
     * @return string
     */
    public function model()
    {
        return 'App\Models\OrderRefund';
    }

}
