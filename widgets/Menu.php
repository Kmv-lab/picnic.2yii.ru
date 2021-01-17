<?php

namespace app\widgets;

use app\commands\helpers;
use app\modules\adm\models\Categories;
use Yii;
use yii\base\Widget;
use app\commands\PagesHelper;

class Menu extends Widget
{
    public $footer = false;

    public function run()
    {
        /*$urlArr = explode('/',Yii::$app->request->pathInfo);
        unset($urlArr[count($urlArr)-1]);
        $okURL  = PagesHelper::getPagesInUrl($urlArr);*/

        //TODO вывод меню
        /*$pages = $this->getAllChildPages(0);

        end(Yii::$app->params['pages']);
        $lastKey = key(Yii::$app->params['pages'])+1;

        $categories = PagesHelper::getMainElemSite();
        $categoriesToMenu = [];
        foreach ($categories as $category){
            $categoriesToMenu[] = [
                'id_page' => $lastKey++,
                'alias' => 'shop/'.$category['alias'],
                'label' => $category['name'],
                'page_link_title' => '',
                'items' => []
            ];
        }
        $pages = array_merge($pages, $categoriesToMenu);*/

        $pages = Yii::$app->params['pages'];

        return $this->render('menu', [
            'pages'=>$pages,
            //'okURL'=>$okURL,
            'isFooter' => $this->footer
            ]);
    }

    function getAllChildPages($id=0){

        $return = [];
        $pages  =   PagesHelper::select(Yii::$app->params['pages'],
            [],
            [
                ['attr_name'   =>  'id_parent_page',   'operand'=> '=', 'value'=> $id ],
                ['attr_name'   =>  'show_in_menu',     'operand'=> '=', 'value'=> '1'],
                ['attr_name'   =>  'is_active',        'operand'=> '=', 'value'=> '1' ],
            ],
            ['attr_name'   =>  'page_priority', 'sort_type'=>'ASC']
        );
        foreach ($pages as $key=>$page){
            $return[$key] = ['label'=> $page['page_menu_name'], 'alias'=>$page['page_alias'],
                'page_link_title'=>$page['page_link_title'], 'id_page'=>$page['id_page'],];
            $return[$key]['items'] = self::getAllChildPages($key);
        }

        return $return;
    }

    function generateLi(){

    }
}