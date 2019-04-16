<?php
/**
 * Created by PhpStorm.
 * User: 13213
 * Date: 2019/3/28
 * Time: 14:24
 */

namespace app\controllers\api;
use app\models\UserModel;
use Yii;
use yii\db\Exception;
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
        $userName = $this->params['userName'];
        $mobile   = $this->params['userPhone'];
        $password = $this->params['password'];
        //生成uid
        $this->params['uid'] = Util::generateUid($userName, $mobile);
        Flogger::$uid = $this->params['uid'];
        //处理密码数据
        $salt = Util::getSalt();
        $this->params['salt'] = $salt;
        $this->params['password'] = Util::passwdEncode($password, $salt);
        //保存数据
        try{
            $userService->saveUserBaseInfo($this->params);
        } catch (Exception $e)
        {
            Flogger::info('用户信息保存失败!' . 'code: ' . $e->getCode() . 'msg: ' . $e->getMessage());
            $this->response = [
                'code'   => BsEnum::SQL_INSERT_FAIL,
                'reason' => BsEnum::$codeMap[BsEnum::SQL_INSERT_FAIL],
            ];
            return;
        }
    }
}