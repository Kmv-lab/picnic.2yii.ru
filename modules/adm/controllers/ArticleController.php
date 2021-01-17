<?php


namespace app\modules\adm\controllers;


use app\commands\ImagickHelper;
use app\modules\adm\models\Articles;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class ArticleController extends Controller
{

    public $title = 'Статьи';

    public function actionIndex(){

        Yii::$app->params['H1'] = $this->title = 'Статьи';

        $dataProvider = new ActiveDataProvider([
            'query' => Articles::find(),
        ]);

        $dataProvider->sort->defaultOrder = ['name' => SORT_ASC];

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionNew()
    {
        Yii::$app->params['H1'] = $this->title = 'Добавить новую статью';

        $newArticle = new Articles();

        if(Yii::$app->request->isPost){
            if ($newArticle->load(Yii::$app->request->post())){
                if($file = $newArticle->upload('img')){
                    $newArticle->img = $file;

                    $newArticle->createThumbOfImage($newArticle, 'img', $newArticle->getResolutionOfImage($newArticle->tableName()));
                }
                if($newArticle->save()){
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('new', [
            'model' => $newArticle,
        ]);
    }

    public function actionUpdate($idArticle)
    {
        Yii::$app->params['H1'] = $this->title = 'Редактирование статьи';

        $article = Articles::findOne($idArticle);

        if(Yii::$app->request->isPost){
            if($article->load(Yii::$app->request->post())){
                if ($file = $article->upload('img')){
                    $article->img = $file;
                    $article->createThumbOfImage($article, 'img', $article->getResolutionOfImage($article->tableName()));
                }
                else{
                    $article->img = $article->oldAttributes['img'];
                }

                if($article->save()){
                    //return $this->redirect(['index']);
                }
            }
        }

        return $this->render('update', [
            'model' => $article
        ]);
    }

    public function actionAjaxcreatethumb($idArticle, $name, $model=null){

        $this->enableCsrfValidation = false;

        if(!$model){
            $model = Articles::findOne($idArticle);
        }

        if($name === 'undefined')
            $name = 'img';

        return json_encode(ImagickHelper::Thumb($_POST, $model, $name));
    }

    public function actionDelete($idArticle)
    {
        $article = Articles::findOne($idArticle);

        $article->deleteOldPhoto('img');


        if($article->delete())
            return $this->redirect(['index']);

        return "Не удалось удалить";

    }

}