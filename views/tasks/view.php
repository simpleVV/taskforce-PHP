<?php

/** 
 * @var yii\web\View $this
 * @var Task $task
 * @var Response $responses
 */

use yii\helpers\Html;
use app\helpers\HtmlHelper;

$user = Yii::$app->user->identity;

$this->title = 'Просмотр задания';
$this->registerJsFile('/js/main.js');
?>

<div class="left-column">
    <div class="head-wrapper">
        <h3 class="head-main"><?= Html::encode($model->title); ?></h3>
        <p class="price price--big"><?= Html::encode($model->price); ?> ₽</p>
    </div>
    <p class="task-description">
        <?= Html::encode($model->description); ?>
    </p>

    <?php foreach (HtmlHelper::getActionButtons($model->getTaskActions($user), $model->id) as $button) : ?>
        <?= $button ?>
    <?php endforeach; ?>

    <div class="task-map">
        <img class="map" src="../../img/map.png" width="725" height="346" alt="Новый арбат, 23, к. 1" />
        <p class="map-address town">Москва</p>
        <p class="map-address">Новый арбат, 23, к. 1</p>
    </div>

    <?php if (!empty($model->getResponses($user)->all())) : ?>
        <h4 class="head-regular">Отклики на задание</h4>

        <?php foreach ($model->getResponses($user)->all() as $response) : ?>
            <?= $this->render('//partials/_response-card', [
                'model' => $response,
            ]); ?>
        <?php endforeach; ?>

    <?php endif; ?>

</div>
<div class="right-column">
    <div class="right-card black info-card">
        <h4 class="head-card">Информация о задании</h4>
        <dl class="black-list">
            <dt>Категория</dt>
            <dd>
                <?= $model->category->name ?>
            </dd>
            <dt>Дата публикации</dt>
            <dd>
                <?= Yii::$app->formatter->asRelativeTime($model->dt_creation) ?>
            </dd>
            <dt>Срок выполнения</dt>
            <dd>
                <?= Yii::$app->formatter->asDatetime($model->dt_expire) ?>
            </dd>
            <dt>Статус</dt>
            <dd>
                <?= $model->status->name ?>
            </dd>
        </dl>
    </div>
    <div class="right-card white file-card">
        <h4 class="head-card">Файлы задания</h4>
        <ul class="enumeration-list">
            <?php foreach ($model->files as $file) : ?>
                <?= $this->render('//partials/_file-item', [
                    'model' => $file,
                ]); ?>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?= $this->render('//modals/_add-response.php', [
    'model' => $responseForm,
    'taskId' => $model->id
]); ?>

<?= $this->render('//modals/_task-completion.php', [
    'model' => $reviewForm,
    'taskId' => $model->id
]); ?>

<?= $this->render('//modals/_task-refusal.php', ['taskId' => $model->id]); ?>