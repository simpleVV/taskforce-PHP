<?php

/** @var yii\web\View $this */

use yii\helpers\BaseStringHelper;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

// use app\assets\AppAsset;

$this->title = 'Просмотр новых заданий'
?>

<div class="left-column">
    <h3 class="head-main head-task">Новые задания</h3>

    <?php foreach ($models as $model) : ?>
        <div class="task-card">
            <div class="header-task">
                <a href="#" class="link link--block link--big">
                    <?= Html::encode($model->title); ?></a>
                <p class="price price--task">
                    <?= Html::encode($model->price); ?> ₽
                </p>
            </div>
            <p class="info-text">
                <span class="current-time"><?= Yii::$app->formatter->asRelativeTime($model->dt_creation) ?></span> назад
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
                <a href="#" class="button button--black">Смотреть Задание</a>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="pagination-wrapper">
        <ul class="pagination-list">
            <li class="pagination-item mark">
                <a href="#" class="link link--page"></a>
            </li>
            <li class="pagination-item">
                <a href="#" class="link link--page">1</a>
            </li>
            <li class="pagination-item pagination-item--active">
                <a href="#" class="link link--page">2</a>
            </li>
            <li class="pagination-item">
                <a href="#" class="link link--page">3</a>
            </li>
            <li class="pagination-item mark">
                <a href="#" class="link link--page"></a>
            </li>
        </ul>
    </div>
</div>
<div class="right-column">
    <div class="right-card black">
        <div class="search-form">
            <?php $form = ActiveForm::begin(); ?>
            <h4 class="head-card">Категории</h4>
            <div class="form-group">
                <div class="checkbox-wrapper">
                    <?= HTML::activeCheckboxList($task, 'category_id', ArrayHelper::map($categories, 'id', 'name'), ['tag' => null, 'itemOptions' => ['labelOptions' => ['class' => 'control-label']]]); ?>
                </div>
            </div>
            <h4 class="head-card">Дополнительно</h4>
            <div class="checkbox-wrapper">
                <?= $form->field($task, 'noPerformer')->checkbox(['labelOptions' => ['class' => 'control-label']]); ?>
            </div>
            <h4 class="head-card">Период</h4>
            <div class="form-group">
                <?= $form->field($task, 'periodOption', ['template' => '{input}'])->dropDownList(['3600' => '1 час', '43200' => '12 часов', '86400' => '24 часа'], ['prompt' => 'Выбрать']); ?>
            </div>
            <input type="submit" class="button button--blue" value="Искать" />
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>