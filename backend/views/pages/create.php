<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model startpl\t2cmsblog\models\Page */

$this->title = Yii::t('nsblog', 'Create Page');
$this->params['breadcrumbs'][] = ['label' => Yii::t('nsblog', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

startpl\t2cmsblog\AssetBundle::register($this);
?>
<div class="page-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'allCategories' => $allCategories,
        'allPages' => $allPages,
    ]) ?>

</div>
