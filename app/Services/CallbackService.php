<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Technician;
use App\Repositories\Eloquent\OrderPaymentRepository;
use App\Repositories\Eloquent\OrderRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Services\BaseService\RedisService;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\Facades\DB;

class CallbackService extends Service
{
    private $userRepository;

    private $orderRepository;

    private $orderPaymentRepository;

    /**
     * @param UserRepository $userRepository
     * @param OrderRepository $orderRepository
     * @param OrderPaymentRepository $orderPaymentRepository
     */
    public function __construct(
        UserRepository $userRepository,
        OrderRepository $orderRepository,
        OrderPaymentRepository $orderPaymentRepository
    ) {
        $this->userRepository = $userRepository;
        $this->orderRepository = $orderRepository;
        $this->orderPaymentRepository = $orderPaymentRepository;
    }

    /**
     * 统一的回调更新订单/支付单状态
     * 其他冗余字段，如用户花费总额可选更新
     *
     * @param $paymentId
     * @return bool
     */
    public function callbackOrderPay($paymentId)
    {
        $orderId = substr($paymentId, 0, 22);

        DB::beginTransaction();
        $order = $this->orderRepository->update(['status' => 1], $orderId, 'order_id');
        $payment = $this->orderPaymentRepository->update(['status' => 1], $paymentId, 'payment_id');
        if ($order && $payment) {
            DB::commit();

            // 更新订单数
            $technicianId = Order::where('order_id', $orderId)->first()->technician_id;
            Technician::whereId($technicianId)->increment('order_count');

            return true;
        } else {
            DB::rollBack();
            return false;
        }
    }

}
