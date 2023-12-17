<?php
namespace app\controllers;
use yii\web\UploadedFile;
use app\models\Tovar;
use Yii;
use yii\filters\auth\HttpBearerAuth;

class TovarController extends FunctionController
{
public $modelClass = 'app\models\Tovar';

public function behaviors()
{
    $behaviors = parent::behaviors();
    $behaviors['authenticator'] = [
        'class' => HttpBearerAuth::class,
        'only'=>['Create,Delete,Update'] 
    ];

    return $behaviors;
}

public function actionCreate(){
    $product=new Tovar(Yii::$app->request->post());
    $product->image=UploadedFile::getInstanceByName('image');
    $token = str_replace('Bearer ', '', Yii::$app->request->headers->get('Authorization'));
    if (!$this->is_admin($token)) return $this->send(401, ['error'=>['code'=>401, 'message'=>'Unauthorized']]);
    if (!$product->validate()) return $this->validation($product);
    
    $image_name='/product_photo/image_product_' . Yii::$app->getSecurity()->generateRandomString(40) .
    '.' . $product->image->extension;
    $product->image->saveAs(Yii::$app->basePath.$image_name);
    $product->image=$image_name;
    $product->save(false);
    return $this->send(200, ['data'=>['status'=>'New item added successfully']]);

}

public function actionDelete($id)
{
    $token = str_replace('Bearer ', '', Yii::$app->request->headers->get('Authorization'));

    if (!$this->is_admin($token)) {
        return $this->send(401, ['error'=>['code'=>401, 'message'=>'Unauthorized']]);
    }

    $product = Tovar::findOne($id);

    if (!$product) {
        return $this->send(404, ['error' => ['code' => 404, 'message' => 'Item not found']]);
    }

    if ($product->delete()) {
        return $this->send(200, ['data' => ['status' => 'Item deleted successfully']]);
    } else {
        return $this->send(500, ['error' => ['code' => 500, 'message' => 'Item cannot be deleted']]);
    }
}


public function actionUpdate($id)
{
    $token = str_replace('Bearer ', '', Yii::$app->request->headers->get('Authorization'));

    if (!$this->is_admin($token)) {
        return $this->send(401, ['error'=>['code'=>401, 'message'=>'Unauthorized']]);
    }

    $product = Tovar::findOne($id);

    if (!$product) {
        return $this->send(404, ['error' => ['code' => 404, 'message' => 'Item not found']]);
    }

    $requestData = Yii::$app->getRequest()->getBodyParams();

    $product->setScenario('Update');

    if ($product->load($requestData, '') && $product->save()) {
        return $this->send(200, ['data' => ['status' => 'Item changed successfully']]);
    } else {
        return $this->send(500, ['error' => ['code' => 500, 'message' => 'Item cannot be changed']]);
    }
}

public function actionView($id)
{
    $product = Tovar::findOne($id);

    if (!$product) {
        return $this->send(404, ['error' => ['code' => 404, 'message' => 'Product not found']]);
    }

    $data = $product->getAttributes();

    return $this->send(200, ['data' => $data]);
}

public function actionView_all()
{
    $products = Tovar::find()->all();

    $data = [];
    foreach ($products as $product) {
        $data[] = $product->getAttributes();
    }

    return $this->send(200, ['data' => $data]);
}
}