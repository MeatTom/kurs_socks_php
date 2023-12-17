<?php

namespace app\controllers;
use app\models\Cart;
use app\models\Tovar;
use app\models\Users;
use Yii;
use yii\filters\auth\HttpBearerAuth;

class CartController extends FunctionController
{
    public $modelClass = 'app\models\Cart';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'only' => ['Add', 'View', 'Delete', 'Amount'] 
        ];

        return $behaviors;
    }

    private function validateCartAmount($amount, $tovar)
{
    if ($tovar === null) {
        return ['status' => 404, 'error' => ['code' => 404, 'message' => 'Product not found']];
    }

    if ($amount > $tovar->stock) {
        return ['status' => 404, 'error' => ['code' => 404, 'message' => 'Out of stock']];
    }

    if ($amount <= 0) {
        return ['status' => 401, 'error' => ['code' => 401, 'message' => 'Amount not enough']];
    }

    return ['status' => 200];
}

public function actionAdd()
{
    $result = $this->checkAuthorization();

    if (isset($result['error'])) {
        return $this->send($result['error']['code'], ['error' => $result['error']]);
    }

    $user = $result;
    $requestData = Yii::$app->getRequest()->getBodyParams();

    if (!isset($requestData['itemId']) || !isset($requestData['amount'])) {
        return $this->send(400, ['error' => ['code' => 400, 'message' => 'Bad Request: Missing "itemId" or "amount" parameter']]);
    }

    $itemId = $requestData['itemId'];
    $amount = $requestData['amount'];

    $existingCartItem = Cart::findOne(['userId' => $user->id, 'itemId' => $itemId]);

    if ($existingCartItem !== null) {
        return $this->send(400, ['error' => ['code' => 400, 'message' => 'Item already exists in the cart']]);
    }

    $result = $this->validateCartAmount($amount, Tovar::findOne($itemId));

    if ($result['status'] !== 200) {
        return $this->send($result['status'], ['error' => $result['error']]);
    }

    $cartItem = new Cart();
    $cartItem->userId = $user->id;
    $cartItem->itemId = $itemId;
    $cartItem->amount = $amount;

    if ($cartItem->save()) {
        return $this->send(200, ['data' => ['id' => $cartItem->id]]);
    } else {
        return $this->send(500, ['error' => ['code' => 500, 'message' => 'Failed to add to cart']]);
    }
}


    public function actionView()
    {
        $result = $this->checkAuthorization();

    if (isset($result['error'])) {
        return $this->send($result['error']['code'], ['error' => $result['error']]);
    }

    $user = $result;

        $cartItems = Cart::findAll(['userId' => $user->id]);

        $cartData = [];

        foreach ($cartItems as $cartItem) {
            $tovar = Tovar::findOne($cartItem->itemId);
            $cartData[] = [
                'id' => $cartItem->id,
                'itemId' => $cartItem->itemId,
                'itemName' => $tovar->name,
                'amount' => $cartItem->amount,
            ];
        }

        return $this->send(200, ['data' => $cartData]);
    }

    public function actionDelete($id)
    {
        $result = $this->checkAuthorization();

    if (isset($result['error'])) {
        return $this->send($result['error']['code'], ['error' => $result['error']]);
    }

    $user = $result;

        $cartItem = Cart::findOne(['id' => $id, 'userId' => $user->id]);

        if ($cartItem === null) {
            return $this->send(404, ['error' => ['code' => 404, 'message' => 'Product not found']]);
        }

        if ($cartItem->delete()) {
            return $this->send(200, ['code' => 200, 'message' => 'Successful']);
        } else {
            return $this->send(500, ['error' => ['code' => 500, 'message' => 'Failed to delete cart item']]);
        }
    }

    public function actionAmount($id)
    {
        $result = $this->checkAuthorization();

    if (isset($result['error'])) {
        return $this->send($result['error']['code'], ['error' => $result['error']]);
    }

    $user = $result;
        $cartItem = Cart::findOne(['id' => $id, 'userId' => $user->id]);
    
        if ($cartItem === null) {
            return $this->send(404, ['error' => ['code' => 404, 'message' => 'Product not found']]);
        }
    
        $requestData = Yii::$app->getRequest()->getBodyParams();
        if (!isset($requestData['amount'])) {
            return $this->send(400, ['error' => ['code' => 400, 'message' => 'Bad Request: Missing "amount" parameter']]);
        }
    
        $amount = $requestData['amount'];
        $tovar = Tovar::findOne($cartItem->itemId);
    
        $result = $this->validateCartAmount($amount, $tovar);
    
        if ($result['status'] !== 200) {
            return $this->send($result['status'], ['error' => $result['error']]);
        }
    
        $cartItem->amount = $amount;
    
        if ($cartItem->save()) {
            return $this->send(200, ['code' => 200, 'message' => 'Amount changed successfully']);
        } else {
            return $this->send(500, ['error' => ['code' => 500, 'message' => 'Failed to update cart item']]);
        }
    }
}
