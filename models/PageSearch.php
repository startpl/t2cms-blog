<?php

namespace startpl\t2cmsblog\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use startpl\t2cmsblog\models\PageContent;

/**
 * PageSearch represents the model behind the search form of `startpl\t2cmsblog\models\Page`.
 */
class PageSearch extends PageContent
{
    public $url;
    public $status;
    public $author_name;
    public $category;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'status', 'author_name', 'name', 'h1', 'image', 'preview_text', 'full_text', 'category'], 'safe'],
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
        $query = Page::find()
            ->joinWith(['pageContent' => function($query) use ($domain_id, $language_id){
                $in = \yii\helpers\ArrayHelper::getColumn(PageContentQuery::getAllId($domain_id, $language_id)->asArray()->all(), 'id');
                $query->andWhere(['IN','page_content.id', $in]);
            }])
            ->joinWith(['category'])
            ->with(['category.categoryContent' => function($query) use ($domain_id, $language_id){
                $in = \yii\helpers\ArrayHelper::getColumn(CategoryContentQuery::getAllId($domain_id, $language_id)->asArray()->all(), 'id');
                $query->andWhere(['IN','category_content.id', $in]);
            }])
            ->andFilterWhere(['like', 'page_content.name', $this->name]);
            
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);
        
        $this->load($params);
        
        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->joinWith('author');
        
//        debug($this->category);
        
        $query->andFilterWhere(['category_id' => $this->category]);
        $query->andFilterWhere(['like', 'page.url', $this->url]);
        $query->andFilterWhere(['like', 'page_content.name', $this->name]);
        $query->andFilterWhere(['page.status' => $this->status]);
        $query->andFilterWhere(['like', 'user.username', $this->author_name]);

        return $dataProvider;
    }
}
