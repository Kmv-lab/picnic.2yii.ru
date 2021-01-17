<?php


namespace app\modules\adm\models;

use app\traits\ImageTrait;
use app\traits\SeoHalperTrait;
use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Categories extends ActiveRecord
{

    use ImageTrait;
    use SeoHalperTrait;

    public function attributeLabels(){
        return [
            'name' => 'Имя',
            'alias' => 'Путь',
            'parent_id' => 'Родительская категория',
            'seo_title' => 'SEO ЗАГОЛОВОК',
            'seo_h1' => 'H1 СТРАНИЦЫ',
            'seo_description' => 'SEO ОПИСАНИЕ',
            'seo_keywords' => 'SEO КЛЮЧЕВЫЕ СЛОВА',
            'description' => 'Описание',
            'short_desc' => 'Краткое описание',
            'additional_block' => 'Дополнительная информация',
            'img' => 'Картинка',
            'in_menu' => 'Показать в меню',
            'filter' => 'Фильтрация',
            'is_active' => 'Вкл/Выкл'
        ];
    }

    public static function tableName()
    {
        return 'categories';
    }

    public function rules(){
        return [
            [['name', 'alias', 'short_desc'], 'required'],
            [['parent_id', 'additional_block'], 'integer'],
            [['description', 'seo_title', 'seo_h1', 'seo_description', 'seo_keywords'], 'string'],
            [['name', 'alias'], 'unique'],
            [['in_menu', 'is_active', 'filter'], 'boolean'],
            ['img', 'file',
                'extensions' => 'jpg, jpeg, png',
                'maxFiles' => 1,
                'minSize'=>Yii::$app->params['min_image_size_for_upload'],
                'maxSize'=>Yii::$app->params['max_image_size_for_upload'],
                'tooBig'=>'Фотография больше {formattedLimit}',
                'tooSmall'=>'Фотография меньше {formattedLimit}',],
        ];
    }
}