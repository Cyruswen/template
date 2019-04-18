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
     * @author wenkaikai
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
     * @author wenkaikai
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

    /**
     * @author wenkaikai
     * @param $password
     * @return false|int
     * @desc 校验密码(最少六位, 必须包含大写字母小写字母和数字)
     */
    public static function isVaildPassword($password)
    {
        if (strlen($password) < 6) {
            return BsEnum::UN_VALID_PASSLEN;
        }
        $result = preg_match( '/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?!.*\s)/', $password);
        if ($result == 0) {
            return BsEnum::UN_VALID_PASSWORD;
        }
        return true;
    }

    /**
     * @author wenkaikai
     * @param $email
     * @return bool
     * @desc 判断邮箱是否合法
     */
    public static function isVaildEmail($email)
    {
        if (empty($email)) {
            return false;
        }
        $ret = strstr($email, '@');
        if ($ret === false || strlen($email) < BsEnum::EMAIL_LESS_LEN) {
            return false;
        }
        return true;
    }

    /**
     * @author wenkaikai
     * @param $userName
     * @param $mobile
     * @return bool|string
     * @desc 生成随机的八位用户uid
     */
    public static function generateUid($userName, $mobile)
    {
        $strInfo = $userName . $mobile;
        $unique_no = substr(base_convert(md5($strInfo), 16, 10), 0, 8);
        return $unique_no;
    }

    /**
     * @author wenkaikai
     * @return string
     * @desc 用于存储密码之前获取盐值
     */
    public static function getSalt()
    {
        return base64_encode(uniqid("BS"));
    }

    /**
     * @param $password
     * @param $salt
     * @return string
     * @desc 加密密码
     */
    public static function passwdEncode($password, $salt)
    {
        return sha1($password . $salt);
    }
}