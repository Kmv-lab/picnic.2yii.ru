<?php
namespace app\widgets;


use app\modules\adm\models\ProductPhotos;
use app\modules\adm\models\VariationsOfProduct;
use Yii;
use yii\base\Widget;

class Products extends Widget
{
    public $count = 0;
    public $category;
    public $productInRow = 3;
    public $products = false;
    public $limit = -1;
    public $isAjax = false;

    public function run()
    {

        $this->products = isset(Yii::$app->params['products']) ? Yii::$app->params['products'] : false;

        if(isset($this->category) && $this->category && $this->products === false){
            $this->products = \app\modules\adm\models\Products::find()
                ->where(['category_id' => $this->category, 'is_active' => 1])
                ->limit($this->limit)
                ->all();

            $prods = [];

            foreach ($this->products as $key => $product){
                $prods[$product->id] = [
                    "prod_name" => $product->name,
                    "alias" => $product->alias
                ];
                $prods[$product->id]['min_price'] = VariationsOfProduct::find()->where(['prod_id' => $product['id'], 'is_active' => 1])->min('price');

                $prods[$product->id]['photo'] = ProductPhotos::find()->where(['id_product' => $product['id']])->one();

            }

            $this->products = $prods;
        }elseif(!empty($this->products)){
            $i = 1;
            if($this->isAjax){
                $path='';
            }
            foreach ($this->products as $key => $product){
                if(isset($product['photos'])){

                    if(($this->limit > -1) && ($i > $this->limit)){
                        unset($this->products[$key]);
                    }

                    $this->products[$key]['photo'] = ProductPhotos::find()->where(['name' => $product['photos'][0]])->one();
                    if($this->isAjax){
                        if(!$path)
                            $path = $this->products[$key]['photo']->DIRview().$this->products[$key]['photo']->getOneResolution('min').'/';

                        $this->products[$key]['photo'] = $path.$this->products[$key]['photo']['name'];
                    }
                    unset($this->products[$key]['photos'], $this->products[$key]['variations']);
                    $i++;
                }
            }
        }

        $categoryObj = \app\modules\adm\models\Categories::find()->where(['id' => $this->category])->one();

        if($this->isAjax){
            return $this->products;
        }

        return $this->render('products', [
            'products' => $this->products,
            'prodInRow' => $this->productInRow,
            'category' => $categoryObj
        ]);

    }

}