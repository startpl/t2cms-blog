<?php
/**
 * @var $this yii\web\View
 * @var form yii\widgets\ActiveForm;
 * @var $model startpl\t2cmsblog\models\Page
 */
?>

<div class="form__field js-field-counter">
    <?= $form->field($model->pageContent, 'title', ['options' => ['id' => 'field-title']])->textInput(['maxlength' => true]) ?>
    <div class="form__field-counter"><?=mb_strlen($model->pageContent->description)?></div>
</div>
<div class="form__field js-field-counter">
    <?= $form->field($model->pageContent, 'description')->textarea() ?>
    <div class="form__field-counter"><?=mb_strlen($model->pageContent->description)?></div>
</div>

<?= $form->field($model->pageContent, 'keywords')->textInput(['maxlength' => true]) ?>

<?= $form->field($model->pageContent, 'og_title', ['options' => ['id' => 'field-og_title']])->textInput(['maxlength' => true]) ?>
<?= $form->field($model->pageContent, 'og_description')->textarea(['options' => ['id' => 'field-og_description']]) ?>
<?= $form->field($model->pageContent, 'og_url', ['options' => ['id' => 'field-og_url']])->textInput(['maxlength' => true]) ?>
<?= $form->field($model->pageContent, 'og_sitename', ['options' => ['id' => 'field-og_sitename']])->textInput(['maxlength' => true]) ?>
<?= $form->field($model->pageContent, 'og_type', ['options' => ['id' => 'field-og_type']])->textInput(['maxlength' => true]) ?>
