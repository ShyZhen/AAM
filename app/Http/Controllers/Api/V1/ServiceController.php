<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ServiceController extends Controller
{
    /**
     * 首页列表接口：颜值专区，附近专区
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request)
    {
        $isRecommend = $request->input('is_recommend', 0);
        $technicianId = $request->input('technician_id', 0);

        $services = Service::with(['technician'])
            ->OrderBy('id', 'desc')
            ->when($isRecommend, function ($query) {
                return $query->where('is_recommend', 1);
            })
            ->when($technicianId, function ($query, $technicianId) {
                return $query->where('technician_id', $technicianId);
            })
            ->simplepaginate(env('PER_PAGE', 10));

        return response()->json(
            [
                'data' => $services,
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
        $service = Service::with(['technician'])->where('uuid', $uuid)->first();

        if ($service) {
            return response()->json(
                [
                    'data' => $service,
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
