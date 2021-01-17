<?php
use yii\helpers\Url;

if(!$isFooter){


?>

<ul class="">
    <?php

        foreach ($pages AS $page) {
            if ($page['in_menu'] == 1) {
                ?>

                <li title="<?= $page['name'] ?>">
                    <a href="/<?= $page['url'] ?>/"
                        <?= Yii::$app->params['curPage'] == $page['alias'] ? 'class="active"' : '' ?>>
                        <?= $page['name'] ?>
                    </a>
                </li>

            <?
            }
        }
    ?>
</ul>

<?}
else{?>

    <ul>
        <?php
        foreach ($pages AS $page){
            $url = Yii::$app->request->pathInfo;
            $url = str_replace('/', '', $url);
            ?>
                <li title="<?=$page['page_link_title']?>"><a href="/<?=$page['alias']?>"><?=$page['label']?></a></li>

        <?}
        ?>
    </ul>

<?}