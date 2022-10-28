<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ShopController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request)
    {
        $shops = Shop::OrderBy('id', 'desc')->simplepaginate(env('PER_PAGE', 10));
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
        $shop = Shop::where('uuid', $uuid)->first();
        if ($shop) {
            return response()->json(
                [
                    'data' => $shop,
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
