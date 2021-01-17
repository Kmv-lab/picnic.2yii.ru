<?php


namespace app\widgets;


use app\modules\adm\models\Page;
use Yii;
use yii\base\Widget;

class InfoArticles extends Widget
{

    public function run()
    {

        // Получаем массив страниц соответствующий текущему url, отправляем URL и все-все страницы
        $urlArr = explode('/',Yii::$app->request->pathInfo);//массив родительских страниц

        array_pop($urlArr);

        $page = Page::find()->where(['page_alias' => $urlArr[0]])->one();

        $articles = Page::find()->where(['id_parent_page' => $page->id_page])->all();

        return $this->render('infoArticles', [
            'articles' => $articles,
            'page' => $page
        ]);
    }

}