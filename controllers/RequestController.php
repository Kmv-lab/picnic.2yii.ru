<?php


namespace app\controllers;


use app\modules\adm\models\ProductPhotos;
use app\modules\adm\models\VariationsOfProduct;
use app\traits\BrouserTrait;
use app\traits\SeoHalperTrait;
use Yii;
use yii\db\Query;
use yii\helpers\VarDumper;
use yii\web\Controller;

class RequestController extends Controller
{
    use SeoHalperTrait;
    use BrouserTrait;

    public function beforeAction($action)
    {
        $this->generatePages();

        $this->getVersion();

        return true;
    }

    public function actionPutinbasket($prodId, $varId = false)
    {

        Yii::$app->session->open();

        if(!$varId){
            $variation = VariationsOfProduct::find()->where(['prod_id' => $prodId])->orderBy(['price' => 'ASC'])->one();

            $varId = $variation->id;
        }

        if(isset($_SESSION['products'])){
            foreach ($_SESSION['products'] as $key => $product){
                if($varId == $product['varId']){
                    $_SESSION['products'][$key]['count']++;
                    unset($prodId, $varId);
                    break;
                }
            }
            if(isset($prodId) && isset($varId))
                $this->addToSessionProducts($prodId, $varId);
        }else{
            $this->addToSessionProducts($prodId, $varId);
        }

        $totalCount = 0;
        foreach ($_SESSION['products'] as $product){
            $totalCount += $product['count'];
        }

        return $totalCount;
    }

    public function actionAjaxsubmitbasket($phone, $receiver)
    {
        $basket = $this->getProductsFromBasket();

        if($this->sendApplicationMail($phone, $receiver, $basket))
            return true;

        return false;

        //TODO Добавит все заказы в базу. посоветоваться в каком формате это делать.
    }

    public function actionBasket()
    {
        Yii::$app->params['seo_h1'] = 'КОРЗИНА С ВАШИМ ЗАКАЗОМ';

        $products = Yii::$app->session->get('products');

        if($products !== null){
            $products = $this->getProductsFromBasket($products);
        }

        return $this->render('basket',[
            'products' => $products
        ]);
    }

    public function actionChangebasket($prodId, $varId, $newCount)
    {

        Yii::$app->session->open();

        foreach ($_SESSION['products'] as $key => $product){
            if($product['prodId'] == $prodId && $product['varId'] == $varId){

                if(!$newCount){
                    unset( $_SESSION['products'][$key]);
                }else{
                    $_SESSION['products'][$key]['count'] = $newCount;
                }
            }
        }

        return true;
    }

    public function actionAjaxform(){

        $request = $_GET;
        $response = [
            'status' => false
        ];
        if(!$request['name'] || !$request['phone']){
            $errors = [];

            if(!$request['name'])
                $errors[] = 'name';

            if(!$request['phone'])
                $errors[] = 'phone';

            $response = [
                'status' => false,
                'errors' => $errors
            ];

        }else{
            if($this->sendApplicationMail($request['phone'], $request['name'])){
                $response = [
                    'status' => true
                ];
            }
        }

        return json_encode($response);
    }

    private function getProductsFromBasket($products = null){
        if(!$products)
            $products = Yii::$app->session->get('products');

        foreach ($products as $key => $product){
            $attributes = (new Query())
                ->select([
                    'product_variation_attribute.value',
                    'categories_attributes.name',
                    'categories_attributes.unit'
                ])
                ->from('product_variation_attribute')
                ->join('INNER JOIN', 'categories_attributes', 'categories_attributes.id = product_variation_attribute.cat_attr_id')
                ->where(['product_variation_attribute.prod_var_id' => $product['varId']])
                ->all();

            $products[$key]['attributes'] = $attributes;

            $products[$key]['price'] = (new Query())
                ->select(['price'])
                ->from('variation_of_product')
                ->where(['id' => $product['varId']])
                ->one()['price'];

            $products[$key]['photo'] = ProductPhotos::find()->where(['id_product' => $product['prodId']])->one();

            $tempData = (new Query())
                ->select('products.alias AS prodAlias, categories.alias AS catAlias, products.name')
                ->from('products')
                ->join('INNER JOIN', 'categories', 'products.category_id = categories.id')
                ->where(['products.id' => $product['prodId']])
                ->all();

            $products[$key]['aliases'] = [
                'catAlias' => $tempData[0]['catAlias'],
                'prodAlias' => $tempData[0]['prodAlias']
            ];
            $products[$key]['name'] = $tempData[0]['name'];
        }

        return $products;
    }

    private function addToSessionProducts($prodId, $varId)
    {
        if($prodId && $varId){
            $_SESSION['products'][] = [
                'count' => 1,
                'prodId' => $prodId,
                'varId' => $varId
            ];
        }

        return false;
    }

    private function sendApplicationMail($phone, $receiver, $basket = null)
    {
        $mailer = Yii::$app->mailer->getTransport();

        $mailer->setUserName(Yii::$app->params['email_from'])
            ->setPassword(Yii::$app->params['email_from_pass']);
        $table='';

        if($basket){
            $table = '
                <table border="1">
                    <caption>Заказаные товары</caption>
                    <tr>
                        <th>Товар</th>
                        <th>Вариация</th>
                        <th>Цена</th>
                        <th>Количество</th>
                        <th>Сумма</th>
                    </tr>';

            $fullPrice = 0;
            foreach ($basket as $key => $prod){
                $varName = (new Query())
                    ->select([
                        'variation_of_product.name'
                    ])
                    ->from('variation_of_product')
                    ->where(['id' => $prod['varId']])
                    ->one();
                $basket[$key]['varName'] = $varName['name'];

                $price = $prod['price']*$prod['count'];
                $fullPrice += $price;

                $table .= '
                    <tr>
                        <td>'.$prod['name'].'</td>
                        <td>'.$varName['name'].'</td>
                        <td>'.$prod['price'].'</td>
                        <td>'.$prod['count'].'</td>
                        <td>'.$price.' руб.</td>
                    </tr>';
            }

            $table .= '
                    <tr>
                        <td>ИТОГО</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>'.$fullPrice.' руб.</td>
                    </tr>
                    </table>
                    ';
        }

        $text = '<p>Клиент : <span style="font-size: 20px">'.$receiver.'</span></p>
        <p>Номер телефона : <span><a href="tel:'.preg_replace('/[^0-9\+]/', '', $phone).'">'.$phone.'</a></span></p>';

        $text .= $table;

        return Yii::$app->mailer->compose()
            ->setFrom( Yii::$app->params['mail_from'])
            ->setTo(Yii::$app->params['adminEmail'])
            ->setSubject('Заявка от '.$receiver)
            ->setTextBody('Текст сообщения')
            ->setHtmlBody($text)
            ->send();
    }
}