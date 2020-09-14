<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model startpl\t2cmsblog\models\Page */

$this->title = $model->id;

foreach($model->parents as $key => $category){
    $this->params['breadcrumbs'][] = ['url' => ['/blog/category', 'id' => $category->id], 'label' => $category->categoryContent->name];
}

//$this->params['breadcrumbs'][] = ['label' => Yii::t('nsblog', 'Blog'), 'url' => ['/blog']];
//$this->params['breadcrumbs'][] = ['label' => Yii::t('nsblog', 'Pages'), 'url' => ['index']];

$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="page-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'url:url',
            'author_id',
            'position',
            'publish_at',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
