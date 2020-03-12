<?php

namespace startpl\t2cmsblog\repositories\read;
use startpl\t2cmsblog\models\{
    Page,
    PageContentQuery
};

/**
 * Description of CategoryRepository
 *
 * @author Koperdog <koperdog@github.com>
 * @version 1.0
 */
class PageReadRepository {
    
    public static function get(int $id, $domain_id = null, $language_id = null): ?array
    {
        $model = Page::find()
            ->with(['pageContent' => function($query) use ($id, $domain_id, $language_id){
                $query->andWhere(['id' => PageContentQuery::getId($id, $domain_id, $language_id)->one()]);
            }])
            ->andWhere(['page.id' => $id])
            ->asArray()
            ->one();
        
        return $model;
    }
    
    public static function getAllByCategory(int $category, $domain_id = null, $language_id = null): ?array
    {
        return Page::find()
            ->joinWith(['pageContent' => function($query) use ($domain_id, $language_id){
                $in = \yii\helpers\ArrayHelper::getColumn(PageContentQuery::getAllId($domain_id, $language_id)->asArray()->all(), 'id');
                $query->andWhere(['IN','page_content.id', $in]);
            }])
            ->andWhere(['category_id' => $category])
            ->asArray()
            ->all();
    }
    
    public static function getAll($domain_id = null, $language_id = null): ?array
    {
        return Page::find()
            ->joinWith(['pageContent' => function($query) use ($domain_id, $language_id){
                $in = \yii\helpers\ArrayHelper::getColumn(PageContentQuery::getAllId($domain_id, $language_id)->asArray()->all(), 'id');
                $query->andWhere(['IN','page_content.id', $in]);
            }])
            ->andFilterWhere(['NOT IN', 'page.id', $exclude])
            ->asArray()
            ->all();
    }
}
