<?php


namespace app\widgets;


use app\modules\adm\models\ProductPhotos;
use yii\base\Widget;

class PhotosView extends Widget
{

    public $photosModeles = false;
    public $photosNames = false;
    public $photoSeoAlt = false;
    public $photoSeoTitle = false;

    public function run(){
        if($this->photosModeles === false && $this->photosNames === false){
            return false;
        }

        if($this->photosModeles === false && $this->photosNames){
            $this->photosModeles = ProductPhotos::find()->where(['name' => $this->photosNames])->all();
        }

        return $this->render('photosView', [
            'photos' => $this->photosModeles,
            'alt' => $this->photoSeoAlt,
            'title' => $this->photoSeoTitle
        ]);
    }

}