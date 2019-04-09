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
        $retCheckMobile = Util::isValidMobile($mobile);
        if (!$retCheckMobile) {
             $failCode = BsEnum::UN_VALID_MOBILE;
             return false;
        }
        $retCheckUserName = Util::isValidUserName($userName);
        if (!$retCheckUserName) {
            $failCode = BsEnum::UN_VALID_MOBILE;
            return false;
        }
        return true;
    }
}