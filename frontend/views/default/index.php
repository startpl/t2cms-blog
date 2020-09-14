<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model startpl\t2cmsblog\models\Page */

$this->title = 'Blog page';

$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="blog-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="categories_wrapper">
        <h2>Категории</h2>
        <?= yii\widgets\ListView::widget([
            'options' => ['class' => 'page-list'],
            'dataProvider' => $categories,
            'itemView' => '_categories',
        ]);?>
    </div>

    <div class="pages_wrapper">
        <h2>Страницы</h2>
        <?= yii\widgets\ListView::widget([
            'options' => ['class' => 'page-list'],
            'dataProvider' => $pages,
            'itemView' => '_pages',
        ]);?>
    </div>

</div>
