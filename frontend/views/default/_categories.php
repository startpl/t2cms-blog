<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="list-item">
    <div class="img">
        <img src="<?=$model->categoryContent->image?>" alt="<?=$model->categoryContent->name?>" />
    </div>
    <div class="article">
        <h3>
            <a href="<?=Url::to(['/blog/category', 'id' => $model->id])?>">
            <?= Html::encode($model->categoryContent->name) ?>
            </a>
        </h3>
        <div class="preview">
            <?=$model->categoryContent->preview_text?>  
        </div>
    </div>
</div>