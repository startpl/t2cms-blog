<?php

namespace startpl\t2cmsblog\components;

use yii\base\BaseObject;
use yii\web\UrlRuleInterface;

class PageUrlRule extends BaseObject implements UrlRuleInterface
{
    public $prefix = '';
    
    private static $page;
    
    public function createUrl($manager, $route, $params)
    {
        if ($route === 'blog/page') {
            return $this->getManager()->createUrl($params);
        }
        
        return false;
    }

    public function parseRequest($manager, $request)
    {
        if (preg_match('#^' . $this->prefix . '/?(.*[a-z0-9\-\_])/?$#is', $request->pathInfo, $matches)) {
            $path = $matches['1'];
            
            if($result = $this->getManager()->parseRequest($path)){
                return $result;
            }
            
        }
        
        return false;
    }
    
    private function getManager()
    {
        if(!self::$page) {
            self::$page = \Yii::createObject(['class' => url\PageUrl::className(), 'owner' => $this]);
        }
        
        return self::$page;
    }
}