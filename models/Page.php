<?php

namespace startpl\t2cmsblog\models;

use Yii;

/**
 * This is the model class for table "{{%page}}".
 *
 * @property int $id
 * @property string $name
 * @property string h1
 * @property string $url
 * @property int $author_id
 * @property int $category_id
 * @property string $image
 * @property string|null $preview_text
 * @property string|null $full_text
 * @property int $position
 * @property int|null $domain_id
 * @property int|null $lang_id
 * @property int $status
 * @property string $access_read
 * @property int $publish_at
 * @property int $created_at
 * @property int $updated_at
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $og_title
 * @property string $og_description
 *
 * @property AdditionalPage[] $additionalPages
 * @property AdditionalPage[] $additionalPages0
 * @property MetaBlogPage[] $metaBlogPages
 * @property Domain $domain
 * @property Language $lang
 * 
 * @property string $main_template JSON
 * @property string $page_template JSON
 * @property string $settings JSON
 */
class Page extends \yii\db\ActiveRecord
{
    const SOURCE_TYPE = 1;
    
    const STATUS = ['DRAFT' => 1, 'PUBLISHED' => 2, 'ARCHIVE' => 3];
    
    const NODE_TYPE = 'LEAF';
    
    public $addCategories;
    public $addPages;
    
    public $rltCategories;
    public $rltPages;
    
    public $parents;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'publish_at', 'access_read'], 'required'],
            [['author_id', 'position', 'category_id', 'status'], 'integer'],
            [['url', 'main_template', 'page_template', 'access_read'], 'string', 'max' => 255],
            [['settings'], 'string'],
            [['publish_at'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['publish_at'], 'default', 'value' => date('Y-m-d H:i:s')],
            [['position'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => self::STATUS['DRAFT']],
            [['author_id'], 'default', 'value' => \Yii::$app->user->id],
            [['url'], 'checkUrl']
        ];
    }
    
    /**
     * {@inheritdoc}
     * @return PageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PageQuery(get_called_class());
    }
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimeStampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s'),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'url' => Yii::t('app', 'Url'),
            'author_id' => Yii::t('app', 'Author ID'),
            'image' => Yii::t('app', 'Image'),
            'preview_text' => Yii::t('app', 'Preview Text'),
            'full_text' => Yii::t('app', 'Full Text'),
            'position' => Yii::t('app', 'Position'),
            'domain_id' => Yii::t('app', 'Domain ID'),
            'lang_id' => Yii::t('app', 'Lang ID'),
            'publish_at' => Yii::t('app', 'Publish At'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'addPages'   => Yii::t('app', 'Additional Pages'),
        ];
    }
    
    public function checkUrl($attribute, $param)
    {
        if(
            self::find()->where(['category_id' => $this->category_id, 'url' => $this->url])->andWhere(['!=', 'id', $this->id])->exists() ||
            Category::find()->where(['parent_id' => $this->category_id, 'url' => $this->url])->exists()
        ){
            $this->addError($attribute, \Yii::t('nsblog/error', 'This Url already exists'));
        }
    }
    
    public function afterSave($insert, $changedAttributes) { 
            $this->saveAdditionalCategories();
            $this->saveAdditionalPages();
            $this->saveRelatedPages();
            $this->saveRelatedCategories();
        
        parent::afterSave($insert, $changedAttributes);
    }
    
    private function saveAdditionalPages()
    {
        $this->addPages = is_array($this->addPages)? $this->addPages : [];
        
        $current = \yii\helpers\ArrayHelper::getColumn($this->additionalPages, 'id');
                
        foreach(array_filter(array_diff($this->addPages, $current)) as $pageId){
            $page = Page::findOne($pageId);
            
            $this->link('additionalPages', $page, ['type' => 0, 'source_type' => self::SOURCE_TYPE]);
        }
        
        foreach(array_filter(array_diff($current, $this->addPages)) as $pageId){
            $page = Page::findOne($pageId);
            
            $this->unlink('additionalPages', $page, true);
        }
        
    }
    
    private function saveAdditionalCategories()
    {
        $this->addCategories = is_array($this->addCategories)? $this->addCategories : [];
        
        $current = \yii\helpers\ArrayHelper::getColumn($this->additionalCategories, 'id');
        
        foreach(array_filter(array_diff($this->addCategories, $current)) as $catId){
            $category = self::findOne($catId);
            
            $this->link('additionalCategories', $category, ['type' => 0, 'source_type' => self::SOURCE_TYPE]);
        }
        
        foreach(array_filter(array_diff($current, $this->addCategories)) as $catId){
            $category = self::findOne($catId);
            
            $this->unlink('additionalCategories', $category, true);
        }
    }
    
    private function saveRelatedPages()
    {
        $this->rltPages = is_array($this->rltPages)? $this->rltPages : [];
        $current = \yii\helpers\ArrayHelper::getColumn($this->relatedPages, 'id');
        
        foreach(array_filter(array_diff($this->rltPages, $current)) as $pageId){
            $page = Page::findOne($pageId);
            
            $this->link('relatedPages', $page, ['type' => 1, 'source_type' => self::SOURCE_TYPE]);
        }

        
        foreach(array_filter(array_diff($current, $this->rltPages)) as $pageId){
            $page = Page::findOne($pageId);
            
            $this->unlink('relatedPages', $page, true);
        }
    }
    
    private function saveRelatedCategories()
    {
        $this->rltCategories = is_array($this->rltCategories)? $this->rltCategories : [];
        $current = \yii\helpers\ArrayHelper::getColumn($this->relatedCategories, 'id');
        
        foreach(array_filter(array_diff($this->rltCategories, $current)) as $catId){
            $category = self::findOne($catId);
            
            $this->link('relatedCategories', $category, ['type' => 1, 'source_type' => self::SOURCE_TYPE]);
        }
        
        foreach(array_filter(array_diff($current, $this->rltCategories)) as $catId){
            $category = self::findOne($catId);
            
            $this->unlink('relatedCategories', $category, true);
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionalCategories()
    {
        return $this->hasMany(self::className(), ['id' => 'category_id'])
                ->viaTable(CategoryAssign::tableName(), ['resource_id' => 'id'], function(\yii\db\ActiveQuery $query){
                    return $query->andWhere(['type' => 0, 'source_type' => self::SOURCE_TYPE]);
                });
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedCategories()
    {
        return $this->hasMany(self::className(), ['id' => 'category_id'])
                ->viaTable(CategoryAssign::tableName(), ['resource_id' => 'id'], function(\yii\db\ActiveQuery $query){
                    return $query->andWhere(['type' => 1, 'source_type' => self::SOURCE_TYPE]);
                });
    }
    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionalPages()
    {
        return $this->hasMany(Page::className(), ['id' => 'page_id'])
                ->viaTable(PageAssign::tableName(), ['resource_id' => 'id'], function(\yii\db\ActiveQuery $query){
                    return $query->andWhere(['type' => 0, 'source_type' => self::SOURCE_TYPE]);
                });
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedPages()
    {
        return $this->hasMany(Page::className(), ['id' => 'page_id'])
                ->viaTable(PageAssign::tableName(), ['resource_id' => 'id'], function(\yii\db\ActiveQuery $query){
                    return $query->andWhere(['type' => 1, 'source_type' => self::SOURCE_TYPE]);
                });
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageContent()
    {
        return $this->hasOne(PageContent::className(), ['src_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(\Yii::$app->user->identityClass, ['id' => 'author_id']);
    }
    
    public function getParents() 
    {        
        $parent = Category::findOne($this->category_id);
                                
        if($parents = $parent->getParents()) {
            return array_merge([$parent], $parents);
        }
        
        return $parent;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
    
    public static function getStatuses(): array
    {
        return [
            Category::STATUS['DRAFT']     => \Yii::t('nsblog', 'Draft'),
            Category::STATUS['PUBLISHED'] => \Yii::t('nsblog', 'Published'),
            Category::STATUS['ARCHIVE']   => \Yii::t('nsblog', 'Archive'),
        ];
    }
    
}
