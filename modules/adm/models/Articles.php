<?php


namespace app\modules\adm\models;


use app\traits\ImageTrait;
use Yii;
use yii\db\ActiveRecord;

class Articles extends ActiveRecord
{

    use ImageTrait;

    public function attributeLabels(){
        return [
            'name' => 'Имя',
            'alias' => 'Путь',
            'content' => 'Содержание',
            'description' => 'Описание',
            'seo_title' => 'SEO ЗАГОЛОВОК',
            'seo_description' => 'SEO ОПИСАНИЕ',
            'seo_keywords' => 'SEO КЛЮЧЕВЫЕ СЛОВА',
            'date' => 'Дата публикации',
            'img' => 'Картинка',
            'is_active' => 'Вкл/Выкл'
        ];
    }

    public static function tableName()
    {
        return 'articles';
    }

    public function rules(){
        return [
            [['name', 'alias', 'content', 'date'], 'required'],
            ['date', 'match', 'pattern' => '/^(\d{4})[-](\d{2})[-](\d{2})/', 'message' => 'Неверная дата'],
            [['name', 'alias', 'content', 'seo_title', 'seo_description', 'seo_keywords'], 'string'],
            [['name', 'alias'], 'unique'],
            ['is_active', 'boolean'],
            ['description', 'string', 'max' => 200],
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