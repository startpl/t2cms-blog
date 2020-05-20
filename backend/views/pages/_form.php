<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model startpl\t2cmsblog\models\Category */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsVar('error_message', \Yii::t('nsblog/error', 'The form contains errors'));
?>

<div class="blog-edit">
    
    <div class="section-justify">
        <div class="btn-group" role="group" id="section_tabs">
            <button type="button" class="btn btn-default active" data-section="main"><?=\Yii::t('nsblog', 'Category')?></button>
            <button type="button" class="btn btn-default" data-section="seo"><?=\Yii::t('nsblog', 'SEO')?></button>
        </div>
        
        <div class="zone-section">
            <?= t2cms\sitemanager\widgets\local\DomainList::widget();?>
            <?= t2cms\sitemanager\widgets\local\LanguageList::widget();?>
        </div>
    </div>
    
    <div class="text-right">
        <?= Html::submitButton(Yii::t('nsblog', 'Save'), ['class' => 'btn btn-success', 'id' => 'btn-save-top']) ?>
        <?= Html::button(Yii::t('nsblog', 'Save & Close'), ['class' => 'btn btn-success js-save-and-close', 'id' => 'btn-save-close-top']) ?>
    </div>
    
    <div id="form-errors">
        
    </div>
    
    <?php $form = ActiveForm::begin([
        'id' => 'blog-form',
        ]); ?>
    
    <div id="main" class="active section">
        <?=$this->render('form/main', [
            'form'  => $form,
            'model' => $model,
            'allCategories' => $allCategories,
            'allPages' => $allPages,
        ]) ?>
    </div>
    
    <div id="seo" class="section">
        <?=$this->render('form/seo', [
            'form'  => $form,
            'model' => $model
        ]) ?>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('nsblog', 'Save'), ['class' => 'btn btn-success', 'id' => 'btn-save-post']) ?>
        <?= Html::button(Yii::t('nsblog', 'Save & Close'), ['class' => 'btn btn-success js-save-and-close', 'id' => 'btn-sc-post']) ?>
    </div>

    <?=Html::hiddenInput('close-identity', false, ['id' => 'close-identity'])?>
    <?php ActiveForm::end(); ?>

</div>