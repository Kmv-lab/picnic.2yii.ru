<?php

use app\modules\adm\models\Categories;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
/*
<form class="form-horizontal">
  <div class="form-group">
    <label class="col-sm-2 control-label">Email</label>
    <div class="col-sm-10">
      <p class="form-control-static">email@example.com</p>
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword" class="col-sm-2 control-label">Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="inputPassword" placeholder="Password">
    </div>
  </div>
</form>*/

$template = '<div class="col-md-12">
               {label}
               <div class="col-md-6">
                    {input}
               </div>
               <div class="col-md-6 col-md-offset-3">{error}</div>
               <div class="col-md-6 col-md-offset-3">{hint}</div>
            </div>';
?>
<!--<a class="btn btn-primary btn-sm at-site" target="_blank" href="#">
    На сайте <span class="glyphicon glyphicon-eye-open"></span>
</a>-->
<div class="block-update">
    <div class="block-form">

        <?php $form = ActiveForm::begin([
                'enableClientValidation'=>false,
                'options'=>['class'=>'form-horizontal'],
                'errorSummaryCssClass'=>'alert alert-danger',
        ]); ?>
            
        <?= $form->errorSummary($model,['header'=>'<strong>Исправьте следующие ошибки!</strong>']) ?>
    
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
                            ])->label('Название категории',['class'=> 'col-md-3 text-right'])->textInput();?>
                    
                    <?=$form->field($model, 'alias',
                            [
                                'template'=>$template,
                                'inputOptions' =>
                                    ['class' => 'translit_dest row form-control']
                            ])->label('В строке адреса',['class'=> 'col-md-3 text-right'])->textInput();?>

                    <?=$form->field($model, 'parent_id',['template'=>$template])
                        ->label('Родительская категория',['class'=> 'col-md-3 text-right'])
                        ->dropDownList($possibleParents, ['prompt' => 'Каталог', 'style' => ['margin-left' => '-15px']]);?>
                    
                            <div class="col-md-12">
                                <div class="checkbox-inline col-md-2 col-md-offset-1">
                                    <?= $form->field($model, 'in_menu',['options'=>['tag'=>false]])->checkbox(); ?>
                                </div>
                                <div class="checkbox-inline col-md-2">
                                    <?= $form->field($model, 'filter',['options'=>['tag'=>false]])->checkbox(); ?>
                                </div>
                                <div class="checkbox-inline col-md-2">
                                    <?= $form->field($model, 'is_active',['options'=>['tag'=>false]])->checkbox(); ?>
                                </div>
                            </div>

                    <div class=" col-md-offset-1 col-md-8">
                        <?= $form->field($model, 'short_desc', [
                            'inputOptions' => ['class' => ' form-control']])->textarea(['rows' => 6]) ?>
                    </div>
                    <?php // vd($form->field($model, 'in_menu')); ?>
                </div><!-- .panel-body -->
            </div><!-- .panel -->
        </div><!-- .row -->

        <div class="row">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Дополнительная информация</h3>
                </div>
                <div class="panel-body">
                    <?=$form->field($model, 'additional_block',['template'=>$template])
                        ->label('Связанный блок',['class'=> 'col-md-3 text-right'])
                        ->dropDownList($additionalBlocks, ['prompt' => 'Без блока', 'style' => ['margin-left' => '-15px']]);?>
                    
                    <div class="col-md-12">
                        <?= $form->field($model, 'description',  [
                            'inputOptions' => ['class' => 'ckeditor form-control']])->textarea(['rows' => 6]) ?>
                    </div>
                </div><!-- .panel-body -->
            </div><!-- .panel -->
        </div><!-- .row -->
                
        <div class="well well-lg">
            <div class="row">
                <h3 class="panel-title">Редактитрование изображений</h3>
                <div class="col-md-12">
                    <?=$form->field($model, 'img', ['options' => ['style' => 'margin: 0;']])->fileInput([
                        'multiple' => false,
                        'id' => "img",
                    ])->label();?>
                </div>
            </div><!-- .row -->
            

            <? if (is_file($model->DIR().'original/'.$model['img'])){
                $resolutions = $model->getResolutionOfImage();
                $iconEdit = '<span class="glyphicon glyphicon-pencil"></span>';

                $this->registerJsFile('/js/jcrop/jquery.Jcrop.min.js', ['depends' => ['yii\web\JqueryAsset']]);
                $this->registerCssFile('/js/jcrop/jquery.Jcrop.min.css');?>
            <div class="row">
                <div class="col-md-9">
                    <? foreach ($resolutions as $resolution){ ?>
                        <div class="col-sm-4">
                            <a class="thumbnail"><?=Html::img($model->DIRview().$resolution.'/'.$model['img']."?no-cache=".rand(0, 1000), ['class' => "img-thumbnail img-responsive", 'data-ratio' => $resolution]); ?>
                            <span><?=$resolution?> </span>
                            </a>
                        </div>
                    <?}?>
                </div>
                <div class="col-md-3">
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
            </div><!-- .row -->

                <!--modal-->
                <div id="dialog-thumb"  title="Редактирование миниатюры" data-big="<?=$model->DIRview().'original/'?>"
                     data-url="<?=Url::toRoute(['category/ajaxcreatethumb', 'id' => $model->id, 'name' => 'img'])?>" class="modal fade multi-exc">
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
            
            <?} //is_file ?>
        </div><!-- .well -->

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

        <div class="row">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Атрибуты категории</h3>
                </div>
                <table class="panel-body table table-striped">
                    <thead class="">
                        <th class="col-md-4"><label>Название атрибута</label></th>
                        <th class="col-md-4"><label>Единицы измерения</label></th>
                        <th class="col-md-4"></th>
                    </thead>
                    <tbody class="attributes-elems-block attributes" data-id-category="<?=$model->id?>"> <!------------ https://getbootstrap.com/docs/3.4/css/#tables-striped стили рядов забери отсюда -->
                        <?php
                            foreach ($attributesElems as $elem){
                                echo $this->render('ajaxNewAttr', ['model' => $elem]);
                            }
                            echo $this->render('ajaxNewAttr', ['model' => false]);
                        ?>
                    </tbody>
                </table><!-- .panel-body -->
            </div><!-- .panel -->
        </div><!-- .row -->


    </div>

</div>
