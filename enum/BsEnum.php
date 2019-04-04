<?php
/**
 * Created by PhpStorm.
 * User: 13213
 * Date: 2019/4/4
 * Time: 17:55
 */

class BsEnum
{
    const ERROR_CODE = 10000; //字段缺失

    public static $codeMap = [
        self::ERROR_CODE => '缺少必传字段',
    ];
}