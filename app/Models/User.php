<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id', 'uuid', 'name', 'phone', 'avatar', 'sex', 'forbidden'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    public function getPhoneAttribute($phone)
    {
        return substr_replace($phone, '****', 3, 4);
    }

    /**
     * 登录 login in
     * 生成 sanctum token
     * @return string
     */
    public function getSanctumToken(): string
    {
        return $this->createToken(env('APP_NAME'))->plainTextToken;
    }

    /**
     * 删除 当前用户所有 sanctum token
     * 先删 再 生成 即可满足单一登录
     * @return mixed
     */
    public function destroyAllSanctumTokens()
    {
        return $this->tokens()->delete();
    }

    /**
     * 删除 当前用户的 某个ID的token（仅能删自己的）
     * @return mixed
     */
    public function destroySanctumTokensById($tokenId)
    {
        return $this->tokens()->where('id', $tokenId)->delete();
    }

    /**
     * 登出 logout
     * 删除 当前用户正在使用的 sanctum token
     * @return mixed
     */
    public function destroyCurrentSanctumTokens()
    {
        return $this->currentAccessToken()->delete();
    }

}
