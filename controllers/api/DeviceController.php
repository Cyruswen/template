<?php
/**
 * Created by PhpStorm.
 * User: 13213
 * Date: 2019/4/19
 * Time: 22:24
 */

namespace app\controllers\api;
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
        }
        $deviceService = new DeviceService();
        $result = $deviceService->getUserDevice($uid);
        Flogger::info("device: " . json_encode($result));
    }

    /**
     * @desc 添加新设备权限
     */
    public function actionAddDevice()
    {

    }

    /**
     * @desc 删除设备权限
     */
    public function actionDelDevice()
    {

    }
}