<?php
/**
 * Created by PhpStorm.
 * User: 13213
 * Date: 2019/4/19
 * Time: 22:24
 */

namespace app\controllers\api;
use app\domain\UserService;
use app\models\User;
use yii\db\Exception;
use GraduationProjectBaseController;
use Flogger;
use app\domain\DeviceService;
use BsEnum;
use Util;

class DeviceController extends GraduationProjectBaseController
{
    /**
     * @desc 获取用户能够使用的设备
     */
    public function actionGetUserDevice()
    {
        $uid = $this->params['uid'];
        if (empty($uid)) {
            $this->response = [
                'code' => BsEnum::PARAMS_ERROR_CODE,
                'reason' => BsEnum::$codeMap[BsEnum::PARAMS_ERROR_CODE],
            ];
            return;
        }
        $this->uid = $uid;
        $deviceService = new DeviceService();
        $arrRes = $deviceService->getUserDevice($uid);
        $this->response = [
            'result' => $arrRes,
        ];
    }

    /**
     * @desc 添加新设备权限
     */
    public function actionAddDevice()
    {
        $filed = ['uid', 'did', 'verifyCode'];
        $userService = new UserService();
        $result = $userService->checkParams($filed, $this->params);
        if (!$result) {
            $this->response = [
                'code' => BsEnum::PARAMS_ERROR_CODE,
                'reason' => BsEnum::$codeMap[BsEnum::PARAMS_ERROR_CODE],
            ];
            return;
        }
        // 去device_info里根据did拿验证码  1. 如果不存在或者验证码不对, 返回错误码及错误原因
        // 2. 如果验证码正确, 向uid_did_map表里添加数据(如果数据已存在返回错误码及错误原因)
        $deviceServie = new DeviceService();
        $did = $this->params['did'];
        $verifyCode = $this->params['verifyCode'];
        $failCode = 0;
        $result = $deviceServie->canAddDevice($did, $verifyCode, $failCode);
        if (!$result) {
            $this->response = [
                'code' => $failCode,
                'reason' => BsEnum::$codeMap[$failCode],
            ];
            return;
        }
        $uid = $this->params['uid'];
        $failCode = 0;
        try {
            $deviceServie->updateUidDidMap($uid, $did, $failCode);
        } catch (Exception $e)
        {
            Flogger::warning($e->getMessage());
            $this->response = [
                'code' => $failCode,
                'reason' => BsEnum::$codeMap[$failCode],
            ];
        }
    }

    /**
     * @desc 删除设备权限
     */
    public function actionDelDevice()
    {
        $filed = ['uid', 'did'];
        $userService = new UserService();
        $result = $userService->checkParams($filed, $this->params);
        if (!$result) {
            $this->response = [
                'code' => BsEnum::PARAMS_ERROR_CODE,
                'reason' => BsEnum::$codeMap[BsEnum::PARAMS_ERROR_CODE],
            ];
            return;
        }
        $deviceService = new DeviceService();
        $uid = $this->params['uid'];
        $did = $this->params['did'];
        $failCode = 0;
        try {
            $deviceService->deleteDevice($uid, $did, $failCode);
        } catch (Exception $e) {
            Flogger::warning($e->getMessage());
            $this->response = [
                'code' => $failCode,
                'reason' => BsEnum::$codeMap[$failCode],
            ];
        }
    }

    /**
     * @desc 保存温度信息
     */
    public function actionSaveTemperature()
    {
        //校验参数是否为空
        $filed = ['did', 'temperature', 'verify_code'];
        $userService = new UserService();
        $result = $userService->checkParams($filed, $this->params);
        if (!$result) {
            $this->response = [
                'code' => BsEnum::PARAMS_ERROR_CODE,
                'reason' => BsEnum::$codeMap[BsEnum::PARAMS_ERROR_CODE],
            ];
            return;
        }
        //查看user_device表 did是否为空 status是否为1
        // 1 如果结果为空, 返回错误码和错误原因
        // 2 did不为空但是status为0 将状态置位1
        $deviceService = new DeviceService();
        $did = $this->params['did'];
        $failCode = 0;
        $temperatureData = $this->params['temperature'];
        $verifyCode = $this->params['verify_code'];
        $temperature = $deviceService->formTemperature($temperatureData);
        try{
            $status = $deviceService->canUpdateDeviceTemperature($did, $verifyCode,$failCode);
            Flogger::info("设备状态为: " . $status);
            if ($status == BsEnum::DEVICE_STATUS_NOT_USE) {
                $updateData = ["status" => BsEnum::DEVICE_STATUS_HAS_USED];
                $deviceService->updateDeviceStatus($did, $updateData, $failCode);
            }
            //保存数据
            $deviceService->saveTemperatureInfo($did, $temperature, $failCode);
        } catch (Exception $e) {
            Flogger::warning($e->getMessage());
            $this->response = [
                'code' => $failCode,
                'reason' => BsEnum::$codeMap[$failCode],
            ];
        }
    }
}