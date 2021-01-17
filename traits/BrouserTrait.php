<?php


namespace app\traits;


use Yii;

trait BrouserTrait
{

    public function getVersion(){

        $mobileDetect = new Mobile_Detect();

        Yii::$app->params['is_mobile'] = $mobileDetect->isMobile();
        if(isset($_GET['browser']) && $_GET['browser'] == 'mobile'){
            Yii::$app->params['is_mobile'] = true;
        }

        $this->layout = 'main';
        if(Yii::$app->params['is_mobile'])
            $this->layout = 'mainMobile';
    }

}