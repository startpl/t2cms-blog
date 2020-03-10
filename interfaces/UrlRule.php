<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace startpl\t2cmsblog\interfaces;

use yii\base\InvalidParamException;

/**
 *
 * @author Koperdog <koperdog@github.com>
 */
abstract class UrlRule {
    
    protected $routeName = "route";
    
    public function createUrl($params): string
    {
        if (empty($params['id'])) throw new InvalidParamException('Empty id.');
        $id = $params['id'];

        $url = \Yii::$app->cache->getOrSet(['category_route', 'id' => $id], function() use ($id) {
            if (!$category = $this->categoryRepository->get($id)) {
                return null;
            }
            return $this->getCategoryPath($category);
        });

        if (!$url) {
            throw new InvalidParamException('Undefined id.');
        }

        $url = $this->prefix . '/' . $url;

        unset($params['id']);
        if (!empty($params) && ($query = http_build_query($params)) !== '') {
            $url .= '?' . $query;
        }
        return $url;
    }
    
    
}
