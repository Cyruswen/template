<?php
/**
 * Created by PhpStorm.
 * User: 13213
 * Date: 2019/3/28
 * Time: 14:39
 */

class Flogger
{
    public static $uid = 0;
    public static $from = '';

    public static function info($msg)
    {

        $logInfo = [
            'uid' => self::$uid,
            'msg' => $msg,
            'from' => self::$from,
        ];
        Yii::log($logInfo, 'info');
    }

    public static function warning($msg)
    {
        $logInfo = [
            'uid' => self::$uid,
            'msg' => $msg,
            'from' => self::$from,
        ];
        Yii::log($logInfo, 'warning');
    }

    public static function error($msg)
    {
        $logInfo = [
            'uid' => self::$uid,
            'msg' => $msg,
            'from' => self::$from,
        ];
        Yii::log($logInfo, 'error');
    }
}