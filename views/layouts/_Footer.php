<?php

$pages = Yii::$app->params['pages'];
$onlyCategories = [];
$infoPages = [];
foreach ($pages as $id => $page){
    if(strripos($id, 'm') !== false){
        $onlyCategories[$id] = $page;
        unset($pages[$id]);
        continue;
    }
    if($page['parent_id'] === 'p1'){
        $infoPages[$id] = $page;
        unset($pages[$id]);
        continue;
    }
}

?>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="logo">
                    <img src = "/img/logo.png" alt = "">
                </div>
            </div>
            <div class="col-md-2">
                <div class="footer-title">Каталог</div>
                <nav>
                    <ul class="">
                        <? foreach ($onlyCategories as $category){?>
                            <li><a href="<?=$category['url']?>" ><?=$category['name']?></a></li>
                        <?}?>
                    </ul>
                </nav>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6">
                <div class="footer-title">Инфо</div>
                <nav>
                    <ul class="">
                        <? foreach ($infoPages as $infoPage){?>
                            <li><a href="<?=$infoPage['url']?>" ><?=$infoPage['name']?></a></li>
                        <?}?>
                    </ul>
                </nav>
            </div>
            <div class="col-md-2 col-sm-3 col-xs-6">
                <div class="footer-title">Навигация</div>
                <nav>
                    <ul>
                        <li><a href="/korzina">Корзина</a></li>
                        <li><a href="/">Личный кабинет</a></li>
                        <li><a href="/">Избранное</a></li>
                        <li><a href="/kontaktyi">Контакты</a></li>
                        <li><a href="/stati/">Статьи</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-md-2 col-sm-3 col-xs-6">
                <div class="footer-title">Контакты</div>
                <nav>
                    <ul class = "footer-contacts">
                        <!--<li>8 (800) 511-85-47</li>-->
                        <li><?=Yii::$app->params['footer_phone']?></li>
                        <li><?=Yii::$app->params['adminEmail']?></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <div class="bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-12"><?=Yii::$app->params['copiright']?></div>
                <div class="col-md-6 col-sm-12 text-right">Адрес производства: <?=Yii::$app->params['address']?></div>
            </div>
        </div>
    </div>
</footer>

<div class="popup-fade" style="display:none;">
    <div class="popup">

        <div class="modal-cart mfp-hide text-center" id="cartDialog" style="display:none;">
            <div class="modal-content">
                <div class = "modal-title">Товар добавлен в корзину!</div>
                <a class="popup-close btn btn-white" >Закрыть</a>
                <a href = "/korzina" class = "btn btn-bright">Перейти в корзину</a>
            </div>
        </div>

        <div class="modal-cart" id="oneclick" style="display:none;">
            <div class="modal-content">
                <div class="modal-title">Закажите <?= isset(Yii::$app->params['prod_name']) ? Yii::$app->params['prod_name'] : '';?> прямо сейчас!</div>
                <form method="post" class="ajax_form_callback">
                    <input type="hidden" name="nospam" value="">
                    <input type="text" class="name" name="name" placeholder="Ваше имя" required>
                    <input type="text" name="phone" placeholder="Ваш телефон" class="input-phone" required="">
                    <!--<input type="text" name="lastname" id="popup-calculate-lastname" placeholder="Ваше lastname">-->
                    <div class="text-center"><button class="btn btn-bright" type="submit">Заказать!</button></div>

                    <input type="hidden" name="af_action" value="cf70fca427c6c5866898df319c9a489d">
                </form>
            </div>
        </div>

        <div class="modal-cart" id="thanks" style="display:none;">
            <div class="modal-content">
                <div class="modal-title">Спасибо за Вашу заявку! Мы свяжемся с Вами в ближайшее время.</div>
            </div>
        </div>
    </div>
</div>



               
