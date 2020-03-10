<?php

namespace startpl\t2cmsblog\components;

use yii\base\BaseObject;
use yii\web\UrlRuleInterface;

class PageUrlRule extends BaseObject implements UrlRuleInterface
{
    public $prefix = '';
    
    private $page;
    
    private $repository;
        
    public function init()
    {
        $this->initManager();
    }
    
    public function createUrl($manager, $route, $params)
    {
        if ($route === 'blog/category') {
            return $this->page->createUrl($params);
        }
        
        return false;
    }

    public function parseRequest($manager, $request)
    {
        \Yii::$app->cache->flush();
        if (preg_match('#^' . $this->prefix . '/?(.*[a-z0-9\-\_])/?$#is', $request->pathInfo, $matches)) {
            $path = $matches['1'];
            
            if($result = $this->page->parseRequest($path)){
                return $result;
            }
        }
        
        return false;
    }
    
    private function initManager()
    {
        $this->page = \Yii::createObject(['class' => url\PageUrl::className(), 'owner' => $this]);
    }
}