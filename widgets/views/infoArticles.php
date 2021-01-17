


<?php
if(!empty($articles)){?>

    <div class="edit">
        <ul class="simple-list">

            <? foreach ($articles as $article){?>

                <li>
                    <a href="<?=$article->page_alias?>"><?=$article->page_name?></a>
                </li>

            <?}?>

        </ul>
    </div>
<?}?>

