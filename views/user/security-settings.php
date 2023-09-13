<?php

/** 
 * @var yii\web\View $this
 */


use yii\widgets\ActiveForm;
use yii\widgets\Menu;

$user = Yii::$app->user->identity;

$this->params['main_class'] = 'main-content--left';
$this->title = 'Настройки';
?>

<div class="left-menu left-menu--edit">
    <h3 class="head-main head-task">Настройки</h3>
    <?= Menu::widget([
        'options' => ['class' => 'side-menu-list'],
        'activeCssClass' => 'side-menu-item--active',
        'itemOptions' => ['class' => 'side-menu-item'],
        'linkTemplate' => '<a href="{url}" class="link link--nav">{label}</a>',
        'items' => [
            ['label' => 'Мой профиль', 'url' => ['user/settings', 'id' => $user->id]],
            ['label' => 'Безопасность', 'url' => ['user/security-settings', 'id' => $user->id]]
        ]
    ]); ?>
</div>
<div class="my-profile-form">
    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'control-label'],
            'errorOptions' => ['tag' => 'span', 'class' => 'help-block'],
        ]
    ]); ?>
    <h3 class="head-main head-regular">Безопасность</h3>

    <?= $form->field($model, 'oldPassword')
        ->passwordInput(); ?>
    <?= $form->field($model, 'newPassword')
        ->passwordInput(); ?>
    <?= $form->field($model, 'confirmNewPassword')
        ->passwordInput(); ?>
    <?= $form->field($model, 'hideContacts')
        ->checkbox([
            'checked' => !empty($user->hide_contacts)
        ]); ?>

    <input type="submit" class="button button--blue" value="Сохранить" />
    <?php ActiveForm::end(); ?>
</div>