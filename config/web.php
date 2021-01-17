<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$dbResort = require __DIR__ . '/dbResort.php';

//Какой-то комент всем плевать

//Но не мне нах ёпт

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'app\controllers\Settings',//класс добавляет к настройкам параметры из бд
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'language' => 'ru-RU',
    'modules' => [
        'adm' => [
            'class' => 'app\modules\adm\Module',
        ],
    ],

    'timeZone' => 'Europe/Moscow',
    'components' => [
        'request' => [
            'baseUrl' => '',
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => md5('hrhsdf;lo894jj;'),
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\modules\adm\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                // 'extraParams' => null,
                'host' => 'smtp.yandex.com',
                /*'username' => 'login',
                'password' => 'password',*/
                'port' => '465',
                'encryption' => 'ssl',
            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'dbResort' => $dbResort,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '/',
            'enableStrictParsing' => false,
            'rules' => [
                /**
                 * 'countries/<direction_alias:\S+>/hotels-<stars:\d+>' => 'directions/hotels',
                 *
                \d    Соответствует любой цифре; эквивалент класса [0-9].
                \D    Соответствует любому нечисловому символу; эквивалент класса [^0-9].
                \s    Соответствует любому символу whitespace; эквивалент [ \t\n\r\f\v].
                \S    Соответствует любому не-whitespace символу; эквивалент [^ \t\n\r\f\v].
                \w    Соответствует любой букве или цифре; эквивалент [a-zA-Z0-9_].
                \W    Наоборот; эквивалент [^a-zA-Z0-9_]
                 */
                'debug/default/toolbar'                 => 'debug/default/toolbar',
                'debug/default/view'                    => 'debug/default/view',//костыл, для работы дебага с функционалом страниц
                'debug/default/db-explain'              => 'debug/default/db-explain',

                //стр будущего сайта
                '/' => 'site/index',
                'informatsiya' => 'site/page',
                'kontaktyi' => 'site/contacts',
                'korzina' => 'request/basket',
                'shop/ajaxfilter' => 'shop/ajaxfilter',
                'stati' => 'site/articles',
                'stati/<alias:\S+>' => 'site/article',
                'request/<action:\S+>' =>  'request/<action>',
                'shop/<category:\S+>/<alias:\S+>' => 'shop/product',
                'shop/<category:\S+>' => 'shop/category',
                [
                    'pattern' => 'sitemap\Sxml' ,
                    'route' => 'site/sitemap',
                    'suffix' => ''
                ],


                'admi/<controller:\w+>/<action:\w+>' =>  'adm/<controller>/<action>',
                'admi' => 'adm/default/index',


                '<alias:\S+>'               => 'site/page', //страницы
            ],
        ],
        'session'=>[
            'class' => 'yii\web\Session',
            'cookieParams' => ['lifetime' => 14*24*60*60]
        ],

    ],
    'params' => $params,
];

if (true) {//if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];
}

return $config;
