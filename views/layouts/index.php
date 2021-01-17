<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;

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
<?=$content?>
<?= $this->render('_Footer') ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
