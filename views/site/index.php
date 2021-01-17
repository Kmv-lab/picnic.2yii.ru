<?php


?>
<main class="main">

    <?php
    if (isset($blocks) && !empty($blocks)){
        foreach ($blocks as $block){
            if(Yii::$app->params['is_mobile'] && $block->name == 'Изображение')
                continue;
            echo $block->content;
        }
    }
    ?>

</main>
