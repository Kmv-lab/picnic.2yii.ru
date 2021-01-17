<?php


namespace app\modules\adm\models;


use app\traits\ImageTrait;
use Yii;
use yii\db\ActiveRecord;

class ProductPhotos extends ActiveRecord
{

    use ImageTrait;

    public function attributeLabels(){
        return [
            'name' => 'Фотография'
        ];
    }

    public function rules()
    {
        return [
            [['name'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 18],
            ['id_product', 'integer']
        ];
    }

    public static function tableName()
    {
        return 'product_photos';
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->name as $file) {
                $fileName = strtolower(md5(uniqid($file->baseName))). '.' . $file->extension;
                $file->saveAs($this->DIR().'original/' . $fileName);
                $filesName[] = $fileName;
            }
            return $filesName;
        } else {
            return false;
        }
    }

    public function deletePhoto(){
        $fileName = $this->name;

        if (is_file($this->DIR().'original/'.$fileName)) {
            unlink($this->DIR().'original/'.$fileName);
        }

        if (is_file($this->DIR().Yii::$app->params['resolution_excursion_photo'].'/'.$fileName)) {
            unlink($this->DIR().Yii::$app->params['resolution_excursion_photo'].'/'.$fileName);
        }
    }

}