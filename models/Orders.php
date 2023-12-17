<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Orders".
 *
 * @property int $id
 * @property int|null $customerId
 * @property float|null $total_price
 * @property string $order_time
 * @property string|null $address
 * @property int $idItem
 * @property int $amount
 * @property string $status
 *
 * @property Users $customer
 * @property Tovar $idItem0
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customerId', 'idItem', 'amount'], 'integer'],
            [['total_price'], 'number'],
            [['order_time'], 'safe'],
            [['address'], 'string'],
            [['idItem', 'amount'], 'required'],
            [['status'], 'string', 'max' => 100],
            [['customerId'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['customerId' => 'id']],
            [['idItem'], 'exist', 'skipOnError' => true, 'targetClass' => Tovar::className(), 'targetAttribute' => ['idItem' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customerId' => 'Customer ID',
            'total_price' => 'Total Price',
            'order_time' => 'Order Time',
            'address' => 'Address',
            'idItem' => 'Id Item',
            'amount' => 'Amount',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Users::className(), ['id' => 'customerId']);
    }

    /**
     * Gets query for [[IdItem0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdItem0()
    {
        return $this->hasOne(Tovar::className(), ['id' => 'idItem']);
    }
}
