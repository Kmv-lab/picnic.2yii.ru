<?php



/* @var $this \yii\web\View */
/* @var $model \app\modules\adm\models\ExcursionComments */
/* @var $excursions array */


use app\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$model->type = 0;

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



        <section class="reviews-page" style="text-align: center">
            <div class="container">
                <h1 class="page-title"><?=Yii::$app->params['seo_h1']?></h1>
                <?=$isSave ? '<div class="alert-success"><span>Ваш отзыв принят! Спасибо.</span></div>' : ''?>
                <?
                $form = ActiveForm::begin([
                    'enableClientValidation'=>true,
                    'options' => [
                        'class' => 'form-horizontal',
                    ],
                ]);
                ?>

                    <?=$form->field($model, 'type', ['options' => ['class' => 'half-elem type-field-review']])->hiddenInput()->label(false);?>

                    <div class="block-form-input">
                        <?=$form->field($model, 'id_exc', ['options' => ['class' => 'select-small']])->label(false)->dropDownList($excursions, [
                            'class' => 'js-select',
                            'id' => 'select',
                            'priceValue' => 111,
                            'data-placeholder' => 'Взрослых'
                        ]);?>
                        <?//=$form->field($model, 'id_exc', ['options' => ['class' => 'part-input ']])->dropDownList($excursions, ['options' => ['class' => 'jq-selectbox__select-text']]);?>
                        <div class="block-separator"></div>
                        <?=$form->field($model, 'name', ['options' => ['class' => 'part-input']])
                            ->input('text', ['class' => '', 'placeholder' => 'Ваше имя'])
                            ->label(false);?>
                        <div class="block-separator"></div>
                        <?=$form->field($model, 'rating', ['options' => ['class' => 'part-input']])
                            ->textInput(['type' => 'number', 'max' => 5, 'min' => 0, 'step' => 'any', 'placeholder' => 'Отцените экскурсию'])
                            ->label(false);?>
                    </div>


                    <div class="block-form-input">
                        <?=$form->field($model, 'content', ['options' => ['class' => 'part-input']])->textArea();?>
                    </div>

                        <?=Html::submitButton('Сохранить', [
                            'class' => 'btn btn_orange-brd reviews-page__header-btn'
                        ]);?>

                <? $form::end();?>

            </div>
        </section>
