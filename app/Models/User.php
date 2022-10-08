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
        'id', 'uuid', 'name', 'phone', 'avatar', 'sex', 'closure'
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
     * 生成 sanctum token
     * @return string
     */
    public function getSanctumToken(): string
    {
        return $this->createToken(env('APP_NAME'))->plainTextToken;
    }

    /**
     * 删除 当前用户所有 sanctum token
     * @return mixed
     */
    public function destroySanctumTokens()
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

}
