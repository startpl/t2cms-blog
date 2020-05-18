<?php

namespace startpl\t2cmsblog\models;

use Yii;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property int $id
 * @property string $url
 * @property int $author_id
 * @property int $status
 * @property int $tree
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 * @property int $parent_id
 * @property int $position
 * @property string $access_read
 * @property int $publish_at
 * @property int $created_at
 * @property int $updated_at
 *
 * @property AdditionalCategory[] $additionalCategories
 * @property AdditionalCategory[] $additionalCategories0
 * @property AdditionalPage[] $additionalPages
 * @property AdditionalPage[] $additionalPages0
 * @property Domain $domain
 * @property Language $lang
 * @property MetaBlogCategory[] $metaBlogCategories
 * @property RelatedCategory[] $relatedCategories
 * 
 * @property int $records_per_page
 * @property string $sort
 * @property string $main_template JSON
 * @property string $category_template JSON
 * @property string $page_template JSON
 * @property string $settings JSON
 */
class Category extends \yii\db\ActiveRecord
{
    const TYPE = 'blog';
    
    const ROOT_ID = 1;
    
    const OFFSET_ROOT = 1;
    const SOURCE_TYPE = 0;
    
    const STATUS = ['DRAFT' => 1, 'PUBLISHED' => 2, 'ARCHIVE' => 3];
    
    public $addCategories;
    public $addPages;
    
    public $rltCategories;
    public $rltPages;
    
    public $children;
    public $parents;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%category}}';
    }
    
    public function behaviors() {
        return [
            [
                'class' => \yii\behaviors\TimeStampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s'),
            ],
            'tree' => [
                'class' => NestedSetsBehavior::className(),
            ]
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'author_id', 'status', 'publish_at', 'access_read'], 'required'],
            [['author_id', 'status', 'tree', 'lft', 'rgt', 'depth', 'position', 'parent_id', 'records_per_page'], 'integer'],
            [['position'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => self::STATUS['DRAFT']],
            [['records_per_page'], 'default', 'value' => 15],
            [['publish_at'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['publish_at'], 'default', 'value' => date('Y-m-d H:i:s')],
            [['url', 'sort', 'main_template', 'category_template', 'page_template', 'access_read'], 'string', 'max' => 255],
            ['url', 'checkUrl'],
            [['url'], 'match', 'pattern' => '/^[\w-]+$/', 
                'message' => 'The field can contain only latin letters, numbers, and signs "_", "-"'],
            [['author_id'], 'default', 'value' => \Yii::$app->user->id],
            [['settings'], 'string'],
            [['addCategories', 'addPages', 'rltPages', 'rltCategories'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'url' => Yii::t('nsblog', 'Url'),
            'author_id' => Yii::t('app', 'Author ID'),
            'status' => Yii::t('nsblog', 'Status'),
            'tree' => Yii::t('nsblog', 'Tree'),
            'lft' => Yii::t('nsblog', 'Lft'),
            'rgt' => Yii::t('nsblog', 'Rgt'),
            'depth' => Yii::t('nsblog', 'Depth'),
            'parent_id' => Yii::t('nsblog', 'Category'),
            'position' => Yii::t('nsblog', 'Position'),
            'access_read' => Yii::t('nsblog', 'Access Read'),
            'publish_at' => Yii::t('nsblog', 'Publish At'),
            'created_at' => Yii::t('nsblog', 'Created At'),
            'updated_at' => Yii::t('nsblog', 'Updated At'),
            'addCategories' => Yii::t('nsblog', 'Additional Categories'),
            'addPages' => Yii::t('nsblog', 'Additional Pages'),
            'rltCategories' => Yii::t('nsblog', 'Related Categories'),
            'rltPages' => Yii::t('nsblog', 'Related Pages'),
            'records_per_page' => Yii::t('nsblog', 'Record per page'),
        ];
    }
    
    /**
     * Get parent's ID
     * @return \yii\db\ActiveQuery 
     */
    public function getParentId()
    {
        $parent = $this->parent;
        return $parent ? $parent->id : null;
    }

    /**
     * Get parent's node
     * @return \yii\db\ActiveQuery 
     */
    public function getParent()
    {
        return $this->parents(1)->one();
    }

    /**
     * Get a full tree as a list, except the node and its children
     * @param  integer $node_id node's ID
     * @return array array of node
     */
    public static function getTree($node_id = 0, $domain_id = null, $language_id = null)
    {
        // don't include children and the node
        $children = [];

        if ( ! empty($node_id))
            $children = array_merge(
                self::findOne($node_id)->children()->column(),
                [$node_id]
                );
        
        $rows = Category::find()
            ->joinWith(['categoryContent' => function($query) use ($domain_id, $language_id){
                $in = CategoryContent::getAllSuitableId($domain_id, $language_id);
                $query->andWhere(['IN','category_content.id', $in]);
            }])
            ->select(['category.id', 'tree', 'depth', 'lft', 'position', 'category_content.name'])
            ->andWhere(['NOT IN', 'category.id', self::ROOT_ID])
            ->andWhere(['NOT IN', 'category.id', $children])
            ->orderBy('tree, lft, position')
            ->all();
        
        $return = [];
        foreach ($rows as $row)
            $return[$row->id] = str_repeat('-', $row->depth - self::OFFSET_ROOT) . ' ' . $row->categoryContent->name;

        return $return;
    }
    
    /**
     * Get a full tree as a list, except the node and its children
     * @param  integer $node_id node's ID
     * @return array array of node
     */
    public static function getTreeArray($node_id = 0)
    {
        $children = [];

        if ( ! empty($node_id)){
            $children = array_merge(self::findOne($node_id)->children()->column(),[$node_id]);
        }

        $rows = self::find()
            ->where(['NOT IN', 'id', $children])
            ->orderBy('tree, lft, position')
            ->asArray()
            ->all();

        $return  = [];
        $last_id = null;
        $level   = 0;
        
        foreach ($rows as $row){
            if($last_id && $row['depth'] > $level){
                $return[$last_id]['child'] = $row;
            }
            else{
                $return[$row['id']] = $row;
            }
            $level   = $row['depth'];
            $last_id = $row['id'];
        }

        return $return;
    }
    
    public function checkUrl($attribute, $params)
    {       
        if(
            self::find()->where(['url' => $this->url, 'parent_id' => $this->parent_id])->andWhere(['!=', 'id', $this->id])->exists() || 
            Page::find()->where(['category_id' => $this->parent_id, 'url' => $this->url])->exists()
        ){
            $this->addError($attribute, \Yii::t('nsblog/error', 'This Url already exists'));
        }
        
        if(true){}
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
    public function getDomain()
    {
        return $this->hasOne(Domain::className(), ['id' => 'domain_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        return $this->hasOne(Language::className(), ['id' => 'lang_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryContent()
    {
        return $this->hasOne(CategoryContent::className(), ['src_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(\Yii::$app->user->identityClass, ['id' => 'author_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPagesCount()
    {
        return $this->hasMany(Page::className(), ['category_id' => 'id'])->count();
    }
    
    public static function getStatuses(): array
    {
        return [
            Category::STATUS['DRAFT']     => \Yii::t('nsblog', 'Draft'),
            Category::STATUS['PUBLISHED'] => \Yii::t('nsblog', 'Published'),
            Category::STATUS['ARCHIVE']   => \Yii::t('nsblog', 'Archive'),
        ];
    }
    
    public function afterFind() {
        parent::afterFind();
        
        $this->settings = json_decode($this->settings, true);
    }
}
