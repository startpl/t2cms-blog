<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace startpl\t2cmsblog\helpers;

use startpl\t2cmsblog\interfaces\BlogHelper;
use startpl\t2cmsblog\repositories\read\{
    CategoryReadRepository,
    PageReadRepository
};

/**
 * Description of CategoryHelper
 *
 * @author Koperdog <koperdog.dev@gmail.com>
 * @version 1.0
 */
class PageHelper implements BlogHelper{
    
    public static function get(int $id, $domain_id = null, $language_id = null): ?array
    {
        $domain_id   = self::getCurrentDomain($domain_id);
        $language_id = self::getCurrentLanguage($language_id);
        
        return PageReadRepository::get($id, $domain_id, $language_id);
    }
    
    public static function getAll($domain_id = null, $language_id = null): ?array
    {
        $domain_id   = self::getCurrentDomain($domain_id);
        $language_id = self::getCurrentLanguage($language_id);
        
        return PageReadRepository::getAll($domain_id, $language_id);
    }
    
    public static function getAllByCategory(int $category, $domain_id = null, $language_id = null): ?array
    {
        $domain_id   = self::getCurrentDomain($domain_id);
        $language_id = self::getCurrentLanguage($language_id);
        
        return PageReadRepository::getAllByCategory($category, $domain_id, $language_id);
    }
    
    private static function getCurrentDomain($domain_id = null){
        return $domain_id !== null? $domain_id : \Yii::$app->request->cookies->getValue('domain');
    }
    
    private static function getCurrentLanguage($language_id = null){
        return $language_id !== null? $language_id : \Yii::$app->request->cookies->getValue('language');
    }
}
