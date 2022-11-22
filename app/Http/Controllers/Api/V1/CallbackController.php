<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\CallbackService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CallbackController extends Controller
{
    private $callbackService;

    public function __construct(CallbackService $callbackService)
    {
        $this->callbackService = $callbackService;
    }

    public function alipay(Request $request)
    {
        $data = $request->all();
        @file_put_contents('/tmp/aam/callback.txt', json_encode($data));

        // TODO 验证参数 进行逻辑处理
        if ($this->alipayValidateData($data)) {
            $res = $this->callbackService->callbackOrderPay($data['out_trade_no']);
            if ($res) {
                die('success');
            }
        }

        @file_put_contents('/tmp/aam/callback_fail_'.$data['out_trade_no'].'.txt', json_encode($data));
        die('fail');
    }

    public function wechat(Request $request)
    {
        $data = $request->all();
        @file_put_contents('/tmp/aam/callback.txt', json_encode($data));

        // TODO 验证参数 进行逻辑处理
        if ($this->wechatValidateData($data)) {
            $res = $this->callbackService->callbackOrderPay($data['out_trade_no']);
            if ($res) {
                die('success');
            }
        }

        @file_put_contents('/tmp/aam/callback_fail_'.$data['out_trade_no'].'.txt', json_encode($data));
        die('fail');
    }

    /**
     * 一些简单的鉴权，防止刷接口
     *
     * @param $data
     *
     * @return bool
     */
    private function alipayValidateData($data)
    {
        // TODO 验证完善
        if (is_array($data)
            && array_key_exists('out_trade_no', $data)
        ) {
            return true;
        }

        return false;
    }

    /**
     * 一些简单的鉴权，防止刷接口
     *
     * @param $data
     *
     * @return bool
     */
    private function wechatValidateData($data)
    {
        // TODO 验证完善
        if (is_array($data)
            && array_key_exists('out_trade_no', $data)
        ) {
            return true;
        }

        return false;
    }
}
