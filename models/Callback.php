<?php


namespace app\models;


use yii\base\Model;

class Callback extends Model
{

    public $name;
    public $phone;
    public $prod_id;
    public $var_id;
    public $question;

    public function rules()
    {
        return [
            [['name', 'phone'], 'required'],
            [['prod_id', 'var_id'], 'integer'],
            [['name', 'question'], 'string'],
            ['question', 'string', 'max' => 300]
        ];
    }

    public function save(){
        //TODO создать модели, сдалать запись через эти модели в бд.
    }

}