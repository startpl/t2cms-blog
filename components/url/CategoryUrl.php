<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace startpl\t2cmsblog\components\url;

use startpl\t2cmsblog\repositories\CategoryRepository;
use yii\helpers\ArrayHelper;
use startpl\t2cmsblog\models\Category;

/**
 * Description of CategoryUrl
 *
 * @author Koperdog <koperdog@github.com>
 * @version 1.0
 */
class CategoryUrl extends Url {
    
    protected $routeName = 'category_route';
    
    protected $urlPath   = 'blog/categories/view';
    
    public static $repository;
    
    public function __construct(CategoryRepository $repository, $config = [])
    {
        parent::__construct($config);
        self::$repository = $repository;
    }
    
    protected function getPath($category): string
    {
        $sections = ArrayHelper::getColumn($category->parents()->andWhere(['>=', 'depth', Category::OFFSET_ROOT])->all(), 'url');
        $sections[] = $category->url;
        return implode('/', array_filter($sections));
    }
    
    protected function isActive(Category $model): bool
    {
        $sections   = $model->parents()->andWhere(['>=', 'depth', Category::OFFSET_ROOT])->all();
        $sections[] = $model;
        
        foreach($sections as $category){
            if($category->status != Category::STATUS['PUBLISHED'] || strtotime($category->publish_at) > time()){
                return false;
            }
        }
                
        return true;
    }
    
    protected function checkAccess(Category $model): bool
    {        
        $sections   = $model->parents()->andWhere(['>=', 'depth', Category::OFFSET_ROOT])->all();
        $sections[] = $model;
        
        foreach($sections as $category){
            if(!in_array($category->access_read, self::ALLOW_ALWAYS) && !(\Yii::$app->user->can($category->access_read))) {
                return false;
            }
        }
        
        return true;
    }
}
