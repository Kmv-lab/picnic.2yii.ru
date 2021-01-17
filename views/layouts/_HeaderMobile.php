<?php


use app\widgets\Menu; ?>

<header class="header-mobile">
    <div class="header-icons">
        <div class="header-incons__item">
            <a href="/">
                <img src="/img/logo_mobile.png" alt="">
            </a>
        </div>
        <div class="header-incons__item">
            <a role="button" class="mobile-menu-trigger" onclick="$('.topnav').fadeIn();">
                <img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHZlcnNpb249IjEuMSIgdmlld0JveD0iMCAwIDI0IDI0IiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCAyNCAyNCIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4Ij4KICA8Zz4KICAgIDxwYXRoIGQ9Ik0yNCwzYzAtMC42LTAuNC0xLTEtMUgxQzAuNCwyLDAsMi40LDAsM3YyYzAsMC42LDAuNCwxLDEsMWgyMmMwLjYsMCwxLTAuNCwxLTFWM3oiIGZpbGw9IiMwMDAwMDAiLz4KICAgIDxwYXRoIGQ9Ik0yNCwxMWMwLTAuNi0wLjQtMS0xLTFIMWMtMC42LDAtMSwwLjQtMSwxdjJjMCwwLjYsMC40LDEsMSwxaDIyYzAuNiwwLDEtMC40LDEtMVYxMXoiIGZpbGw9IiMwMDAwMDAiLz4KICAgIDxwYXRoIGQ9Ik0yNCwxOWMwLTAuNi0wLjQtMS0xLTFIMWMtMC42LDAtMSwwLjQtMSwxdjJjMCwwLjYsMC40LDEsMSwxaDIyYzAuNiwwLDEtMC40LDEtMVYxOXoiIGZpbGw9IiMwMDAwMDAiLz4KICA8L2c+Cjwvc3ZnPgo=">
            </a>
        </div>
    </div>
    <div class="header-icons">
        <div class="header-incons__item">
            <a href="/korzina">
                <img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBoZWlnaHQ9IjUxMnB4IiB2aWV3Qm94PSIwIC0zMSA1MTIuMDAwMjYgNTEyIiB3aWR0aD0iNTEycHgiPjxwYXRoIGQ9Im0xNjQuOTYwOTM4IDMwMC4wMDM5MDZoLjAyMzQzN2MuMDE5NTMxIDAgLjAzOTA2My0uMDAzOTA2LjA1ODU5NC0uMDAzOTA2aDI3MS45NTcwMzFjNi42OTUzMTIgMCAxMi41ODIwMzEtNC40NDE0MDYgMTQuNDIxODc1LTEwLjg3ODkwNmw2MC0yMTBjMS4yOTI5NjktNC41MjczNDQuMzg2NzE5LTkuMzk0NTMyLTIuNDQ1MzEzLTEzLjE1MjM0NC0yLjgzNTkzNy0zLjc1NzgxMi03LjI2OTUzMS01Ljk2ODc1LTExLjk3NjU2Mi01Ljk2ODc1aC0zNjYuNjMyODEybC0xMC43MjI2NTctNDguMjUzOTA2Yy0xLjUyNzM0My02Ljg2MzI4Mi03LjYxMzI4MS0xMS43NDYwOTQtMTQuNjQ0NTMxLTExLjc0NjA5NGgtOTBjLTguMjg1MTU2IDAtMTUgNi43MTQ4NDQtMTUgMTVzNi43MTQ4NDQgMTUgMTUgMTVoNzcuOTY4NzVjMS44OTg0MzggOC41NTA3ODEgNTEuMzEyNSAyMzAuOTE3OTY5IDU0LjE1NjI1IDI0My43MTA5MzgtMTUuOTQxNDA2IDYuOTI5Njg3LTI3LjEyNSAyMi44MjQyMTgtMjcuMTI1IDQxLjI4OTA2MiAwIDI0LjgxMjUgMjAuMTg3NSA0NSA0NSA0NWgyNzJjOC4yODUxNTYgMCAxNS02LjcxNDg0NCAxNS0xNXMtNi43MTQ4NDQtMTUtMTUtMTVoLTI3MmMtOC4yNjk1MzEgMC0xNS02LjczMDQ2OS0xNS0xNSAwLTguMjU3ODEyIDYuNzA3MDMxLTE0Ljk3NjU2MiAxNC45NjA5MzgtMTQuOTk2MDk0em0zMTIuMTUyMzQzLTIxMC4wMDM5MDYtNTEuNDI5Njg3IDE4MGgtMjQ4LjY1MjM0NGwtNDAtMTgwem0wIDAiIGZpbGw9IiMwMDAwMDAiLz48cGF0aCBkPSJtMTUwIDQwNWMwIDI0LjgxMjUgMjAuMTg3NSA0NSA0NSA0NXM0NS0yMC4xODc1IDQ1LTQ1LTIwLjE4NzUtNDUtNDUtNDUtNDUgMjAuMTg3NS00NSA0NXptNDUtMTVjOC4yNjk1MzEgMCAxNSA2LjczMDQ2OSAxNSAxNXMtNi43MzA0NjkgMTUtMTUgMTUtMTUtNi43MzA0NjktMTUtMTUgNi43MzA0NjktMTUgMTUtMTV6bTAgMCIgZmlsbD0iIzAwMDAwMCIvPjxwYXRoIGQ9Im0zNjIgNDA1YzAgMjQuODEyNSAyMC4xODc1IDQ1IDQ1IDQ1czQ1LTIwLjE4NzUgNDUtNDUtMjAuMTg3NS00NS00NS00NS00NSAyMC4xODc1LTQ1IDQ1em00NS0xNWM4LjI2OTUzMSAwIDE1IDYuNzMwNDY5IDE1IDE1cy02LjczMDQ2OSAxNS0xNSAxNS0xNS02LjczMDQ2OS0xNS0xNSA2LjczMDQ2OS0xNSAxNS0xNXptMCAwIiBmaWxsPSIjMDAwMDAwIi8+PC9zdmc+Cg==">
            </a>
        </div>

        <div class="header-incons__item">
            <a href="tel:<?=preg_replace('/[^0-9\+]/', '', Yii::$app->params['phone'])?>">
                <img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4IiB2aWV3Qm94PSIwIDAgMzQ4LjA3NyAzNDguMDc3IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCAzNDguMDc3IDM0OC4wNzc7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8Zz4KCQk8Zz4KCQkJPHBhdGggZD0iTTM0MC4yNzMsMjc1LjA4M2wtNTMuNzU1LTUzLjc2MWMtMTAuNzA3LTEwLjY2NC0yOC40MzgtMTAuMzQtMzkuNTE4LDAuNzQ0bC0yNy4wODIsMjcuMDc2ICAgICBjLTEuNzExLTAuOTQzLTMuNDgyLTEuOTI4LTUuMzQ0LTIuOTczYy0xNy4xMDItOS40NzYtNDAuNTA5LTIyLjQ2NC02NS4xNC00Ny4xMTNjLTI0LjcwNC0yNC43MDEtMzcuNzA0LTQ4LjE0NC00Ny4yMDktNjUuMjU3ICAgICBjLTEuMDAzLTEuODEzLTEuOTY0LTMuNTYxLTIuOTEzLTUuMjIxbDE4LjE3Ni0xOC4xNDlsOC45MzYtOC45NDdjMTEuMDk3LTExLjEsMTEuNDAzLTI4LjgyNiwwLjcyMS0zOS41MjFMNzMuMzksOC4xOTQgICAgIEM2Mi43MDgtMi40ODYsNDQuOTY5LTIuMTYyLDMzLjg3Miw4LjkzOGwtMTUuMTUsMTUuMjM3bDAuNDE0LDAuNDExYy01LjA4LDYuNDgyLTkuMzI1LDEzLjk1OC0xMi40ODQsMjIuMDIgICAgIEMzLjc0LDU0LjI4LDEuOTI3LDYxLjYwMywxLjA5OCw2OC45NDFDLTYsMTI3Ljc4NSwyMC44OSwxODEuNTY0LDkzLjg2NiwyNTQuNTQxYzEwMC44NzUsMTAwLjg2OCwxODIuMTY3LDkzLjI0OCwxODUuNjc0LDkyLjg3NiAgICAgYzcuNjM4LTAuOTEzLDE0Ljk1OC0yLjczOCwyMi4zOTctNS42MjdjNy45OTItMy4xMjIsMTUuNDYzLTcuMzYxLDIxLjk0MS0xMi40M2wwLjMzMSwwLjI5NGwxNS4zNDgtMTUuMDI5ICAgICBDMzUwLjYzMSwzMDMuNTI3LDM1MC45NSwyODUuNzk1LDM0MC4yNzMsMjc1LjA4M3oiIGZpbGw9IiMwMDAwMDAiLz4KCQk8L2c+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==">
            </a>
        </div>
    </div>
</header>

<div class="contacts-header-mobile">
    <div class="contacts-header-phone">
        <a href = "tel:<?=preg_replace('/[^0-9\+]/', '', Yii::$app->params['phone'])?>"><?=Yii::$app->params['phone']?></a>
    </div>
    <div class="messengers messengers-mobile">
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

<nav class="topnav">
    <div class="container">
        <?=Menu::widget();?>
    </div>
    <span class="menu-close" onclick="$('.topnav').fadeOut();"></span>
</nav>
