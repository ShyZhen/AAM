<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\OrderService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    private $orderService;

    private $payType = [1, 2]; // 1支付宝，2微信
    private $orderType = [1, 2, 3, 4, 5]; // 1全部，2待支付，3进行中，4已完成，5已过期

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_duration' => 'required|integer|max:10',
            'service_id' => 'required',
            'technician_id' => 'required',
            'pay_type' => [
                'required',
                Rule::in($this->payType),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'message' => $validator->errors()->first(),
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            return $this->orderService->createOrder($request->all());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_type' => [
                'required',
                Rule::in($this->orderType),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'message' => $validator->errors()->first(),
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            return $this->orderService->getAll($request->input('order_type'));
        }
    }

    /**
     * @param Request $request
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOne(Request $request, $orderId)
    {
        return $this->orderService->getOne($orderId);
    }

    /**
     * 删除订单
     *
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteOrder($orderId)
    {
        return $this->orderService->deleteOrder($orderId);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function doPay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|max:32',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'message' => $validator->errors()->first(),
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            return $this->orderService->doPay($request->get('order_id'));
        }
    }

    /**
     * 调取app支付后，用户点击关闭，如果走到了回调的fail方法，调用该方法，防止用户没支付还要等支付单过期才可以继续支付
     *
     * @param $paymentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelPay($paymentId)
    {
        return $this->orderService->cancelPay($paymentId);
    }

    /**
     * 申请退款
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refund(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|max:32',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'message' => $validator->errors()->first(),
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            return $this->orderService->orderRefund($request->get('order_id'));
        }
    }

    /**
     * 调用隐私保护打电话
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function call(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|max:32',
            'technician_id' => 'required|max:32',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'message' => $validator->errors()->first(),
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            return $this->orderService->call($request->get('order_id'), $request->get('technician_id'));
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeTechnician(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|max:32',
            'technician_id' => 'required|max:32',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'message' => $validator->errors()->first(),
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            return $this->orderService->changeTechnician($request->get('order_id'), $request->get('technician_id'));
        }
    }
}
