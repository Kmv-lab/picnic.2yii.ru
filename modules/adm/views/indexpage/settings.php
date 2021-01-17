<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>

<div class="col-xs-12">
    <div class="row">
        <div class="well well-sm">
            <ul class="nav nav-pills">
                <li><?=Html::a('Добавить', ['new'])?></li>
            </ul>
        </div>
    </div>
</div>

<h2>Настройки главной страницы</h2>

<div class="settings-update">
    <div class="settings-form">
        <?php $form = ActiveForm::begin(['id' => 'settings-form',
            'layout' => 'horizontal',
            'method'=>'post',
            'options' => [
                // класс формы
                'class' => 'form-horizontal',
                // возможность загрузки файлов
                // 'enctype' => 'multipart/form-data'
            ],]);
        foreach ($settings AS $setting){ ?>
            <div class="elem">
                <p><?=$setting['description']?></p>
                <?= $form->field($models[$setting['id']], '['.$setting['id'].']name')
                    ->textarea(['maxlength' => true])->label('мета-атрибут') ?>
                <?= $form->field($models[$setting['id']], '['.$setting['id'].']value')
                    ->textarea(['maxlength' => true])->label('Значение') ?>
            </div>
            <?php
        }?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
