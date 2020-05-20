<?php

namespace startpl\t2cmsblog\repositories\read;

use yii\helpers\ArrayHelper;
use startpl\t2cmsblog\models\{
    Category
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
        return Category::find()->withContent($id, $domain_id, $language_id)->asArray()->one();
    }
    
    public static function getSubcategories(int $id, int $level = 1, $domain_id = null, $language_id = null): ?array
    {
        $category = Category::findOne($id);
        
        if($category){        
            return $category->children($level)
                ->joinWith(['categoryContent' => function($query) use ($domain_id, $language_id){
                    $in = \startpl\t2cmsblog\models\CategoryContent::getAllSuitableId($domain_id, $language_id);
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
        return self::asTree(self::getSubcategories($id, $level, $domain_id, $language_id));
    }
    
    public static function getAll($domain_id = null, $language_id = null, $exclude = []): ?array
    {
        array_push($exclude, Category::ROOT_ID);
        return Category::find()->withAllContent($domain_id, $language_id, $exclude)->asArray()->all();
    }
    
    public static function getAllAsTree($domain_id = null, $language_id = null): ?array
    {
        return Category::getTree(null, $domain_id, $language_id);
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
