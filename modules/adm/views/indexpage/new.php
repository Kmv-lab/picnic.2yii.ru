<?php



/* @var $this \yii\web\View */
/* @var $model \app\modules\adm\models\index_head */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html; ?>

<div class="col-xs-12">
    <div class="row">
        <div class="well well-sm">
            <ul class="nav nav-pills">
                <li><?=Html::a('Назад ко  всем настройкам', ['settings'])?></li>
            </ul>
        </div>
    </div>
</div>

<div class="static-seo-update">

    <div class="static-seo-form">

        <?php $form = ActiveForm::begin(['layout' => 'horizontal',
            'options' => [
                // класс формы
                'class' => 'form-horizontal',
                // возможность загрузки файлов
            ],]); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
