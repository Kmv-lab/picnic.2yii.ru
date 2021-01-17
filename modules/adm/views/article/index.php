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

<div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'attribute' => 'name',
                'content'=>function($data){
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.$data['name'],
                        ['update', 'idArticle'=>$data['id']], ['class' => 'btn btn-primary btn-xs']);
                }
            ],
            [
                'attribute' => 'Перейти на страницу',
                'content' => function($data){
                    return '<a class="btn btn-info btn-xs" href="/articles/'.$data["alias"].'"><span class="glyphicon glyphicon-share-alt"></span> </a>';
                }
            ],
            [
                'attribute' => '',
                'content' => function($data){
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                        ['delete', 'idArticle'=>$data['id']], ['class' => 'btn btn-danger btn-xs']);
                }
            ]
        ],
    ]);?>
</div>

