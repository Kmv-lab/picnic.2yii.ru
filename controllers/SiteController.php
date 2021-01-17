<?php

namespace app\controllers;

use app\models\Sitemap;
use app\modules\adm\models\Articles;
use app\modules\adm\models\index_head;
use app\modules\adm\models\Main_page;
use app\traits\BrouserTrait;
use app\traits\SeoHalperTrait;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
use app\commands\PagesHelper;
use yii\web\NotFoundHttpException;
use app\commands\helpers;
/*use app\widgets\Pagination;
use app\modules\adm\controllers\SanatoriumController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\modules\adm\models\StaticSeo;
use app\models\ContactForm;
use app\modules\adm\models\News;
use app\modules\adm\models\Testi;*/

class SiteController extends Controller{

    use SeoHalperTrait;
    use BrouserTrait;

    public function beforeAction($action)
    {
        $this->generatePages();

        $this->getVersion();

        return true;
    }


    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;

        if ($exception !== null) {
            $statusCode = $exception->statusCode;
            $message = $exception->getMessage();
            $this->layout = 'page';

            helpers::createSeo([], 'Ошибка!', 'Ошибка!');
            return $this->render('error', [
                'exception' => $exception,
                'statusCode' => $statusCode,
                'message' => $message
            ]);
        }
        return false;
    }

    public function actionContacts()
    {
        return $this->render('contacts');
    }

    public function actionPage(){

        $this->layout = 'page';
        if(Yii::$app->params['is_mobile'])
            $this->layout = 'pageMobile';

        $currentPage = $this->getPageContent();
        if($currentPage){
            return $this->render('page',['page'=>$currentPage]);
        }

        throw new NotFoundHttpException('Страница не найдена');
    }

    public function actionIndex(){

        $this->layout = 'index';
        if(Yii::$app->params['is_mobile'])
            $this->layout = 'indexMobile';

        $blocks = Main_page::find()->orderBy(['priority' => 'ASC'])->all();
        foreach ($blocks as $key => $block){
            $blocks[$key]->content = helpers::checkForWidgets($block->content);
        }

        $headToIndex = index_head::find()->all();

        foreach ($headToIndex as $elem){
            if($elem['name'] == 'title'){
                Yii::$app->view->title = $elem['value'];
            }else{
                Yii::$app->view->registerMetaTag(['name' => $elem['name'], 'content' => $elem['value']]);
            }
        }

        return $this->render('index',[
            'blocks' => $blocks
        ]);
    }

    public function actionArticles(){

        $articles = Articles::find()->all();

        return $this->render('articles', [
            'articles' => $articles
        ]);
    }

    public function actionArticle($alias){

        $article = Articles::find()->where(['alias' => $alias])->one();

        $newPage = $this->getDynamicPage($article);
        if($newPage){
            Yii::$app->params['pages'][] = $newPage;
        }else{
            throw new NotFoundHttpException('Страница не найдена');
        }

        return $this->render('article', [
            'article' => $article
        ]);
    }

    public function actionSitemap(){
        $sitemap = new Sitemap();
        $arrAliases = $sitemap->getPages();

        return $this->renderPartial('sitemap', compact('arrAliases'));
    }

    /**
     *
     * Генерирует ссылки из БД для создания SiteMap
     *
     *
     * @return array - array url'ы динамических страниц
     */
    private function generateUrls(){
        $db_static_pages = new Sitemap;
        $arrPages = $db_static_pages->getStatickPages();

        $alias[]= Url::home(true);

        foreach ($arrPages as  $key=>$value){
            $alias [] = Url::home(true) . $this->getAliasOnStaticPage($arrPages, $key);
        }

        //для динамических страниц нужно создать правильную структуру в Sitemap->arrayOfDynamicPages

        $db_dynamic_pages = new Sitemap;
        $arrPagesDyn = $db_dynamic_pages->getDynamicPages();
        $arrayRuslDynamicPages = $db_dynamic_pages->arrayOfDynamicPages;

        foreach ($arrPagesDyn as $key=>$pageAliases){
            $firstPartOfUrl = Url::home(true);
            for ($i = 0; $i< count($arrayRuslDynamicPages); $i++){
                if ($key == $arrayRuslDynamicPages[$i]['type']){
                    $firstPartOfUrl .= $arrayRuslDynamicPages[$i]['page'];
                }
            }
            foreach ($pageAliases as $pageAlias){
                $alias [] = $firstPartOfUrl . $pageAlias['alias'] . "/";
            }
        }
        return $alias;
    }

    /**
     *
     * Генерирует ссылки из БД для создания SiteMap
     *
     * @param array
     * @param int
     * @param string/null
     *
     * @return array - array url'ы статических страниц
     */
    private function getAliasOnStaticPage($fullArr, $curId, $alias = NULL){

        if ($fullArr[$curId]['id_parent_page'] != 0){

            $alias = $fullArr[$curId]['page_alias'] . "/" . $alias;
            $alias = $this->getAliasOnStaticPage($fullArr, $fullArr[$curId]['id_parent_page'], $alias);
        }
        else{
            $alias = $fullArr[$curId]['page_alias'] . "/"  . $alias;
        }
        return $alias;

    }

    /*public static function CreateSeo($page = false){
        $urlArr = explode('/',Yii::$app->request->pathInfo);//массив родительских страниц
        array_pop($urlArr);//удаление последнего элемента массива, он пуст.

        if($page !== false && !empty($page)){

            Yii::$app->params['pages'][9999] = [
                'id_page' => 9999,
                'id_parent_page' => 84,
                'page_alias' => $page->alias,
                'page_name' => $page->seo_title,
                'page_menu_name' => $page->name,
                'page_breadcrumbs_name' => $page->name,
                'page_link_title' => $page->name,
                'page_priority' => '100',
                'seo_h1' => $page->seo_h1,
                'seo_h1_span' => '',
                'seo_description' => $page->seo_description,
                'seo_keywords' => $page->seo_keywords,
                'seo_title' => $page->seo_title,
                'is_active' => '1',
                'show_in_menu' => '0',
                'show_childs' => '0',
                'no_del' => '0',
                'no_change_position' => '0',
                'show_sitemap' => '0',
                'file_name' => null
            ];
        }

        /*if(Yii::$app->controller->action->id == 'booking'){
            foreach (Yii::$app->params['pages'][92] as $key => $boockingPageElem){
                Yii::$app->params['pages'][92][$key] = str_replace('NAME_EXC', $page->name, $boockingPageElem);
            }
            Yii::$app->params['pages'][92]['id_parent_page'] = 9999;
        }

        $okURL  = PagesHelper::getPagesInUrl($urlArr);

        if(!$okURL)
            throw new NotFoundHttpException('Страница не найдена');

        $currentPage  =   $okURL[count($okURL)-1];
        //============ Обработка вызовов виджетов в тексте
        helpers::createSeo($currentPage, $currentPage['page_name'], $currentPage['page_name']);
    }*/

    /**
     *
     *
     * @param $idSan int id нужного санатория
     *
     * @return array/false подготовленный массив данных о комнатах
     */
    public function getRoomsDataForSanatorium($idSan){
        $SQL = 'SELECT * FROM `rooms` WHERE `id_san` = :id AND `is_active` = 1';
        $rooms = Yii::$app->dbResort->createCommand($SQL)->bindValues([':id' => $idSan, ] )->queryAll();

        $dataRooms = [];
        foreach ($rooms as $value){

            $minPrice = json_decode($value['price_json'], true);

            $price = "Уточняйте у менеджера";
            $type = 0;

            if ($minPrice){
                if (isset($minPrice[4])){
                    $price = $minPrice[4];
                    $type = 4;
                }
                else{
                    $price = $minPrice[5];
                    $type = 5;
                }
            }

            $text = (isset($value['text']) && $value['text']!='') ? $value['text'] : ((isset($value['short_text']) && $value['short_text']!='') ? $value['short_text'] : 'нет описания' );

            $dataRooms [$value['id_room']] = [
                'name'  => $value['name'],
                'text'  => $text,
                'price' => $price.'руб',
                'type'  => $type,
            ];
            unset($type, $price);
        }

        $arrayOnOwnDbRooms = Rooms::find()->where(['id_room_in_main_table' => array_keys($dataRooms)])->asArray()->all();

        if (!empty($arrayOnOwnDbRooms)){
            foreach ($arrayOnOwnDbRooms as $key=>$value){
                $value['name'] = $dataRooms[$value['id_room_in_main_table']]['name'];
                $value['type'] = $dataRooms[$value['id_room_in_main_table']]['type'];
                $value['price'] = $dataRooms[$value['id_room_in_main_table']]['price'];
                unset($dataRooms[$value['id_room_in_main_table']]);
                $arrayOnOwnDbRooms[$key]=$value;
            }
        }

        $roomsReturns[0] = $dataRooms;
        $roomsReturns[1] = $arrayOnOwnDbRooms;

        return $roomsReturns;
    }

    private function getPageInfo(){
        $urlArr = explode('/',Yii::$app->request->pathInfo);//массив родительских страниц

        array_pop($urlArr);//удаление последнего элемента массива, он пуст.

        $okURL  = PagesHelper::getPagesInUrl($urlArr);
        Yii::$app->params['breadcrumbs'] = PagesHelper::generateBreadcrumbs($okURL);

        $pageParam = [];

        foreach ($okURL as $key=>$value){
            if ($value['page_alias'] == $urlArr[0]){
                $pageParam = $okURL[$key];
            }
        }

        if (empty($pageParam)){
            die("Критическая ошибка. Обезьянки уже трудяться.");
        }

        return $pageParam;
    }

    /**
     *
     * @param $dataRooms array массив данных о комнатах, для получения названия комнат
     * @param $type int Тип проживания(4 - Проживание и питание, 5 - Проживание, питание и лечение)
     *
     * @return array - подготовленный массив данных о ценах на комнаты[0] и временные переоды[1] колличество
     */
    /*private function getPricesForSanatorium($dataRooms, $type=5){
        $dataRoomsIds = [];

        if( isset($dataRooms[0])){
            foreach ($dataRooms as $key1=>$value){
                foreach ($value as $key2=>$room){
                    $dataRoomsIds[] = isset($room['id_room_in_main_table']) ? $room['id_room_in_main_table'] : $key2;
                    if (isset($room['id_room_in_main_table'])){
                        $dataRooms[0][$room['id_room_in_main_table']] = $dataRooms[$key1][$key2];
                    }
                }
            }
        }
        else{
            foreach ($dataRooms as $key=>$value){
                $dataRoomsIds[] = $key;
            }
        }

        $dataRooms = $dataRooms[0];

        $SQL = "SELECT `id_room`, `id_when`, `main`, `add`, `alone`
                FROM `room_price` 
                WHERE `id_room` IN (". implode(',', $dataRoomsIds) .") AND `year_start` = 0 AND `year_end` = 0 AND `type` = :type_room";
        $prices = Yii::$app->dbResort->createCommand($SQL)->bindValues([':type_room' => $type])->queryAll();

        //vd($prices);

        $uniquePriceTime = [];

        foreach ($prices as $key=>$price){
            $uniquePriceTime[] = $price['id_when'];
        }

        $uniquePriceTime = array_unique($uniquePriceTime);
        sort($uniquePriceTime);
        $SQL = 'SELECT `start`, `end` FROM `sans_price_time` WHERE `id_time` = :id;';
        for ($i=0; $i<count($uniquePriceTime); $i++){
            $timePrice[$uniquePriceTime[$i]] = Yii::$app->dbResort->createCommand($SQL)->bindValues([':id' => $uniquePriceTime[$i], ] )->queryOne();
        }

        $preparedPrices = [];
        $i = 0;


        foreach ($prices as $price){
            foreach ($timePrice as $key=>$value){
                if ($price['id_when']==$key){
                    $preparedPrices[$i] = array_merge($value, $price);
                }
            }
            $preparedPrices[$i] = array_merge($preparedPrices[$i],['name' => $dataRooms[$price['id_room']]['name']]);
            $i++;
        }

        foreach ($preparedPrices as $key => $value){
            $idRooms[$key] = $value['id_room'];
            $idStart[$key] = $value['start'];
        }

        if (empty($preparedPrices)){
            return false;
        }
        array_multisort($idRooms, SORT_ASC, $idStart, SORT_ASC, $preparedPrices);


        $data[0] = $preparedPrices;
        $data[1] = count($uniquePriceTime);

        return $data;
    }*/

}
