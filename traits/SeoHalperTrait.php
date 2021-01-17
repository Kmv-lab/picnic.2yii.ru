<?php


namespace app\traits;


use app\commands\helpers;
use app\modules\adm\models\Block;
use Yii;
use yii\base\Event;

trait SeoHalperTrait
{
    public function getMaxCountSeo($val){
        switch ($val){
            case 'h1':
            case 'title':
                return 125;
                break;
            case 'keywords':
            case 'description':
                return 255;
                break;
        }

        return false;
    }

    public function generatePages(){
        $urlArr = explode('/', Yii::$app->request->pathInfo);
        array_pop($urlArr);//удаление последнего элемента массива, он пуст.

        $pageArray = $this->generateAllVariationsPage($urlArr);
        Yii::$app->params['pages'] = $pageArray;
        $alias = array_pop($urlArr);

        Yii::$app->params['curPage'] = $alias;

        Event::on(\yii\web\View::className(), \yii\web\View::EVENT_BEFORE_RENDER, function($event) {
            if(!isset(Yii::$app->params['stopGenerateSeo'])){
                $this->generateSeo();
                $this->addToHeader();

                Yii::$app->params['stopGenerateSeo'] = true;
            }

            return true;
        });

        return true;
    }

    public function addToHeader(){
        $addToHead = Block::find()->where(['id_block' => 30])->one();

        Yii::$app->params['addToHead'] = $addToHead->block_content;
    }

    public function generateSeo(){

        foreach (Yii::$app->params['pages'] as $page){
            if($page['alias'] == Yii::$app->params['curPage']){

                $this->createSEO($page);

                Yii::$app->params['breadcrumbs'] = $this->generateBreadcrumbs(Yii::$app->params['curPage'], Yii::$app->params['pages']);

                $lastElem = array_pop(Yii::$app->params['breadcrumbs']);
                unset($lastElem['url']);
                Yii::$app->params['breadcrumbs'][] = $lastElem;
            }
        }

        return true;
    }

    public function getParentIdByAlias($alias){

        $parent = false;
        foreach (Yii::$app->params['pages'] as $id => $page){
            if($page['alias'] == $alias){
                $parent = $id;
            }
        }

        return $parent;
    }

    public function getDynamicPage($paramThisPage){

        if(empty($paramThisPage))
            return false;

        $urlArr = explode('/', Yii::$app->request->pathInfo);
        array_pop($urlArr);
        $aliasThisPage = array_pop($urlArr);
        $aliasParent = array_pop($urlArr);

        $parentId = $this->getParentIdByAlias($aliasParent);

        $newArray['name'] = $paramThisPage['name'];
        if($aliasThisPage == $paramThisPage['alias']){
            $newArray['alias'] = $aliasThisPage;
        }else{
            return false;
        }
        $newArray['url'] = '/'.Yii::$app->params['pages'][$parentId]['url'].'/'.$paramThisPage['alias'];
        $newArray['parent_id'] = $parentId;
        $newArray['seo_h1'] = (isset($paramThisPage['seo_h1']) && !empty($paramThisPage['seo_h1'])) ? $paramThisPage['seo_h1'] : $paramThisPage['name'];
        $newArray['seo_title'] = $paramThisPage['seo_title'];
        $newArray['seo_description'] = $paramThisPage['seo_description'];
        $newArray['seo_keywords'] = $paramThisPage['seo_keywords'];
        $newArray['in_menu'] = 0;

        return $newArray;
    }

    public function getPageContent(){
        $alias = Yii::$app->params['curPage'];

        $SQL = 'SELECT page_content FROM pages WHERE page_alias = :alias';
        $currentPage = Yii::$app->db->createCommand($SQL)->bindValue(':alias', $alias)->queryOne();

        if(empty($currentPage)){
            return false;
        }

        $currentPage['page_content'] = helpers::checkForWidgets($currentPage['page_content']);

        return $currentPage;
    }

    public function generateBreadcrumbs($alias, $pageArray, $id = null){
        $breadcrumbs=[];
        if($id){
            if($pageArray[$id]['parent_id']){
                $breadcrumbs = $this->generateBreadcrumbs('', $pageArray, $pageArray[$id]['parent_id']);
            }
            $breadcrumbs[] = [
                'url' => '/'.$pageArray[$id]['url'],
                'label' => $pageArray[$id]['name']
            ];
        }else{
            foreach ($pageArray as $page){
                if($alias == $page['alias']){
                    if($page['parent_id']){
                        $breadcrumbs = $this->generateBreadcrumbs('', $pageArray, $page['parent_id']);
                    }
                    $breadcrumbs[] = [
                        'url' => $page['alias'],
                        'label' => $page['name']
                    ];

                }
            }
        }

        return $breadcrumbs;
    }

    public function generateAllVariationsPage($urlArr){

        $SQL = 'SELECT * FROM `pages` WHERE `is_active` = 1';
        $elems = Yii::$app->db->createCommand($SQL)->queryAll();

        $mainElems = $this->getMainElemSite();

        $pages = $this->generatePagesArray($elems, $mainElems);

        /*if(count($urlArr) == 3 && $urlArr[0] == 'shop'){
            $pages[] = $this->getProductPage(array_pop($urlArr), $pages);
        }*/

        return $pages;
    }

    public function getMainElemSite(){
        $dbName = Yii::$app->params['main_site_elem'];

        $SQL = 'SELECT * FROM '.$dbName.' WHERE `is_active` = 1';
        $elems = Yii::$app->db->createCommand($SQL)->queryAll();

        foreach ($elems as $key => $elem){
            if (!helpers::checkProdVar(false, $elem['id'])){
                unset($elems[$key]);
            }
        }

        return $elems;
    }

    public function generatePagesArray($arr1, $arr2, $add1 = 'p', $add2 = 'm'){

        for($i = 1; $i<3; $i++){
            $var = 'arr'.$i;
            $add = 'add'.$i;
            $$var = $this->prepareArray($$var, $$add);
        }

        return array_merge($arr2, $arr1);
    }

    //TODO На новом проэкте переделать категории и страницы привести к общему виду. И переделать метод

    public function prepareArray($arr, $add){
        $newArray = [];
        if(!empty($arr)){
            foreach ($arr as $key => $elem){
                if(isset($elem['parent_id'])){
                    if($elem['parent_id'])
                        $elem['parent_id'] = $add.$elem['parent_id'];
                }elseif(isset($elem['id_parent_page'])){
                    if($elem['id_parent_page'])
                        $elem['parent_id'] = $add.$elem['id_parent_page'];
                }

                if(isset($elem['id'])){
                    $newArray[$add.$elem['id']]['name'] = $elem['name'];
                    $newArray[$add.$elem['id']]['alias'] = $elem['alias'];
                    $newArray[$add.$elem['id']]['url'] = 'shop/'.$elem['alias'];
                    $newArray[$add.$elem['id']]['parent_id'] = $elem['parent_id'];
                    $newArray[$add.$elem['id']]['seo_h1'] = $elem['name'];
                    $newArray[$add.$elem['id']]['seo_title'] = $elem['seo_title'];
                    $newArray[$add.$elem['id']]['seo_description'] = $elem['seo_description'];
                    $newArray[$add.$elem['id']]['seo_keywords'] = $elem['seo_keywords'];
                    $newArray[$add.$elem['id']]['in_menu'] = $elem['in_menu'];
                }elseif(isset($elem['id_page'])){
                    $newArray[$add.$elem['id_page']]['name'] = $elem['page_name'];
                    $newArray[$add.$elem['id_page']]['alias'] = $elem['page_alias'];
                    //$newArray[$add.$elem['id_page']]['url'] = $elem['page_alias'];
                    $newArray[$add.$elem['id_page']]['parent_id'] = isset($elem['parent_id']) ? $elem['parent_id'] : '' ;
                    $newArray[$add.$elem['id_page']]['seo_h1'] = $elem['seo_h1'];
                    $newArray[$add.$elem['id_page']]['seo_title'] = $elem['seo_title'];
                    $newArray[$add.$elem['id_page']]['seo_description'] = $elem['seo_description'];
                    $newArray[$add.$elem['id_page']]['seo_keywords'] = $elem['seo_keywords'];
                    $newArray[$add.$elem['id_page']]['in_menu'] = $elem['show_in_menu'];
                    if(isset($elem['parent_id']) && $elem['parent_id']){
                        //TODO на новом проэкте решить эту проблему. Суть: нужно подставлять alias родителя, учитывая, что он ещё может быть не создан и может быть уровень вложенности более 2!
                        $newArray[$add.$elem['id_page']]['url'] = 'informatsiya/'.$elem['page_alias'];
                    }else{
                        $newArray[$add.$elem['id_page']]['url'] = $elem['page_alias'];
                    }
                }
            }
        }

        return $newArray;
    }

    /**
     * @param array $model
     * @param string $defTitle
     * @param string $defH1
     * @param string $defDescription
     * @param string $defKeywords
     * @return boolean
     */
    public function createSEO($model=[],$defTitle='',$defH1='',$defDescription='',$defKeywords='')
    {
        //общее название сайта из параметров
        $CommonTitle = Yii::$app->params['common_title'];

        if ( !empty($model)  )
        {
            $seo_title         = empty ($model['seo_title'])       ? $CommonTitle.$defTitle    :   $CommonTitle.$model['seo_title'] ;
            $seo_description   = empty ($model['seo_description']) ? $defDescription           :   $model['seo_description'];
            $seo_keywords      = empty ($model['seo_keywords'])    ? $defKeywords              :   $model['seo_keywords'];
            $seo_h1            = empty ($model['seo_h1'])          ? $model['name']            :   $model['seo_h1'];
            Yii::$app->params['seo_h1_span'] = empty ($model['seo_h1_span']) ? '' : $model['seo_h1_span'];
        }
        else
        {
            $seo_title         = $defTitle.$CommonTitle ;
            $seo_description   = $defDescription;
            $seo_keywords      = $defKeywords;
            $seo_h1            = !empty($defH1)   ?   $defH1   : '';
        }
        $seo_h1 = str_replace('««','«',$seo_h1);
        $seo_h1 = str_replace('»»','»',$seo_h1);
        $seo_title = str_replace('««','«',$seo_title);
        $seo_title = str_replace('»»','»',$seo_title);

        $seo_description   = htmlspecialchars($seo_description);
        $seo_keywords      = htmlspecialchars($seo_keywords);
        Yii::$app->view->title = $seo_title;
        Yii::$app->params['seo_h1'] = $seo_h1;
        Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => $seo_keywords]);
        Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => $seo_description]);


        return true;
    }


}