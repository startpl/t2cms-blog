<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace startpl\t2cmsblog\components\url;

use startpl\t2cmsblog\repositories\PageRepository;
use yii\helpers\ArrayHelper;
use startpl\t2cmsblog\models\Page;
use startpl\t2cmsblog\models\Category;

/**
 * Description of CategoryUrl
 *
 * @author Koperdog <koperdog@github.com>
 * @version 1.0
 */
class PageUrl extends Url {
    
    protected $routeName = 'page_route';
    
    protected $urlPath   = 'blog/pages/view';
    
    public function __construct(PageRepository $repository, $config = [])
    {
        parent::__construct($config);
        
        $this->repository = $repository;
    }
    
    protected function getPath($page): string
    {
        if(!$page->category_id){
            return $page->url;
        }
        
        if($category = Category::findOne($page->category_id)){
            $sections = ArrayHelper::getColumn($category->parents()->andWhere(['>=', 'depth', Category::OFFSET_ROOT])->all(), 'url');
            $sections[] = $category->url;
            $sections[] = $page->url;
            return implode('/', $sections);
        }
        
        return false;
    }
    
    protected function isActive(Page $model): bool
    {
        $parent = $model->category;
        
        if($parent->id != Category::ROOT_ID){
            $sections   = $parent->parents()->andWhere(['>=', 'depth', Category::OFFSET_ROOT])->all();
            $sections[] = $parent;
            
            foreach($sections as $category){
                if($category->status != Category::STATUS['PUBLISHED'] || strtotime($category->publish_at) > time()){
                    return false;
                }
            }
        }
        
        return (bool)($model->status == Page::STATUS['PUBLISHED'] && strtotime($model->publish_at) < time());
    }
    
    protected function checkAccess(Page $model): bool
    {        
        $parent = $model->category;
        
        if($parent->id != Category::ROOT_ID){
            $sections   = $parent->parents()->andWhere(['>=', 'depth', Category::OFFSET_ROOT])->all();
            $sections[] = $parent;
            
            foreach($sections as $category){
                if(!in_array($category->access_read, self::ALLOW_ALWAYS) && !(\Yii::$app->user->can($category->access_read))) {
                    return false;
                }
            }
        }
               
        return (in_array($model->access_read, self::ALLOW_ALWAYS) || \Yii::$app->user->can($model->access_read));
    }
}
