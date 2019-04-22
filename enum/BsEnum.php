<?php
/**
 * Created by PhpStorm.
 * User: 13213
 * Date: 2019/4/4
 * Time: 17:55
 */

class BsEnum
{
    const EMAIL_LESS_LEN = 7;

    const PARAMS_ERROR_CODE = 10000; //字段缺失
    const UN_VALID_MOBILE   = 10001; //手机号不合法
    const UN_VALID_USERNAME = 10002; //用户手机号不合法
    const UN_VALID_PASSWORD = 10003; //密码不合法
    const UN_VALID_PASSLEN  = 10004; //密码长度不合法
    const UN_VALID_EMAIL    = 10005; //邮箱不合法
    const SQL_INSERT_FAIL   = 10006; //数据库操作失败
    const HAS_REGISTER      = 10007; //用户已经注册
    const USERNAME_HAS_USED = 10008; //用户名已被占用
    const MOBILE_HAS_USED   = 10009; //手机号已被占用
    const NO_SUCH_USER      = 10010; //用户不存在, 请注册后登录
    const UN_CORRECT_PASS   = 10011; //密码错误
    const SAME_PASSWORD     = 10012; //密码相同
    const SAME_MOBILE       = 10013; //新旧手机号相同
    const SAME_EMAIL        = 10014; //新旧邮箱相同
    const NO_SUCH_DEVICE    = 10015; //设备号不存在
    const UN_CORRECT_VERIFY = 10016; //验证码不对
    const HAS_SUCH_DATA     = 10017; //数据已存在
    const NOT_USE_DEVICE    = 10018; //设备未启用

    const MOBILE   = 'mobile';
    const USERNAME = 'userName';

    public static $codeMap = [
        self::PARAMS_ERROR_CODE => '缺少必传字段',
        self::UN_VALID_MOBILE   => '手机号不合法',
        self::UN_VALID_USERNAME => '用户名不合法',
        self::UN_VALID_PASSWORD => '密码必须包含大小写字母和数字',
        self::UN_VALID_PASSLEN  => '密码长度不合法',
        self::UN_VALID_EMAIL    => '邮箱不合法',
        self::SQL_INSERT_FAIL   => '操作失败',
        self::HAS_REGISTER      => '用户已注册, 请前往登录',
        self::USERNAME_HAS_USED => '该用户名已被占用',
        self::MOBILE_HAS_USED   => '该手机号已被占用',
        self::NO_SUCH_USER      => '用户不存在, 请注册后登录',
        self::UN_CORRECT_PASS   => '密码错误',
        self::SAME_PASSWORD     => '新密码必须与旧密码不同',
        self::SAME_EMAIL        => '新旧邮箱不能相同',
        self::SAME_MOBILE       => '新旧手机号不能相同',
        self::NO_SUCH_DEVICE    => '设备号不存在, 请重新输入',
        self::UN_CORRECT_VERIFY => '设备序列号不正确',
        self::HAS_SUCH_DATA     => '该设备已添加',
        self::NOT_USE_DEVICE    => '该设备未启用',
    ];
}