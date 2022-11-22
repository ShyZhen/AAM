<?php

namespace App\Repositories\Eloquent;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderRepository extends Repository
{
    /**
     * 实现抽象函数获取模型
     *
     * @return string
     */
    public function model()
    {
        return 'App\Models\Order';
    }

    /**
     * 获取我的订单
     *
     * @param $userId
     * @param $orderType
     * @return mixed
     */
    public function getAllOrders($userId, $orderType)
    {
        $ex = env('ORDER_EX');
        $time = Carbon::parse('-'.$ex.'days')->toDateTimeString();

        $orders = [];
        switch ($orderType) {
            case 1:
                $orders = $this->model()::with(['serviceItem', 'technician'])
                    ->where('user_id', $userId)
                    ->where('created_at', '>=', $time)
                    ->orderByDesc('created_at')
                    ->simplepaginate(env('PER_PAGE', 10));
                break;
            case 2:
                $orders = $this->model()::with(['serviceItem', 'technician'])
                    ->where('user_id', $userId)
                    ->where('status', 0)
                    ->where('created_at', '>=', $time)
                    ->orderByDesc('created_at')
                    ->simplepaginate(env('PER_PAGE', 10));
                break;
            case 3:
                $orders = $this->model()::with(['serviceItem', 'technician'])
                    ->where('user_id', $userId)
                    ->where('status', 1)
                    ->where('created_at', '>=', $time)
                    ->orderByDesc('created_at')
                    ->simplepaginate(env('PER_PAGE', 10));
                break;
            case 4:
                $orders = $this->model()::with(['serviceItem', 'technician'])
                    ->where('user_id', $userId)
                    ->where('status', 4)
                    ->where('created_at', '>=', $time)
                    ->orderByDesc('created_at')
                    ->simplepaginate(env('PER_PAGE', 10));
                break;
            case 5:
                $orders = $this->model()::with(['serviceItem', 'technician'])
                    ->where('user_id', $userId)
                    ->where('status', 0)
                    ->where('created_at', '<', $time)
                    ->orderByDesc('created_at')
                    ->simplepaginate(env('PER_PAGE', 10));
                break;
        }
        return $orders;
    }

    /**
     * 获取我的单个订单详情
     *
     * @param $userId
     * @param $orderId
     * @return mixed
     */
    public function getOneOrder($userId, $orderId)
    {
        $order = $this->model()::with(['serviceItem', 'technician'])
            ->where(['user_id' => $userId, 'order_id' => $orderId])
            ->first();

        if ($order) {
            $ex = env('ORDER_EX');
            $order['expire_at'] = Carbon::parse($order->created_at. ' -'.$ex.'days');
        }

        return $order;
    }

}
