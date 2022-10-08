<?php
/**
 * AuthController
 *
 * @author huaixiu.zhen
 * @link https://www.litblc.com
 * 2022/10/8 14:13
 **/

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function test()
    {
        $user = User::find(2);
//        dd($user->destroySanctumTokensById(11));
//        dd($user->destroySanctumTokens());
//        dd($user->getSanctumToken());
    }

    public function getUser()
    {
        dd(Auth::user());
    }
}
