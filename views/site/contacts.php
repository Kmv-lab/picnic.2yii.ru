<?php


?>


<div class="content-block">
    <div class="row">
        <div class="col-md-6">
            <div class="contacts">
                <div class="title">Контактная информация</div>
                <div class="row" style="margin-top:20px;">
                    <div class="col-md-2"><img src="/img/icon-phone2.png" alt=""></div>
                    <div class="col-md-10">
                        <div class="contact-line">Тел.: <?=Yii::$app->params['phone']?> (для заказов)</div>
                        <div class="contact-line">Тел.: <?=Yii::$app->params['major_phone']?> (руководитель)</div>
                    </div>
                </div>
                <div class="row" style="margin-top:20px;">
                    <div class="col-md-2"><img src="/img/icon-at.png" alt=""></div>
                    <div class="col-md-10">
                        <div class="contact-line" style="position:relative; top:10px">E-mail: <?=Yii::$app->params['adminEmail']?></div>
                    </div>
                </div>
                <div class="row" style="margin-top:20px;">
                    <div class="col-md-2"><img src="/img/icon-pin.png" alt=""></div>
                    <div class="col-md-10">
                        <div class="contact-line">Адрес производства: <?=Yii::$app->params['address']?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="contact-form">
                <div class="title">Напишите нам!</div>
                <form method="post" class="ajax_form_callback">
                    <input type="text" class="name" name="name" placeholder="Ваше имя *" required>
                    <input type="text" name="phone" placeholder="Ваш телефон *" class="input-phone" required>
                    <textarea name="question" class="question" placeholder="Ваш вопрос"></textarea>
                    <input type="submit" class="btn btn-bright">
                </form>
            </div>
        </div>
    </div>
</div>
<div class="content-block">
    <div class="title">Мы в соцсетях</div>
    <div class="row">
        <div class="col-md-6">
            <ul class="social cnt">
                <li><a href="https://vk.com/picnic_shop" target="_blank"><i class="icon-vkontakte-rect"></i></a></li>
                <li><a href="https://instagram.com" target="_blank"><i class="icon-instagram"></i></a></li>
                <li><a href="https://ok.ru" target="_blank"><i class="icon-odnoklassniki-rect"></i></a></li>
            </ul>
        </div>
        <div class="col-md-6">
            <script type="text/javascript" src="https://vk.com/js/api/openapi.js?168"></script>

            <!-- VK Widget -->
            <div id="vk_groups"></div>
            <script type="text/javascript">
                VK.Widgets.Group("vk_groups", {mode: 4, width: "auto", height: "400"}, 159837774);
            </script>
        </div>
    </div>
</div>
