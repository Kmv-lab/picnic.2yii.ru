<?php
namespace app\models;
use app\modules\adm\models\Products;
use Yii;
use yii\base\Model;
use app\commands\PagesHelper;
use yii\helpers\Url;

class Sitemap extends Model{

    public function getStatickPages(){

    }

    public function getPages(){
        $dynamicPasges = $this->getDynamicPages();

        $pages = [];
        foreach (Yii::$app->params['pages'] as $page){
            $pages[] = $page['url'];
        }

        return array_merge($pages, $dynamicPasges);
    }

    public function getDynamicPages(){

        $pagesProducts = $this->getProductsPages();

        $articles = $this->getArticlesPages();

        return array_merge($pagesProducts, $articles);
    }

    public function getProductsPages(){
        $SQL = 'SELECT * FROM products WHERE is_active = 1';
        $products = Yii::$app->db->createCommand($SQL)->queryAll();

        $returnedPages = [];
        foreach ($products as $product){
            $returnedPages[] = Yii::$app->params['pages']['m'.$product['category_id']]['url'].'/'.$product['alias'];
        }

        return $returnedPages;
    }

    public function getArticlesPages(){
        $SQL = 'SELECT * FROM articles WHERE is_active = 1';
        $articles = Yii::$app->db->createCommand($SQL)->queryAll();

        $returnedPages = [];
        foreach ($articles as $article){
            $returnedPages[] = Yii::$app->params['pages']['p7']['alias'].'/'.$article['alias'];
        }

        return $returnedPages;
    }

}