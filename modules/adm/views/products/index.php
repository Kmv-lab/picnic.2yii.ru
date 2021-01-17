<?php

use app\modules\adm\models\Categories;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$catNames = Categories::find()->all();
$catNamesArray = [];

foreach ($catNames as $catName){
    $catNamesArray[$catName->id] = $catName->name;
}

?>


<div class="col-xs-12">
    <div class="row">
        <div class="well well-sm">
            <ul class="nav nav-pills">
                <li><?=Html::a('Добавить товар', ['new'])?></li>
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
                        ['update', 'idProduct'=>$data['id']], ['class' => 'btn btn-primary btn-xs']);
                }
            ],
            [
                'attribute' => 'is_active',
                'content' => function($data){
                    return $data['is_active'] ? '<span class="glyphicon glyphicon-ok"></span>' : '';
                }
            ],
            [
                'attribute' => 'category_id',
                'content' => function($data) use ($catNamesArray){
                    return '<span class="">'.$catNamesArray[$data["category_id"]].'</span>';
                }
            ],
            [
                'attribute' => '',
                'content' => function($data){
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                        ['delete', 'idProd'=>$data['id']], ['class' => 'btn btn-danger btn-xs']);
                }
            ]
        ],
    ]);?>
</div>