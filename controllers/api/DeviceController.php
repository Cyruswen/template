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
        $filed = ['d', 'v', 't'];
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
        $did = $this->params['d'];
        $failCode = 0;
        $verifyCode = $this->params['v'];
        $temperature = intval($this->params['t'])/100;
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

    public function actionMaxCost()
    {
        //校验参数
        $filed = ['cost'];
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
        $resultData = $deviceService->getMaxCostData($this->params['cost'], "d_tcscws", "d_trscws");
        $this->response = [
            'result' => $resultData,
        ];
    }

    public function actionMinEnergy()
    {
        //校验参数
        $filed = ['cost'];
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
        $resultData = $deviceService->getMaxCostData($this->params['cost'], "d_ecscws", "d_trscws");
        $this->response = [
            'result' => $resultData,
        ];
    }

    public function actionMinTime()
    {
        //校验参数
        $filed = ['cost'];
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
        $resultData = $deviceService->getMaxCostData($this->params['cost'], "d_trscws", "d_tcscws");
        $this->response = [
            'result' => $resultData,
        ];
    }

    public function actionGetWarningTemperature()
    {
        //校验参数
        $filed = ['uid']; // uid_did_map
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

        //获取用户可以查看的设备
        $uidMap = $deviceService->getUserDevice($this->params['uid']);
        $userDidMap = [];
        foreach ($uidMap as $item) {
            $userDidMap[] = $item['did'];
        }
        Flogger::info("获取到用户可以查看的设备:" . json_encode($userDidMap));
        //根据用户拥有权限的did到device_info表里查设备对应的阈值
        $threshold =  $deviceService->getThreshold($userDidMap);
        Flogger::info("获取设备的阈值: " . json_encode($threshold));
        //遍历查询设备温度
        $resultData = [];
        foreach ($threshold as $item) {
            $warningData = $deviceService->getWarningData($item['did'], $item['threshold']);
            if (!empty($warningData)) {
                $resultData[] = $warningData;
            }
        }
        Flogger::info("查询温度预警结果:" . json_encode($resultData));

        $this->response = [
            'result' => $result,
        ];
    }

    /**
     * @desc 查询温度信息 根据interval字段确定时间,默认返回一小时 同时返回温度预测结果
     */
    public function actionQueryTemperature()
    {
        //校验参数
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
        if (empty($this->params['interval'])) {
            $this->params['interval'] = BsEnum::DEFAULT_TIME;
        }
        $uid = $this->params['uid'];
        $did = $this->params['did'];
        $update_time = time();
        $interval = intval($this->params['interval']);
        $this->uid = $uid;
        $deviceService = new DeviceService();
        $arrTemperature = $deviceService->queryTemperature($did, $update_time, $interval);
        $forecastTemperature = $deviceService->forecastTemperature($arrTemperature);
        $arrRestult = $deviceService->formTemperatureData($arrTemperature);
        $this->response = [
            'forecastTemperature' =>$forecastTemperature,
            'result' => $arrRestult,
        ];
    }

    //设置设备温度阈值 默认30度
    public function actionChangeThreshold()
    {
        //校验参数
        $filed = ['did', 'threshold'];
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
        $failCode = 0;
        $did = $this->params['did'];
        //如果fromPc参数为空, 表示来自PC端, 不需要校验验证码
        if (empty($this->params['fromPc'])) {
            if (empty($this->params['verifyCode'])) {
                $this->response = [
                    'code' => BsEnum::PARAMS_ERROR_CODE,
                    'reason' => BsEnum::$codeMap[BsEnum::PARAMS_ERROR_CODE],
                ];
                return;
            }
            $verifyCode = $this->params['verifyCode'];
            $result = $deviceService->canAddDevice($did, $verifyCode, $failCode);
            if (!$result) {
                $this->response = [
                    'code' => $failCode,
                    'reason' => BsEnum::$codeMap[$failCode],
                ];
                return;
            }
        }
        $threshold = $this->params['threshold'];
        try{
            $updateData = ["threshold" => $threshold];
            $deviceService->updateDeviceStatus($did, $updateData, $failCode);
        } catch (Exception $e) {
            Flogger::warning($e->getMessage());
            $this->response = [
                'code' => $failCode,
                'reason' => BsEnum::$codeMap[$failCode],
            ];
        }
    }
}