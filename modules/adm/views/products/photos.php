<?php

use app\modules\adm\models\ExcursionPhotos;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


$iconDel    = '<span class="glyphicon glyphicon-remove"></span>';
?>

<div class="gallery-update">
    <div class="gallery-form">
        <?php
        if ($uploadSuccess){
            echo '<div class="row alert-temporary">
                <span class="alert alert-success col-xs-12 text-center">Фото загружены успешно!</span>
                </div>';
        }

        $form = ActiveForm::begin([
            'options' => [
                'class' => 'sanatorium-page form-horizontal',
            ],
        ]);

        echo $form->field($model, 'name[]')->fileInput(['multiple' => true, 'accept' => 'image/*'])->label('Фотографии товара');

        echo Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']);

        $form::end();
        ?>
    </div>
    <br>
    <br>
    <br>
    <br>
    <div class="form-horizontal">

        <?php
        if(!empty($photos)){
        foreach ($photos as $photo){
            $delUrl = URL::to(['delete_photo', 'idProd'=>$idProd, 'idPhoto'=>$photo->id]);

            $resolutions = $photo->getResolutionOfImage();
            $iconEdit = '<span class="glyphicon glyphicon-pencil"></span>';

            $this->registerJsFile('/js/jcrop/jquery.Jcrop.min.js', ['depends' => ['yii\web\JqueryAsset']]);
            $this->registerCssFile('/js/jcrop/jquery.Jcrop.min.css');?>


            <div class="form-group image_thumb" style="margin: 0">
                <div class="col-sm-12">
                    <div class="col-sm-9">
                        <div class="form-group">
                            <div class="col-sm-12 block-img-prod" data-id="<?=$photo->id?>">
                                <?php
                                foreach ($resolutions as $resolution){?>

                                    <div class="col-sm-4">
                                        <?php echo Html::img($photo->DIRview().$resolution.'/'.$photo['name']."?no-cache=".rand(0, 1000), ['class' => "img-thumbnail img-responsive", 'data-ratio' => $resolution]); ?>
                                        <p><?php echo $resolution ?> </p>
                                    </div>

                                <?}?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3" style="float: right;padding-top: 20px;">
                        <div class="form-group">
                            <select class="form-control input-sm" name="Photo_ratio_<?= $photo->id ?>"
                                    id="Photo_ratio_<?= $photo->id ?>">
                                <option value="0" selected="selected">Все разрешения</option>
                                <?php foreach ($resolutions AS $resolution) { ?>
                                    <option value="<?php echo $resolution ?>"><?php echo $resolution ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <a href="#" class="btn btn-primary btn-xs show-dialog-thumb" data-id="<?= $photo->id ?>" data-file_name="<?= $photo->name ?>">
                                Редактировать миниатюру <?= $iconEdit ?>
                            </a>
                        </div>
                        <div class="form-group">
                            <a href="<?=$delUrl?>" class="col-sm-5 deleteItem btn btn-inverse btn-default btn-xs">Удалить <?=$iconDel?></a>
                        </div>
                    </div>
                </div>
            </div>

        <?}?>
    </div>

    <div id="dialog-thumb"  title="Редактирование миниатюры" data-big="<?=$model->DIRview().'original/'?>"
         data-url="<?=Url::toRoute(['products/ajaxcreatethumb', 'id' => $idProd, 'name' => 'name'])?>" class="modal fade multi-exc">
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

    <?
    }?>


</div>