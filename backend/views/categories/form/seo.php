<?php
/**
 * @var $this yii\web\View
 * @var form yii\widgets\ActiveForm;
 * @var $model startpl\t2cmsblog\models\Category
 */
?>

<?= $form->field($model->categoryContent, 'title', ['options' => ['id' => 'field-title']])->textInput(['maxlength' => true]) ?>
<?= $form->field($model->categoryContent, 'keywords')->textInput(['maxlength' => true]) ?>
<?= $form->field($model->categoryContent, 'description')->textarea() ?>
<?= $form->field($model->categoryContent, 'og_title', ['options' => ['id' => 'field-og_title']])->textInput(['maxlength' => true]) ?>
<?= $form->field($model->categoryContent, 'og_description')->textarea() ?>