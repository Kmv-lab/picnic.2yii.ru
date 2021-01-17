<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="col-xs-12">
    <div class="row">
        <div class="well well-sm">
            <ul class="nav nav-pills">
                <li><?=Html::a('Добавить категорию', ['new'])?></li>
            </ul>
        </div>
    </div>
</div>

<? if($param){
    echo '<div class="row alert-temporary">
        <span class="alert alert-danger col-xs-12 text-center">Невозможно удалить категорию в которой уже добавлены товары.<br>Вам необходимо удалить, либо перенести товары, относящиеся к этой категории</span>
        </div>';
}?>

<div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'attribute' => 'name',
                'content'=>function($data){
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.$data['name'],
                        ['update', 'idCat'=>$data['id']], ['class' => 'btn btn-primary btn-xs']);
                }
            ],
            [
                'attribute' => 'Перейти на страницу',
                'content' => function($data){
                    return '<a target="_blank" class="btn btn-info btn-xs" href="/shop/'.$data["alias"].'"><span class="glyphicon glyphicon-share-alt"></span> </a>';
                }
            ],
            [
                'attribute' => '',
                'content' => function($data){
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                        ['delete', 'idCat'=>$data['id']], ['class' => 'btn btn-danger btn-xs']);
                }
            ]
        ],
    ]);?>
</div>

