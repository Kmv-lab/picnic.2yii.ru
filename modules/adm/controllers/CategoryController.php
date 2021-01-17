<?php


namespace app\modules\adm\controllers;

use app\commands\ImagickHelper;
use app\modules\adm\models\AttributesOfCat;
use app\modules\adm\models\Block;
use app\modules\adm\models\Categories;
use app\modules\adm\models\Products;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use app\traits\ImageTrait;

class CategoryController extends Controller
{

    //use ImageTrait;

    public $title = 'Категории';

    public function actionIndex($param = false){

        //vd($this->getResolutionOfImage('category'));

        //$categoryes = Categories::find()->all();

        Yii::$app->params['H1'] = $this->title = 'Категории товаров';

        $dataProvider = new ActiveDataProvider([
            'query' => Categories::find(),
        ]);

        $dataProvider->sort->defaultOrder = ['name' => SORT_ASC];

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'param' => $param
        ]);
    }

    public function actionNew()
    {
        Yii::$app->params['H1'] = $this->title = 'Добавление новой категории';
        $this->createSEO('New');

        $newCategory = new Categories();

        if(Yii::$app->request->isPost){
            if($newCategory->load(Yii::$app->request->post())){
                if($file = $newCategory->upload('img')){
                    $newCategory->img = $file;

                    $newCategory->createThumbOfImage($newCategory, 'img', $newCategory->getResolutionOfImage($newCategory->tableName()));
                }
                if($newCategory->save()){
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('new', [
            'model' => $newCategory,
            'possibleParents' => $this->getPossibleParents(),
            'additionalBlocks' => $this->getBlocks()
        ]);
    }

    public function actionAjaxcreatethumb($id, $name, $model=null){

        $this->enableCsrfValidation = false;

        if(!$model){
            $model = Categories::findOne($id);
        }

        if($name === 'undefined')
            $name = 'img';

        return json_encode(ImagickHelper::Thumb($_POST, $model, $name));
    }

    /*public function createThumbOfImage($model, $key, $resolutions = false){
        //$resolutions = explode("x", Yii::$app->params[$resolution]);
        if(!$resolutions){
            $resolution[0] = Yii::$app->params['default_resolution_img'];
        }

        foreach ($resolutions as $resolution){
            $resolutionNow = explode("x", $resolution);

            $post = [
                'id' => $model->id,
                'x1' => '0',
                'y1' => '0',
                'x2' => $resolutionNow[0],
                'y2' => $resolutionNow[1],
                'r' => $resolution
            ];

            ImagickHelper::Thumb($post, $model, $key);
        }

    }*/

    public function actionUpdate($idCat)
    {
        $category = Categories::findOne($idCat);
        $this->createSEO('Update',$category);

        if(Yii::$app->request->isPost){
            if($category->load(Yii::$app->request->post())){
                if ($file = $category->upload('img')){
                    $category->img = $file;
                    $category->createThumbOfImage($category, 'img', $category->getResolutionOfImage($category->tableName()));
                }
                else{
                    $category->img = $category->oldAttributes['img'];
                }

                if($category->save()){
                    //return $this->redirect(['index']);
                }
            }
        }

        $attributesElems = AttributesOfCat::find()->where(['category_id' => $category->id])->all();

        return $this->render('update', [
            'model' => $category,
            'possibleParents' => $this->getPossibleParents($idCat),
            'attributesElems' => $attributesElems,
            'additionalBlocks' => $this->getBlocks()
        ]);
    }
    
    
    public function actionAjaxaddattribute()
    {
        return $this->renderAjax('ajaxNewAttr', [
            'model' => false
        ]);
    }

    public function actionAjaxsaveattr($name, $unit, $forFilter, $idCat, $isNew=true, $id=false)
    {

        if($name && $idCat){
            if($isNew){
                $attrCategory = new AttributesOfCat();
            }
            else{
                $attrCategory = AttributesOfCat::find()->where(['id' => $id])->one();
            }

            $attrCategory->name = $name;
            $attrCategory->unit = $unit;
            $attrCategory->for_filter = $forFilter;
            $attrCategory->category_id = $idCat;

            if($attrCategory->save()){
                $return = $attrCategory->attributes;

                return json_encode($return);
            }

        }

        return false;
    }

    public function actionDelete($idCat){
        $product = Products::find()->where(['category_id' => $idCat])->all();
        if(!empty($product)){
            return $this->redirect(['index', 'param' => 'notDelete']);
        }

        $attrCategory = AttributesOfCat::find()->where(['category_id' => $idCat])->all();
        foreach ($attrCategory as $attr){
            $attr->delete();
        }
        $category = Categories::findOne($idCat);
        $category->delete();

        return $this->redirect(['index']);
    }

    public function actionAjaxdeleteattr($id)
    {
        if($id){
            $model = AttributesOfCat::findOne($id);

            if(!empty($model)){
                $model->delete();
            }
        }
    }

    public function getPossibleParents($id = 0){
        $possibleParents = Categories::find()->where(['!=', 'id', $id])->andWhere(['is_active' => 1])->all();

        $arrPosPar = [];
        $children = [];
        foreach ($possibleParents as $parent){
            //if($parent->parent_id)
            //TODO сделать передачу нормальную, для реализации вложенности родительских категорий
            /**
             *<select id="page-id_parent_page" class="form-control" name="Page[id_parent_page]" aria-required="true">
<option value="0" selected="">Главная</option>
<option value="1" disabled="">|— Информация</option>
<option value="2">|—— Как сделать заказ</option>
<option value="3">|—— Оплата и доставка</option>
<option value="4">|—— Наше производство</option>
<option value="5">|— Контакты</option>
<option value="7">|— Статьи</option>
</select> 
Мало того убрать дурацкую надпись "родительская категория не выбрана" она равняется 0 по умолчанию и называется наприме "Каталог"
             **/
            $arrPosPar[$parent->id] = $parent->name;
        }

        return $arrPosPar;
    }

    public function getBlocks(){
        $blocks = Block::find()->all();
        $blocksArray = [];
        foreach ($blocks as $block){
            $blocksArray[$block->id_block] = $block->block_name;
        }
        return $blocksArray;
    }

/**
*   Функция SEO для удобства редактирования должна быть в одном месте
*/
    public function createSEO($action,$var1='',$var2=''){
        $dopMenu[] = ['name'=>'Все категории','url'=>Url::toRoute(['category/index'])];
        switch($action){
            case 'New':
                $this->title = '➕ Добавление новой категории товаров';
                Yii::$app->params['H1'] = 'Добавление новой категории товаров';
            break;
            case 'Update':
                $this->title = '✔ «'.$var1->name.'» редактирование категории';
                Yii::$app->params['H1'] = 'Редактирование категории «'.$var1->name.'»';
                $dopMenu[] = ['name'=>'Добавить категорию','url'=>Url::toRoute(['category/new'])];
            break;
        }
        
        Yii::$app->params['dopMenu'] = $dopMenu;
    }
}