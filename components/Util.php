<?php
/**
 * Created by PhpStorm.
 * User: 13213
 * Date: 2019/4/8
 * Time: 21:36
 */


class Util
{
    /**
     * @param $mobile
     * @return bool
     * @desc 校验手机号是否合法
     */
    public static function isValidMobile($mobile)
    {
        if(empty($mobile)){
            return false;
        }
        $ret = preg_match('/^(13[0-9]|14[5679]|15[012356789]|16[56]|17[0-9]|18[0-9]|19[89]|14[57])[0-9]{8}$/', $mobile);
        if ($ret) {
            return true;
        }
        return false;
    }

    /**
     * @param $userName
     * @return bool
     * @desc 校验用户姓名是否合法
     */
    public static function isValidUserName($userName)
    {
        if (empty($userName)) {
            return false;
        }

        if (mb_strlen($userName) > 20 || mb_strlen($userName) < 1) {
            return false;
        }
        $userName = str_replace('·', '', $userName);
        if (!preg_match("/^[\x{4e00}-\x{9fa5}]+$/u", $userName)) {
            return false;
        }
        return true;
    }
}