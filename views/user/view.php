<?php

/** 
 * @var yii\web\View $this
 * @var Task $task
 * @var Review $reviews
 * @var Category $categories
 */

use yii\helpers\BaseStringHelper;
use yii\helpers\Html;

$this->title = 'Профиль пользователя';
?>

<div class="left-column">
    <h3 class="head-main"><?= Html::encode($user->name); ?></h3>
    <div class="user-card">
        <div class="photo-rate">
            <img class="card-photo" src="/../img/man-hat.png" width="191" height="190" alt="Фото пользователя" />
            <div class="card-rate">
                <div class="stars-rating big">
                    <span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span>
                </div>
                <span class="current-rate">4.23</span>
            </div>
        </div>
        <p class="user-description">
            <?= Html::encode($user->about); ?>
        </p>
    </div>
    <div class="specialization-bio">
        <div class="specialization">
            <p class="head-info">Специализации</p>
            <ul class="special-list">
                <?php foreach ($user->categories as $category) : ?>
                    <li class="special-item">
                        <a href="#" class="link link--regular">
                            <?= $category->name; ?>
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
                    <?= $user->city->name; ?>
                </span>
                <?php if ($user->bd_date) : ?>
                    , <span class="age-info">
                        <?= $user->bd_date; ?>
                    </span> лет
                <?php endif; ?>
            </p>
        </div>
    </div>
    <?php if ($user->reviews) : ?>
        <h4 class="head-regular">Отзывы заказчиков</h4>
        <?php foreach ($user->reviews as $review) : ?>
            <div class="response-card">
                <img class="customer-photo" src="/../img/man-coat.png" width="120" height="127" alt="Фото заказчиков" />
                <div class="feedback-wrapper">
                    <p class="feedback">
                        <?= Html::encode(BaseStringHelper::truncate($review->description, 200)); ?>
                    </p>
                    <p class="task">
                        Задание «<a href="#" class="link link--small">Повесить полочку</a>» выполнено
                    </p>
                </div>
                <div class="feedback-wrapper">
                    <div class="stars-rating small">
                        <span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span>
                    </div>
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
            <dd>4 выполнено, 0 провалено</dd>
            <dt>Место в рейтинге</dt>
            <dd>25 место</dd>
            <dt>Дата регистрации</dt>
            <dd>
                <?= Yii::$app->formatter->asDate($user->dt_registration); ?>
            </dd>
            <dt>Статус</dt>
            <?php if ($user->haveActiveTask()) : ?>
                <dd>Занят</dd>
            <?php else : ?>
                <dd>Открыт для новых заказов</dd>
            <?php endif; ?>
        </dl>
    </div>
    <div class="right-card white">
        <h4 class="head-card">Контакты</h4>
        <ul class="enumeration-list">
            <li class="enumeration-item">
                <a href="#" class="link link--block link--phone">
                    <?= Html::encode($user->phone); ?>
                </a>
            </li>
            <li class="enumeration-item">
                <a href="#" class="link link--block link--email">
                    <?= Html::encode($user->email); ?>
                </a>
            </li>
            <li class="enumeration-item">
                <a href="#" class="link link--block link--tg">
                    <?= Html::encode($user->telegram); ?>
                </a>
            </li>
        </ul>
    </div>
</div>