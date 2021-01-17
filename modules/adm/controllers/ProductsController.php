<?php


namespace app\modules\adm\controllers;


use app\commands\helpers;
use app\commands\ImagickHelper;
use app\modules\adm\models\AttributesOfCat;
use app\modules\adm\models\Categories;
use app\modules\adm\models\ProductPhotos;
use app\modules\adm\models\Products;
use app\modules\adm\models\ProductVariationAttribute;
use app\modules\adm\models\VariationsOfProduct;
use yii\helpers\Html;use yii\helpers\Url;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\UploadedFile;

class ProductsController extends Controller
{

    public $title = 'Товары';

    public function actionIndex()
    {
        Yii::$app->params['H1'] = $this->title = 'Товары';

        $dataProvider = new ActiveDataProvider([
            'query' => Products::find(),
        ]);

        $dataProvider->sort->defaultOrder = ['name' => SORT_ASC];

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionNew()
    {
        Yii::$app->params['H1'] = $this->title = 'Добавление нового товара';

        $newProduct = new Products();

        $categories = $this->getCategoriesAsArray();

        if(Yii::$app->request->isPost){
            if($newProduct->load(Yii::$app->request->post())){
                $newProduct->is_active = 0;
                if($newProduct->save()){
                    return $this->redirect(['update', 'idProduct'=>$newProduct->id]);
                }
            }
        }

        return $this->render('new', [
            'model' => $newProduct,
            'categories' => $categories
        ]);
    }

    public function actionUpdate($idProduct)
    {

        Yii::$app->params['H1'] = $this->title = 'Редактирование товара';
        $showMessage = false;

        $product = Products::findOne($idProduct);
        $this->createSEO('Update',$product);

        if(Yii::$app->request->isPost){
            if($product->load(Yii::$app->request->post())){
                if(!helpers::checkProdVar($idProduct, false)){
                    if($product->is_active == 1){
                        $showMessage = true;
                        $product->is_active=0;
                    }
                }

                if($product->save()){
                    //return $this->redirect(['index']);
                }
            }
        }

        $attribetes = AttributesOfCat::find()->where(['category_id' => $product->category_id])->all();

        $variations = VariationsOfProduct::find()->where(['prod_id' => $product->id])->asArray()->all();

        foreach ($variations as $key => $variation){
            //$attrsVals = ProductVariationAttribute::find()->where(['prod_var_id' => $variation['id']])->asArray()->all();
            $attrOfVar = [];
            $attrs = AttributesOfCat::find()->where(['category_id' => $product->category_id])->asArray()->all();
            foreach ($attrs as $key2 => $attr){

                $valAttr = ProductVariationAttribute::find()->where(['prod_var_id' => $variation['id'], 'cat_attr_id' => $attr['id']])->one();

                $attrOfVar[$key2] = [
                    'id_val' => !empty($valAttr) ? $valAttr['id'] : $this->addNewProdVarAttr($variation['id'], $attr['id']),
                    'id_attr' => $attr['id'],
                    'value' => !empty($valAttr) ? $valAttr['value'] : '',
                    'name' => $attr['name'],
                    'unit' => $attr['unit']
                ];
            }
            /*foreach ($attrsVals as $key2 => $attrVal){
                $attr = AttributesOfCat::find()->where(['id' => $attrVal['cat_attr_id']])->asArray()->one();
                $attrOfVar[$key2] = array_merge($attrVal, $attr);
                $attrOfVar[$key2]['id_attr'] = $attr['id'];
                $attrOfVar[$key2]['id_val'] = $attrVal['id'];

                vd($attrOfVar);
            }*/
            $variations[$key]['attr'] = $attrOfVar;
        }//TODO Переписать на sql-запросе

        Yii::$app->params['dopMenu'][] = [
            'name' => 'Редактировать фотографии',
            'url' => Url::toRoute(['products/photos', 'idProd' => $product->id])
        ];

        $categories = $this->getCategoriesAsArray();

        return $this->render('update', [
            'model' => $product,
            'categories' => $categories,
            'attributes' => $attribetes,
            'variations' => $variations,
            'showMessage' => $showMessage
        ]);
    }

    public function actionPhotos($idProd)
    {
        $model = new ProductPhotos();
        $uploadSuccess = false;

        $product = Products::findOne($idProd);

        $this->title = 'Редактирование фотографий';
        Yii::$app->params['H1'] = 'Редактирование фотографий для '.$product->name;
        Yii::$app->params['dopMenu'][] = [
            'name' => 'Вернуться к товару',
            'url' => Url::toRoute(['products/update', 'idProduct' => $idProd])
        ];


        if (Yii::$app->request->isPost) {
            $model->name = UploadedFile::getInstances($model, 'name');
            if ($filesName = $model->upload()) {
                $uploadSuccess = true;

                foreach ($filesName as $value){
                    $modelForUpload = new ProductPhotos();
                    $modelForUpload->id_product = $idProd;
                    $modelForUpload->name = $value;


                    $modelForUpload->save(false);

                    $modelForUpload->createThumbOfImage($modelForUpload, 'name', $modelForUpload->getResolutionOfImage());
                }
            }
        }

        $photos = ProductPhotos::find()->where(['id_product' => $idProd])->all();

        return $this->render('photos', [
            'photos' => $photos,
            'idProd' => $idProd,
            'model' => $model,
            'uploadSuccess' => $uploadSuccess,
        ]);
    }


    public function actionAjaxcreatethumb($id, $name, $model=null){

        $this->enableCsrfValidation = false;

        $post = Yii::$app->request->post();

        if(!$model){
            $model = ProductPhotos::find()->where(['id' => $post['id']])->one();
        }

        if($name === 'undefined')
            $name = 'name';

        return json_encode(ImagickHelper::Thumb($post, $model, $name));
    }

    public function actionDelete_photo($idProd, $idPhoto){
        $model = ProductPhotos::findOne($idPhoto);
        $model->deleteOldPhoto('name');
        $model->delete();

        return $this->redirect(['photos', 'idProd' => $idProd]);
    }

    public function actionAjaxsavevar($data)
    {
        $result = json_decode($data, true);
        $returnArray = [];

        if(isset($result['variationsId']) && $result['variationsId']){
            $prodVar = VariationsOfProduct::find()->where(['id' => $result['variationsId']])->one();
        }else{
            $prodVar = new VariationsOfProduct();
        }

        $prodVar->name = $result['name'];
        $prodVar->price = $result['price'];
        $prodVar->prod_id = $result['prodId'];
        $prodVar->is_active = (integer) $result['isActive'];

        if($prodVar->save()){
            foreach ($result['attr'] as $key => $attr) {
                $catAttrId = (integer)str_replace('attr-', '', $key);

                if(isset($attr['valueID']) && $attr['valueID']){
                    $attributeOfProd = ProductVariationAttribute::find()->where(['id' => $attr['valueID']])->one();
                }else{
                    $attributeOfProd = new ProductVariationAttribute();
                }

                $attributeOfProd->value = $attr['value'];
                $attributeOfProd->cat_attr_id = $catAttrId;
                $attributeOfProd->prod_var_id = $prodVar->id;
                if ($attributeOfProd->save()) {
                    $returnArray['attr'][$key] = $attributeOfProd->id;
                }
            }
        }
        else{
            $returnArray['errors'] = $prodVar->errors;
        }

        if(!isset($returnArray['errors'])){
            $returnArray['id_var'] = $prodVar->id;
        }

        return json_encode($returnArray);
    }

    public function getCategoriesAsArray(){
        $categories = Categories::find()->all();

        $categoriesArray = [];

        foreach ($categories as $category){
            $categoriesArray[$category->id] = $category->name;
        }

        return $categoriesArray;
    }

    public function actionDelete($idProd)
    {
        $varsProd = VariationsOfProduct::find()->where(['prod_id' => $idProd])->all();
        foreach ($varsProd as $varProd){
            $attrsVarProd = ProductVariationAttribute::find()->where(['prod_var_id' => $varProd->id])->all();
            foreach ($attrsVarProd as $attribute){
                $attribute->delete();
            }
            $varProd->delete();
        }

        $prodPhotos = ProductPhotos::find()->where(['id_product' => $idProd])->all();
        foreach ($prodPhotos as $photo){
            $photo->deleteOldPhoto('name');
            $photo->delete();
        }
        $prod = Products::findOne($idProd);
        $prod->delete();

        return $this->redirect(Url::toRoute(['products/index']));

    }

    public function actionAjaxgetemptyvar($categoryId)
    {

        $attribetes = AttributesOfCat::find()->where(['category_id' => $categoryId])->all();

        return $this->renderAjax('emptyVar', [
            'attributes' => $attribetes,
            'model' => false
        ]);
    }

    public function actionAjaxdeletevar($varId)
    {
        $variationProd = VariationsOfProduct::findOne($varId);
        $attributesVarProd = ProductVariationAttribute::find()->where(['prod_var_id' => $varId])->all();
        $error = false;

        foreach ($attributesVarProd as $attr){
            if(!$attr->delete())
                $error = true;
        }

        if(!$variationProd->delete())
            $error = true;

        if($error)
            return json_encode(true);

        return json_encode(false);
    }

    /**
     *   Функция SEO для удобства редактирования должна быть в одном месте
     */
    public function createSEO($action,$var1='',$var2='')
    {
        $dopMenu[] = ['name' => 'Все товары', 'url' => Url::toRoute(['products/index'])];
        switch ($action) {
            case 'New':
                $this->title = '➕ Добавление нового товара';
                Yii::$app->params['H1'] = 'Добавление нового товара';
                break;
            case 'Update':
                $this->title = '✔ «' . $var1->name . '» редактирование товара';
                Yii::$app->params['H1'] = 'Редактирование товара «' . $var1->name . '»';
                $dopMenu[] = ['name' => 'Добавить товар', 'url' => Url::toRoute(['products/new'])];
                break;
        }

        Yii::$app->params['dopMenu'] = $dopMenu;
    }

    private function addNewProdVarAttr($prodVarId, $catAttrId)
    {
        $newProdVarAttr = new ProductVariationAttribute();

        $newProdVarAttr->prod_var_id = $prodVarId;
        $newProdVarAttr->cat_attr_id = $catAttrId;

        $newProdVarAttr->save();

        return $newProdVarAttr->id;
    }

}