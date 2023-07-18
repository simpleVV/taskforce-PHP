<?php

/** 
 * @var yii\web\View $this 
 * @var Task $model
 * */

use yii\helpers\Html;
use yii\helpers\Url;

?>

<li class="enumeration-item">
    <a href="<?= Url::to($model->path) ?>" class="link link--block link--clip">
        <?= Html::encode($model->name); ?>
    </a>
    <p class="file-size">
        <?= $model->size; ?> Кб
    </p>
</li>