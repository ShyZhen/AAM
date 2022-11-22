<?php

namespace App\Repositories\Eloquent;

class OrderPaymentTypeRepository extends Repository
{
    /**
     * 实现抽象函数获取模型
     *
     * @return string
     */
    public function model()
    {
        return 'App\Models\OrderPaymentType';
    }

}
