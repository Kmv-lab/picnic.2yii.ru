<?php


namespace app\controllers;


use app\modules\adm\models\Categories;
use app\modules\adm\models\ProductPhotos;
use app\modules\adm\models\VariationsOfProduct;
use app\traits\BrouserTrait;
use app\traits\SeoHalperTrait;
use app\widgets\Products;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ShopController extends Controller
{
    use SeoHalperTrait;
    use BrouserTrait;

    public $idCategory;
    public $filterData;
    public $filterMinPrice;
    public $filterMaxPrice;
    public $quantity = 15;
    public $sort = "popular-ASC";
    public $suitableVariations = false;

    public function beforeAction($action)
    {
        $this->generatePages();

        $this->getVersion();

        return true;
    }

    public function actionCategory($category)
    {

        $categoryModel = Categories::find()->where(['alias' => $category])->one();
        if(empty($categoryModel)){
            throw new NotFoundHttpException('Страница не найдена');
        }
        $this->idCategory = $categoryModel->id;

        if($this->generateProductsList()){
            return $this->render('category_filters', [
                'category' => $categoryModel,
                'filterData' => $this->filterData
            ]);
        }
        throw new NotFoundHttpException('Страница не найдена');
    }

    public function actionProduct($category, $alias)
    {

        $product = \app\modules\adm\models\Products::find()->where(['alias' => $alias])->one();
        $newPage = $this->getDynamicPage($product);
        if(!$newPage || empty($product))
            throw new NotFoundHttpException('Страница не найдена');

        $photos = ProductPhotos::find()->where(['id_product' => $product->id])->all();
        $variations = VariationsOfProduct::find()->where(['prod_id' => $product->id, 'is_active' => 1])->orderBy(['name' => 'ASC'])->all();

        Yii::$app->params['pages'][] = $newPage;

        $variationsArray = [];
        foreach ($variations as $variation){
            $variationsArray[$variation->id] = [
                'name' => $variation->name,
                'price' => $variation->price,
                'id' => $variation->id
            ];

            $prodVarAttr = (new Query())
                ->select([
                    'product_variation_attribute.value',
                    'product_variation_attribute.id',
                    'categories_attributes.name',
                    'categories_attributes.unit'
                ])
                ->from('product_variation_attribute')
                ->join('INNER JOIN', 'categories_attributes', 'categories_attributes.id = product_variation_attribute.cat_attr_id')
                ->where(['product_variation_attribute.prod_var_id' => $variation->id])
                ->orderBy(['categories_attributes.name' => 'ASC'])
                ->all();

            $variationsArray[$variation->id]['attr'] = $prodVarAttr;
        }

        return $this->render('product', [
            'product' => $product,
            'photos' => $photos,
            'variations' => $variationsArray
        ]);

    }

    public function actionAjaxfilter()
    {
        //TODO Если в значении фильтра указана запитая - всё ломается!!
        $get = (array) json_decode(Yii::$app->request->get()['data']);

        $this->idCategory = $get['idCat'];
        unset($get['idCat']);
        $this->filterMaxPrice = $get['max'];
        unset($get['max']);
        $this->filterMinPrice = $get['min'];
        unset($get['min']);
        $this->sort = $get['sort'];
        unset($get['sort']);
        $this->quantity = $get['quantity'];
        unset($get['quantity']);

        //выбрасываем пустые значения
        foreach ($get as $key => $param){
            if(empty($param))
                unset($get[$key]);
        }

        if(!$this->generateProductsList($get)){//TODO false
            if(isset(Yii::$app->params['products']) && (Yii::$app->params['products'] === [])){
                return json_encode(['status' => false]);
            }
        }


        $productsWidget = new Products(['category' => $this->idCategory, 'products' => Yii::$app->params['products']]);

        $returns = $productsWidget->run();

        return json_encode(['newElems' => $returns, 'filter' => $this->filterData]);
    }


    /*
     * @params Array - params for filter
     *
     * @return bool
     * */
    public function generateProductsList($params = null){

        $idProds = false;
        if($this->paramsNotSet($params)){
            $idProds = $this->getProdsFromParam($params);
        }

        if($idProds === []){
            Yii::$app->params['products'] = [];
            return false;
        }

        if($this->getProductsArray($this->idCategory, $idProds)){
            if($this->getFiltersData()){
                $this->getRequiredQuantityOfProducts(Yii::$app->params['products']);
                return true;
            }
        }

        return false;
    }

    private function paramsNotSet($params){
        if((isset($params) && !empty($params)) || ($this->filterMinPrice && $this->filterMaxPrice))
            return true;
        return false;
    }

    public function getProdsFromParam($params){
        if(!$this->paramsNotSet($params)){
            return false;
        }

        $prodVarId = [];

        if(!empty($params)){
            foreach ($params as $idAttrCat => $vals){
                $prodVarAttr = (new Query())
                    ->select(['prod_var_id'])
                    ->from('product_variation_attribute')
                    ->where(['value' => $vals, 'cat_attr_id' => $idAttrCat])
                    ->all();

                foreach($prodVarAttr as $id){
                    $prodVarId[$idAttrCat][] = $id['prod_var_id'];
                }
            }

            if(count($prodVarId) > 1){
                $prodVarId = array_intersect(...$prodVarId);
            }else{
                $temp=[];
                foreach ($prodVarId as $var){
                    $temp=$var;
                }
                $prodVarId=$temp;
                unset($var);

                $prodVarId = array_unique($prodVarId);
            }
        }else{
            //чтобы при фильтрации только по цене не выгружать ненужные варации из базы(например: вариации товаров из других категорий)
            $idsCatAttr =  $this->getIdsCatAttr();
            //only price filter
            $prodVarAttr = (new Query())
                ->select(['prod_var_id'])
                ->from('product_variation_attribute')
                ->where(['cat_attr_id' => $idsCatAttr])
                ->all();

            foreach($prodVarAttr as $id){
                $prodVarId[$id['prod_var_id']] = $id['prod_var_id'];
            }

            sort($prodVarId);
        }

        $this->suitableVariations = $prodVarId;

        $prodsArray = [];
        if(!empty($prodVarId)){
            $query = 'SELECT DISTINCT `prod_id` FROM `variation_of_product` WHERE `id` IN (';

            $lastKey = array_keys($prodVarId);
            $lastKey = array_pop($lastKey);
            foreach ($prodVarId as $key => $id){
                if($key != $lastKey){
                    $query .= $id.', ';
                }else{
                    $query .= $id.') ';
                }
            }

            $query .= 'AND `is_active`=1 AND `price` BETWEEN '.$this->filterMinPrice.' AND '.$this->filterMaxPrice;


            $prods = Yii::$app->db->createCommand($query)->queryAll();

            /*vd($prods);

            $prods = (new Query())
                ->select(['prod_id'])
                ->from('variation_of_product')
                ->where(['id' => $prodVarId, 'is_active' => 1])
                ->andWhere(['<=', 'price', $this->filterMaxPrice])
                ->andWhere(['>=', 'price', $this->filterMinPrice])
                ->createCommand()
                ->getSql();
                //->distinct()
                //->all();

            vd($prods);*/

            foreach ($prods as $prodId){
                $prodsArray[] = $prodId['prod_id'];
            }
        }

        return $prodsArray;
    }

    public function getProductsArray($idCat, $idProds = false){
        $prodsQuery = (new Query())
            ->select(['id', 'category_id', 'name', 'alias'])
            ->from('products')
            ->where(['is_active' => 1, 'category_id' => $idCat]);

        if($idProds){
            $prodsQuery->andWhere(['id' => $idProds]);
        }

        $prodsResult = $prodsQuery->createCommand();
        $prods = $prodsResult->queryAll();

        $categoryAttributes = (new Query())
            ->select(['id', 'name', 'unit', 'for_filter'])
            ->from('categories_attributes')
            ->where(['category_id' => $idCat])
            ->all();
        $catAttrArray = [];
        foreach ($categoryAttributes as $attribute){
            $catAttrArray[$attribute['id']] = [
                'cat_attr_name' => $attribute['name'],
                'unit' => $attribute['unit'],
                'for_filter' => $attribute['for_filter']
            ];
        }

        $resultProdArray = [];
        $prices = [];

        foreach ($prods as $prod){
            $resultProdArray[$prod['id']] = [
                'prod_name' => $prod['name'],
                'alias' => $prod['alias'],
                'min_price' => VariationsOfProduct::find()->where(['prod_id' => $prod['id'], 'is_active' => 1])->min('price')
            ];

            $photos = (new Query())
                ->select('name')
                ->from('product_photos')
                ->where(['id_product' => $prod['id']])
                ->all();

            foreach ($photos as $photo){
                $resultProdArray[$prod['id']]['photos'][] = $photo['name'];
            }

            $variations = (new Query())
                ->select(['id', 'name', 'price'])
                ->from('variation_of_product')
                ->where(['prod_id' => $prod['id'], 'is_active' => 1])
                ->all();

            foreach ($variations as $variation){
                $resultProdArray[$prod['id']]['variations'][$variation['id']] = [
                    'variation_name' => $variation['name'],
                    'variation_price' => $variation['price']
                ];

                $prices[] = $variation['price'];

                $variationAttribute = (new Query())
                    ->select(['id', 'value', 'cat_attr_id'])
                    ->from('product_variation_attribute')
                    ->where(['prod_var_id' => $variation['id']])
                    ->all();

                foreach ($variationAttribute as $value){
                    $resultProdArray[$prod['id']]['variations'][$variation['id']]['attributes'][$value['id']] = [
                        'id_cat_attr' => $value['cat_attr_id'],
                        'value' => $value['value'],
                        'category_attribute_name' => $catAttrArray[$value['cat_attr_id']]['cat_attr_name'],
                        'unit' => $catAttrArray[$value['cat_attr_id']]['unit'],
                        'for_filter' =>  $catAttrArray[$value['cat_attr_id']]['for_filter']
                    ];

                }
            }
        }

        $resultProdArray = $this->sorting($resultProdArray);
        //$resultProdArray = $this->getRequiredQuantityOfProducts($resultProdArray);

        Yii::$app->params['products'] = $resultProdArray;
        Yii::$app->params['cat_prices'] = [
            'min' => min($prices),
            'max' => max($prices)
        ];

        return true;
    }

    private function sorting($products){
        if(empty($products)){
            return false;
        }

        switch ($this->sort){
            case 'popular-DESC':
                krsort($products);
                break;

            case 'popular-ASC':
                ksort($products);
                break;

            case 'price-DESC':
                usort($products, function ($a, $b){
                    return ($a['min_price'] >= $b['min_price']);
                });
                break;

            case 'price-ASC':
                usort($products, function ($a, $b){
                    return ($a['min_price'] <= $b['min_price']);
                });
                break;
        }

        return $products;
    }

    private function getRequiredQuantityOfProducts($products){

        if(count($products) > $this->quantity){
            $products = array_slice($products, 0, $this->quantity);
        }
        return $products;
    }

    public function getFiltersData()
    {

        //TODO Замер скорости
        $products = Yii::$app->params['products'];
        $filterData = [];

        foreach ($products as $key => $product){
            foreach ($product['variations'] as $variationId => $variation){
                foreach ($variation['attributes'] as $attribute){
                    if((isset($attribute['for_filter']) && $attribute['for_filter']) && $attribute['value']){
                        $filterData[$attribute['id_cat_attr']] ['values'] [$attribute['value']] [] = $key;
                        $filterData[$attribute['id_cat_attr']] ['unit'] = $attribute['unit'];
                        $filterData[$attribute['id_cat_attr']] ['name'] = $attribute['category_attribute_name'];
                    }
                }
            }
        }

        foreach ($filterData as $idAttr => $attrData){
            foreach ($attrData['values'] as $attrVal => $countsAttr){
                $filterData[$idAttr]['values'][$attrVal] = count(array_count_values($countsAttr));

                uksort($filterData[$idAttr]['values'], function ($a, $b){
                    preg_match("/[\d]+/", $a,$r1);
                    preg_match("/[\d]+/", $b,$r2);

                    if(isset($r1[0]) && isset($r2[0])){
                        return $r1[0] > $r2[0];
                        //Числа
                    }else{
                        $result = strnatcasecmp($a, $b);
                        if($result>0){
                            return true;
                        }else{
                            return false;
                        }
                    }

                });
            }
        }

        $this->filterData = $filterData;
        return true;
    }

    private function getIdsCatAttr()
    {
        $idsCatAttr = (new Query())
            ->select(['id'])
            ->from('categories_attributes')
            ->where(['category_id' => $this->idCategory])
            ->all();

        $return = [];
        foreach ($idsCatAttr as $id){
            $return[$id['id']] = $id['id'];
        }
        sort($return);
        return $return;
    }

}