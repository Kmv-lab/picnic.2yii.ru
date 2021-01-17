<?php

use yii\helpers\Url;
use app\widgets\Menu;
?>
<div class="top">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="header-slogan">
                    Качественные товары для пикника от производителя с гарантией
                </div>
            </div>
            <div class="col-md-7 text-right">
                <a href = "<?=Yii::$app->params['vk']?>" target = "_blank"><i class="fa fa-vk" aria-hidden="true"></i></a>
                <a href = "<?=Yii::$app->params['insta']?>" target = "_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                <a href = "<?=Yii::$app->params['youtube']?>" target = "_blank"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
                <a class = "oneclick-link"><i class="fa fa-phone" aria-hidden="true"></i> Заказать звонок</a>

                <a href = "/korzina"><i class="fa fa-shopping-cart"></i>
                    <span id="msMiniCart" class="">
                        <?php
                        Yii::$app->session->open();

                        $totalCount = 0;
                        if(isset($_SESSION['products']) && !empty($_SESSION['products'])){
                            foreach ($_SESSION['products'] as $product){
                                $totalCount += $product['count'];
                            }
                        }
                        ?>
                        <span>
                            Корзина (<span class="basket-count"><?=$totalCount?></span>)
                        </span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>
<header class="pages-header">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="logo">
                    <a href = "/"><img src = "/img/logo.png" alt = "Picnic-Shop"></a>
                </div>
            </div>
            <div class="col-md-8">
                <div class="header-contacts">
                    <div class="phone"><a href = "tel:<?=preg_replace('/[^0-9\+]/', '', Yii::$app->params['phone'])?>"><?=Yii::$app->params['phone']?></a></div>
                    <div class="time">Пн - Сб с 9:00 до 18:00</div>
                </div>
                <div class="messengers">
                    <div class="messengers__item">
                        <a href = "https://wa.me/<?=Yii::$app->params['phone_whatsapp']?>" target = "_blank">
                            <img src = "/img/whatsapp.svg" alt = "whatsapp">
                        </a>
                    </div>
                    <div class="messengers__item">
                        <a href = "viber://chat?number=<?=Yii::$app->params['phone_whatsapp']?>"" target = "_blank">
                        <img src = "/img/viber.svg" alt = "viber">
                        </a>
                    </div>
                    <div class="messengers__item">
                        <a href = "<?=Yii::$app->params['telegram']?>" target = "_blank">
                            <img src = "/img/telegram.svg" alt = "telegram">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<nav class="topnav">
    <div class="container">
        <?=Menu::widget();?>
    </div>
</nav>

