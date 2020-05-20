<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model startpl\t2cmsblog\models\Category */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsVar('error_message', \Yii::t('nsblog/error', 'The form contains errors'));
$this->registerJsVar('ACF_URL', Url::to(['/acf/ajax']));
?>

<div class="blog-edit">
    
    <div class="section-justify">
        <div class="btn-group" role="group" id="section_tabs">
            <button type="button" class="btn btn-default active" data-section="main"><?=\Yii::t('nsblog', 'Category')?></button>
            <button type="button" class="btn btn-default" data-section="seo"><?=\Yii::t('nsblog', 'SEO')?></button>
            <button type="button" class="btn btn-default" data-section="acf"><?=\Yii::t('nsblog', 'Custom Fields')?></button>
        </div>
        
        <div class="zone-section">
            <?= t2cms\sitemanager\widgets\local\DomainList::widget();?>
            <?= t2cms\sitemanager\widgets\local\LanguageList::widget();?>
        </div>
    </div>
    
    <div class="text-right">
        <?= Html::submitButton(Yii::t('nsblog', 'Save'), ['class' => 'btn btn-success', 'id' => 'btn-save-top']) ?>
        <?= Html::button(Yii::t('nsblog', 'Save & Close'), ['class' => 'btn btn-success js-save-and-close', 'id' => 'btn-sc-post']) ?>
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
    
    
    <?php //debug($model);?>
    <?php // ajax will load acf form ?>
    <?= Html::tag('div', null,[
        'id' => 'acf',
        'class' => 'section',
        'data' => [
            'acf' => [
                'group_id'    => $model->settings['acf_group_id'],
                'src_type'    => \startpl\t2cmsblog\models\Category::TYPE,
                'src_id'      => $model->id,
                'domain_id'   => \t2cms\sitemanager\components\Domains::getEditorDomainId(),
                'language_id' => \t2cms\sitemanager\components\Languages::getEditorLangaugeId(),
            ]
        ]
    ])?>
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('nsblog', 'Save'), ['class' => 'btn btn-success', 'id' => 'btn-save-post']) ?>
        <?= Html::button(Yii::t('nsblog', 'Save & Close'), ['class' => 'btn btn-success js-save-and-close', 'id' => 'btn-sc-post']) ?>
    </div>

    <?=Html::hiddenInput('close-identity', false, ['id' => 'close-identity'])?>
    <?php ActiveForm::end(); ?>
</div>

<?php

$js = <<<JS
    loadAcf();
    function loadAcf(){
    const acf  = $('#acf');

    $.ajax({
        url: ACF_URL,
        type: "POST",
        data: acf.data(),
        success: function(response){
            acf.html(response);
        }
    })
}
JS;

$this->registerJs($js);
?>