<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\UserModel;
use yii\console\Controller;
use yii\console\ExitCode;
use Util;
use yii\db\Exception;
use Flogger;

class HelloController extends Controller
{

    /**
     * @param int $num
     * @return int
     * @desc 生成设备号以及序列号的脚本
     */
    public function actionGenerateDevice($num = 10)
    {
        $arrData = [];
        for($i = 0; $i < $num; $i++) {
            $arrData[$i][] = Util::generateDid();
            $arrData[$i][] = Util::generateVerifyCode();
            $arrData[$i][] = time();
            usleep(100);
        }
        $tableName = 'device_info';
        $items = ['did', 'verify_code', 'update_time'];
        try {
            (new UserModel())->saveBatchData($tableName, $items, $arrData);
        } catch (Exception $e) {
            Flogger::info("添加新设备id/序列号失败! code: " . $e->getCode() . "message: " . $e->getMessage());
            return -1;
        }
        return ExitCode::OK;
    }

}
