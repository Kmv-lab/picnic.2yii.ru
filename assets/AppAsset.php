<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/all.min.css',
        'css/stylesheet.css',
        'css/light.min.css',
        'css/styles.css',
        'css/media.css',
        'libs/font-awesome/css/font-awesome.min.css',
        //'libs/sumoselect/sumoselect.min.css',
        'libs/owl/assets/owl.carousel.min.css',
        'libs/owl/assets/owl.theme.default.min.css',
        'libs/fancybox/jquery.fancybox.min.css',
        'libs/jquery-ui/jquery-ui.min.css',
        //'css/normalize.css',
        //'css/normalize.css',
    ];
    public $js = [
        '/assets/ab2eca81/jquery.js',
        'libs/owl/owl.carousel.min.js',
        'libs/inputmask/jquery.inputmask.min.js',
        //'libs/sumoselect/jquery.sumoselect.min.js',
        'libs/fancybox/jquery.fancybox.min.js',
        'libs/jquery-ui/jquery-ui.min.js',
        //'js/script.js',
        //'js/vendors.min.js',
        'js/scripts.min.js',
    ];
    public $depends = [
        //'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
