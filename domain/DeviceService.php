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
use yii\db\Exception;

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
        $data = ['verify_code', 'status'];
        $userModel = new UserModel();
        $verifyCodeByDid = $userModel->getUserInfo($data, $table, "did", $did);
        if (empty($verifyCodeByDid)) {
            $failCode = BsEnum::NO_SUCH_DEVICE;
            return false;
        }
        if ($verifyCodeByDid['status'] == BsEnum::DEVICE_STATUS_NOT_USE) {
            $failCode = BsEnum::NOT_USE_DEVICE;
            return false;
        }
        if ($verifyCodeByDid['verify_code'] !== $verifyCode) {
            $failCode = BsEnum::UN_CORRECT_VERIFY;
            return false;
        }
        return true;
    }

    /**
     * @param $uid
     * @param $did
     * @param $failCode
     * @throws Exception
     * @desc 更新uid_did_map 表
     */
    public function updateUidDidMap($uid, $did, &$failCode)
    {
        $table = "uid_did_map";
        $data = ['did'];
        $userModel = new UserModel();
        $set = $userModel->getUserInfo($data, $table, "uid", $uid, true, "did", $did);
        if (!empty($set)) {
            $failCode = BsEnum::HAS_SUCH_DATA;
            throw new Exception("数据已经添加!");
        }
        $saveData = [
            'uid' => $uid,
            'did' => $did,
        ];
        $userModel->saveBaseInfo($table, $saveData);
    }

    /**
     * @param $uid
     * @param $did
     * @param $failCode
     * @throws Exception
     */
    public function deleteDevice($uid, $did, &$failCode)
    {
        $table = "uid_did_map";
        $userModel = new UserModel();
        $data = ['uid'];
        $set = $userModel->getUserInfo($data, $table, "uid", $uid, true, "did", $did);
        if (empty($set)) {
            $failCode = BsEnum::NO_SUCH_DATA;
            throw new Exception("数据不存在, 无法删除");
        }
        $userModel->deleteDevice($table, $uid, $did);
    }
}