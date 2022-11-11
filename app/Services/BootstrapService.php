<?php

namespace App\Services;

use App\Models\Banner;
use App\Models\Setting;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class BootstrapService extends Service
{
    /**
     * 所有启动数据
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStartData()
    {
        $data['banner'] = $this->getBanner();
        $data['setting'] = $this->getSetting();

        return response()->json(
            [
                'data' => $data,
                'code' => 0,
                'message' => ''
            ],
            Response::HTTP_OK
        );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function geth5config()
    {
        $loginUid = Auth::id();
        $appName = env('APP_NAME', 'YiAnMo');            // 应用英文名（）
        $keFu = env('APP_KEFU', 'yianmo@126.com');       // 客服邮箱或电话，多个用半角逗号分隔

        $key = base_convert($loginUid, 10, 9) . '9' . base_convert(strrev($loginUid), 10, 9);
        $h5im = "https://m.ituiuu.com/im?appname=$appName&kefu=$keFu&key=$key";
        $h5find = "https://m.ituiuu.com/find?appname=$appName&kefu=$keFu&key=$key";

        $data = [
            "msg_url" => $h5im,
            "find_url" => $h5find
        ];

        return response()->json(
            [
                'data' => $data,
                'code' => 0,
                'message' => ''
            ],
            Response::HTTP_OK
        );
    }

    /**
     * 启动banner数据
     *
     * @return mixed
     */
    private function getBanner()
    {
        return Banner::orderBy('sort', 'asc')->orderBy('id', 'asc')->get();
    }

    /**
     * @return array
     */
    private function getSetting()
    {
        $res = [];
        if (Schema::hasTable('setting')) {
            $setting = Setting::all();
            foreach ($setting as $item) {
                $res[$item['key']] = $item['value'];
            }
        }

        return $res;
    }
}
