<?php

namespace startpl\t2cmsblog\useCases;

use \startpl\t2cmsblog\repositories\CategoryRepository;
use \startpl\t2cmsblog\models\{
    forms\CategoryForm,
    Category,
    CategoryContent
};

/**
 * Description of CategoryService
 *
 * @author Koperdog <koperdog@github.com>
 * @version 1.0
 */
class CategoryService {
    
    private $repository;
    
    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function get(int $id): ?Category
    {
        $model = Category::find()
                ->with('additionalPages')
                ->with('additionalCategories')
                ->where(['id' => $id])
                ->one();
        
        $model->addCategories = $model->additionalCategories;
        $model->addPages      = $model->additionalPages;
        
        $model->rltCategories = $model->relatedCategories;
        $model->rltPages      = $model->relatedPages;
        
        return $model;
    }
    
    public function save(Category $model, \yii\base\Model $form, $domain_id = null, $language_id = null): bool
    {
        $model->load($form->attributes, '');
        $model->categoryContent->load($form->categoryContent->attributes, ''); 
        
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            if($model->parent_id != $model->oldAttributes['parent_id'] && ($model->id != $model->parent_id)) {
                if(empty($model->parent_id)) $model->parent_id = 1;
                $this->repository->appendTo($model);
            } else {
                $this->repository->save($model);
            }
            
            $this->repository->saveContent($model->categoryContent, $domain_id, $language_id);
            
            $transaction->commit();
        } catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
        
        return true;
    }
    
    public function create(CategoryForm $form): ?Category
    {
        $category = new Category();
        $category->attributes = $form->attributes;
        if(empty($form->parent_id)) $category->parent_id = 1;
        
        $categoryContent = new CategoryContent();
        $categoryContent->attributes = $form->categoryContent->attributes;
        
        $categoryContent->domain_id   = null;
        $categoryContent->language_id = null;
                
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $this->repository->appendTo($category);
            $this->repository->link('category', $category, $categoryContent);
            $transaction->commit();
        } catch(\Exception $e){
            $transaction->rollBack();
            return null;
        }
        
        return $category;
    }
    
    public function sort(array $data): int
    {
        $result = 0;
        
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $parentNode = $this->repository->getParentNodeById($data[0]);
            
            foreach($data as $index => $value){
                $category = $this->repository->get($value);
                $category->position = (int)$index;
                                
                $this->repository->setPosition($category, $parentNode);
                $result++;
            }
            
            $transaction->commit();
        } catch(\Exception $e){
            $transaction->rollBack();
            return 0;
        }
        
        return $result;
    }
    
    public function changeStatus(array $data): bool
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $this->repository->setStatus($data['id'], $data['status']);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
        
        return true;
    }
    
    public function delete(array $data): bool
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $this->repository->delete($data);
            $transaction->commit();
        } catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
        
        return true;
    }
}
