<?php

namespace App\Repositories\Eloquent;

use Illuminate\Support\Facades\Auth;

class UserRepository extends Repository
{
    /**
     * 实现抽象函数获取模型
     *
     * @Author xxx
     * http://litblc.com
     *
     * @return mixed|string
     */
    public function model()
    {
        return 'App\Models\User';
    }
}
