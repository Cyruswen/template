<?php
/**
 * Created by PhpStorm.
 * User: 13213
 * Date: 2019/4/4
 * Time: 11:40
 */
namespace app\domain;
use Flogger;
use Util;
use BsEnum;

class UserService
{
    /**
     * @author wenkaikai
     * @param $checkFiled
     * @param $params
     * @return bool
     */
    public function checkParams($checkFiled, $params)
    {
        foreach ($checkFiled as $value) {
            if (empty($params[$value])) {
                Flogger::warning("缺少参数: " . $value);
                return false;
            }
        }
        return true;
    }

    public function checkLoginParams($params, &$failCode)
    {
        $mobile = $params['userPhone'];
        $userName = $params['userName'];
        $password = $params['password'];
        $email = $params['userEmail'];
        $retCheckMobile = Util::isValidMobile($mobile);
        //校验用户手机号
        if (!$retCheckMobile) {
             $failCode = BsEnum::UN_VALID_MOBILE;
             return false;
        }
        //校验用户名
        $retCheckUserName = Util::isValidUserName($userName);
        if (!$retCheckUserName) {
            $failCode = BsEnum::UN_VALID_USERNAME;
            return false;
        }
        //校验密码是否正确
        $retCheckPassword = Util::isVaildPassword($password);
        if ($retCheckPassword == BsEnum::UN_VALID_PASSLEN) {
            $failCode = BsEnum::UN_VALID_PASSLEN;
            return false;
        } elseif ($retCheckPassword == BsEnum::UN_VALID_PASSWORD) {
            $failCode = BsEnum::UN_VALID_PASSWORD;
            return false;
        }
        //校验邮箱是否合法
        $retCheckEmail = Util::isVaildEmail($email);
        if (!$retCheckEmail) {
            $failCode = BsEnum::UN_VALID_EMAIL;
            return false;
        }
        return true;
    }
}