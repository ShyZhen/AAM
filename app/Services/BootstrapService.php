<?php

namespace App\Services;

use App\Models\Banner;
use App\Models\Setting;
use Illuminate\Http\Response;
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
