<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * 发送登录验证码
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginCodeCrul(Request $request)
    {
        $account = $request->get('phone');

        // 正则验证是邮箱还是手机号
        $type = $this->authService->regexAccountType($account);

        if ($type) {
            return $this->authService->sendLoginCodeCurl($account);
        } else {
            return response()->json(
                [
                    'message' => '输入的不是一个正常的手机号',
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * 登录 (远程调用)
     * 所有鉴权失败都应跳转到login
     * 所有需要鉴权的操作需要在header携带登录所生成的access_token
     * headers => [
     *    'Accept' => 'application/json',
     *    'Authorization' => 'Bearer '.$accessToken,
     * ]
     *
     * @param Request $request
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function loginCurl(Request $request)
    {
        $account = $request->get('phone');

        // 正则验证是邮箱还是手机号
        $type = $this->authService->regexAccountType($account);

        if ($type) {
            $validator = Validator::make($request->all(), [
                'account' => 'max:255',
                'code' => 'required|size:5',
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
                $code = $request->get('code');

                return $this->authService->loginCurl($account, $code, $type);
            }
        } else {
            return response()->json(
                [
                    'message' => '输入的不是一个正常的手机号',
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * 登出
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        return $this->authService->logout();
    }

    /**
     * 获取个人信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function myInfo()
    {
        return $this->authService->myInfo();
    }

    /**
     * 更新个人信息
     * 如果更新了forbidden是yes，要调用登出逻辑
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function updateMyInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sex' => 'max:10|in:male,female,secrecy',
            'forbidden' => 'in:none,yes',
            'name' => 'max:20',
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
            $params = $request->only('sex', 'forbidden', 'name', 'avatar');
            return $this->authService->updateMyInfo($params);
        }
    }

    /**
     * 获取用户信息
     *
     * @param $uuid
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserByUuid($uuid)
    {
        return $this->authService->getUserByUuid($uuid);
    }

    /**
     * 更新个人用户名
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function updateMyName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:20',
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
            return $this->authService->updateMyName($request->get('name'));
        }
    }
}
