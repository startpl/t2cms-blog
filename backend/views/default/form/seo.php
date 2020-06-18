<?php

use startpl\t2cmsblog\hooks\CategoryForm;

/**
 * @var $this yii\web\View
 * @var form yii\widgets\ActiveForm;
 * @var $model startpl\t2cmsblog\models\Category
 */
?>
<div class="form__field js-field-counter">
    <?= $form->field($model->categoryContent, 'title', ['options' => ['id' => 'field-title']])->textInput(['maxlength' => true]) ?>
    <div class="form__field-counter"><?=mb_strlen($model->categoryContent->description)?></div>
</div>
<div class="form__field js-field-counter">
    <?= $form->field($model->categoryContent, 'description')->textarea() ?>
    <div class="form__field-counter"><?=mb_strlen($model->categoryContent->description)?></div>
</div>

<?= $form->field($model->categoryContent, 'tags')->textInput(['maxlength' => true]) ?>

<?= $form->field($model->categoryContent, 'keywords')->textInput(['maxlength' => true]) ?>

<?= $form->field($model->categoryContent, 'og_title', ['options' => ['id' => 'field-og_title']])->textInput(['maxlength' => true]) ?>
<?= $form->field($model->categoryContent, 'og_description')->textarea(['options' => ['id' => 'field-og_description']]) ?>
<?= $form->field($model->categoryContent, 'og_url', ['options' => ['id' => 'field-og_url']])->textInput(['maxlength' => true]) ?>
<?= $form->field($model->categoryContent, 'og_sitename', ['options' => ['id' => 'field-og_sitename']])->textInput(['maxlength' => true]) ?>
<?= $form->field($model->categoryContent, 'og_type', ['options' => ['id' => 'field-og_type']])->textInput(['maxlength' => true]) ?>

<?php foreach(CategoryForm::getSeoSections() as $title => $section): ?>
<div class="panel panel-default">
    <div class="panel-heading"><?=\Yii::t('t2cms', $title);?></div>
    <div class="panel-body"><?=$section?></div>
</div>
<?php endforeach;?>