<?php

namespace startpl\t2cmsblog\frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use startpl\t2cmsblog\models\{
    Category,
    Page
};
use startpl\t2cmsblog\repositories\{
    CategoryRepository,
    PageRepository  
};


/**
 * PageController implements the CRUD actions for Page model.
 */
class DefaultController extends Controller
{
    private $categoryRepository;
    private $pageRepository;
    
    public function __construct(
        $id, 
        $module, 
        CategoryRepository $categoryRepository,
        PageRepository $pageRepository,
        $config = array()
    ) {
        parent::__construct($id, $module, $config);
        
        $this->categoryRepository = $categoryRepository;
        $this->pageRepository = $pageRepository;
        
        $this->viewPath = '@themePath/blog/default';
    }
    
    public function actionIndex() 
    {
        $domain_id = \Yii::$app->params['domain_id'];
        $language_id = \Yii::$app->params['language_id'];
        
        $categories = new ActiveDataProvider([
            'query' => $this->findFirstLevelCategories($domain_id, $language_id),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        
        $pages = new ActiveDataProvider([
            'query' => $this->findPages($domain_id, $language_id),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        
        return $this->render('index', [
            'categories' => $categories,
            'pages' => $pages
        ]);
    }
    
    private function findFirstLevelCategories($domain_id = null, $language_id = null, $exclude = [])
    {
        array_push($exclude, Category::ROOT_ID);
        return Category::find()
            ->where(['NOT IN', 'id', $exclude])
            ->andWhere([
                'status' => Category::STATUS['PUBLISHED'],
                'access_read' => ['user', 'everyone']]
            )
            ->orderBy('created_at DESC')
            ->withAllContent($domain_id, $language_id, $exclude);
    }
    
    private function findPages($domain_id = null, $language_id = null, $exclude = [])
    {
        return Page::find()
            ->andWhere([
                'status' => Category::STATUS['PUBLISHED'],
                'access_read' => ['user', 'everyone']]
            )
            ->orderBy('created_at DESC')
            ->withAllContent($domain_id, $language_id, $exclude);
    }
}
