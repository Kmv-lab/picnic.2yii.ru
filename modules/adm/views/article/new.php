<?php

use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

?>

<div class="block-update">

    <div class="block-form">

        <?php $form = ActiveForm::begin([
            'enableClientValidation'=>false,]); ?>

        <div class="row">
            <div class="col-xs-4">
                <?=$form->field($model, 'name',
                    [
                        'inputOptions' =>
                            ['class' => 'translit_source form-control']
                    ])->label('Название')->textInput();?>
            </div>
            <div class="col-xs-4">
                <?=$form->field($model, 'alias',
                    [
                        'inputOptions' =>
                            ['class' => 'translit_dest form-control']
                    ])->label('Путь')->textInput();?>
            </div>
            <div class="col-xs-2"><?= $form->field($model, 'is_active')->checkbox() ?></div>
            <div class="col-xs-2">
                <?= $form->field($model, 'date')->widget(DatePicker::className(), [
                    'dateFormat' => 'yyyy-MM-dd',
                    'options' => ['class' => 'form-control'],
                ]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <?=$form->field($model, 'description')->label()->textInput();?>
            </div>
        </div>

        <div class="row elem-form">
            <div class="col-xs-12">
                <?=$form->field($model, 'img', ['options' => ['style' => 'margin: 0;']])->fileInput([
                    'multiple' => false,
                    'id' => "img",
                ])->label();?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <span style="font-weight: bold">Содержание</span>
                <div>
                    <?=$form->field($model, 'content',  [
                        'inputOptions' => ['class' => 'ckeditor'],
                        'labelOptions' => ['class' => 'col-sm-3 control-label']
                    ])->textArea(['id' => 'wysiwyg1', 'style' => 'margin: 0;'])->label(false);?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <?=$form->field($model, 'seo_title')->label()->textInput();?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6">
                <?= $form->field($model, 'seo_description',  [
                    'inputOptions' => ['class' => ' form-control']])->textarea(['rows' => 6]) ?>
            </div>
            <div class="col-xs-6">
                <?= $form->field($model, 'seo_keywords',  [
                    'inputOptions' => ['class' => ' form-control']])->textarea(['rows' => 6]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton($model->getIsNewRecord() ? 'Добавить' : 'Сохранить', ['class' => 'btn btn-success pull-right']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
