<?php

use startpl\t2cmsblog\models\Category;

use t2cms\sitemanager\components\Domains;
use t2cms\sitemanager\components\Languages;
/**
 * @var $this yii\web\View
 * @var form yii\widgets\ActiveForm;
 * @var $model startpl\t2cmsblog\models\Category
 */
?>

<div class="row">
<?= $form->field($model->categoryContent, 'name', ['options' => ['class' => 'col-md-4', 'id' => 'field-name']])->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'url', [
    'options' => ['class' => 'col-md-4', 'id' => 'field-url'], 
    'template' => "{label}\n"
            . "<div class='input-group'>"
            . "{input}"
            . "<span class='input-group-btn'>"
            . "<button class='btn btn-default' id='url-autofill' type='button'>"
            . "<span class='glyphicon glyphicon-repeat'></span>".\Yii::t('nsblog','Autofill').""
            . "</button>"
            . "</span>"
            . "</div>\n"
            . "{hint}\n{error}"
    ])->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'status', ['options' => ['class' => 'col-md-2']])->dropDownList(Category::getStatuses()) ?>
<?= $form->field($model, 'publish_at', ['options' => ['class' => 'col-md-2']])->widget(kartik\datetime\DateTimePicker::classname(), [
	'options' => ['placeholder' => \Yii::t('nsblog', 'Select date'), 'autocomplete' => 'off'],
    'removeButton' => false,
	'pluginOptions' => [
		'autoclose' => true,
        'format' => 'yyyy-mm-dd hh:ii:ss'
	]
]); ?>
</div>

<?= $form->field($model, 'parent_id')->dropDownList(
    startpl\t2cmsblog\models\Category::getTree($model->id, Domains::getEditorDomainId(), Languages::getEditorLangaugeId()), 
    ['prompt' => Yii::t('nsblog','No Parent'), 'class' => 'form-control']
);?>

<?= $form->field($model->categoryContent, 'h1')->textInput(['maxlength' => true]) ?>

<?= $form->field($model->categoryContent, 'image')->widget(\mihaildev\elfinder\InputFile::className(), [
    'controller'    => 'blog/elfinder',
    'filter'        => 'image',
    'template'      => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
    'options'       => ['class' => 'form-control'],
    'buttonOptions' => ['class' => 'btn btn-default'],
    'multiple'      => false 
]); ?>

<?= $form->field($model->categoryContent, 'preview_text')->widget(vova07\imperavi\Widget::className(), [
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'plugins' => [
            'fullscreen',
        ],
        'buttons' => [
            'html',
            'formatting',
            'bold',
            'italic',
            'deleted',
            'unorderedlist',
            'orderedlist',
            'outdent',
            'indent',
            'image',
            'file',
            'link',
            'alignment',
            'horizontalrule'
        ],
    ],
]); ?>

<?= $form->field($model->categoryContent, 'full_text')->widget(vova07\imperavi\Widget::className(), [
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'plugins' => [
            'fullscreen',
        ],
        'buttons' => [
            'html',
            'formatting',
            'bold',
            'italic',
            'deleted',
            'unorderedlist',
            'orderedlist',
            'outdent',
            'indent',
            'image',
            'file',
            'link',
            'alignment',
            'horizontalrule'
        ],
    ],

]); ?>    

<?= $form->field($model, 'addCategories')->widget(\kartik\select2\Select2::className(), [
    'data' => $allCategories,   
    'language' => 'ru',
    'options' => ['placeholder' => Yii::t('nsblog', 'Select a category'), 'multiple' => true],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);?>

<?= $form->field($model, 'addPages')->widget(\kartik\select2\Select2::className(), [
    'data' => $allPages,   
    'language' => 'ru',
    'options' => ['placeholder' => Yii::t('nsblog', 'Select a page'), 'multiple' => true],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);?>

<?= $form->field($model, 'rltCategories')->widget(\kartik\select2\Select2::className(), [
    'data' => $allCategories,   
    'language' => 'ru',
    'options' => ['placeholder' => Yii::t('nsblog', 'Select a category'), 'multiple' => true],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);?>

<?= $form->field($model, 'rltPages')->widget(\kartik\select2\Select2::className(), [
    'data' => $allPages,   
    'language' => 'ru',
    'options' => ['placeholder' => Yii::t('nsblog', 'Select a page'), 'multiple' => true],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);?>

<div class="panel panel-default">
    <div class="panel-heading"><?=\Yii::t('nsblog', 'Settings of view');?></div>
    <div class="panel-body">
        <?= $form->field($model, 'access_read')->dropDownList([0 => \Yii::t('nsblog', 'Every One'), 1 => \Yii::t('nsblog', 'No One'), 2 => \Yii::t('nsblog', 'Admin')]) ?>
        <?= $form->field($model, 'records_per_page')->textInput(['maxlength' => true])?>
        <?//= $form->field($model, 'sort')->textInput(['maxlength' => true])?>
        <hr/>
        <?= $form->field($model, 'mainTemplateName')->textInput(['maxlength' => true])?>
        <?= $form->field($model, 'mainTemplateApplySub')->checkbox()?>
        <hr/>
        <?= $form->field($model, 'categoryTemplateName')->textInput(['maxlength' => true])?>
        <?= $form->field($model, 'categoryTemplateApplySub')->checkbox()?>
        <hr/>
        <?= $form->field($model, 'pageTemplateName')->textInput(['maxlength' => true])?>
        <?= $form->field($model, 'pageTemplateApplySub')->checkbox()?>
    </div>
</div>