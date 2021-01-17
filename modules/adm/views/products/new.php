<?php

use app\modules\adm\models\Categories;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;



$template = '<div class="col-md-12">
               {label}
               <div class="col-md-6">
                    {input}
               </div>
               <div class="col-md-6 col-md-offset-3">{error}</div>
               <div class="col-md-6 col-md-offset-3">{hint}</div>
            </div>';
?>

<div class="block-update">

    <div class="block-form">

        <?php $form = ActiveForm::begin([
            'enableClientValidation'=>false,]); ?>

        <div class="row">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Основная информация</h3>
                </div>
                <div class="panel-body">
                    <?=$form->field($model, 'name',
                        [
                            'template'=>$template,
                            'inputOptions' =>
                                ['class' => 'translit_source row form-control']
                        ])->label('Название товара',['class'=> 'col-md-3 text-right'])->textInput();?>

                    <?=$form->field($model, 'alias',
                        [
                            'template'=>$template,
                            'inputOptions' =>
                                ['class' => 'translit_dest row form-control']
                        ])->label('В строке адреса',['class'=> 'col-md-3 text-right'])->textInput();?>

                    <?=$form->field($model, 'category_id',['template'=>$template])
                        ->label('Категория',['class'=> 'col-md-3 text-right'])
                        ->dropDownList($categories, ['prompt' => 'Не выбрано!', 'style' => ['margin-left' => '-15px']]);?>

                    <div class="col-md-12">
                        <div class="checkbox-inline col-md-2 col-md-offset-1">
                            <?= $form->field($model, 'is_active',['options'=>['tag'=>false]])->checkbox(); ?>
                        </div>
                    </div>

                    <div class=" col-md-offset-1 col-md-8">
                        <?= $form->field($model, 'description',  [
                            'inputOptions' => ['class' => 'ckeditor form-control']])->textarea(['rows' => 6]) ?>
                    </div>
                </div>
            </div>
        </div>

        <? $templateSEO = '{label}{input}';?>
        <div class="row">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">SEO</h3>
                </div>
                <div class="panel-body panel-body-seo">
                    <div class="seo-options col-md-offset-1 col-md-8">
                        <? $count = $model->getMaxCountSeo('title') - mb_strlen($model->seo_title);
                        echo $form->field($model, 'seo_title',['template'=>$templateSEO])
                            ->label('SEO Title <span class="badge">'.$count.'</span>')
                            ->textInput(['max-count' => $model->getMaxCountSeo('title')]);?>
                    </div>
                    <div class="seo-options col-md-offset-1 col-md-8">
                        <? $count = $model->getMaxCountSeo('h1') - mb_strlen($model->seo_h1);
                        echo $form->field($model, 'seo_h1',['template'=>$templateSEO])
                            ->label('SEO H1 <span class="badge">'.$count.'</span>')
                            ->textInput(['max-count' => $model->getMaxCountSeo('h1')]);?>
                    </div>
                    <div class="seo-options col-md-offset-1 col-md-8">
                        <? $count = $model->getMaxCountSeo('description') - mb_strlen($model->seo_description);
                        echo $form->field($model, 'seo_description',  [
                            'template'=>$templateSEO,
                            'inputOptions' => ['class' => ' form-control']
                        ])->label('SEO Description <span class="badge">'.$count.'</span>')
                            ->textarea(['rows' => 6, 'max-count' => $model->getMaxCountSeo('description')]) ?>
                    </div>
                    <div class="seo-options col-md-offset-1 col-md-8">
                        <? $count = $model->getMaxCountSeo('keywords') - mb_strlen($model->seo_keywords);
                        echo $form->field($model, 'seo_keywords',  [
                            'template'=>$templateSEO,
                            'inputOptions' => ['class' => ' form-control']
                        ])
                            ->label('SEO Keywords <span class="badge">'.$count.'</span>')
                            ->textarea(['rows' => 6, 'max-count' => $model->getMaxCountSeo('keywords')]) ?>
                    </div>
                </div><!-- .panel-body -->
            </div><!-- .panel -->
        </div><!-- .row -->

        <div class="row">
            <div class="well">
                <?= Html::submitButton($model->getIsNewRecord() ? 'Добавить' : 'Сохранить', ['class' => 'btn btn-success']) ?>
            </div>
        </div><!-- .row -->
        <? ActiveForm::end(); ?>

    </div>

</div>
