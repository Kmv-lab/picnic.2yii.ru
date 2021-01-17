<?php


namespace app\modules\adm\models;


use app\traits\SeoHalperTrait;
use yii\db\ActiveRecord;

class Products extends ActiveRecord
{

    use SeoHalperTrait;

    public function attributeLabels(){
        return [
            'category_id' => 'Категория товара',
            'alias' => 'Путь',
            'name' => 'Наименование',
            'seo_title' => 'SEO ЗАГОЛОВОК',
            'seo_h1' => 'H1 СТРАНИЦЫ',
            'seo_description' => 'SEO ОПИСАНИЕ',
            'seo_keywords' => 'SEO КЛЮЧЕВЫЕ СЛОВА',
            'description' => 'Описание',
            'is_active' => 'ВКЛ.ВЫКЛ'
        ];
    }

    public static function tableName()
    {
        return 'products';
    }

    public function rules(){
        return [
            [['name', 'alias', 'category_id'], 'required'],
            [['description', 'seo_title', 'seo_h1', 'seo_description', 'seo_keywords'], 'string'],
            [['name', 'alias'], 'unique'],
            ['is_active', 'integer']
        ];
    }

}