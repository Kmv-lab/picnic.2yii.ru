<?php


namespace app\modules\adm\controllers;


use app\modules\adm\models\index_head;
use app\modules\adm\models\Main_page;
use app\modules\adm\models\ModelIndexHead;
use Yii;
use yii\base\Model;
use yii\web\Controller;

class IndexpageController extends Controller
{

    public $model;

    public function actionIndex(){

        $emptyModel = new Main_page();
        $errorModel = [];

        if((Yii::$app->request->isPost) && !isset(Yii::$app->request->post()['Main_page']['id'])){
            //vd(Yii::$app->request->post());
            if ($emptyModel->load(Yii::$app->request->post())){
                //vd(Yii::$app->request->post());
                if($emptyModel->save()){
                    $emptyModel = new Main_page();
                }
            }
        }
        else if((Yii::$app->request->isPost) && isset(Yii::$app->request->post()['Main_page']['id'])){
            $model = Main_page::find()->where(['id' => Yii::$app->request->post()['Main_page']['id']])->one();
            if($model->load(Yii::$app->request->post())){
                if(!$model->save()){
                    $errorModel = $model;
                }
            }
        }

        $this->model = Main_page::find()->orderBy('priority')->all();

        if(!empty($errorModel)){
            foreach ($this->model as $key => $modelPage){
                if($modelPage->id == $errorModel->id){
                    $this->model[$key] = $errorModel;
                }
            }
        }

        return $this->render('index', [
            "model" => $this->model,
            'emptyModel' => $emptyModel
        ]);
    }

    public function actionDelete($idBlock){
        $model = Main_page::find()->where(['id' => $idBlock])->one();
        if (!empty($model))
            $model->delete();

        return $this->redirect(['index']);
    }

    public function actionSettings()
    {
        $SQL = 'SELECT * FROM index_head ORDER BY id';
        $settings = Yii::$app->db->createCommand($SQL)->queryAll();
        $models = [];
        foreach ($settings AS $setting){
            $models[$setting['id']] = new ModelIndexHead();
            $models[$setting['id']]->name = $setting['name'];
            $models[$setting['id']]->value = $setting['value'];
            $models[$setting['id']]->description = $setting['description'];
        }


        if ((Model::loadMultiple($models, Yii::$app->request->post()) && Model::validateMultiple($models))) {

            foreach ($models AS $key => $model){
                Yii::$app->db->createCommand()
                    ->update('index_head', [
                        'name' => $model->name,
                        'value' => $model->value,
                        'description' => $model->description
                    ], 'id = :id')
                    ->bindValue(':id', $key)->execute();
            }

        }

        return $this->render('settings', [
            'settings' => $settings,
            'models' => $models
        ]);
    }

    public function actionNew()
    {
        $model = new index_head();

        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post())){
            $model->save();
        }

        return $this->render('new', [
            'model' => $model
        ]);
    }

    private function processingData($ids){

        $request = Yii::$app->request;
        $post = $request->post();

        if( !empty($post) && $post['Main_page']['type']!=3){//костыльная проверка типа, я знаю всё очень плохо. Потом нужно поправить

            $newData = $this->model[array_search($post['Main_page']['id'], $ids)];

            $newData->content = $post['Main_page']['content'];

            $result = $newData->save() ? true : false;
        }
        else if( !empty($post) && $post['Main_page']['type']==3 ){

            $newData = $this->model[array_search($post['Main_page']['id'], $ids)];
            $currentImageName = $newData->content;
            $newData->curType = $post['Main_page']['type'];
            $fileName = $newData->upload();
            $newData->content = $fileName;

            if ($newData->save()){
                $newData->deleteOldImage($currentImageName);
                $result = true;
            }
            else{
                $result = false;
            }
        }
        return $result;
    }

}