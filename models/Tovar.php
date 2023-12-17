<?php

namespace app\models;
use yii\web\UploadedFile;

use Yii;

/**
 * This is the model class for table "Tovar".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property float $price
 * @property int $stock
 * @property string|null $image
 *
 * @property Cart[] $carts
 * @property OrderItems[] $orderItems
 */
class Tovar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Tovar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'price', 'stock'], 'required'],
            [['description'], 'string'],
            [['price'], 'number'],
            [['stock'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['image'], 'file', 'extensions' => ['jpg', 'bmp'], 'skipOnEmpty' => false],
            [['image'], 'image', 'maxSize' => 2 * 1024 * 1024, 'skipOnEmpty' => false],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'price' => 'Price',
            'stock' => 'Stock',
            'image' => 'Image',
        ];
    }

    /**
     * Gets query for [[Carts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarts()
    {
        return $this->hasMany(Cart::className(), ['itemId' => 'id']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItems::className(), ['tovarId' => 'id']);
    }

    public function beforeValidate(){
        $this->image=UploadedFile::getInstanceByName('image');
        return parent::beforeValidate();
    }

    public function scenarios()
{
    $scenarios = parent::scenarios();
    $scenarios['Update'] = ['name', 'description', 'price', 'stock'];
    return $scenarios;
}

}
