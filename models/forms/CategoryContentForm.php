<?php

namespace startpl\t2cmsblog\models\forms;

use yii\base\Model;
use startpl\t2cmsblog\models\{
    Category
};

/**
 * Setting create form
 */
class CategoryContentForm extends Model
{        
    public $name;
    public $src_id;
    public $h1;
    public $image;
    public $preview_text;
    public $full_text;
    
    public $language_id;
    public $domain_id;

    public $title;
    public $keywords;
    public $description;
    public $og_title;
    public $og_description;
    public $og_url;
    public $og_sitename;
    public $og_type;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'h1', 'preview_text', 'full_text', 'title', 'og_title', 'keywords', 'description', 'og_description'], 'required'],
            [['src_id', 'language_id'], 'integer'],
            [['preview_text', 'full_text', 'description', 'og_description'], 'string'],
            [['name', 'h1', 'image', 'title', 'og_title', 'keywords', 'og_url', 'og_sitename', 'og_type'], 'string', 'max' => 255],
            [['src_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['src_id' => 'id']],
            
            
        ];
    }
        
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'src_id' => \Yii::t('app', 'Category ID'),
            'name' => \Yii::t('app', 'Name'),
            'h1' => \Yii::t('app', 'H1'),
            'image' => \Yii::t('app', 'Image'),
            'preview_text' => \Yii::t('app', 'Preview Text'),
            'full_text' => \Yii::t('app', 'Full Text'),
            'title' => \Yii::t('app', 'Title'),
            'og_title' => \Yii::t('app', 'OG Title'),
            'keywords' => \Yii::t('app', 'Keywords'),
            'description' => \Yii::t('app', 'Description'),
            'og_description' => \Yii::t('app', 'OG Description'),
            'og_url'      => \Yii::t('app', 'OG Url'),
            'og_sitename' => \Yii::t('app', 'OG Sitename'),
            'og_type'     => \Yii::t('app', 'OG Type')
        ];
    }
}
