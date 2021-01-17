<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\assets\AppAssetMobile;
use app\widgets\Breadcrumbs;
use app\widgets\Block;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?= $this->render('_Head') ?>
    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />
    <?php $this->registerCsrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
    <?= $this->render('_Header')?>
    <main class = "main page-inner">
        <div class="content">
            <div class="container">
                <div class="page-top">
                    <div class="breadcrumbs">
                        <nav>
                            <?=Breadcrumbs::widget([
                                'links' => isset(Yii::$app->params['breadcrumbs']) ? Yii::$app->params['breadcrumbs'] : [],
                                'tag'=>'ul vocab="https://schema.org/" typeof="BreadcrumbList"',
                                'itemTemplate'=>'<li property="itemListElement" typeof="ListItem">{link}</li>',
                                'activeItemTemplate'=>'<li property="itemListElement" typeof="ListItem"><span property="name">{link}</span></li>'
                            ]);?>
                        </nav>
                    </div>
                    <h1><?=Yii::$app->params['seo_h1']?></h1>
                </div>
                <?=$content?>
            </div>
        </div>
    </main>
    <?= $this->render('_Footer') ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
