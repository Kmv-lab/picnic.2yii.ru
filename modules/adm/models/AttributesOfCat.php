<?php


namespace app\modules\adm\models;


use yii\db\ActiveRecord;

class AttributesOfCat extends ActiveRecord
{

    public function attributeLabels(){
        return [
            'name' => 'Название',
            'unit' => 'Единицы измерения',
            'for_filter' => 'Использовать для фильтрации'
        ];
    }

    public static function tableName()
    {
        return 'categories_attributes';
    }

    public function rules(){
        return [
            [['name', 'category_id'], 'required'],
            ['unit', 'string'],
            ['for_filter', 'boolean'],
        ];
    }

}