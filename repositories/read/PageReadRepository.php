<?php

namespace startpl\t2cmsblog\repositories\read;
use startpl\t2cmsblog\models\Page;

/**
 * Description of CategoryRepository
 *
 * @author Koperdog <koperdog@github.com>
 * @version 1.0
 */
class PageReadRepository {
    
    public static function get(int $id, $domain_id = null, $language_id = null): ?array
    {
        return Page::find()->withContent($id, $domain_id, $language_id)->asArray()->one();
    }
    
    public static function getAllByCategory(int $category, $domain_id = null, $language_id = null): ?array
    {
        return Page::find()
            ->joinWith(['pageContent' => function($query) use ($domain_id, $language_id){
                $in = PageContent::getAllSuitableId($domain_id, $language_id);
                $query->andWhere(['IN','page_content.id', $in]);
            }])
            ->andWhere(['category_id' => $category])
            ->asArray()
            ->all();
    }
    
    public static function getAll($domain_id = null, $language_id = null, $exclude = null): ?array
    {                   
        return Page::find()->withAllContent($domain_id, $language_id, $exclude)->asArray()->all();
    }
}
