<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Technician;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class TechnicianController extends Controller
{
    /**
     * 首页列表接口：颜值专区，附近专区
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request)
    {
        $isPretty = $request->input('is_pretty', 0);
        $isRecommend = $request->input('is_recommend', 0);

        $shops = Technician::with(['shop', 'labels'])
            ->OrderBy('id', 'desc')
            ->when($isPretty, function ($query) {
                return $query->where('is_pretty', 1);
            })
            // 不确定首页是推荐技师还是项目，都做个筛选
            ->when($isRecommend, function ($query) {
                return $query->where('is_recommend', 1);
            })
            ->simplepaginate(env('PER_PAGE', 10));

        return response()->json(
            [
                'data' => $shops,
                'code' => 0,
                'message' => ''
            ],
            Response::HTTP_OK
        );
    }

    /**
     * @param $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOne($uuid)
    {
        $item = Technician::with(['shop', 'labels'])->where('uuid', $uuid)->first();

        if ($item) {
            return response()->json(
                [
                    'data' => $item,
                    'code' => 0,
                    'message' => ''
                ],
                Response::HTTP_OK
            );
        } else {
            return response()->json(
                [
                    'data' => [],
                    'code' => -1,
                    'message' => ''
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
