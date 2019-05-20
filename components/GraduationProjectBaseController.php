<?php
/**
 * Created by PhpStorm.
 * User: 13213
 * Date: 2019/3/28
 * Time: 14:16
 */

class GraduationProjectBaseController extends \yii\web\Controller
{
    protected $params = [];
    protected $response = '';
    protected $input = [];
    protected $uid = 0;

    public function beforeAction($action)
    {
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $this->input = json_decode(file_get_contents("php://input"), true);
        }else{
            $this->input = $_GET;
        }
        Flogger::info('收到参数' . json_encode($this->input, JSON_UNESCAPED_UNICODE));
        $this->params = $this->input;
        return true;
    }

    public function afterAction($action, $result)
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $responseData = [
            'code'   => 200,
            'status' => 'success',
            'uid'    => $this->uid,
            'bizData'   => $this->response,
        ];
        Flogger::info('响应参数' . json_encode($responseData, JSON_UNESCAPED_UNICODE));
        return $responseData;
    }
}