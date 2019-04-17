<?php
/**
 * Created by PhpStorm.
 * User: 13213
 * Date: 2019/4/13
 * Time: 10:44
 */

namespace app\models;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\Query;

class UserModel extends ActiveRecord
{
    /**
     * @param $tableName
     * @param $data
     * @return bool
     * @throws Exception
     * @desc $data总共有两个元素, 第一个元素表示要插入数据库的字段 第二个元素表示插入的数据
     */
    public function saveBaseInfo($tableName, $data)
    {
        if (empty($data) || empty($tableName)) {
            throw new Exception('参数为空');
        }
        /*
        //暂时弃用, 批量添加
        $items = [];
        $insertData = [];
        foreach ($data as $key => $value) {
            $items[] = $key;
            $ideepnsertData[] = $value;
        }
        Yii::$app->db->createCommand()->batchInsert($tableName, $items, $insertData)->execute();
        */
        Yii::$app->db->createCommand()->insert($tableName, $data)->execute();
        return true;
    }

    /**
     * @param $data
     * @param $tableName
     * @param $uid
     * @return array
     */
    public function getInfoByUid($data, $tableName, $uid)
    {
        $userInfo = (new Query())->select($data)->from($tableName)->where('uid=:uid', [':uid'=>$uid])->all();
        return $userInfo;
    }
}