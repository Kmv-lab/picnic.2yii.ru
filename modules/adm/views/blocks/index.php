<?php

use yii\helpers\Html;
use yii\grid\GridView;

?>

<div class="col-xs-12">
    <div class="row">
        <div class="well well-sm">
            <ul class="nav nav-pills">
                <li><?=Html::a('Добавить блок', ['new'])?></li>
            </ul>
        </div>
    </div>
</div>

<div class="block-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id_block',
            [
                'attribute' => 'block_name',
                'content'=>function($data){
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.$data['block_name'],
                        ['update', 'id'=>$data['id_block']], ['class' => 'btn btn-primary btn-xs']);
                }
            ],
            [
                'attribute' => '',
                'content' => function($data){
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                        ['delete', 'id'=>$data['id_block']], ['class' => 'btn btn-danger btn-xs']);
                }
            ]
        ],
    ]); ?>
</div>
