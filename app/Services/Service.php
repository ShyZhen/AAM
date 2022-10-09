<?php
/**
 * Service 父类
 */

namespace App\Services;

use Illuminate\Http\Request;

class Service
{
    protected $imageProcess = '';

    protected $imagePosterProcess = '';

    /**
     * 生成 uuid
     *
     * @param string $prefix
     *
     * @return string
     */
    protected static function uuid($prefix = '')
    {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid = substr($chars, 0, 8) . '-';
        $uuid .= substr($chars, 8, 4) . '-';
        $uuid .= substr($chars, 12, 4) . '-';
        $uuid .= substr($chars, 16, 4) . '-';
        $uuid .= substr($chars, 20, 12);

        return $prefix . $uuid;
    }

    /**
     * 随机6位验证码
     *
     * @return string
     */
    protected static function code()
    {
        $code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_BOTH);

        return $code;
    }

    /**
     * 获取客户端真实IP
     *
     * @return null|string
     */
    protected static function getClientIp()
    {
        return Request()->getClientIp();
    }

}
