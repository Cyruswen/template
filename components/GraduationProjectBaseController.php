<?php
/**
 * Created by PhpStorm.
 * User: 13213
 * Date: 2019/3/28
 * Time: 14:16
 */

class GraduationProjectBaseController extends \yii\web\Controller
{
    public $params = [];
    public $response = '';
    public $input = [];

    public function beforeAction($action)
    {
        $this->input = json_decode(file_get_contents("php://input"), true);

    }
}