<?php


namespace app\modules\adm\models;


use yii\base\Model;

class ModelIndexHead extends Model
{

    public $name;
    public $value;
    public $description;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'value', 'description'], 'filter', 'filter' => 'trim'],
            [['name', 'value', 'description'], 'required'],
            [['name', 'value', 'description'], 'string'],
            [['value', 'description'], 'string', 'max' => 255],
            ['name', 'string', 'max' => 100]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'value' => 'Значение',
            'description' => 'Описание'
        ];
    }

}