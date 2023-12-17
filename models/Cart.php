<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Cart".
 *
 * @property int $id
 * @property int|null $itemId
 * @property int $amount
 * @property int $userId
 *
 * @property Tovar $item
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Cart';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['itemId', 'amount', 'userId'], 'integer'],
            [['amount', 'userId'], 'required'],
            [['itemId'], 'exist', 'skipOnError' => true, 'targetClass' => Tovar::className(), 'targetAttribute' => ['itemId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'itemId' => 'Item ID',
            'amount' => 'Amount',
            'userId' => 'User ID',
        ];
    }

    /**
     * Gets query for [[Item]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Tovar::className(), ['id' => 'itemId']);
    }
}
