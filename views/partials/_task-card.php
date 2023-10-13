<?php

/** 
 * @var yii\web\View $this 
 * @var Task $model
 * */

use yii\helpers\Html;
use yii\helpers\BaseStringHelper;
use yii\helpers\Url;

?>

<div class="task-card">
    <div class="header-task">
        <a href="<?= Url::to(["/tasks/view", "id" => $model->id]); ?>" class="link link--block link--big">
            <?= Html::encode($model->title); ?></a>
        <p class="price price--task">
            <?= Html::encode($model->price); ?> ₽
        </p>
    </div>
    <p class="info-text">
        <span class="current-time">
            <?= Yii::$app->formatter->asRelativeTime($model->dt_creation); ?>
        </span> назад
    </p>
    <p class="task-text">
        <?= Html::encode(BaseStringHelper::truncate($model->description, 200)); ?>
    </p>
    <div class="footer-task">
        <p class="info-text town-text">
            <?= $model->location; ?>
        </p>
        <p class="info-text category-text">
            <?= $model->category->name; ?>
        </p>
        <?= HTML::a('Смотреть Задание', ['tasks/view', "id" => $model->id], ['class' => 'button button--black']) ?>
    </div>
</div>