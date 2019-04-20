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
    public function getUserDevice($uid)
    {
        //查询uid是否拥有设备
        $userModel = new UserModel();
        $data = ['did'];
        $table = "uid_did_map";
        $result = $userModel->getUserInfo($data, $table, "uid", $uid);
        return $result;
    }
}