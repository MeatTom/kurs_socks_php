<?php
namespace app\controllers;
use yii\rest\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;
use Yii;
use app\models\Users;


/*Опишите здесь действия, которые приходится
часто рсуществлять*/

class FunctionController extends Controller{


 /* Подготовка и отправка ответа*/
    public function send($code, $data){
        $response=$this->response;
        $response->format = Response::FORMAT_JSON;        
        $response->data=$data;
        $response->statusCode=$code;
        return $response;
    }

    /* Формирование и отправка ошибок валидации*/
    public function validation($model){
        $error=['error'=> ['code'=>422, 'message'=>'Validation error',
                'errors'=>ActiveForm::validate($model)]];
        return $this->send(422, $error);
 }

 
/*Проверка является ли пользователь админом*/
    public function is_admin($token){
        
        {
            $user = Users::findOne(['access_token' => $token]);
            return $user !== null && $user->isAdmin == 1;
        }
    }

    public function checkAuthorization()
{
    $token = str_replace('Bearer ', '', Yii::$app->request->headers->get('Authorization'));

    if ($token === null) {
        return ['error' => ['code' => 401, 'message' => 'Unauthorized']];
    }

    $user = Users::findOne(['access_token' => $token]);

    if ($user === null) {
        return ['error' => ['code' => 401, 'message' => 'Unauthorized']];
    }

    return $user;
}


    
    
    



    

}