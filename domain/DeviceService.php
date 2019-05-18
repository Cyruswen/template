<?php
/**
 * Created by PhpStorm.
 * User: 13213
 * Date: 2019/4/20
 * Time: 15:56
 */

namespace app\domain;
use app\models\UserModel;
use Codeception\Lib\Parser;
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

    /**
     * @param $did
     * @param $failCode
     * @return mixed
     * @throws Exception
     */
    public function canUpdateDeviceTemperature($did, $verifyCode, &$failCode)
    {
        $table = "device_info";
        $userModel = new UserModel();
        $data = ['verify_code','status'];
        $set = $userModel->getUserInfo($data, $table, "did", $did);
        if (empty($set)) {
            $failCode = BsEnum::NO_SUCH_DEVICE;
            throw new Exception("该设备不存在");
        }
        if ($set['verify_code'] != $verifyCode) {
            $failCode = BsEnum::UN_CORRECT_VERIFY;
            throw new Exception("验证码不正确");
        }
        return $set['status'];
    }

    /**
     * @param $did
     * @param $data
     * @param $failCode
     * @throws Exception
     */
    public function updateDeviceStatus($did, $data, &$failCode)
    {
        $table = "device_info";
        $userModel = new UserModel();
        try {
            $userModel->updateInfoById($table, $data, $did, false);
        } catch (Exception $e)
        {
            $failCode = BsEnum::SQL_UPDATE_FAIL;
            throw new Exception("更新用户状态失败!");
        }
    }

    /**
     * @param $did
     * @param $temperature
     * @param $failCode
     * @throws Exception
     */
    public function saveTemperatureInfo($did, $temperature, &$failCode) {
        $table = "device_temperature";
        $userModel = new UserModel();
        $data = [
          "did" => $did,
          "temperature" => $temperature,
          "update_time" => time(),
        ];
        try{
            $userModel->saveBaseInfo($table, $data);
        } catch (Exception $e) {
            $failCode = BsEnum::SQL_INSERT_FAIL;
            throw new Exception("温度保存失败!");
        }
    }

    public function getMaxCostData($maxCost, $compare, $orderBy)
    {
        $table = "decision";
        $userModel = new UserModel();
        $data = ["d_m", "d_c", "d_a", "d_n", "d_vx", "d_vy", "d_ax", "d_ay", "d_vz", "d_trscws", "d_ecscws", "d_tcscws", "d_cr","d_cs"];
        $orderData = $userModel->getOrderData($data, $table, $compare, $maxCost, $orderBy);
        return $orderData[0];
    }

    public function formTemperature($temperature)
    {
        $hundred = $temperature['hundred'];
        $decade = $temperature['decade'];
        $unit = $temperature['unit'];
        $zeroPointOne = $temperature['zeroPointOne'];
        $zeroPointZeroOne = $temperature['zeroPointZeroOne'];
        $temperatureData = floatval($hundred . $decade . $unit . '.' . $zeroPointOne . $zeroPointZeroOne);
        return $temperatureData;
    }

    public function getWarningData($uidMap, $warningTemperature)
    {
        $table = "device_temperature";
        $data = ['did', 'temperature', 'update_time'];
        $userModel = new UserModel();
        $lastWeek = strtotime("-7 days");
        $result = $userModel->getWarningTemperature($data, $table, $warningTemperature, $lastWeek);
        return $result;
    }
}