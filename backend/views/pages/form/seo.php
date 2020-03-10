<?php
/**
 * @var $this yii\web\View
 * @var form yii\widgets\ActiveForm;
 * @var $model startpl\t2cmsblog\models\Page
 */
?>

<?= $form->field($model->pageContent, 'title', ['options' => ['id' => 'field-title']])->textInput(['maxlength' => true]) ?>
<?= $form->field($model->pageContent, 'keywords')->textInput(['maxlength' => true]) ?>
<?= $form->field($model->pageContent, 'description')->textarea() ?>
<?= $form->field($model->pageContent, 'og_title', ['options' => ['id' => 'field-og_title']])->textInput(['maxlength' => true]) ?>
<?= $form->field($model->pageContent, 'og_description')->textarea() ?>