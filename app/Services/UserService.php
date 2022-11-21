<?php
/**
 * @Author huaixiu.zhen@gmail.com
 * http://litblc.com
 * User: huaixiu.zhen
 */

namespace App\Services;

use App\Models\Technician;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\UsersFollowRepository;

class UserService extends Service
{
    private $pageSize = 10;

    private $userRepository;

    private $usersFollowRepository;

    /**
     * ActionService constructor.
     *
     * @param UserRepository        $userRepository
     * @param UsersFollowRepository $usersFollowRepository
     */
    public function __construct(
        UserRepository $userRepository,
        UsersFollowRepository $usersFollowRepository
    ) {
        $this->userRepository = $userRepository;
        $this->usersFollowRepository = $usersFollowRepository;
    }

    /**
     * 关注、取关 某个用户（Mysql版）
     *
     * @author z00455118 <zhenhuaixiu@huawei.com>
     *
     * @param $userUuid
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function followDB($userUuid)
    {
        // 当前用户ID
        $mine = Auth::user();
        $currId = $mine->id;

        // 目标技师
        $technician = Technician::where('uuid', $userUuid)->first();

        if ($technician) {
            $follow = $this->usersFollowRepository->isFollowed($currId, $technician->id);

            // 取关操作
            if ($follow) {
                $follow->delete();
                // 更新数据库计数
                $this->updateFansAndFollowNum($mine, $technician, 'cancel');
                $msg = '已取消关注';
            } else {
                // 关注操作

                // 查看他是否已经关注了我
                $createFollow = $this->usersFollowRepository->create([
                    'master_user_id' => $technician->id,
                    'following_user_id' => $currId,
                ]);

                if ($createFollow) {
                    // 更新数据库计数
                    $this->updateFansAndFollowNum($mine, $technician, '');
                    $msg = '关注成功';
                }
            }

            return response()->json(
                [
                    'data' => [],
                    'code' => 0,
                    'message' => $msg
                ],
                Response::HTTP_OK
            );
        } else {
            return response()->json(
                [
                    'data' => [],
                    'code' => -1,
                    'message' => '该技师不存在'
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * 查看某个用户与我的互粉状态（MYSQL版）
     * 用在查看用户detail时
     *
     * @author z00455118 <zhenhuaixiu@huawei.com>
     *
     * @param $userUuid
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function statusDB($userUuid)
    {
        // 当前用户ID
        $mine = Auth::user();
        $currId = $mine->id;

        // 目标技师
        $technician = Technician::where('uuid', $userUuid)->first();

        if ($technician) {
            $iFollowedYou = (bool) $this->usersFollowRepository->isFollowed($currId, $technician->id);
            $youFollowMe = (bool) $this->usersFollowRepository->isFollowed($technician->id, $currId);

            $data = [
                'inMyFollows' => $iFollowedYou,
                // 'inMyFans' => $youFollowMe
            ];
            return response()->json(
                [
                    'data' => $data,
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
                    'message' => '该技师不存在'
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * 获取用户的关注列表（MYSQL版本）
     *
     * @author z00455118 <zhenhuaixiu@huawei.com>
     *
     * @param $userUuid
     * @param $page
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFollowsListDB($userUuid, $page)
    {
        // 当前用户ID
        $mine = Auth::user();
        $currId = $mine->id;

        // 目标用户(其实AAM项目就是自己)
        $user = $this->userRepository->findBy('uuid', $userUuid);

        if ($user) {
            $start = ($page - 1) * $this->pageSize;

            // 目标用户的关注列表
            $userFollows = $this->usersFollowRepository->getSomeoneFollows($user->id, $start, $this->pageSize);
            $userFollowsIdArr = $userFollows->pluck('master_user_id');
            $userFollowsList = Technician::getByIdArr($userFollowsIdArr);

            // 看别人
            if ($user->id != $currId) {
                // 找到这些人中 我同时关注了的
                $myFollowedArr = $this->usersFollowRepository->getSomeoneFollowsByIdArr($currId, $userFollowsIdArr);

                foreach ($userFollowsList as &$userFollow) {
                    // $userFollow->inMyFans = (bool) false;
                    $userFollow->inMyFollows = (bool) false;

                    // 我同时关注了他（她）
                    foreach ($myFollowedArr as $myFollower) {
                        if ($myFollower->master_user_id == $userFollow->id) {
                            $userFollow->inMyFollows = (bool) true;
                        }
                    }
                }
                unset($userFollow);
            } else {
                // 看的是自己
                foreach ($userFollowsList as &$userFollow) {
                    $userFollow->inMyFollows = true;
                    unset($userFollow->both_status);
                }
                unset($userFollow);
            }
            return response()->json(
                [
                    'data' => $userFollowsList,
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
                    'message' => '该用户不存在'
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * 关注、取关 操作用户表计数
     *
     * @author z00455118 <zhenhuaixiu@huawei.com>
     *
     * @param $mine    // 当前登录用户对象
     * @param $technician    // 被操作用户对象
     * @param $type    // cancel 为取关
     */
    private function updateFansAndFollowNum($mine, $technician, $type)
    {
        // 正向操作
        if ($type !== 'cancel') {
            // $mine->followed_count += 1;
            // $mine->save();
            $technician->fans_count += 1;
            $technician->save();

        } else {
            // 逆向操作
            // $mine->followed_count > 0 && $mine->followed_count -= 1;
            // $mine->save();
            $technician->fans_count > 0 && $technician->fans_count -= 1;
            $technician->save();

        }
    }
}
