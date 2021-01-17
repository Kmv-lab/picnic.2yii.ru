<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAssetMobile;
use app\widgets\Breadcrumbs;

AppAssetMobile::register($this);

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
    <?= $this->render('_HeaderMobile')?>

    <main class = "main page-inner">
        <div class="content">
            <?=$content?>
        </div>
    </main>
    <?= $this->render('_FooterMobile') ?>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>