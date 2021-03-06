<?php

namespace startpl\t2cmsblog\repositories;

use \yii\helpers\ArrayHelper;
use startpl\t2cmsblog\models\{
    CategorySearch,
    Category,
    CategoryContent
};
use startpl\t2cmsblog\interfaces\IEventRepository;
// use startpl\t2cmsblog\dto\Category;

/**
 * Description of CategoryRepository
 *
 * @author Koperdog <koperdog@github.com>
 * @version 1.0
 */
class CategoryRepository extends \yii\base\Component implements IEventRepository
{   
    private $eventDispatcher;
    private $searchModel = null;
        
    public function __construct(\startpl\t2cmsblog\events\EventDispatcher $dispatcher, $config = array()) 
    {
        parent::__construct($config);
                
        $this->eventDispatcher = $dispatcher;
    }
    
    public function getSearchModel(): ?CategorySearch
    {
        return $this->searchModel;
    }
    
    public function get(int $id, $domain_id = null, $language_id = null): Category
    {        
        $model = Category::find()->withContent($id, $domain_id, $language_id)->one();
        
        if(!$model){
            throw new \DomainException("Category with id: {$id} was not found");
        }
        
        $event = new \startpl\t2cmsblog\events\category\GetEvent([
            'model' => $model
        ]);
        $this->eventDispatcher->trigger(IEventRepository::EVENT_GET, $event);
        
        return $model;
    }
    
    public function getParents(Category $model): ?array
    {
        $parents = $model->parents()->all();
        array_shift($parents); // offset depth shift 
        return $parents;
    }
    
    public function getParentNodeById(int $id): Category
    {
        if(!$model = Category::findOne($id)->parents(1)->one()){
            throw new \DomainException("Category have not parents");
        }
        
        return $model;
    }
    
    public function getParentNode(Category $model): Categor
    {
        if(!$model = $model->parents(1)->one()){
            throw new \DomainException("Category have not parents");
        }
        
        return $model;
    }
    
    public function search(array $params = [], $domain_id = null, $language_id = null): \yii\data\BaseDataProvider
    {
        $this->searchModel = new CategorySearch();
        $dataProvider = $this->searchModel->search($params, $domain_id, $language_id);
        
        $event = new \startpl\t2cmsblog\events\category\SearchEvent([
            'dataProvider' => $dataProvider
        ]);
        $this->eventDispatcher->trigger(IEventRepository::EVENT_SEARCH, $event);
        
        return $dataProvider;
    }
    
    public function save($model): bool
    {
        if(!$model->save()){
            throw new \RuntimeException('Error saving model');
        }
        
        if($model instanceof Category) {
            $event = new \startpl\t2cmsblog\events\category\SaveEvent([
                'model' => $model
            ]);
            $this->eventDispatcher->trigger(IEventRepository::EVENT_SAVE, $event);
        }
        
        return true;
    }
    
    public function saveContent(CategoryContent $model, $domain_id = null, $language_id = null): bool
    {
        if(($model->domain_id != $domain_id || $model->language_id != $language_id) && $model->getDirtyAttributes())
        {
            return $this->copyCategoryContent($model, $domain_id, $language_id);
        }
        
        return $this->save($model);
    }
    
    public function link(string $name, $target, $model): void
    {
        $model->link($name, $target);
    }
    
    public function appendTo(Category $model): bool
    {
        $parent = $this->get($model->parent_id);
        
        if(!$model->appendTo($parent)){
            throw new \RuntimeException("Error append model");
        } 
        
        $event = new \startpl\t2cmsblog\events\category\SaveEvent([
            'model' => $model
        ]);
        $this->eventDispatcher->trigger(IEventRepository::EVENT_SAVE, $event);
        
        return true;
    }
    
    public function setPosition(Category $model, Category $parentNode): bool
    {
        if(!$model->appendTo($parentNode, false)){
            throw new \RuntimeException('Error saving model');
        }
        
        return true;
    }
    
    public function getByPath(string $path): ?Category
    {
        $sections = explode('/', $path);
        
        $category = Category::find()
                ->where(['url' => array_shift($sections), 'depth' => Category::OFFSET_ROOT])
                ->one();
        
        $offset = Category::OFFSET_ROOT + 1; // +1 because array shift from sections
        
        foreach($sections as $key => $section){
            if($category){
                $category = $category->children(1)->where(['url' => $section, 'depth' => $key + $offset])->one();
            }
        }
        
        return $category;
    }
    
    public function setStatus(array $id, bool $status): void
    {
        $status = ['status' => $status? Category::STATUS['PUBLISHED'] : Category::STATUS['ARCHIVE']];
        
        if(!Category::updateAll($status, ['id' => $id])){
            throw new \RuntimeException("Error update status");
        }
    }
    
    public function delete(array $data): void 
    {
        foreach($data as $id){
            $model = $this->get($id);
            $this->deleteCategory($model);
        }
    }
    
    private function deleteCategory(Category $model): bool
    {        
        $id = $model->id;
        
        if ($model->isRoot()){
            $result = $model->deleteWithChildren();
        }
        else{
            $result = $model->delete();
        }
        
        if($result){
            \startpl\t2cmsblog\models\CategoryAssign::deleteAll(['OR', 
                ['category_id' => $id], 
                ['resource_id' =>  $id, 'source_type' => Category::SOURCE_TYPE]
            ]);
            \startpl\t2cmsblog\models\PageAssign::deleteAll(['resource_id' =>  $id, 'source_type' => Category::SOURCE_TYPE]);
        }
        else{
            throw new \RuntimeException("Error delete");
            return false;
        }
        
        $event = new \startpl\t2cmsblog\events\category\DeleteEvent([
            'model' => $model
        ]);
        $this->eventDispatcher->trigger(IEventRepository::EVENT_DELETE, $event);
        
        return true;
    }
    
    public static function getAll($domain_id = null, $language_id = null, $exclude = []): ?array
    {
        array_push(ArrayHelper::toArray($exclude), Category::ROOT_ID);
        $models = Category::find()->withAllContent($domain_id, $language_id, $exclude)->all();
        
        $event = new \startpl\t2cmsblog\events\category\GetAllEvent([
            'models' => $models
        ]);
        try {
            \yii\base\Event::trigger(static::className(), IEventRepository::EVENT_GET_ALL, $event);
        } catch (\Exception $e) {
            // do nothing
        }
        
        return $models;
    }
    
    private function copyCategoryContent(\yii\db\ActiveRecord $model, $domain_id, $language_id)
    {
        $newContent = new CategoryContent();
        $newContent->attributes = $model->attributes;
        
        $newContent->domain_id   = $domain_id;
        $newContent->language_id = $language_id;
        
        return $this->save($newContent);
    }
}
