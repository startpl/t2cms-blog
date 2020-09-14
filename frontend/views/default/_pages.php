<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="list-item">
    <div class="img">
        <img src="<?=$model->pageContent->image?>" alt="<?=$model->pageContent->name?>" />
    </div>
    <div class="article">
        <h3>
            <a href="<?=Url::to(['/blog/page', 'id' => $model->id])?>">
            <?= Html::encode($model->pageContent->name) ?>
            </a>
        </h3>
        <div class="preview">
            <?=$model->pageContent->preview_text?>  
        </div>
    </div>
</div>