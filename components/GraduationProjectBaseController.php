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
        Flogger::info('收到参数' . json_encode($this->input));
        $this->params = $this->input;
        return true;
    }

    public function afterAction($action, $result)
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $responseData = [
            'code'   => 200,
            'status' => 'success',
        ];
        return $responseData;
    }
}