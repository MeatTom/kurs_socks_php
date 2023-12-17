<?php
namespace app\controllers;
use yii\filters\auth\HttpBearerAuth;
use Yii;
use app\models\LoginForm;
use app\models\Users;


class UsersController extends FunctionController
{
public $modelClass = 'app\models\Users';

public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'only'=>['User,Email'] 
        ];
        return $behaviors;
    }

public function actionCreate(){
    $request=Yii::$app->request->post(); 
    $user=new Users($request); 
    if (!$user->validate()) return $this->validation($user); 
    $user->password=Yii::$app->getSecurity()->generatePasswordHash($user->password); 
    $user->save();
    return $this->send(201, ['Successful'=>['Status'=>204]]);
}

public function actionLogin(){
    $request=Yii::$app->request->post();
    $loginForm=new LoginForm($request);
    if (!$loginForm->validate()) return $this->validation($loginForm);
    $user=Users::find()->where(['email'=>$request['email']])->one();
    if (isset($user) && Yii::$app->getSecurity()->validatePassword($request['password'], $user->password)){
        $user->access_token=Yii::$app->getSecurity()->generateRandomString();
        $user->save(false);
        return $this->send(200, ['data'=>['token'=>$user->access_token]]);
    }
    return $this->send(401, ['error'=>['code'=>401, 'message'=>'Unauthorized', 'errors' => ['error'=>'phone or password incorrect']]]);
}

public function actionUser()
{
    $result = $this->checkAuthorization();

    if (isset($result['error'])) {
        return $this->send($result['error']['code'], ['error' => $result['error']]);
    }

    $user = $result; 

    $data = [
        'id' => $user->id,
        'fio' => $user->fio,
        'phone' => $user->phone,
        'email' => $user->email,
    ];

    return $this->send(200, ['data' => ['user' => $data]]);
}

public function actionEmail()
{
    $result = $this->checkAuthorization();

    if (isset($result['error'])) {
        return $this->send($result['error']['code'], ['error' => $result['error']]);
    }

    $user = $result;

    $requestData = Yii::$app->getRequest()->getBodyParams();

    if (!isset($requestData['email'])) {
        return $this->send(422, ['error' => ['code' => 422, 'message' => 'Validation error', 'error' => 'The email should not be empty.']]);
    }

    $user->email = $requestData['email'];

    if ($user->save()) {
        return $this->send(200, ['data' => ['status' => 'Email was changed successfully']]);
    } else {
        return $this->send(422, ['error' => ['code' => 422, 'message' => 'Validation error']]);
    }
}

}




