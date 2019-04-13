<?php
/**
 * Created by PhpStorm.
 * User: 13213
 * Date: 2019/4/13
 * Time: 10:44
 */

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\db\Exception;

class UserModel extends ActiveRecord
{
    /**
     * @param $tableName
     * @param $data
     * @return bool
     * @throws Exception
     */
    public function saveBaseInfo($tableName, $data)
    {
        if (empty($params)) {
            return false;
        }
        $items = $data[0];
        $insertData = $data[1];
        Yii::$app->db->createCommand()->batchInsert($tableName, $items, $insertData)->execute();
        return true;
    }
}