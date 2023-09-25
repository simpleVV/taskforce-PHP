<?php

/** 
 * @var yii\web\View $this
 * @var Task $model
 * @var Category[] $categories 
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var int $pageSize
 * 
 */

use Faker\Core\Number;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

$this->title = 'Просмотр новых заданий';
?>

<div class="left-column">
    <h3 class="head-main head-task">Новые задания</h3>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '//partials/_task-card',
        'summary' => '',
        'pager' => [
            'options' => ['class' => 'pagination-list'],
            'linkOptions' => ['class' => 'link link--page'],
            'nextPageCssClass' => 'pagination-item mark',
            'prevPageCssClass' => 'pagination-item mark',
            'nextPageLabel' => '',
            'prevPageLabel' => '',
            'pageCssClass' => 'pagination-item',
            'activePageCssClass' => 'pagination-item--active',
            'maxButtonCount' => $pageSize,
        ]
    ])
    ?>
</div>
<div class="right-column">
    <div class="right-card black">
        <div class="search-form">
            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'fieldConfig' => [
                    'template' => "{label}\n{input}",
                    'labelOptions' => ['class' => 'control-label'],
                ]
            ]); ?>
            <h4 class="head-card">Категории</h4>
            <div class="form-group">
                <div class="checkbox-wrapper">
                    <?= HTML::activeCheckboxList($model, 'categoryId', ArrayHelper::map($categories, 'id', 'name'), [
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
                <?= $form->field($model, 'noPerformer')->checkbox([
                    'labelOptions' => [
                        'class' => 'control-label'
                    ]
                ]); ?>
                <?= $form->field($model, 'remoteWork')->checkbox([
                    'labelOptions' => [
                        'class' => 'control-label'
                    ]
                ]); ?>
            </div>
            <h4 class="head-card">Период</h4>
            <?= $form->field($model, 'periodOption', [
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