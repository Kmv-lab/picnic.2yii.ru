<?php


namespace app\modules\adm\models;


use yii\db\ActiveRecord;

class VariationsOfProduct extends ActiveRecord
{

    public function attributeLabels(){
        return [
            'prod_id' => 'Товар',
            'name' => 'Название',
            'price' => 'Цена',
            'is_active' => 'ВКЛ\\ВЫКЛ'
        ];
    }

    public static function tableName()
    {
        return 'variation_of_product';
    }

    public function rules(){
        return [
            [['name', 'prod_id', 'price'], 'required'],
            ['is_active', 'integer'],
            ['name', function(){
                if($this->isNewRecord){
                    $anotherVarOfProd = self::find()->where(['prod_id' => $this->prod_id, 'name' => $this->name])->all();
                }else{
                    $anotherVarOfProd = self::find()->where(['prod_id' => $this->prod_id, 'name' => $this->name])->andWhere(['!=', 'id', $this->id])->all();
                }
                if(empty($anotherVarOfProd)) return true;

                $this->addError('name', 'Название вариации не должно повторяться');
                return false;
            }],
        ];
    }

}