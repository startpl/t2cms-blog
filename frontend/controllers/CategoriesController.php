<?php

namespace startpl\t2cmsblog\frontend\controllers;

use Yii;
use startpl\t2cmsblog\models\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoriesController extends Controller
{
    
    private $categoryService;
    private $categoryRepository;
    
    public function __construct
    (
        $id, 
        $module, 
        \startpl\t2cmsblog\useCases\CategoryService $categoryService,
        \startpl\t2cmsblog\repositories\CategoryRepository $categoryRepository,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        
        $this->categoryService    = $categoryService;
        $this->categoryRepository = $categoryRepository;
    }
    
    public function actionView($id)
    {
        $model   = $this->findModel($id);
        
        return $this->render('view', ['model' => $model]);
    }
    
    private function findModel(int $id): Category
    {
        try{
            $model          = $this->categoryRepository->get($id);
            $model->parents = $this->categoryRepository->getParents($model);
        } catch (\DomainException $e){
            throw new NotFoundHttpException("Not Found");
        }
        
        return $model;
    }
}
