<?php

namespace app\modules\adm\controllers;

use Yii;
use yii\web\Controller;
use app\commands\helpers;

/**
 * Default controller for the `adm` module
 */
class DefaultController extends Controller
{

    public $title = 'Админ-панель';

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        Yii::$app->params['H1'] = $this->title;

        helpers::createSeo('', 'Админка', '');
        $dataForTest = "Привет, как интересно...";
        return $this->render('index', [
            'dataForTest' => $dataForTest,
        ]);
    }

    public function actionAjaxtranslite($str)
    {
        Yii::$app->params['H1'] = $this->title;

        $str = helpers::translit($str);
        return $str;
    }
}
