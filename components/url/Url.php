<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace startpl\t2cmsblog\components\url;

use yii\base\InvalidParamException;

/**
 *
 * @author Koperdog <koperdog@github.com>
 */
abstract class Url extends \yii\base\BaseObject
{
    const ALLOW_ALWAYS = ['everyone', 'user'];
    
    protected $routeName = "route";
    protected $urlPath   = 'blog/index';
        
    public $owner;
        
    public function createUrl($params): string
    {
        if (empty($params['id'])) throw new InvalidParamException('Empty id.');
        $id = $params['id'];
        
        if (!$model = static::$repository->get($id)) {
            return null;
        }
        
        $url = $this->getPath($model);
        
        if (!$url) {
            throw new InvalidParamException('Undefined id.');
        }
                
        $url = $this->owner->prefix . '/' . $url;

        unset($params['id']);
        if (!empty($params) && ($query = http_build_query($params)) !== '') {
            $url .= '?' . $query;
        }
        return $url;
    }
    
    public function parseRequest($path)
    {       
        if (!$model = static::$repository->getByPath($path)) {
            $result = ['id' => null, 'path' => null];
        } else {
            if(!$this->isActive($model) || !$this->checkAccess($model)) return false;
            $result = ['id' => $model->id];
        }
        
        if (empty($result['id'])) {
            return false;
        }
                
        return [$this->urlPath, ['id' => $result['id']]];
    }
    
    abstract protected function getPath($model): string;
}
