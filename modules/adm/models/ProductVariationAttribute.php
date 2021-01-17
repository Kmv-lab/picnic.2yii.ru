<?php


namespace app\modules\adm\models;


use yii\db\ActiveRecord;

class ProductVariationAttribute extends ActiveRecord
{

    public function attributeLabels(){
        return [
            'value' => 'Значение атрибута',
            'prod_var_id' => 'Вариация товара',
            'cat_attr_id' => 'Атрибут'
        ];
    }

    public static function tableName()
    {
        return 'product_variation_attribute';
    }

    public function rules(){
        return [
            [['prod_var_id', 'cat_attr_id'], 'required'],
            ['value', 'string'],
            ['value', 'trim'],
            /*['value', function(){
                if(gettype($this->value) === 'string' && )
            }]*/
        ];
    }

}