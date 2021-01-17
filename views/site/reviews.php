<?php

use app\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;

?>
    <main class="main"><div class="page clearfix page_grey">
        <nav class="breadcrumbs">
            <div class="container">
                <?=Breadcrumbs::widget([
                    'links' => isset(Yii::$app->params['breadcrumbs']) ? Yii::$app->params['breadcrumbs'] : [],
                    'tag'=>'ul vocab="https://schema.org/" typeof="BreadcrumbList"',
                    'itemTemplate'=>'<li property="itemListElement" typeof="ListItem">{link}</li>',
                    'activeItemTemplate'=>'<li property="itemListElement" typeof="ListItem"><span property="name">{link}</span></li>'
                ]);?>
            </div>
        </nav>
        <section class="reviews-page">
            <div class="container">
                <div class="reviews-page__header">
                    <h1 class="page-title"><?=Yii::$app->params['seo_h1']?></h1>
                    <?=Html::a('ОСТАВИТЬ ОТЗЫВ', '/otzyivyi/novyiy-otzyiv', ['class' => 'btn btn_orange-brd reviews-page__header-btn'])?>
                </div>
                <div class="reviews-page__main">
                    <?php
                    $i = 1;
                    foreach ($reviews as $review){

                        echo $this->render('excursionsHelpers/reviewsElem', ['excName' => $excNames[$review->id_exc]['name'], 'review' => $review, 'id' =>$i++]);

                    }?>

                </div>
                <?php if($showMoreReviews){?>
                    <div class="reviews-page__footer">
                        <button class="btn btn_orange reviews-page__footer-btn">ПОКАЗАТь ВСЕ ОТЗЫВЫ</button>
                    </div>
                <?}?>
            </div>
        </section>
    </div>
    <div class="video-wrapper" id="js-video-vrapper" style="display: none;">
        <div class="video-wrapper__overlay js-video-close"></div>
        <div class="video-wrapper__container">
            <button class="btn video-wrapper__close js-video-close" title="Закрыть">Закрыть</button>
            <div class="video-wrapper__blc" id="js-video-container">
                <iframe frameborder="0" allowfullscreen=""></iframe>
            </div>
        </div>
    </div>
</main>