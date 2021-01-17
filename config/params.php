<?php

$path = $_SERVER['DOCUMENT_ROOT'];
return [

    'default_resolution_img' => '300x200',

    'main_site_elem' => 'categories',

    'monhts_to_russian'             => [
                                        'Января',
                                        'Февраля',
                                        'Марта',
                                        'Апреля',
                                        'Мая',
                                        'Июня',
                                        'Июля',
                                        'Августа',
                                        'Сентабря',
                                        'Октября',
                                        'Ноября',
                                        'Декабря'
                                    ],

    'cityes'                        => [
                                        'Пятигорск',
                                        'Ессентуки',
                                        'Кисловодск',
                                        'Железноводск'
                                    ],

    'min_image_size_for_upload'       => 100,
    'max_image_size_for_upload'       => 100000000000,

    'full_path_to_dynamic_images'         => $path.'/content/images/',
    'path_to_dynamic_images'              => '/content/images/',

    'path_to_official_images'               => '/img/',

    'second_menu'                       => [],

    'resolutions_folders'               =>  [
                                                'categories' => 'Категории',
                                                'product_photos' => 'Фотографии товаров',
                                                'articles' => 'Статьи'
                                            ],
    'resolutions_parent_folders'        =>  'content/images',
    'path'                              => $path,

    'seo_h1'                            => '',

    'seo_h1_span'                       => '',

    'counter_form'                      => 0,

    'photo'                             => '',

];
