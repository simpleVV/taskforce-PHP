<?php

/** 
 * @var yii\web\View $this
 * @var Task[] $models
 * @var Task $task
 * @var Category[] $categories 
 * @var Pagination $pages
 * 
 */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

$this->title = 'Просмотр новых заданий';
?>

<div class="left-column">
    <h3 class="head-main head-task">Новые задания</h3>

    <?php foreach ($models as $model) : ?>
        <?= $this->render('//partials/_task-card', ['model' => $model]); ?>
    <?php endforeach; ?>
    <div class="pagination-wrapper">
        <?= LinkPager::widget([
            'pagination' => $pagination,
            'options' => [
                'class' => 'pagination-list'
            ],
            'prevPageCssClass' => 'pagination-item mark',
            'nextPageCssClass' => 'pagination-item mark',
            'pageCssClass' => 'pagination-item',
            'activePageCssClass' => 'pagination-item--active',
            'linkOptions' => ['class' => 'link link--page'],
            'nextPageLabel' => '',
            'prevPageLabel' => '',
            'maxButtonCount' => $pageSize
        ]); ?>
    </div>
</div>
<div class="right-column">
    <div class="right-card black">
        <div class="search-form">
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'template' => "{label}\n{input}",
                    'labelOptions' => ['class' => 'control-label'],
                ]
            ]); ?>
            <h4 class="head-card">Категории</h4>
            <div class="form-group">
                <div class="checkbox-wrapper">
                    <?= HTML::activeCheckboxList($task, 'category_id', ArrayHelper::map($categories, 'id', 'name'), [
                        'tag' => null,
                        'itemOptions' => [
                            'labelOptions' => [
                                'class' => 'control-label'
                            ]
                        ]
                    ]); ?>
                </div>
            </div>
            <h4 class="head-card">Дополнительно</h4>
            <div class="checkbox-wrapper">
                <?= $form->field($task, 'noPerformer')->checkbox([
                    'labelOptions' => [
                        'class' => 'control-label'
                    ]
                ]); ?>
                <?= $form->field($task, 'remoteWork')->checkbox([
                    'labelOptions' => [
                        'class' => 'control-label'
                    ]
                ]); ?>
            </div>
            <h4 class="head-card">Период</h4>
            <?= $form->field($task, 'periodOption', [
                'template' => '{input}'
            ])->dropDownList(
                [
                    '3600' => '1 час',
                    '43200' => '12 часов',
                    '86400' => '24 часа'
                ],
                [
                    'prompt' => 'Выбрать'
                ]
            ); ?>
            <input type="submit" class="button button--blue" value="Искать" />
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>