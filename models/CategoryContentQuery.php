<?php

namespace startpl\t2cmsblog\models;

use startpl\t2cmsblog\models\CategoryContent;
use t2cms\sitemanager\repositories\DomainRepository;

/**
 * This is the ActiveQuery class for [[CategoryContent]].
 *
 * @see CategoryContent
 */
class CategoryContentQuery extends \yii\db\ActiveQuery
{
    public static function get(int $id, $domain_id = null, $language_id = null): \yii\db\ActiveQuery
    {
        $defaultDomain = DomainRepository::getDefaultId();
        
        $query = CategoryContent::find()
                ->andWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => $language_id]);
        
        if($language_id){
            $subquery = CategoryContent::find()
                ->andWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => null])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryContent::tableName())
                ->andWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => $language_id])
                ]);
            
            $query->union($subquery);
        }
                
        if($domain_id != $defaultDomain && $domain_id){
            $subquery = CategoryContent::find()
                ->andWhere(['category_id' => $id, 'domain_id' => $defaultDomain, 'language_id' => $language_id])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryContent::tableName())
                ->andWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => $language_id])
                ->orWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => null])
                ]);
            
            $query->union($subquery);
        }
        
        if($domain_id != $defaultDomain && $domain_id && $language_id){
            $subquery = CategoryContent::find()
                ->andWhere(['category_id' => $id, 'domain_id' => $defaultDomain, 'language_id' => null])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryContent::tableName())
                ->andWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => $language_id])
                ->orWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => null])
                ->orWhere(['category_id' => $id, 'domain_id' => $defaultDomain, 'language_id' => $language_id])
                ]);
            
            $query->union($subquery);
        }
        
        if($domain_id && $language_id){
            $subquery = CategoryContent::find()
                ->andWhere(['domain_id' => null, 'language_id' => $language_id])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryContent::tableName())
                ->andWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => $language_id])
                ->orWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => null])
                ->orWhere(['category_id' => $id, 'domain_id' => $defaultDomain, 'language_id' => $language_id])
                ->orWhere(['category_id' => $id, 'domain_id' => $defaultDomain, 'language_id' => null])
                ]);
            
            $query->union($subquery);
        }
        
        if($domain_id || $language_id){
            
            $exclude = (new \yii\db\Query)
                    ->select('category_id')
                    ->from(CategoryContent::tableName())
                    ->andWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => $language_id]);
            
            if($domain_id) $exclude->orWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => null]);
            
            $exclude->orWhere(['category_id' => $id, 'domain_id' => $defaultDomain, 'language_id' => $language_id])
                ->orWhere(['category_id' => $id, 'domain_id' => $defaultDomain, 'language_id' => null]);
            
            if($language_id) $exclude->orWhere(['domain_id' => null, 'language_id' => $language_id]);
            
            $subquery = CategoryContent::find()
                ->andWhere(['category_id' => $id, 'domain_id' => null, 'language_id' => null])
                ->andWhere(['NOT IN', 'category_id', $exclude]);
            
            $query->union($subquery);
        }
        
        return $query;
    }
    
    public static function getId(int $id, $domain_id = null, $language_id = null): \yii\db\ActiveQuery
    {
        $defaultDomain = DomainRepository::getDefaultId();
        
        $query = CategoryContent::find()
                ->select('id')
                ->andWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => $language_id]);
        
        if($language_id){
            $subquery = CategoryContent::find()
                ->select('id')
                ->andWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => null])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryContent::tableName())
                ->andWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => $language_id])
                ]);
            
            $query->union($subquery);
        }
                
        if($domain_id != $defaultDomain && $domain_id){
            $subquery = CategoryContent::find()
                ->select('id')
                ->andWhere(['category_id' => $id, 'domain_id' => $defaultDomain, 'language_id' => $language_id])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryContent::tableName())
                ->andWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => $language_id])
                ->orWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => null])
                ]);
            
            $query->union($subquery);
        }
        
        if($domain_id != $defaultDomain && $domain_id && $language_id){
            $subquery = CategoryContent::find()
                ->select('id')
                ->andWhere(['category_id' => $id, 'domain_id' => $defaultDomain, 'language_id' => null])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryContent::tableName())
                ->andWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => $language_id])
                ->orWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => null])
                ->orWhere(['category_id' => $id, 'domain_id' => $defaultDomain, 'language_id' => $language_id])
                ]);
            
            $query->union($subquery);
        }
        
        if($domain_id && $language_id){
            $subquery = CategoryContent::find()
                ->select('id')
                ->andWhere(['domain_id' => null, 'language_id' => $language_id])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryContent::tableName())
                ->andWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => $language_id])
                ->orWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => null])
                ->orWhere(['category_id' => $id, 'domain_id' => $defaultDomain, 'language_id' => $language_id])
                ->orWhere(['category_id' => $id, 'domain_id' => $defaultDomain, 'language_id' => null])
                ]);
            
            $query->union($subquery);
        }
        
        if($domain_id || $language_id){
            
            $exclude = (new \yii\db\Query)
                    ->select('category_id')
                    ->from(CategoryContent::tableName())
                    ->andWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => $language_id]);
            
            if($domain_id) $exclude->orWhere(['category_id' => $id, 'domain_id' => $domain_id, 'language_id' => null]);
            
            $exclude->orWhere(['category_id' => $id, 'domain_id' => $defaultDomain, 'language_id' => $language_id])
                ->orWhere(['category_id' => $id, 'domain_id' => $defaultDomain, 'language_id' => null]);
            
            if($language_id) $exclude->orWhere(['domain_id' => null, 'language_id' => $language_id]);
            
            $subquery = CategoryContent::find()
                ->select('id')
                ->andWhere(['category_id' => $id, 'domain_id' => null, 'language_id' => null])
                ->andWhere(['NOT IN', 'category_id', $exclude]);
            
            $query->union($subquery);
        }
        
        return $query;
    }
    
    public static function getAll($domain_id = null, $language_id = null): \yii\db\ActiveQuery
    {
        $defaultDomain = DomainRepository::getDefaultId();
        
        $query = CategoryContent::find()
                ->andWhere(['domain_id' => $domain_id, 'language_id' => $language_id]);
        
        if($language_id){
            $subquery = CategoryContent::find()
                ->andWhere(['domain_id' => $domain_id, 'language_id' => null])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryContent::tableName())
                ->andWhere(['domain_id' => $domain_id, 'language_id' => $language_id])
                ]);
            
            $query->union($subquery);
        }
                
        if($domain_id != $defaultDomain && $domain_id){
            $subquery = CategoryContent::find()
                ->andWhere(['domain_id' => $defaultDomain, 'language_id' => $language_id])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryContent::tableName())
                ->andWhere(['domain_id' => $domain_id, 'language_id' => $language_id])
                ->orWhere(['domain_id' => $domain_id, 'language_id' => null])
                ]);
            
            $query->union($subquery);
        }
        
        if($domain_id != $defaultDomain && $domain_id && $language_id){
            $subquery = CategoryContent::find()
                ->andWhere(['domain_id' => $defaultDomain, 'language_id' => null])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryContent::tableName())
                ->andWhere(['domain_id' => $domain_id, 'language_id' => $language_id])
                ->orWhere(['domain_id' => $domain_id, 'language_id' => null])
                ->orWhere(['domain_id' => $defaultDomain, 'language_id' => $language_id])
                ]);
            
            $query->union($subquery);
        }
        
        if($domain_id && $language_id){
            $subquery = CategoryContent::find()
                ->andWhere(['domain_id' => null, 'language_id' => $language_id])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryContent::tableName())
                ->andWhere(['domain_id' => $domain_id, 'language_id' => $language_id])
                ->orWhere(['domain_id' => $domain_id, 'language_id' => null])
                ->orWhere(['domain_id' => $defaultDomain, 'language_id' => $language_id])
                ->orWhere(['domain_id' => $defaultDomain, 'language_id' => null])
                ]);
            
            $query->union($subquery);
        }
        
        if($domain_id || $language_id){
            
            $exclude = (new \yii\db\Query)
                    ->select('category_id')
                    ->from(CategoryContent::tableName())
                    ->andWhere(['domain_id' => $domain_id, 'language_id' => $language_id]);
            
            if($domain_id) $exclude->orWhere(['domain_id' => $domain_id, 'language_id' => null]);
            
            $exclude->orWhere(['domain_id' => $defaultDomain, 'language_id' => $language_id])
                ->orWhere(['domain_id' => $defaultDomain, 'language_id' => null]);
            
            if($language_id) $exclude->orWhere(['domain_id' => null, 'language_id' => $language_id]);
            
            $subquery = CategoryContent::find()
                ->andWhere(['domain_id' => null, 'language_id' => null])
                ->andWhere(['NOT IN', 'category_id', $exclude]);
            
            $query->union($subquery);
        }
        
        return $query;
    }
    
    public static function getAllId($domain_id = null, $language_id = null): \yii\db\ActiveQuery
    {
        $defaultDomain = DomainRepository::getDefaultId();
        
        $query = CategoryContent::find()
                ->select('id')
                ->andWhere(['domain_id' => $domain_id, 'language_id' => $language_id]);
        
        if($language_id){
            $subquery = CategoryContent::find()
                ->select('id')
                ->andWhere(['domain_id' => $domain_id, 'language_id' => null])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryContent::tableName())
                ->andWhere(['domain_id' => $domain_id, 'language_id' => $language_id])
                ]);
            
            $query->union($subquery);
        }
                
        if($domain_id != $defaultDomain && $domain_id){
            $subquery = CategoryContent::find()
                ->select('id')
                ->andWhere(['domain_id' => $defaultDomain, 'language_id' => $language_id])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryContent::tableName())
                ->andWhere(['domain_id' => $domain_id, 'language_id' => $language_id])
                ->orWhere(['domain_id' => $domain_id, 'language_id' => null])
                ]);
            
            $query->union($subquery);
        }
        
        if($domain_id != $defaultDomain && $domain_id && $language_id){
            $subquery = CategoryContent::find()
                ->select('id')
                ->andWhere(['domain_id' => $defaultDomain, 'language_id' => null])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryContent::tableName())
                ->andWhere(['domain_id' => $domain_id, 'language_id' => $language_id])
                ->orWhere(['domain_id' => $domain_id, 'language_id' => null])
                ->orWhere(['domain_id' => $defaultDomain, 'language_id' => $language_id])
                ]);
            
            $query->union($subquery);
        }
        
        if($domain_id && $language_id){
            $subquery = CategoryContent::find()
                ->select('id')
                ->andWhere(['domain_id' => null, 'language_id' => $language_id])
                ->andWhere(['NOT IN', 'category_id', 
                (new \yii\db\Query)
                ->select('category_id')
                ->from(CategoryContent::tableName())
                ->andWhere(['domain_id' => $domain_id, 'language_id' => $language_id])
                ->orWhere(['domain_id' => $domain_id, 'language_id' => null])
                ->orWhere(['domain_id' => $defaultDomain, 'language_id' => $language_id])
                ->orWhere(['domain_id' => $defaultDomain, 'language_id' => null])
                ]);
            
            $query->union($subquery);
        }
        
        if($domain_id || $language_id){
            
            $exclude = (new \yii\db\Query)
                    ->select('category_id')
                    ->from(CategoryContent::tableName())
                    ->andWhere(['domain_id' => $domain_id, 'language_id' => $language_id]);
            
            if($domain_id) $exclude->orWhere(['domain_id' => $domain_id, 'language_id' => null]);
            
            $exclude->orWhere(['domain_id' => $defaultDomain, 'language_id' => $language_id])
                ->orWhere(['domain_id' => $defaultDomain, 'language_id' => null]);
            
            if($language_id) $exclude->orWhere(['domain_id' => null, 'language_id' => $language_id]);
            
            $subquery = CategoryContent::find()
                ->select('id')
                ->andWhere(['domain_id' => null, 'language_id' => null])
                ->andWhere(['NOT IN', 'category_id', $exclude]);
            
            $query->union($subquery);
        }
        
        return $query;
    }
}
