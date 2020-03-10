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
        return PageReadRepository::get($id, $domain_id, $language_id);
    }
    
    public static function getAll($domain_id = null, $language_id = null): ?array
    {
        return PageReadRepository::getAll($domain_id, $language_id);
    }
    
    public static function getAllByCategory(int $category, $domain_id = null, $language_id = null): ?array
    {
        return PageReadRepository::getAllByCategory($category, $domain_id, $language_id);
    }
}
