<?php
/**
 * Created by PhpStorm.
 * User: 13213
 * Date: 2019/4/19
 * Time: 22:24
 */

namespace app\controllers\api;
use GraduationProjectBaseController;

class DeviceController extends GraduationProjectBaseController
{
    public function actionGetUserDevice()
    {
        $this->response = [
            'info' => '接口测试',
        ];
    }
}