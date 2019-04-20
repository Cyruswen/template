<?php
/**
 * Created by PhpStorm.
 * User: 13213
 * Date: 2019/4/20
 * Time: 15:56
 */

namespace app\domain;
use app\models\UserModel;
use Flogger;
use Util;
use BsEnum;

class DeviceService
{
    /**
     * @param $uid
     * @return array
     * @desc 获取用户设备信息
     */
    public function getUserDevice($uid)
    {
        //查询uid是否拥有设备
        $userModel = new UserModel();
        $data = ['did'];
        $table = "uid_did_map";
        $result = $userModel->getBatchUserInfo($data, $table, "uid", $uid);
        return $result;
    }

    /**
     * @param $did
     * @param $verifyCode
     * @param $failCode
     * @return bool
     * @desc 查询能否添加设备
     */
    public function canAddDevice($did, $verifyCode, &$failCode)
    {
        $table = "device_info";
        $data = ['verify_code'];
        $userModel = new UserModel();
        $verifyCodeByDid = $userModel->getUserInfo($data, $table, "did", $did);
        if (empty($verifyCodeByDid)) {
            $failCode = BsEnum::NO_SUCH_DEVICE;
            return false;
        }
        if ($verifyCodeByDid['verify_code'] !== $verifyCode) {
            $failCode = BsEnum::UN_CORRECT_VERIFY;
            return false;
        }
        return true;
    }
}