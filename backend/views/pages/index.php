<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use startpl\t2cmsblog\models\Category;
use startpl\t2cmsblog\models\Page;
use t2cms\sitemanager\components\{
    Domains,
    Languages
};

/* @var $this yii\web\View */
/* @var $searchModel startpl\t2cmsblog\models\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('nsblog', 'Pages');
$this->params['breadcrumbs'][] = $this->title;

startpl\t2cmsblog\AssetBundle::register($this);

$url = [
    'status' => Url::to(['change-status']),
    'sort'   => Url::to(['sort']),
    'delete' => Url::to(['delete']),
];

$this->registerJsVar('url', $url);
$this->registerJsVar('i18n', [
    'confirm' => \Yii::t('nsblog', 'Are you sure?'),
    'delete_confirm' => \Yii::t('nsblog', 'Are you sure you want to delete the selected?'),
]);
?>

<div class="page-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <div class="section-justify">
        <div>
            <?= Html::a(Yii::t('nsblog', 'Create Page'), ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="section-right">
            <div class="zone-section">
                <div class="domain-change">
                    <?= t2cms\sitemanager\widgets\local\DomainList::widget();?>
                </div>
                <div class="language-change">
                    <?= t2cms\sitemanager\widgets\local\LanguageList::widget();?>
                </div>
            </div>
            <div id="group-controls" class="section-right">
                <button data-type="publish" class="btn btn-success">
                    <span class="glyphicon glyphicon-ok"></span>
                    <?=\Yii::t('nsblog', 'Publish')?>
                </button>
                <button data-type="delete" id="delete_all" class="btn btn-danger">
                    <span class="glyphicon glyphicon-remove"></span>
                    <?=\Yii::t('nsblog', 'Delete')?>
                </button>
            </div>
        </div>
    </div>
    
    <?php Pjax::begin(); ?>
    
    <div class="row">
        
        <div class="col-md-2 categories_gridlist">
            <?= \startpl\yii2NestedSetsMenu\Menu::widget([
                'items' => \startpl\t2cmsblog\services\nestedSets\MenuArray::getData(),
                'options' => ['id'=>'blog-categories-list2', 'class' => 'categories-menu2'],
                'encodeLabels'=>false,
                'activateParents'=>true,
                'activeCssClass'=>'active',
            ]);?>
        </div>
        <div class="col-md-10">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchForm,
                'summary' => false,
                'id' => 'blog-grid',
                'options' => ['class' => 'gridView'],
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'options' => ['width'=>'20px'],
                    ],
                    'id',
                    [
                        'label' => 'Name',
                        'attribute' => 'name',
                        'format' => 'html',
                        'value' => function($model, $key, $index){
                            return Html::a($model->pageContent->name, Url::to(['update', 'id' => $model->id]));
                        }
                    ],
                    [
                        'label' => 'url',
                        'attribute' => 'url',
                        'format' => 'html',
                        'value' => function($model, $key, $index){
                            $anchor = [
                                $model->url,
                                rmrevin\yii\fontawesome\FontAwesome::i('external-link')
                            ];
                            return Html::a(implode(' ', $anchor), \Yii::$app->urlManagerFrontend->createAbsoluteUrl(['blog/page', 'id' => $model->id]));
                        }
                    ],
                    [
                        'label' => 'Category',
                        'filter' => \yii\helpers\ArrayHelper::merge(
                                [Category::ROOT_ID => \Yii::t('nsblog', 'No Category')], // trick for filtering "no category"
                                Category::getTree(0, Domains::getEditorDomainId(), Languages::getEditorLangaugeId())
                            ),
                        'attribute' => 'category',
                        'format' => 'html',
                        'value' => function($model, $key, $index){
                            return $model->category->categoryContent->name? : \Yii::t('nsblog', 'No Category');
                        }
                    ],
                    [
                        'label' => 'author',
                        'attribute' => 'author_name',
                        'format' => 'html',
                        'value' => function($model, $key, $index){
                            return Html::a($model->author->username, yii\helpers\Url::to(['update', 'id' => $model->id]));
                        }  
                    ],
                    'pageContent.image',
                    [
                        'label'     => 'status',
                        'attribute' => 'status',
                        'options' => ['width'=>'120px'],
                        'format'    => 'raw',
                        'filter'    => Page::getStatuses(),
                        'value' => function($model, $key, $index){
                            $html  = Html::beginTag('div', ['class' => 'switch_checkbox']);
                            $html .= Html::checkbox(
                                        "status[{$model->id}]", 
                                        $model->status == Page::STATUS['PUBLISHED'], 
                                        ['id' => 'status_'.$model->id, 'class' => 'change-status']
                                    );
                            $html .= Html::label('Switch', 'status_'.$model->id);
                            $html .= Html::endTag('div');
                            return $html;
                        }
                    ]
                ],
            ]); ?>
        </div>
    </div>

    <?php Pjax::end(); ?>

</div>
