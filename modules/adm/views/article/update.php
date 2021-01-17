<?php

use app\modules\adm\models\Categories;
use yii\helpers\Html;
use yii\helpers\Url;
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

            <?if (is_file($model->DIR().'original/'.$model['img'])){

                $resolutions = $model->getResolutionOfImage();
                $iconEdit = '<span class="glyphicon glyphicon-pencil"></span>';

                $this->registerJsFile('/js/jcrop/jquery.Jcrop.min.js', ['depends' => ['yii\web\JqueryAsset']]);
                $this->registerCssFile('/js/jcrop/jquery.Jcrop.min.css');?>

                <div class="col-xs-12">
                    <div class="form-group image_thumb" style="margin: 0">
                        <div class="col-sm-12">
                            <div class="col-sm-9">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <?php
                                        foreach ($resolutions as $resolution){?>

                                            <div class="col-sm-4">
                                                <?php echo Html::img($model->DIRview().$resolution.'/'.$model['img']."?no-cache=".rand(0, 1000), ['class' => "img-thumbnail img-responsive", 'data-ratio' => $resolution]); ?>
                                                <p><?php echo $resolution ?> </p>
                                            </div>

                                        <?}?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3" style="float: right;padding-top: 20px;">
                                <div class="form-group">
                                    <select class="form-control input-sm" name="Photo_ratio_<?= $model->id ?>"
                                            id="Photo_ratio_<?= $model->id ?>">
                                        <option value="0" selected="selected">Все разрешения</option>
                                        <?php foreach ($resolutions AS $resolution) { ?>
                                            <option value="<?php echo $resolution ?>"><?php echo $resolution ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <a href="#" class="btn btn-primary btn-xs show-dialog-thumb" data-id="<?= $model->id ?>" data-file_name="<?= $model->img ?>">
                                        Редактировать миниатюру <?= $iconEdit ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?}?>
        </div>

        <div id="dialog-thumb"  title="Редактирование миниатюры" data-big="<?=$model->DIRview().'original/'?>"
             data-url="<?=Url::toRoute(['article/ajaxcreatethumb', 'idArticle' => $model->id, 'name' => 'img'])?>" class="modal fade multi-exc">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Заголовок модального окна -->
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Детали</h4>
                    </div>
                    <!-- Основное содержимое модального окна -->
                    <div class="modal-body">
                        Пока пусто
                    </div>
                    <!-- Футер модального окна -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Закрыть</button>
                        <a href="#" id="thumb-ready"  class="btn btn-primary btn-sm">Готово</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <span style="font-weight: bold">Описание</span>
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
