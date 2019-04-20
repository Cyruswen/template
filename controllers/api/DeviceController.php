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
        $this->uid = $uid;
        $deviceService = new DeviceService();
        $arrRes = $deviceService->getUserDevice($uid);
        $response = [];
        foreach ($arrRes as $item) {
            $result[] = $item['did'];
        }
        $this->response = [
            'result' => $response,
        ];
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