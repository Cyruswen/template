<?php
/**
 * Created by PhpStorm.
 * User: 13213
 * Date: 2019/4/4
 * Time: 11:40
 */
namespace app\domain;
use app\models\UserModel;
use Flogger;
use Util;
use BsEnum;

class UserService
{
    /**
     * @author wenkaikai
     * @param $checkFiled
     * @param $params
     * @return bool
     */
    public function checkParams($checkFiled, $params)
    {
        foreach ($checkFiled as $value) {
            if (empty($params[$value])) {
                Flogger::warning("缺少参数: " . $value);
                return false;
            }
        }
        return true;
    }

    /**
     * @param $params
     * @param $failCode
     * @return bool
     * @desc 校验登录参数是否合法
     */
    public function checkLoginParams($params, &$failCode)
    {
        $mobile = $params['userPhone'];
        $userName = $params['userName'];
        $password = $params['password'];
        $email = $params['userEmail'];
        $retCheckMobile = Util::isValidMobile($mobile);
        //校验用户手机号
        if (!$retCheckMobile) {
             $failCode = BsEnum::UN_VALID_MOBILE;
             return false;
        }
        //校验用户名
        $retCheckUserName = Util::isValidUserName($userName);
        if (!$retCheckUserName) {
            $failCode = BsEnum::UN_VALID_USERNAME;
            return false;
        }
        //校验密码是否正确
        $retCheckPassword = Util::isVaildPassword($password);
        if ($retCheckPassword === BsEnum::UN_VALID_PASSLEN) {
            $failCode = BsEnum::UN_VALID_PASSLEN;
            return false;
        } elseif ($retCheckPassword === BsEnum::UN_VALID_PASSWORD) {
            $failCode = BsEnum::UN_VALID_PASSWORD;
            return false;
        }
        //校验邮箱是否合法
        $retCheckEmail = Util::isVaildEmail($email);
        if (!$retCheckEmail) {
            $failCode = BsEnum::UN_VALID_EMAIL;
            return false;
        }
        return true;
    }

    /**
     * @param $account
     * @desc 判断登录时用户输入的类型(电话号, 邮箱, 用户名)
     */
    public function loginType(&$params)
    {
        $account = $params['account'];
        if (is_numeric($account)) {
            $params['mobile'] = $account;
            $params['userType'] = BsEnum::MOBILE;
        } else {
            $params['userName'] = $account;
            $params['userType'] = BsEnum::USERNAME;
        }
        unset($params['account']);
    }

    /**
     * @param $params
     * @throws \yii\db\Exception
     */
    public function saveUserBaseInfo($params)
    {
        $table = "user_base";
        $saveData = $this->formUserData($params);
        $saveRes = (new UserModel())->saveBaseInfo($table, $saveData);
        return $saveRes;
    }

    /**
     * @param $userName
     * @param $mobile
     * @param $failCode
     * @return bool
     * @desc 校验用户是否已经注册
     */
    public function hasRegist($userName, $mobile, &$failCode)
    {
        $table = "user_base";
        $data = ['email'];
        $userModel = new UserModel();
        $infoByUserName = $userModel->getUserInfo($data, $table, 'user_name', $userName);
        $infoByMobile = $userModel->getUserInfo($data, $table, 'mobile', $mobile);
        if(!empty($infoByMobile) && !empty($infoByUserName)) {
            $failCode = BsEnum::HAS_REGISTER;
            return true;
        } elseif (!empty($infoByUserName)) {
            $failCode = BsEnum::USERNAME_HAS_USED;
            return true;
        } elseif (!empty($infoByMobile)) {
            $failCode = BsEnum::MOBILE_HAS_USED;
            return true;
        }
        return false;
    }

    /**
     * @param $params
     * @param $failCode
     * @return bool
     * @desc 判断用户是否可以登录
     */
    public function canLogin($params, &$failCode) {
        $table = "user_base";
        $data = ['uid', 'password', 'salt'];
        $userModel = new UserModel();
        $ret = [];
        if ($params['userType'] === BsEnum::MOBILE) {
            $mobile = $params['mobile'];
            $ret = $userModel->getUserInfo($data, $table, 'mobile', $mobile);
        } else {
            $userName = $params['userName'];
            $ret = $userModel->getUserInfo($data, $table, 'user_name', $userName);
        }
        if (empty($ret)) {
            $failCode = BsEnum::NO_SUCH_USER;
            return false;
        }
        $password = $params['password'];
        $salt = $ret['salt'];
        $passwordEncrypt = Util::passwdEncode($password, $salt);
        if ($passwordEncrypt !== $ret['password']) {
            $failCode = BsEnum::UN_CORRECT_PASS;
            return false;
        }
        return $ret['uid'];
    }

    /**
     * @param $data
     * @throws \yii\db\Exception
     * @desc 更改用户信息
     */
    public function changeUserInfo($data, $uid)
    {
        $table = 'user_base';
        $userModel = new UserModel();
        $userModel->updateInfoByUid($table, $data, $uid);
    }

    public function getOldPasswdInfoByUid($uid)
    {
        $table = "user_base";
        $data = ['password', 'salt'];
        $userModel = new UserModel();
        $ret = $userModel->getUserInfo($data, $table, 'uid', $uid);
        return $ret;
    }

    /**
     * @param $uid
     * @param $mobile
     * @param $failCode
     * @return bool
     * @desc 判断用户是否可以更改手机号
     */
    public function canChangeMobile($uid, $mobile, &$failCode)
    {
        $userModel = new UserModel();
        $table = "user_base";
        $data = ['mobile'];
        $userInfo = $userModel->getUserInfo($data, $table, 'uid', $uid);
        if ($userInfo['mobile'] === $mobile) {
            $failCode = BsEnum::SAME_MOBILE;
            return false;
        }
        $data = ['email'];
        $userInfo = $userModel->getUserInfo($data, $table, 'mobile', $mobile);
        if (!empty($userInfo)) {
            $failCode = BsEnum::MOBILE_HAS_USED;
            return false;
        }
        $retCheckMobile = Util::isValidMobile($mobile);
        if (!$retCheckMobile) {
            $failCode = BsEnum::UN_VALID_MOBILE;
            return false;
        }
        return true;
    }

    /**
     * @param $uid
     * @param $email
     * @param $failCode
     * @return bool
     * @desc 判断用户是否能更改邮箱
     */
    public function canChangeEmail($uid, $email, &$failCode)
    {
        $userModel = new UserModel();
        $table = "user_base";
        $data = ['email'];
        $userInfo = $userModel->getUserInfo($data, $table, 'uid', $uid);
        if($userInfo['email'] === $email) {
            $failCode = BsEnum::SAME_EMAIL;
            return false;
        }
        $retCheckMobile = Util::isVaildEmail($email);
        if (!$retCheckMobile) {
           $failCode = BsEnum::UN_VALID_EMAIL;
           return false;
        }
        return true;
    }

    /**
     * @desc 格式化用户信息
     */
    private function formUserData($params)
    {
        $saveData = [
            'uid'       => $params['uid'],
            'user_name' => $params['userName'],
            'password'  => $params['password'],
            'mobile'    => $params['userPhone'],
            'email'     => $params['userEmail'],
            'salt'      => $params['salt'],
            'update_time' => time(),
        ];
        return $saveData;
    }
}