<?php

/** 
 * @var yii\web\View $this
 * @var Task[] $models
 * @var Pagination $pages
 * 
 */

use yii\widgets\Menu;
use taskforce\logic\TaskManager;

$this->title = 'Мои задания';
?>

<div class="left-menu">
    <h3 class="head-main head-task">Мои задания</h3>
    <?= Menu::widget([
        'options' => ['class' => 'side-menu-list'],
        'activeCssClass' => 'side-menu-item--active',
        'itemOptions' => ['class' => 'side-menu-item'],
        'linkTemplate' => '<a href="{url}" class="link link--nav">{label}</a>',
        'items' => [
            !$isPerformer
                ? ['label' => 'Новое', 'url' => ['tasks/view-my', 'status' => TaskManager::STATUS_NEW]]
                : ['label' => 'В процессе', 'url' => ['tasks/view-my', 'status' => TaskManager::STATUS_IN_PROGRESS]],
            !$isPerformer
                ? ['label' => 'В процессе', 'url' => ['tasks/view-my', 'status' => TaskManager::STATUS_IN_PROGRESS]]
                : ['label' => 'Просрочено', 'url' => ['tasks/view-my', 'status' => TaskManager::STATUS_OVERDUE]],

            ['label' => 'Закрытые', 'url' => ['tasks/view-my', 'status' => TaskManager::STATUS_CANCEL]],
        ]
    ]); ?>
</div>
<div class="left-column left-column--task">
    <h3 class="head-main head-regular">Новые задания</h3>
    <?php foreach ($models as $model) : ?>
        <?= $this->render('//partials/_task-card', ['model' => $model]); ?>
    <?php endforeach; ?>
</div>