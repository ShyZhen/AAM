<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Services\BootstrapService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class BootstrapController extends Controller
{
    private $bootstrapService;

    public function __construct(BootstrapService $bootstrapService)
    {
        $this->bootstrapService = $bootstrapService;
    }

    /**
     * 所有启动初始化的数据
     *
     * @param Request $request
     */
    public function start(Request $request)
    {
        return $this->bootstrapService->getStartData();
    }

    public function h5config(Request $request)
    {
        return $this->bootstrapService->geth5config();
    }

    /**
     * 下单检查（废弃，使用start接口中的数据）
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
//    public function bookCheck(Request $request)
//    {
//        // 已满
////        return response()->json(
////            [
////                'data' => [],
////                'code' => -1,
////                'message' => '该服务已满请预约其它服务'
////            ],
////            Response::HTTP_OK
////        );
//
//        // 正常
//        return response()->json(
//            [
//                'data' => [],
//                'code' => 0,
//                'message' => 'ok'
//            ],
//            Response::HTTP_OK
//        );
//    }

}
