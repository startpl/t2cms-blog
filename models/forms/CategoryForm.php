<?php

namespace startpl\t2cmsblog\models\forms;

use yii\base\Model;
use startpl\t2cmsblog\models\{
    Category,
    Page
};

/**
 * Setting create form
 */
class CategoryForm extends Model
{       
    public $id;
    public $url;
    public $status;
    public $author_id;
    public $parent_id;
    public $publish_at;
    public $settings;

    public $addCategories;
    public $addPages;
    public $rltCategories;
    public $rltPages;
    
    public $access_read;
    public $records_per_page;
    public $sort;
    
    public $main_template;
    public $mainTemplateName;
    public $mainTemplateApplySub;
    
    public $category_template;
    public $categoryTemplateName;
    public $categoryTemplateApplySub;
    
    public $page_template;
    public $pageTemplateName;
    public $pageTemplateApplySub;

    public $categoryContent;

    public function __construct($config = array()) {
        parent::__construct($config);
        
        $this->categoryContent = new CategoryContentForm();
        
        $this->publish_at = date('Y-m-d H:i:s');
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'status', 'publish_at', 'access_read'], 'required'],
            [['id','author_id', 'status', 'parent_id', 'records_per_page'], 'integer'],
            [['mainTemplateApplySub', 'categoryTemplateApplySub', 'pageTemplateApplySub'], 'boolean'],
            [['status'], 'default', 'value' => Category::STATUS['DRAFT']],
            [['records_per_page'], 'default', 'value' => 15],
            [['publish_at'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['publish_at'], 'default', 'value' => date('Y-m-d H:i:s')],
            [['url', 'sort', 'main_template', 'category_template', 'page_template', 'access_read'], 'string', 'max' => 255],
            [['mainTemplateName', 'categoryTemplateName', 'pageTemplateName'], 'string', 'max' => 150],
            [['mainTemplateName', 'categoryTemplateName', 'pageTemplateName'], 'default', 'value' => ''],
            ['url', 'checkUrl'],
            [['url'], 'match', 'pattern' => '/^[\w-]+$/', 
                'message' => 'The field can contain only latin letters, numbers, and signs "_", "-"'],
            [['author_id'], 'default', 'value' => \Yii::$app->user->id],
            [['addCategories', 'addPages', 'rltPages', 'rltCategories', 'main_template', 'category_template', 'page_template'], 'safe'],
            [['settings'], 'string'],
        ];
    }
    
    public function checkUrl($attribute, $params)
    {       
        if(
            Category::find()->where(['url' => $this->url, 'parent_id' => $this->parent_id])->andWhere(['!=', 'id', $this->id])->exists() || 
            Page::find()->where(['category_id' => $this->parent_id, 'url' => $this->url])->exists()
        ){
            $this->addError($attribute, \Yii::t('nsblog/error', 'This Url already exists'));
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'category_id' => \Yii::t('app', 'Category ID'),
            'name' => \Yii::t('app', 'Name'),
            'h1' => \Yii::t('app', 'H1'),
            'image' => \Yii::t('app', 'Image'),
            'preview_text' => \Yii::t('app', 'Preview Text'),
            'full_text' => \Yii::t('app', 'Full Text'),
            'title' => \Yii::t('app', 'Title'),
            'og_title' => \Yii::t('app', 'Og Title'),
            'keywords' => \Yii::t('app', 'Keywords'),
            'description' => \Yii::t('app', 'Description'),
            'og_description' => \Yii::t('app', 'Og Description'),
        ];
    }
    
    public function loadModel(Model $model) 
    {
        $this->attributes = $model->attributes;
        $this->categoryContent->attributes = $model->categoryContent->attributes;
        
        $mainTmp = json_decode($this->main_template, true);
        if(is_array($mainTmp) && array_key_exists('file', $mainTmp) && array_key_exists('apply', $mainTmp)){
            $this->mainTemplateName     = $mainTmp['file'];
            $this->mainTemplateApplySub = $mainTmp['apply'];
        }
        
        $categoryTmp = json_decode($this->category_template, true);
        if(is_array($categoryTmp) && array_key_exists('file', $categoryTmp) && array_key_exists('apply', $categoryTmp)){
            $this->categoryTemplateName     = $categoryTmp['file'];
            $this->categoryTemplateApplySub = $categoryTmp['apply'];
        }
        
        $pageTmp = json_decode($this->page_template, true);
        if(is_array($pageTmp) && array_key_exists('file', $pageTmp) && array_key_exists('apply', $pageTmp)){
            $this->pageTemplateName = $pageTmp['file'];
            $this->pageTemplateApplySub = $pageTmp['apply'];
        }
    }
    
    public function beforeValidate()
    {        
        $this->main_template     = json_encode(['file' => $this->mainTemplateName, 'apply' => $this->mainTemplateApplySub]);
        $this->category_template = json_encode(['file' => $this->categoryTemplateName, 'apply' => $this->categoryTemplateApplySub]);
        $this->page_template     = json_encode(['file' => $this->pageTemplateName, 'apply' => $this->pageTemplateApplySub]);
        
        return parent::beforeValidate();
    }
}
