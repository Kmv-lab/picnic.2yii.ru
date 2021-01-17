<?php


namespace app\traits;


use app\commands\ImagickHelper;
use Imagick;
use Yii;
use yii\web\UploadedFile;

trait ImageTrait
{

    public $resolutions;

    public function DIR()
    {
        return Yii::$app->params['full_path_to_dynamic_images'].$this->tableName().'/';
    }

    public function DIRview()
    {
        return Yii::$app->params['path_to_dynamic_images'].$this->tableName().'/';
    }

    public function getOneResolution($param){

        $resolutionsArray = $this->getResolutionOfImage();

        if(strcasecmp($param, 'max') == 0){
            return array_pop($resolutionsArray);
        }else if (strcasecmp($param, 'min') == 0){
            return array_shift($resolutionsArray);
        }else if (strcasecmp($param, 'mid') == 0){
            return $resolutionsArray[ceil(count($resolutionsArray)/2)];
        }else if (preg_match('/^(\d{2,4})[x](\d{2,4})/', $param)){
            if(in_array($param, $resolutionsArray)){
                return $param;
            }
        }

        return array_shift($resolutionsArray);

    }

    public function getResolutionOfImage($typeImage = false)
    {

        if(!$typeImage)
            $typeImage = $this->tableName();

        $skip = array('.', '..', 'original');
        $files = scandir('content/images/'.$typeImage);
        $resultArray = [];

        foreach($files as $file) {
            if(!in_array($file, $skip)){
                $resultArray[] = $file;
            }
        }

        usort($resultArray, function ($a, $b){
            return explode("x", $a)[0]>explode("x", $b)[0];
        });

        if(!empty($resultArray))
            return $resultArray;

        return false;
    }

    public function upload($name){
        $file = UploadedFile::getInstance($this, $name);

        if($file){
            if (isset($this->oldAttributes[$name]) && $this->oldAttributes[$name]){
                $this->deleteOldPhoto($name, $this->oldAttributes[$name]);
            }
            $file->name = strtolower(md5(uniqid($file->baseName))). '.' . $file->extension;
            $file->saveAs( $this->DIR().'original/'.$file->name);

            return $file->name;
        }
        return false;
    }

    public function createThumbOfImage($model, $key, $resolutions = false){
        if(!$resolutions){
            $resolutions[0] = Yii::$app->params['default_resolution_img'];
        }

        foreach ($resolutions as $resolution){
            $resolutionNow = explode("x", $resolution);

            $newImage = new Imagick($this->DIR().'original/'.$model[$key]);
            $geometry = $newImage->getImageGeometry();

            unset($newImage);

            $x2 = $geometry['width'];
            $y2 = ($geometry['width']*$resolutionNow[1])/$resolutionNow[0];

            if($y2>$geometry['height']){
                $x2 = ($geometry['height']*$resolutionNow[0])/$resolutionNow[1];
                $y2 = $geometry['height'];
            }

            $post = [
                'id' => $model->id,
                'x1' => '0',
                'y1' => '0',
                'x2' => $x2,
                'y2' => $y2,
                'r' => $resolution
            ];

            ImagickHelper::Thumb($post, $model, $key);
        }
    }

    public function deleteOldPhoto($name, $fileName=null){
        if(!$fileName){
            $fileName = $this->{$name};
        }

        if (is_file($this->DIR().'original/'.$fileName)) {
            unlink($this->DIR().'original/'.$fileName);
        }

        foreach ($this->getResolutionOfImage() as $resolution){
            if (is_file($this->DIR().$resolution.'/'.$fileName)) {
                unlink($this->DIR().$resolution.'/'.$fileName);
            }
        }

        return;
    }

}
