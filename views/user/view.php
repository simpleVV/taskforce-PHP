<?php

/** 
 * @var yii\web\View $this
 * @var Task $task
 * @var Review $reviews
 * @var Category $categories
 */

use yii\helpers\BaseStringHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use app\helpers\HtmlHelper;

$this->title = 'Профиль пользователя';
?>

<div class="left-column">
    <h3 class="head-main"><?= Html::encode($model->name); ?></h3>
    <div class="user-card">
        <div class="photo-rate">
            <img class="card-photo" src="/../img/man-hat.png" width="191" height="190" alt="Фото пользователя" />
            <div class="card-rate">
                <?= HtmlHelper::getStarElements($model->rating, false, 'big') ?>
                <span class="current-rate">
                    <?= $model->rating ?>
                </span>
            </div>
        </div>
        <p class="user-description">
            <?= Html::encode($model->about); ?>
        </p>
    </div>
    <div class="specialization-bio">
        <div class="specialization">
            <p class="head-info">Специализации</p>
            <ul class="special-list">
                <?php foreach ($model->tasks as $task) : ?>
                    <li class="special-item">
                        <a href="<?= Url::to(['tasks/index', 'category' => $task->category_id]) ?>" class="link link--regular">
                            <?= $task->category->name; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="bio">
            <p class="head-info">Био</p>
            <p class="bio-info">
                <span class="country-info">Россия</span>,
                <span class="town-info">
                    <?= $model->city->name; ?>
                </span>
                <?php if ($model->bd_date) : ?>
                    , <span class="age-info">
                        <?= $model->bd_date; ?>
                    </span> лет
                <?php endif; ?>
            </p>
        </div>
    </div>
    <?php if ($model->reviews) : ?>
        <h4 class="head-regular">Отзывы заказчиков</h4>
        <?php foreach ($model->reviews as $review) : ?>
            <div class="response-card">
                <img class="customer-photo" src="/../img/man-coat.png" width="120" height="127" alt="Фото заказчиков" />
                <div class="feedback-wrapper">
                    <p class="feedback">
                        <?= Html::encode(BaseStringHelper::truncate($review->description, 200)); ?>
                    </p>
                    <p class="task">
                        Задание
                        «
                        <a href="<?= Url::to(['tasks/view', 'id' => $review->task->id]); ?>" class="link link--small">
                            <?= $review->task->title; ?>
                        </a>
                        »
                        <?= $review->task->status->name; ?>
                    </p>
                </div>
                <div class="feedback-wrapper">
                    <?= HtmlHelper::getStarElements($review->rate, false) ?>
                    <p class="info-text">
                        <span class="current-time">
                            <?= Yii::$app->formatter->asRelativeTime($review->dt_creation); ?>
                        </span>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<div class="right-column">
    <div class="right-card black">
        <h4 class="head-card">Статистика исполнителя</h4>
        <dl class="black-list">
            <dt>Всего заказов</dt>
            <dd>
                <?= $model->getCompleteTasks(); ?> выполнено,
                <?= $model->fail_tasks; ?> провалено</dd>
            <dt>Место в рейтинге</dt>
            <dd>
                <?= $model->position; ?> место
            </dd>
            <dt>Дата регистрации</dt>
            <dd>
                <?= Yii::$app->formatter->asDate($model->dt_registration); ?>
            </dd>
            <dt>Статус</dt>
            <?= $model->haveActiveTask()
                ? '<dd>Занят</dd>'
                : '<dd>Открыт для новых заказов</dd>'
            ?>
        </dl>
    </div>
    <div class="right-card white">
        <h4 class="head-card">Контакты</h4>
        <ul class="enumeration-list">
            <li class="enumeration-item">
                <a href="#" class="link link--block link--phone">
                    <?= Html::encode($model->phone); ?>
                </a>
            </li>
            <li class="enumeration-item">
                <a href="#" class="link link--block link--email">
                    <?= Html::encode($model->email); ?>
                </a>
            </li>
            <li class="enumeration-item">
                <a href="#" class="link link--block link--tg">
                    <?= Html::encode($model->telegram); ?>
                </a>
            </li>
        </ul>
    </div>
</div>