<?php
/**
 * Created by PhpStorm.
 * User: 13213
 * Date: 2019/4/4
 * Time: 11:40
 */
namespace app\domain;
use Flogger;

class UserService
{
    public function checkParams($checkFiled, $params)
    {
        foreach ($checkFiled as $value) {
            if (!isset($params[$value])) {
                Flogger::warning("缺少参数: " . $value);
                return false;
            }
        }
        return true;
    }
}