<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\modules\adm\assets\AdminAsset;

AdminAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode(isset($this->context->title) ? $this->context->title : $this->title) ?></title>
    <?php
    //vd($this->context->title);
    ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?php
    //echo (isset($dataForTest) ? $dataForTest : "не передано ничерта");
?>
<div class="wrap">
    <?php
    NavBar::begin([
        //'brandLabel' => Yii::$app->name,
        'brandLabel' => 'На Сайт',
        'brandUrl' => Yii::$app->homeUrl,
        'brandOptions' => ['target'=>'_blank'],
        'options' => [
            'class' => 'navbar-inverse',
        ],
    ]);
    if(!Yii::$app->user->isGuest){
        $arr = [['label' => 'Страницы', 'url' => ['pages/index']],
                ['label' => 'Категории', 'url' =>  ['category/index']],
                ['label' => 'Главная страница', 'url' =>  ['indexpage/index']],
                ['label' => 'Настройки главной', 'url' => ['indexpage/settings']],
                ['label' => 'Товары', 'url' =>  ['products/index']],
                ['label' => 'Статьи', 'url' =>  ['article/index']],
                ['label' => 'Настройки',
                    'items' => [
                        ['label' => 'Шаблончики', 'url' =>  ['templates/index']],
                        ['label' => 'Сниппеты', 'url' =>  ['snippets/index']],
                        ['label' => 'Блоки', 'url' =>  ['blocks/index']],
                        ['label' => 'Разрешения', 'url' => ['resolution/index']],
                        ['label' => 'Настройки', 'url' => ['settings/index']],
                        ['label' => 'Пользователи', 'url' => ['users/index']],
                    ],],
                ['label' => 'Выйти (' . Yii::$app->user->identity->username . ')', 'url' => ['users/logout']],
               ];
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $arr,
            /*  Yii::$app->user->isGuest ? (
                  ['label' => 'Login', 'url' => ['/site/login']]
              ) : (
                  '<li>'
                  . Html::beginForm(['/site/logout'], 'post')
                  . Html::submitButton(
                      'Logout (' . Yii::$app->user->identity->username . ')',
                      ['class' => 'btn btn-link logout']
                  )
                  . Html::endForm()
                  . '</li>'
              ) */
        ]);
    }
    NavBar::end();
    ?>

    <div class="container">
        <div class="row">
            <?php //Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>
            <?php //Alert::widget() ?>
        </div>
    </div>
    <?php
    if (!empty(Yii::$app->params['dopMenu']))
    {
        $page_link = !empty($this->context->page_front_url) ? '<li style="float: right;"><a href="'.$this->context->page_front_url.'" target="_blank"><span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a></li>' : '';
        echo '
            <div class="container">
                <div class="row">
                    <div class="well well-sm">
                        <ul class="nav nav-pills">';
        foreach(Yii::$app->params['dopMenu'] as $item)
            echo '
                            <li><a href="'.$item['url'].'">'.$item['name'].'</a></li>';

            echo '          '.$page_link.'
                        </ul>
                    </div>
                </div>
            </div>';
    }
    ?>
    <div class="container">
        <div class="row">
            <blockquote><h3><?=isset(Yii::$app->params['H1']) ? Yii::$app->params['H1'] : ''?></h3>
            </blockquote>
        </div>
    </div>
    <div class="container">
        <?= $content ?>
    </div>
</div>
    <div class="footer">
        <div class="progress">
            <div class="progress-bar" style="width: 100%"></div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                </div>
            </div>
        </div>
    </div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
