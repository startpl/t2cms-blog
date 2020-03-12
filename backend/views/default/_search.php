<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model startpl\t2cmsblog\models\CategorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'url') ?>

    <?= $form->field($model, 'author_id') ?>

    <?= $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'h1') ?>

    <?php // echo $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'preview_text') ?>

    <?php // echo $form->field($model, 'full_text') ?>

    <?php // echo $form->field($model, 'tree') ?>

    <?php // echo $form->field($model, 'lft') ?>

    <?php // echo $form->field($model, 'rgt') ?>

    <?php // echo $form->field($model, 'depth') ?>

    <?php // echo $form->field($model, 'position') ?>

    <?php // echo $form->field($model, 'access_read') ?>

    <?php // echo $form->field($model, 'domain_id') ?>

    <?php // echo $form->field($model, 'lang_id') ?>

    <?php // echo $form->field($model, 'publish_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('nsblog', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('nsblog', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
