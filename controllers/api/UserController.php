<?php
/**
 * Created by PhpStorm.
 * User: 13213
 * Date: 2019/3/28
 * Time: 14:24
 */

namespace app\controllers\api;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use GraduationProjectBaseController;
use Flogger;
//use UserService;
use app\domain\UserService;

class UserController extends GraduationProjectBaseController
{

    /**
     * author: wenkaikai
     * desc: 用户注册接口
     */
    public function actionRegister()
    {
        $userService = new UserService();
        $checkFiled = ['userName', 'passWord'];
        $result = $userService->checkParams($checkFiled, $this->params);
        if (!$result) {
            $this->response = [
                'code'   => -1,
                'reason' => '缺少必须字段',
            ];
        }
    }

    /**
     * author: wenkaikai
     * desc: 用户登录接口
     */
    public function actionLogin()
    {

    }


}