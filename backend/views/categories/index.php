<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use startpl\t2cmsblog\models\Category;

/* @var $this yii\web\View */
/* @var $searchModel startpl\t2cmsblog\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('nsblog', 'Categories');
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
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    
    <div class="section-justify">
        <div>
            <?= Html::a(Yii::t('nsblog', 'Create Category'), ['create'], ['class' => 'btn btn-success']) ?>
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
        
    <?=    \t2cms\treeview\TreeView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchForm,
        'id' => 'blog-grid',
//        'collapse' => true,
        'depthRoot' => 1,
        'columns' => [
            [
                'class' => '\t2cms\treeview\base\CheckboxColumn',
            ],
            'id',
            [
                'label' => 'name',
                'attribute' => 'name',
                'format' => 'html',
                'value' => function($model, $key, $index){
                    $anchor = str_repeat(' — ', $model->depth - 1).$model->categoryContent->name;
                    return Html::a($anchor, yii\helpers\Url::to(['update', 'id' => $model->id]));
                }
            ],
            [
                'label' => 'url',
                'attribute' => 'url',
                'format' => 'html',
                'value' => function($model, $key, $index){
                    return Html::a($model->url, yii\helpers\Url::to(['update', 'id' => $model->id]));
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
            [
                'label' => \Yii::t('nsblog', 'Number of pages'),
                'attribute' => 'pagesCount',
                'format' => 'text',
                'value' => function($model, $key, $index){
                    return $model->getPagesCount();
                }
            ],
            'position',
            [
                        'label'     => 'status',
                        'attribute' => 'status',
                        'options' => ['width'=>'120px'],
                        'format'    => 'raw',
                        'filter'    => Category::getStatuses(),
                        'value' => function($model, $key, $index){
                            $html  = Html::beginTag('div', ['class' => 'switch_checkbox']);
                            $html .= Html::checkbox(
                                        "status[{$model->id}]", 
                                        $model->status == Category::STATUS['PUBLISHED'], 
                                        ['id' => 'status_'.$model->id, 'class' => 'change-status']
                                    );
                            $html .= Html::label('Switch', 'status_'.$model->id);
                            $html .= Html::endTag('div');
                            return $html;
                        }
                    ]
        ]
    ]);?>
    
    <?php Pjax::end(); ?>

</div>