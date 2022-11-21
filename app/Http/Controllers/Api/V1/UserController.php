<?php
/**
 * 用户动作相关
 *
 * @Author huaixiu.zhen
 * http://litblc.com
 * User: z00455118
 * Date: 2022/11/19
 */

namespace App\Http\Controllers\Api\V1;

use App\Services\UserService;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    private $userService;

    /**
     * ActionController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * 关注某人、取关某人
     *
     * @author z00455118 <zhenhuaixiu@huawei.com>
     *
     * @param $userUuid
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function follow($userUuid)
    {
        return $this->userService->followDB($userUuid);
    }

    /**
     * 查看某个用户与我的互粉关系
     *
     * @author z00455118 <zhenhuaixiu@huawei.com>
     *
     * @param $userUuid
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function status($userUuid)
    {
        return $this->userService->statusDB($userUuid);
    }

    /**
     * 获取某人关注的用户列表
     *
     * @author z00455118 <zhenhuaixiu@huawei.com>
     *
     * @param $userUuid
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFollowsList($userUuid)
    {
        $page = (int) request('page') > 0 ? (int) request('page') : 1;
        return $this->userService->getFollowsListDB($userUuid, $page);
    }
}
