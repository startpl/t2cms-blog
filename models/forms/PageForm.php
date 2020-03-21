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
class PageForm extends Model
{    
    public $id;
    public $url;
    public $status;
    public $author_id;
    public $category_id;
    public $publish_at;

    public $addCategories;
    public $addPages;
    public $rltCategories;
    public $rltPages;
    
    public $access_read;
    
    public $main_template;
    public $mainTemplateName;
    public $mainTemplateApplySub;
    
    public $page_template;
    public $pageTemplateName;
    public $pageTemplateApplySub;

    public $pageContent;

    public function __construct($config = array()) {
        parent::__construct($config);
        
        $this->pageContent = new PageContentForm();
        
        $this->publish_at = date('Y-m-d H:i:s');
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'status', 'publish_at', 'access_read'], 'required'],
            [['id', 'author_id', 'status', 'access_read', 'category_id'], 'integer'],
            [['mainTemplateApplySub', 'pageTemplateApplySub'], 'boolean'],
            [['status'], 'default', 'value' => Page::STATUS['DRAFT']],
            [['publish_at'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['publish_at'], 'default', 'value' => date('Y-m-d H:i:s')],
            [['url', 'main_template', 'page_template'], 'string', 'max' => 255],
            [['mainTemplateName', 'pageTemplateName'], 'string', 'max' => 150],
            [['mainTemplateName', 'pageTemplateName'], 'default', 'value' => ''],
            ['url', 'checkUrl'],
            [['url'], 'match', 'pattern' => '/^[\w-]+$/', 
                'message' => 'The field can contain only latin letters, numbers, and signs "_", "-"'],
            [['author_id'], 'default', 'value' => \Yii::$app->user->id],
            [['addCategories', 'addPages', 'rltPages', 'rltCategories', 'main_template', 'page_template'], 'safe'],
        ];
    }
    
    public function checkUrl($attribute, $params)
    {       
        if(
            Category::find()->where(['url' => $this->url, 'parent_id' => $this->category_id])->exists() || 
            Page::find()
                ->where(['category_id' => $this->category_id, 'url' => $this->url])
                ->andWhere(['!=', 'id', $this->id])
                ->exists()
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
        $this->pageContent->attributes = $model->pageContent->attributes;
        
        $mainTmp = json_decode($this->main_template, true);
        if(is_array($mainTmp) && array_key_exists('file', $mainTmp) && array_key_exists('apply', $mainTmp)){
            $this->mainTemplateName     = $mainTmp['file'];
            $this->mainTemplateApplySub = $mainTmp['apply'];
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
        $this->page_template     = json_encode(['file' => $this->pageTemplateName, 'apply' => $this->pageTemplateApplySub]);
        
        return parent::beforeValidate();
    }
}
