<?php

namespace App\Services;

use App\Models\Technician;
use App\Repositories\Eloquent\OrderPaymentRepository;
use App\Repositories\Eloquent\OrderPaymentTypeRepository;
use App\Repositories\Eloquent\OrderRefundRepository;
use App\Repositories\Eloquent\OrderRepository;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class OrderService extends Service
{
    private $orderRepository;
    private $orderPaymentRepository;
    private $paymentTypeRepository;
    private $orderRefundRepository;

    /**
     * 支付单过期时间
     * @var float|int
     */
    private $ttl = 2 * 60;

    /**
     * @param OrderRepository $orderRepository
     * @param OrderPaymentRepository $orderPaymentRepository
     * @param OrderPaymentTypeRepository $paymentTypeRepository
     * @param OrderRefundRepository $orderRefundRepository
     */
    public function __construct(
        OrderRepository $orderRepository,
        OrderPaymentRepository $orderPaymentRepository,
        OrderPaymentTypeRepository $paymentTypeRepository,
        OrderRefundRepository $orderRefundRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderPaymentRepository = $orderPaymentRepository;
        $this->paymentTypeRepository = $paymentTypeRepository;
        $this->orderRefundRepository = $orderRefundRepository;
    }

    /**
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     */
    public function createOrder($params)
    {
        $userId = Auth::id();

        // 数据必须存在并且有效
        $service = \App\Models\Service::find($params['service_id']);
        $technician = Technician::find($params['technician_id']);

        if (!$service || !$technician) {
            return response()->json(
                [
                    'message' => '数据不存在',
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        // 准备下单入库
        $payStatus = 0;                                                        // 订单表支付状态,0待支付,1已完成,2已取消
        $orderAmount = $service->tour_price * $params['order_duration'];       // 最终提交支付的价格，必须大于0
        $orderId = self::createOrderId();

        if ($orderAmount <= 0) {
            return response()->json(
                [
                    'message' => '服务价格设置错误',
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        DB::beginTransaction();
        $order = $this->orderRepository->create([
            'order_id' => $orderId,
            'user_id' => $userId,
            'service_id' => $params['service_id'],
            'technician_id' => $params['technician_id'],
            'order_amount' => $orderAmount,
            'status' => $payStatus,
            'pay_type' => $params['pay_type'],
        ]);

        if ($order) {
            DB::commit();
            return response()->json(
                [
                    'data' => $order->toArray(),
                    'code' => 0,
                    'message' => ''
                ],
                Response::HTTP_CREATED
            );
        } else {
            DB::rollBack();
            return response()->json(
                [
                    'message' => '网络错误请重试',
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll($orderType)
    {
        $userId = Auth::id();
        $orders = $this->orderRepository->getAllOrders($userId, $orderType);

//        foreach ($orders as $order) {
//            self::handleOrderItem($order);
//        }

        return response()->json(
            [
                'data' => $orders,
                'code' => 0,
                'message' => ''
            ],
            Response::HTTP_OK
        );
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOne($orderId)
    {
        $userId = Auth::id();
        $order = $this->orderRepository->getOneOrder($userId, $orderId);

        // self::handleOrderItem($order);

        return response()->json(
            [
                'data' => $order,
                'code' => 0,
                'message' => ''
            ],
            Response::HTTP_OK
        );
    }

    /**
     * 硬删未完成订单
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteOrder($orderId)
    {
        $userId = Auth::id();
        $order = $this->orderRepository->getOneOrder($userId, $orderId);
        if (!$order) {
            return response()->json(
                [
                    'message' => '订单不存在',
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        if ($order->status != 0) {
            return response()->json(
                [
                    'message' => '只有未支付可以删除',
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        if ($order->delete()) {
            return response()->json(
                [
                    'data' => [],
                    'code' => 0,
                    'message' => '删除成功'
                ],
                Response::HTTP_OK
            );
        } else {
            return response()->json(
                [
                    'message' => '网络错误请重试',
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * 查询订单，订单产品是否存在，并且是待支付状态
     * 订单支付状态是待支付
     * 支付单是否存在，未过期，并且是待支付状态？过期则重新生成，没过期则提示已经支付请勿重复支付，保证幂等，保证同一时间只有一个有效支付单
     * 调用网关支付逻辑 （写入支付|退款记录表）
     * 金额不能为负数
     *
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function doPay($orderId)
    {
        // 订单校验
        $userId = Auth::id();
        $order = $this->orderRepository->getOneOrder($userId, $orderId);
        if (!$order || $order->status != 0 || !$order->serviceItem) {
            return response()->json(
                [
                    'message' => '该订单不存在',
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        // 支付流水校验，避免重复支付
        // 已经存在支付成功的，不得重复支付
        $paySuccess = $this->orderPaymentRepository->model()::where([
            'order_id' => $orderId,
            'user_id' => $userId,
        ])->where('status', 1)
            ->get();

        if ($paySuccess->count()) {
            return response()->json(
                [
                    'message' => '该订单已支付成功，请勿重复支付',
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_TOO_MANY_REQUESTS
            );
        }

        // 正在支付，支付成功，有效期内
        $expireTime = date('Y-m-d H:i:s', time() - $this->ttl);
        $payReady = $this->orderPaymentRepository->model()::where([
            'order_id' => $orderId,
            'user_id' => $userId,
        ])->where('status', 0)
            ->where('updated_at', '>', $expireTime)
            ->get();

        if ($payReady->count()) {
            return response()->json(
                [
                    'message' => '该订单正在支付，请稍后',
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_TOO_MANY_REQUESTS
            );
        } else {
            // 重新生成支付单进行支付网关逻辑
            $paymentId = self::createPaymentId($orderId);
            DB::beginTransaction();
            $payment = $this->orderPaymentRepository->create([
                'order_id' => $orderId,
                'payment_id' => $paymentId,
                'pay_amount' => $order->order_amount,
                'user_id' => $userId,
                'status' => 0,
                'pay_type' => $order->pay_type
            ]);

            $paymentType = $this->paymentTypeRepository->create([
                'payment_id' => $paymentId,
                'action_type' => 1,  // 1支付，2退款
                'pay_amount' => $order->order_amount
            ]);

            if ($payment && $paymentType) {
                DB::commit();
                // 支付宝
                if ($order->pay_type == 1) {
                    // 调用支付宝网关支付
                    $result = $this->payGateway($paymentId, $order->serviceItem->title, $order->order_amount * 0.01);

                    if (!empty($result) && !empty($result = json_decode($result, true)) && $result['code'] == 0) {
                        return response()->json(
                            [
                                'message' => $result['msg'],
                                'code' => 0,
                                'data' => ['payment_id' => $paymentId, 'order_str' => $result['data']]
                            ],
                            Response::HTTP_OK
                        );
                    }
                } elseif ($order->pay_type == 2) {



                    // TODO 微信支付
                    return response()->json(
                        [
                            'message' => 'ok',
                            'code' => 0,
                            'data' => ['payment_id' => $paymentId, 'order_str' => 'xxx']
                        ],
                        Response::HTTP_OK
                    );




                } else {
                    DB::rollBack();
                    return response()->json(
                        [
                            'message' => '网络错误请重试',
                            'code' => -1,
                            'data' => []
                        ],
                        Response::HTTP_INTERNAL_SERVER_ERROR
                    );
                }

            } else {
                DB::rollBack();
                return response()->json(
                    [
                        'message' => '网络错误请重试',
                        'code' => -1,
                        'data' => []
                    ],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            }
        }
    }

    /**
     * 主动取消支付，防止等2分钟过期
     *
     * @param $paymentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelPay($paymentId)
    {
        $userId = Auth::id();
        $this->orderPaymentRepository->model()::where([
            'user_id' => $userId,
            'payment_id' => $paymentId,
            'status' => 0
        ])->update(['status' => 2]);

        return response()->json(
            [
                'message' => '已取消',
                'code' => 0,
                'data' => []
            ],
            Response::HTTP_OK
        );
    }

    /**
     * 判断订单状态是否是已支付
     * 获取支付单status=1为已支付的payment_id
     * payment_type是否存在退款记录，防止重复发起退款记录
     * 写入refund表和payment_type表
     *
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderRefund($orderId)
    {
        // 订单验证
        $userId = Auth::id();
        $order = $this->orderRepository->getOneOrder($userId, $orderId);
        if (!$order || $order->status != 1) {
            return response()->json(
                [
                    'message' => '该订单不存在或未支付',
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        // 是否支付成功
        $paySuccess = $this->orderPaymentRepository->model()::where([
            'order_id' => $orderId,
            'user_id' => $userId,
        ])->where('status', 1)
            ->first();
        if (!$paySuccess) {
            return response()->json(
                [
                    'message' => '该订单未支付',
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        // 重复提交
        $paymentType = $this->paymentTypeRepository->model()::where([
            'payment_id' => $paySuccess->payment_id,
            'action_type' => 2,    // 2 退款类型
        ])->first();
        if ($paymentType) {
            return response()->json(
                [
                    'message' => '已发起退款申请，请耐心等待',
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_TOO_MANY_REQUESTS
            );
        }

        // 正常入库逻辑
        DB::beginTransaction();
        $orderRefund = $this->orderRefundRepository->create([
            'order_id' => $orderId,
            'payment_id' => $paySuccess->payment_id,
            'pay_amount' => $order->order_amount,
            'user_id' => $userId,
            'status' => 0,
            'pay_type' => $order->pay_type
        ]);

        $paymentType = $this->paymentTypeRepository->create([
            'payment_id' => $paySuccess->payment_id,
            'action_type' => 2,  // 1支付，2退款
            'pay_amount' => $order->order_amount
        ]);

        if ($orderRefund && $paymentType) {
            DB::commit();
            return response()->json(
                [
                    'message' => '',
                    'code' => 0,
                    'data' => $orderRefund
                ],
                Response::HTTP_OK
            );
        } else {
            DB::rollBack();
            return response()->json(
                [
                    'message' => '网络错误请重试',
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @param $orderId
     * @param $technicianId
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function call($orderId, $technicianId)
    {
        // 订单必须已存在，并且已支付
        $userId = Auth::id();
        $order = $this->orderRepository->getOneOrder($userId, $orderId);
        if (!$order || $order->status != 1) {
            return response()->json(
                [
                    'message' => '该订单不存在',
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $result = $this->callGateway($userId, $technicianId);
        if (!empty($result) && !empty($result = json_decode($result, true))) {
            return response()->json(
                [
                    'message' => $result['msg'],
                    'code' => 0,
                    'data' => []
                ],
                Response::HTTP_OK
            );
        } else {
            return response()->json(
                [
                    'message' => '网络错误请重试',
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * 后台处理完退款后，需要处理如下逻辑，并没有暴漏接口
     * order_refund.status=1, order.status=3
     *
     * @param $paymentId
     */
    public function adminRefund($paymentId)
    {
        $orderId = substr($paymentId, 0, 22);
        $this->orderRepository->update(['status' => 3], $orderId, 'order_id');
        $this->orderRefundRepository->update(['status' => 1], $paymentId, 'payment_id');
    }

    /**
     * 调用网关生成
     *
     * @param $paymentId
     * @param $itemName
     * @param $amount
     * @return string
     */
    private function payGateway($paymentId, $itemName, $amount)
    {
        $callbackUrl = env('CALLBACK_URL_ALIPAY');
        $uri = 'http://sender.ituiuu.com/alipay'.
            '?key=yianmo'.
            '&callback_url='.$callbackUrl.
            '&trade_money='.$amount.
            '&trade_no='.$paymentId.
            '&project_name='.$itemName.
            '&project_info=';    // 防止get请求超过限制，给空

        $client = new Client();
        $response = $client->get($uri);
        return $response->getBody()->getContents();
    }

    /**
     * 调用号码隐私保护接口打电话
     *
     * @param $userId
     * @param $toUserId
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function callGateway($userId, $toUserId)
    {
        $uri = 'http://com.ituiuu.com/?~[order/call]'.
            '&uid='.$userId.
            '&to_uid='.$toUserId.
            '&appname=AiAnMo';


        $client = new Client();
        $response = $client->get($uri);
        return $response->getBody()->getContents();
    }

    private static function createOrderId()
    {
        return date('Ymd').substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(1000, 9999));
    }

    private static function createPaymentId($orderId)
    {
        return $orderId . sprintf('%02d', rand(1000, 9999));
    }

    /**
     * 兼容，给订单的技师快照添加一对多逻辑（假的，代码处理的，实际上目前不支持）
     *
     * @param $orderItem
     * @return mixed
     */
    private static function handleOrderItem(&$orderItem)
    {
        $temp['staff_id'] = $orderItem['orderItem']['staff_id'];
        $temp['staff_name'] = $orderItem['orderItem']['staff_name'];
        $temp['staff_mobile'] = $orderItem['orderItem']['staff_mobile'];
        $temp['staff_profession'] = $orderItem['orderItem']['staff_profession'];
        $temp['staff_avatar'] = $orderItem['orderItem']['staff_avatar'];
        $temp['staff_years_of_work'] = $orderItem['orderItem']['staff_years_of_work'];

        $orderItem['staffs'] = [$temp];
    }

}
