<?php

namespace startpl\t2cmsblog\components;

use yii\base\BaseObject;
use yii\web\UrlRuleInterface;

class CategoryUrlRule extends BaseObject implements UrlRuleInterface
{
    public $prefix = '';
    
    private static $category;   
            
    public function createUrl($manager, $route, $params)
    {
        if ($route === 'blog/category') {
            return $this->getManager()->createUrl($params);
        }
        
        return false;
    }

    public function parseRequest($manager, $request)
    {
        if (preg_match('#^' . $this->prefix . '/?(.*[a-z0-9\-\_])/?$#is', $request->pathInfo, $matches)) {
            $path = $matches[1];
            
            if($result = self::getManager()->parseRequest($path)){
                return $result;
            }
        }
        
        return false;
    }
    
    private function getManager()
    {
        if(!self::$category) {
            self::$category = \Yii::createObject(['class' => url\CategoryUrl::className(), 'owner' => $this]);
        }
        
        return self::$category;
    }
}