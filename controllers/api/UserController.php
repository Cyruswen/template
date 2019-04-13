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
use Util;

class UserController extends GraduationProjectBaseController
{

    /**
     * author: wenkaikai
     * desc: 用户注册接口
     */
    public function actionLogin()
    {
        $userService = new UserService();
        $checkFiled = ['account', 'password'];
        $result = $userService->checkParams($checkFiled, $this->params);
        if (!$result) {
            $this->response = [
                'code'   => BsEnum::PARAMS_ERROR_CODE,
                'reason' => BsEnum::$codeMap[BsEnum::PARAMS_ERROR_CODE],
            ];
        }
        $userService->judjeLoginType($this->params);
        
    }

    /**
     * author: wenkaikai
     * desc: 用户登录接口
     */
    public function actionRegister()
    {
        $userService = new UserService();
        $checkFiled = ["userName", "password", "userPhone", "userEmail"];
        //校验参数是否为空
        $result = $userService->checkParams($checkFiled, $this->params);
        if (!$result) {
            $this->response = [
                'code'   => BsEnum::PARAMS_ERROR_CODE,
                'reason' => BsEnum::$codeMap[BsEnum::PARAMS_ERROR_CODE],
            ];
            return;
        }
        //校验参数是否合法
        $failCode = 0;
        $checkRet = $userService->checkLoginParams($this->params, $failCode);
        if (!$checkRet) {
            $this->response = [
                'code'   => $failCode,
                'reason' => BsEnum::$codeMap[$failCode],
            ];
            return;
        }
        //TODO 保存数据
    }


}