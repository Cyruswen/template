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
use app\domain\UserService;
use BsEnum;

class UserController extends GraduationProjectBaseController
{

    /**
     * author: wenkaikai
     * desc: 用户注册接口
     */
    public function actionRegister()
    {
        $userService = new UserService();
        $checkFiled = ['userName', 'password'];
        $result = $userService->checkParams($checkFiled, $this->params);
        if (!$result) {
            $this->response = [
                'code'   => BsEnum::ERROR_CODE,
                'reason' => BsEnum::$codeMap[BsEnum::ERROR_CODE],
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