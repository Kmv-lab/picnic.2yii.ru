<?php


namespace app\widgets;


use app\traits\SeoHalperTrait;
use yii\base\Widget;

class Categories extends Widget
{
    use SeoHalperTrait;

    public $categoryArray = false;
    public $dontShow = false;

    public function run()
    {
        if(!$this->categoryArray){
            $this->categoryArray = $this->getMainElemSite();
        }

        $catIds=[];
        foreach ($this->categoryArray as $category){
            $catIds[]=$category['id'];
        }

        if($this->dontShow){
            $this->categoryArray = \app\modules\adm\models\Categories::find()->where(['id' => $catIds])->andWhere(['!=', 'id', $this->dontShow])->all();
        }else{
            $this->categoryArray = \app\modules\adm\models\Categories::find()->where(['id' => $catIds])->all();
        }

        return $this->render('category', [
            'categories' => $this->categoryArray
        ]);
    }

}