<?php

/** 
 * @var yii\web\View $this 
 * @var Review $model
 * */

use yii\helpers\BaseStringHelper;
use yii\helpers\Html;
use yii\helpers\Url;

use app\helpers\HtmlHelper;

?>

<div class="response-card">
    <img class="customer-photo" src=<?= $model->client->avatar_path ?> width="120" height="127" alt="Фото заказчиков" />
    <div class="feedback-wrapper">
        <p class="feedback">
            <?= Html::encode($model->description); ?>
        </p>
        <p class="task">
            Задание
            «
            <a href="<?= Url::to(['tasks/view', 'id' => $model->task->id]); ?>" class="link link--small">
                <?= $model->task->title; ?>
            </a>
            »
            <?= $model->task->status->name; ?>
        </p>
    </div>
    <div class="feedback-wrapper">
        <?= HtmlHelper::getStarElements($model->rate, false) ?>
        <p class="info-text">
            <span class="current-time">
                <?= Yii::$app->formatter->asRelativeTime($model->dt_creation); ?>
            </span>
        </p>
    </div>
</div>