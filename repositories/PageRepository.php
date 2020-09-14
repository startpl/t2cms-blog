<?php

namespace startpl\t2cmsblog\repositories;

use startpl\t2cmsblog\models\{
    Page,
    Category,
    PageSearch,
    PageContent
};

use startpl\t2cmsblog\interfaces\IEventRepository;

/**
 * Description of CategoryRepository
 *
 * @author Koperdog <koperdog@github.com>
 * @version 1.0
 */
class PageRepository extends \yii\base\Component implements IEventRepository
{
    
    private $searchModel = null;
    
    public function getSearchModel(): ?PageSearch
    {
        return $this->searchModel;
    }
    
    public function get(int $id, $domain_id = null, $language_id = null): Page
    {
        
        $model = Page::find()->withContent($id, $domain_id, $language_id)->one();
        
        if(!$model){
            throw new \DomainException("Page with id: {$id} was not found");
        }
        
        $event = new \startpl\t2cmsblog\events\page\GetEvent([
            'model' => $model
        ]);
        $this->trigger(IEventRepository::EVENT_GET, $event);
        
        return $model;
    }
    
    public function search(array $params = [], $domain_id = null, $language_id = null): \yii\data\BaseDataProvider
    {
        $this->searchModel = new PageSearch();
        $dataProvider = $this->searchModel->search($params, $domain_id, $language_id);
        
        $event = new \startpl\t2cmsblog\events\page\SearchEvent([
            'dataProvider' => $dataProvider
        ]);
        $this->trigger(IEventRepository::EVENT_SEARCH, $event);
        
        return $dataProvider;
    }
    
    public function save(\yii\db\ActiveRecord $model): bool
    {
        if(!$model->save()){
            throw new \RuntimeException('Error saving model');
        }
        
        if($model instanceof Page) {
            $event = new \startpl\t2cmsblog\events\page\SaveEvent([
                'model' => $model
            ]);
            $this->trigger(IEventRepository::EVENT_SAVE, $event);
        }
        
        return true;
    }
    
    public function link(string $name, $target, $model): void
    {
        $model->link($name, $target);
    }
    
    public function saveContent(PageContent $model, $domain_id = null, $language_id = null): bool
    {
        if(($model->domain_id != $domain_id || $model->language_id != $language_id) && $model->getDirtyAttributes())
        {
            return $this->copyPageContent($model, $domain_id, $language_id);
        }
        
        return $this->save($model);
    }
    
    public function setStatus(array $id, bool $status): void
    {
        $status = ['status' => $status? Page::STATUS['PUBLISHED'] : Page::STATUS['ARCHIVE']];
        
        if(!Page::updateAll($status, ['id' => $id])){
            throw new \RuntimeException("Error update status");
        }
    }
    
    public function delete(array $data): void 
    {
        if(Page::deleteAll(['id' => $data])){
            \startpl\t2cmsblog\models\PageAssign::deleteAll(['OR', 
                ['page_id' => $data], 
                ['resource_id' =>  $data, 'source_type' => Page::SOURCE_TYPE]
            ]);
            \startpl\t2cmsblog\models\CategoryAssign::deleteAll(['resource_id' =>  $data, 'source_type' => Page::SOURCE_TYPE]);
        }
        else{
            throw new \RuntimeException("Error delete");
        }
        
        $event = new \startpl\t2cmsblog\events\page\DeleteEvent([
            'id' => $data
        ]);
        $this->trigger(IEventRepository::EVENT_DELETE, $event);
    }
    
    public static function getAll($domain_id = null, $language_id = null, $exclude = null): ?array
    {          
        $models = Page::find()->withAllContent($domain_id, $language_id, $exclude)->all();
        
        $event = new \startpl\t2cmsblog\events\page\GetAllEvent([
            'models' => $models
        ]);
        \yii\base\Event::trigger(static::className(), IEventRepository::EVENT_GET_ALL, $event);
        
        return $models;
    }
    
    public static function getAllByCategory(int $category, $domain_id = null, $language_id = null): ?array
    {
        $models = Page::find()
            ->joinWith(['pageContent' => function($query) use ($domain_id, $language_id){
                $in = PageContent::getAllSuitableId($domain_id, $language_id);
                $query->andWhere(['IN','page_content.id', $in]);
            }])
            ->andWhere(['category_id' => $category])
            ->all();
            
        $event = new \startpl\t2cmsblog\events\page\GetAllEvent([
            'models' => $models
        ]);
        \yii\base\Event::trigger(static::className(), IEventRepository::EVENT_GET_ALL, $event);
        
        return $models;
    }
    
    public function getByPath(string $path): ?Page
    {
        $sections = explode('/', $path);
        
        $page = array_pop($sections);
        
        if(count($sections) > 0){
            $category = Category::find()
                    ->where(['url' => array_shift($sections), 'depth' => Category::OFFSET_ROOT])
                    ->one();

            $offset = Category::OFFSET_ROOT + 1; // +1 because array shift from sections

            foreach($sections as $key => $section){
                if($category){
                    $category = $category->children(1)->where(['url' => $section, 'depth' => $key + $offset])->one();
                }
            }
            $categoryId = $category->id;
        } else {
            $categoryId = Category::ROOT_ID;
        }
        
        return Page::find()->where(['url' => $page, 'category_id' => $categoryId])->one();
    }
    
    private function copyPageContent(\yii\db\ActiveRecord $model, $domain_id, $language_id)
    {
        $newContent = new PageContent();
        $newContent->attributes = $model->attributes;
        
        $newContent->domain_id   = $domain_id;
        $newContent->language_id = $language_id;
        
        return $this->save($newContent);
    }
}