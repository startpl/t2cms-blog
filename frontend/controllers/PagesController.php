<?php

namespace startpl\t2cmsblog\frontend\controllers;

use Yii;
use startpl\t2cmsblog\models\{
    Page,
    Category
};
use startpl\t2cmsblog\models\PageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PagesController extends Controller
{
    private $categoryRepository;
    
    public function __construct(
        $id, 
        $module, 
        \startpl\t2cmsblog\repositories\CategoryRepository $categoryRepository,
        $config = array()
    ) {
        parent::__construct($id, $module, $config);
        
        $this->viewPath = '@themePath/blog/pages';
        $this->categoryRepository = $categoryRepository;
    }
    
    public function actionIndex() 
    {
        return 'da';
    }
    
    /**
     * Displays a single Page model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            if($model->category->id !== Category::ROOT_ID){
                $parents = $this->categoryRepository->getParents($model->category);
                $model->parents = array_merge($parents, [$model->category]);
            } else {
                $model->parents = [];
            }
            
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('nsblog', 'The requested page does not exist.'));
    }
}
