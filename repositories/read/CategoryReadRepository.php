<?php

namespace startpl\t2cmsblog\repositories\read;

use yii\helpers\ArrayHelper;
use startpl\t2cmsblog\models\{
    Category,
    CategoryContentQuery
};

/**
 * Description of CategoryRepository
 *
 * @author Koperdog <koperdog@github.com>
 * @version 1.0
 */
class CategoryReadRepository {
    
    public function get(int $id, $domain_id = null, $language_id = null): ?array
    {   
        $model = Category::find()
            ->with(['categoryContent' => function($query) use ($id, $domain_id, $language_id){
                $query->andWhere(['id' => CategoryContentQuery::getId($id, $domain_id, $language_id)->one()]);
            }])
            ->andWhere(['category.id' => $id])
            ->asArray()
            ->one();
        
        return $model;
    }
    
    public static function getSubcategories(int $id, int $level = 1, $domain_id = null, $language_id = null): ?array
    {
        $category = Category::findOne($id);
        
        if($category){        
            return $category->children($level)
                ->joinWith(['categoryContent' => function($query) use ($domain_id, $language_id){
                    $in = ArrayHelper::getColumn(CategoryContentQuery::getAllId($domain_id, $language_id)->asArray()->all(), 'id');
                    $query->andWhere(['IN','category_content.id', $in]);
                }])
                ->orderBy('lft')
                ->asArray()
                ->all();
        }
        
        return null;
    }
    
    public static function getSubcategoriesAsTree(int $id, int $level = 1, $domain_id = null, $language_id = null): ?array
    {
        return self::asTree(self::getSubcategories($id, $level));
    }
    
    public static function getAll($domain_id = null, $language_id = null, $exclude = null): ?array
    {
        return Category::find()
            ->joinWith(['categoryContent' => function($query) use ($domain_id, $language_id){
                $in = \yii\helpers\ArrayHelper::getColumn(CategoryContentQuery::getAllId($domain_id, $language_id)->asArray()->all(), 'id');
                $query->andWhere(['IN','category_content.id', $in]);
            }])
            ->andWhere(['NOT IN', 'category.id', 1])
            ->andFilterWhere(['NOT IN', 'category.id', $exclude])
            ->asArray()
            ->all();
    }
    
    private static function asTree(array $models): ?array
    {
        $tree = [];

        foreach ($models as $n) {
            $node = &$tree;

            for ($depth = $models[0]['depth']; $n['depth'] > $depth; $depth++) {
                $node = &$node[count($node) - 1]['children'];
            }
            $n['children'] = null;
            $node[] = $n;
        }
        return $tree;
    }
}
