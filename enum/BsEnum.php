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

    public static $codeMap = [
        self::PARAMS_ERROR_CODE => '缺少必传字段',
        self::UN_VALID_MOBILE   => '手机号不合法',
    ];
}