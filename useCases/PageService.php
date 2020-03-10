<?php

namespace startpl\t2cmsblog\useCases;

use \startpl\t2cmsblog\repositories\PageRepository;
use startpl\t2cmsblog\models\{
    Page,
    PageContent,
    forms\PageForm
};

/**
 * Description of CategoryService
 *
 * @author Koperdog <koperdog@github.com>
 * @version 1.0
 */
class PageService {
    
    private $repository;
    
    public function __construct(PageRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function save(Page $model, \yii\base\Model $form, $domain_id = null, $language_id = null): bool
    {
        $model->load($form->attributes, '');
        $model->pageContent->load($form->pageContent->attributes, ''); 
        
        if($model->getDirtyAttributes(['category_id']) && ($model->id != $model->category_id)){
            if(empty($model->category_id)) $model->category_id = 1;
        }
        
            
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $this->repository->save($model);
            $this->repository->saveContent($model->pageContent, $domain_id, $language_id);
            
            $transaction->commit();
        } catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
        
        return true;
    }
    
    public function create(PageForm $form)
    {
        $page = new Page();
        $page->attributes = $form->attributes;
        if(empty($page->category_id)) $page->category_id = 1;
        
        $pageContent = new PageContent();
        $pageContent->attributes = $form->pageContent->attributes;
        
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $this->repository->save($page);
            $this->repository->link('page', $page, $pageContent);
            $transaction->commit();
        } catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
        
        return $page;
    }
    
    public function delete(array $id): bool
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $this->repository->delete($id);
            $transaction->commit();
        } catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
        
        return true;
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
}
