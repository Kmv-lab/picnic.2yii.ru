<?php



/* @var $this \yii\web\View */
/* @var $articles \app\modules\adm\models\Articles[]|\app\modules\adm\models\index_head[]|\app\modules\adm\models\Main_page[]|array|\yii\db\ActiveRecord[] */
?>

<div class="edit">
    <div class="row">
        <? foreach ($articles as $article){
            ?>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="article">
                    <div class="article-image">
                        <a href="/stati/<?=$article['alias']?>"><img src="<?=$article->DIRview()."/".$article->getOneResolution('min').'/'.$article['img']?>" alt=""></a>
                        <div class="border"></div>
                    </div>
                    <div class="dateago">
                        <?=$article['date']?>
                    </div>
                    <div class="article-title">
                        <h3><a href="/stati/<?=$article['alias']?>"><?=$article['name']?></a></h3>
                    </div>
                    <div class="introtext">
                        <?=$article['description']?>
                    </div>
                    <div class="readmore">
                        <a href="/stati/<?=$article['alias']?>" class="btn btn-light">Читать статью</a>
                    </div>
                </div>
            </div>
        <?}?>
    </div>
</div>
