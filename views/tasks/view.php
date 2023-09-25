<?php

/** 
 * @var yii\web\View $this
 * @var Task $model
 * @var ResponseForm $responseForm
 * @var ReviewForm reviewForm
 */

use app\models\forms\ReviewForm;

use app\models\forms\ResponseForm;

use yii\helpers\Html;
use yii\web\View;
use app\helpers\HtmlHelper;
use app\assets\MapAsset;

MapAsset::register($this);
$this->registerJsVar('coords', [$model->lat, $model->long], View::POS_END);

$user = Yii::$app->user->identity;

$this->title = 'Просмотр задания';

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

    <?php if ($model->location) : ?>
        <div class="task-map">
            <div id="map" style="width: 725px; height: 346px"></div>
            <p class="map-address town"><?= Html::encode($model->city->name ?? ''); ?></p>
            <p class="map-address"><?= Html::encode($model->location ?? ''); ?></p>
        </div>
    <?php endif; ?>

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
    'taskId' => $model->id,
    'userId' => $user->getId()
]); ?>

<?= $this->render('//modals/_task-completion.php', [
    'model' => $reviewForm,
    'taskId' => $model->id
]); ?>

<?= $this->render('//modals/_task-refusal.php', ['taskId' => $model->id]); ?>