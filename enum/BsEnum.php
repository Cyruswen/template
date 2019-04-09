<?php
/**
 * Created by PhpStorm.
 * User: 13213
 * Date: 2019/4/4
 * Time: 17:55
 */

class BsEnum
{
    const PARAMS_ERROR_CODE = 10000; //字段缺失
    const UN_VALID_MOBILE   = 10001; //手机号不合法
    const UN_VALID_USERNAME = 10002; //用户手机号不合法
    const UN_VALID_PASSWORD = 10003; //密码不合法
    const UN_VALID_PASSLEN  = 10004; //密码长度不合法
    const UN_VALID_EMAIL    = 10005; //邮箱不合法

    public static $codeMap = [
        self::PARAMS_ERROR_CODE => '缺少必传字段',
        self::UN_VALID_MOBILE   => '手机号不合法',
        self::UN_VALID_USERNAME => '用户名不合法',
        self::UN_VALID_PASSWORD => '密码必须包含大小写字母和数字',
        self::UN_VALID_PASSLEN  => '密码长度不合法',
        self::UN_VALID_EMAIL    => '邮箱不合法',
    ];
}