<?php

namespace startpl\t2cmsblog\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use startpl\t2cmsblog\models\CategoryContent;

/**
 * CategorySearch represents the model behind the search form of `startpl\t2cmsblog\models\Category`.
 */
class CategorySearch extends CategoryContent
{
    public $url;
    public $status;
    public $author_name;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'status', 'author_name', 'name', 'h1', 'image', 'preview_text', 'full_text'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $domain_id = null, $language_id = null)
    {   
        $this->load($params);
        
        $name = $this->name;
        
        $query = Category::find()
            ->joinWith(['categoryContent' => function($query) use ($domain_id, $language_id){
                $in = CategoryContent::getAllSuitableId($domain_id, $language_id, [Category::ROOT_ID]);
                $query->andWhere(['IN','category_content.id', $in]);
            }])
            ->andFilterWhere(['like', 'category_content.name', $name])
            ->andWhere(['!=', 'category.id', Category::ROOT_ID]);
                        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->andFilterWhere(['like', 'category.url', $this->url]);
        $query->andFilterWhere(['category.status' => $this->status]);
        $query->andFilterWhere(['like', 'user.username', $this->author_name]);
        
        $query->joinWith('author');
       
        $query->orderBy(['category.tree' => SORT_ASC, 'category.lft' => SORT_ASC]);
        
        return $dataProvider;
    }
}
