<?php

/** 
 * @var yii\web\View $this
 * @var Task $task
 * @var Review $reviews
 * @var Contact $contacts
 * @var Category $categories
 */

use yii\helpers\BaseStringHelper;
use yii\helpers\Html;

$this->title = 'Профиль пользователя';
?>

<div class="left-column">
    <h3 class="head-main"><?= Html::encode($model->name) ?></h3>
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
            Внезапно, ключевые особенности структуры проекта неоднозначны и
            будут подвергнуты целой серии независимых исследований. Следует
            отметить, что высококачественный прототип будущего проекта, в своём
            классическом представлении, допускает внедрение своевременного
            выполнения сверхзадачи.
        </p>
    </div>
    <div class="specialization-bio">
        <div class="specialization">
            <p class="head-info">Специализации</p>
            <ul class="special-list">
                <li class="special-item">
                    <a href="#" class="link link--regular">Ремонт бытовой техники</a>
                </li>
                <li class="special-item">
                    <a href="#" class="link link--regular">Курьер</a>
                </li>
                <li class="special-item">
                    <a href="#" class="link link--regular">Оператор ПК</a>
                </li>
            </ul>
        </div>
        <div class="bio">
            <p class="head-info">Био</p>
            <p class="bio-info">
                <span class="country-info">Россия</span>,
                <span class="town-info">
                    <?= $model->city->name ?>
                </span>,
                <span class="age-info">30</span> лет
            </p>
        </div>
    </div>
    <?php if ($reviews) : ?>
        <h4 class="head-regular">Отзывы заказчиков</h4>
        <?php foreach ($reviews as $review) : ?>
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
                            <?= Yii::$app->formatter->asRelativeTime($review->dt_creation) ?>
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
                <?= Yii::$app->formatter->asDate($model->dt_registration) ?>
            </dd>
            <dt>Статус</dt>
            <dd>
                <?= $status ?>
            </dd>
        </dl>
    </div>
    <div class="right-card white">
        <h4 class="head-card">Контакты</h4>
        <ul class="enumeration-list">
            <li class="enumeration-item">
                <a href="#" class="link link--block link--phone">
                    <?= Html::encode($contacts ? $contacts->phone : "") ?>
                </a>
            </li>
            <li class="enumeration-item">
                <a href="#" class="link link--block link--email">
                    <?= Html::encode($contacts ? $contacts->email : "") ?>
                </a>
            </li>
            <li class="enumeration-item">
                <a href="#" class="link link--block link--tg">
                    <?= Html::encode($contacts ? $contacts->telegram : "") ?>
                </a>
            </li>
        </ul>
    </div>
</div>