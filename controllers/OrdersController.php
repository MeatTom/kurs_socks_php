<?php
namespace app\controllers;
use app\models\Cart;
use app\models\Tovar;
use app\models\Orders;
use Yii;
use yii\filters\auth\HttpBearerAuth;
class OrdersController extends FunctionController
{
public $modelClass = 'app\models\Orders';

public function behaviors()
{
    $behaviors = parent::behaviors();
    $behaviors['authenticator'] = [
        'class' => HttpBearerAuth::class,
        'only' => ['Order', 'View_all', 'View']
    ];

    return $behaviors;
}

public function actionOrder()
{
    $result = $this->checkAuthorization();

    if (isset($result['error'])) {
        return $this->send($result['error']['code'], ['error' => $result['error']]);
    }

    $user = $result;

    $requestData = Yii::$app->getRequest()->getBodyParams();

    if (!isset($requestData['address'])) {
        return $this->send(400, ['error' => ['code' => 400, 'message' => 'Bad Request: Missing "address" parameter']]);
    }

    $address = $requestData['address'];

    $cartItems = Cart::findAll(['userId' => $user->id]);

    if (empty($cartItems)) {
        return $this->send(400, ['error' => ['code' => 400, 'message' => 'Bad Request: Cart is empty']]);
    }

    $totalPrice = 0;

    foreach ($cartItems as $cartItem) {
        $order = new Orders();
        $order->customerId = $user->id;
        $order->idItem = $cartItem->itemId;
        $order->amount = $cartItem->amount;

        $tovar = Tovar::findOne($cartItem->itemId);

        if ($tovar === null) {
            return $this->send(404, ['error' => ['code' => 404, 'message' => 'Product not found']]);
        }

        $order->total_price = $cartItem->amount * $tovar->price;
        $order->address = $address;

        if (!$order->save()) {
            return $this->send(500, ['error' => ['code' => 500, 'message' => 'Failed to create order']]);
        }

        Tovar::updateAllCounters(['stock' => -$cartItem->amount], ['id' => $cartItem->itemId]);

        $totalPrice += $order->total_price;
    }

    Cart::deleteAll(['userId' => $user->id]);

    return $this->send(200, ['data' => ['message' => 'Order successfully placed']]);
}


public function actionView_all()
{
    $result = $this->checkAuthorization();

    if (isset($result['error'])) {
        return $this->send($result['error']['code'], ['error' => $result['error']]);
    }

    $user = $result;

    $orders = Orders::find()
        ->where(['customerId' => $user->id])
        ->orderBy(['order_time' => SORT_DESC])
        ->all();

    if (empty($orders)) {
        return $this->send(404, ['error' => ['code' => 404, 'message' => 'Orders not found']]);
    }

    $groupedOrders = [];
    $processedOrderTimes = [];

    foreach ($orders as $order) {
        if (in_array($order->order_time, $processedOrderTimes)) {
            continue;
        }

        $tovars = Orders::find()
            ->select(['idItem', 'amount'])
            ->where(['order_time' => $order->order_time])
            ->all();

        $totalPrice = 0;

        $groupedOrder = [
            'order_id' => $order->id,
            'status' => $order->status,
            'total_cost' => number_format($totalPrice, 2),
            'order_date' => Yii::$app->formatter->asDatetime($order->order_time),
            'address' => $order->address,
            'tovars' => [],
        ];

        foreach ($tovars as $tovarData) {
            $tovar = Tovar::findOne($tovarData['idItem']);

            if ($tovar !== null) {
                $totalPrice += $tovar->price * $tovarData['amount'];

                $groupedOrder['tovars'][] = [
                    'name' => $tovar->name,
                    'price' => number_format($tovar->price, 2),
                    'amount' => $tovarData['amount'],
                ];
            }
        }

        $groupedOrder['total_cost'] = number_format($totalPrice, 2);
        $groupedOrders[] = $groupedOrder;
        $processedOrderTimes[] = $order->order_time;
    }

    return $this->send(200, ['data' => $groupedOrders]);
}

    public function actionView($id)
{
    $result = $this->checkAuthorization();

    if (isset($result['error'])) {
        return $this->send($result['error']['code'], ['error' => $result['error']]);
    }

    $user = $result;

    $order = Orders::findOne(['id' => $id, 'customerId' => $user->id]);

    if ($order === null) {
        return $this->send(404, ['error' => ['code' => 404, 'message' => 'Order not found']]);
    }

    $groupedOrders = [];
    $processedOrderTimes = [];

    $ordersWithSameTime = Orders::find()
        ->where(['order_time' => $order->order_time])
        ->all();

    foreach ($ordersWithSameTime as $order) {
        if (in_array($order->order_time, $processedOrderTimes)) {
            continue;
        }

        $tovars = Orders::find()
            ->select(['idItem', 'amount'])
            ->where(['order_time' => $order->order_time])
            ->all();

        $totalPrice = 0;

        $groupedOrder = [
            'order_id' => $id,
            'status' => $order->status,
            'total_cost' => number_format($totalPrice, 2),
            'order_date' => Yii::$app->formatter->asDatetime($order->order_time),
            'address' => $order->address,
            'tovars' => [],
        ];

        foreach ($tovars as $tovarData) {
            $tovar = Tovar::findOne($tovarData['idItem']);

            if ($tovar !== null) {
                $totalPrice += $tovar->price * $tovarData['amount'];

                $groupedOrder['tovars'][] = [
                    'name' => $tovar->name,
                    'price' => number_format($tovar->price, 2),
                    'amount' => $tovarData['amount'],
                ];
            }
        }

        $groupedOrder['total_cost'] = number_format($totalPrice, 2);
        $groupedOrders[] = $groupedOrder;
        $processedOrderTimes[] = $order->order_time;
    }

    return $this->send(200, ['data' => $groupedOrders]);
}

}