<?php

namespace startpl\t2cmsblog\frontend\controllers;

use Yii;
use startpl\t2cmsblog\models\Page;
use startpl\t2cmsblog\models\PageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PagesController extends Controller
{
    public function __construct($id, $module, $config = array()) {
        parent::__construct($id, $module, $config);
        
        $this->viewPath = '@themePath/blog/pages';
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
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('nsblog', 'The requested page does not exist.'));
    }
}
