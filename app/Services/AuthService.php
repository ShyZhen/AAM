<?php

namespace App\Services;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use App\Models\UserNameAudit;
use Illuminate\Support\Facades\Auth;
use App\Services\BaseService\EmailService;
use App\Services\BaseService\RegexService;
use App\Repositories\Eloquent\UserRepository;

class AuthService extends Service
{
    private $emailService;

    private $userRepository;

    /**
     * AuthService constructor.
     *
     * @param EmailService   $emailService
     * @param UserRepository $userRepository
     */
    public function __construct(
        EmailService $emailService,
        UserRepository $userRepository
    ) {
        $this->emailService = $emailService;
        $this->userRepository = $userRepository;
    }

    /**
     * 发送注册码服务,仅仅为远程调用方案
     *
     * @param $account
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendLoginCodeCurl($account)
    {
        $uri = 'http://sender.ituiuu.com/'.
            '?key='.$this->getKey().
            '&appname=aianmo'.
            '&mobile='.$account;

        $client = new Client();
        $response = $client->get($uri, $this->getHeader());
        $result = $response->getBody()->getContents();

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
                    'message' => '短信网关异常，请重试',
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * 登录服务
     * 已经注销的用户无法登录，提示联系管理员
     *
     * @param $account
     * @param $requestCode
     * @param $type
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginCurl($account, $requestCode, $type)
    {
        $result = $this->verifyLoginCodeCurl($account, $requestCode);

        if (empty($result) || empty($result = json_decode($result, true))) {
            return response()->json(
                [
                    'message' => '短信验证网关异常，请重试',
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        if ($result['code'] != 0) {
            return response()->json(
                [
                    'message' => $result['msg'],
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (isset($result['data']['member_uid'])) {
            // do
            $user = $this->userRepository->find($result['data']['member_uid']);
            if ($user) {
                if ($user->forbidden == 'yes') {
                    return response()->json(
                        [
                            'message' => '该用户已注销，请联系管理员',
                            'code' => -1,
                            'data' => []
                        ],
                        Response::HTTP_UNPROCESSABLE_ENTITY
                    );
                } else {
                    $token = $user->getSanctumToken();
                }
            } else {
                // 创建用户
                $uuid = self::uuid('user-');
                $user = User::create([
                    'id' => $result['data']['member_uid'],
                    'name' => substr_replace($account, '****', 3, 4),
                    'uuid' => $uuid,
                    $type => $account,
                ]);
                $token = $user->getSanctumToken();
            }

            return response()->json(
                [
                    'message' => '',
                    'code' => 0,
                    'data' => ['id' => $user->id, 'token' => $token]
                ],
                Response::HTTP_OK
            );
        } else {
            return response()->json(
                [
                    'message' => '网关异常，请重试',
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
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
        $columns = [
            'uuid', 'name', 'avatar', 'sex'
        ];
        $user = $this->userRepository->findBy('uuid', $uuid, $columns);

        if ($user) {
            return response()->json(
                [
                    'data' => $user,
                    'code' => 0,
                    'message' => ''
                ],
                Response::HTTP_OK
            );
        }

        return response()->json(
            [
                'message' => '用户不存在',
                'code' => -1,
                'data' => []
            ],
            Response::HTTP_NOT_FOUND
        );
    }

    /**
     * 获取当前登录用户信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function myInfo()
    {
        return response()->json(
            [
                'data' => Auth::user(),
                'code' => 0,
                'message' => ''
            ],
            Response::HTTP_OK
        );
    }

    /**
     * 修改个人信息 (不包括昵称)
     *
     * @param array $data
     *
     * @return mixed
     */
    public function updateMyInfo(array $data)
    {
        $user = Auth::user();

        if ($this->userRepository->update($data, $user->id)) {
            return response()->json(
                [
                    'data' => $this->userRepository->find($user->id),
                    'code' => 0,
                    'message' => ''
                ],
                Response::HTTP_OK
            );
        } else {
            return response()->json(
                [
                    'message' => '更新失败，请重试',
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * 只有一条待审核，不得重复提交
     * 后台审核，无论通过还是不通过，都要删除该条记录，留存无意义。
     * 如果通过，需要开启事务同步修改当前user_id的用户name
     *
     * @param $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateMyName($name)
    {
        $user = Auth::user();
        $userId = $user->id;
        $oldName = $user->name;
        $newName = $name;

        $audit = UserNameAudit::where('user_id', $userId)->first();
        if ($audit) {
            return response()->json(
                [
                    'message' => '您已有一条待审核记录，请勿重复提交',
                    'code' => -1,
                    'data' => []
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        UserNameAudit::create([
            'user_id' => $userId,
            'old_name' => $oldName,
            'new_name' => $newName,
        ]);

        return response()->json(
            [
                'message' => '提交成功',
                'code' => 0,
                'data' => []
            ],
            Response::HTTP_OK
        );
    }

    /**
     * 登出
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return response()->json(
            [
                'message' => '登出成功',
                'code' => 0,
                'data' => []
            ],
            Response::HTTP_OK
        );
    }

    /**
     * 正则判断是email还是mobile
     * 返回字段与数据库一致
     *
     * @param $account
     *
     * @return string 'email'/'mobile'
     */
    public function regexAccountType($account)
    {
        $type = '';

//        if (RegexService::test('email', $account)) {
//            $type = 'email';
//        }

        if (RegexService::test('mobile', $account)) {
            $type = 'phone';
        }

        return $type;
    }

    /**
     * @param $account
     * @param $code
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function verifyLoginCodeCurl($account, $code)
    {
        $uri = 'https://sender.ituiuu.com/'.
            '?key='.$this->getKey().
            '&appname=aianmo'.
            '&mobile='.$account.
            '&code='.$code;

        $client = new Client();
        $response = $client->get($uri, $this->getHeader());
        return $response->getBody()->getContents();
    }

    private function getKey()
    {
        $appName = 'aianmo';
        $hour = date('H');
        return md5($appName.$hour);
    }

    private function getHeader()
    {
        $clientIp = Request()->getClientIp();
        return [
            'headers' => [
                'CLIENT-IP' => $clientIp,
            ]
        ];
    }
}
