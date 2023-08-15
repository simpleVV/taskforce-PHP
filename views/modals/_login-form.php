<?php

/** 
 * @var yii\web\View $this 
 * @var LoginForm $form
 * */

use yii\widgets\ActiveForm;

?>

<section class="modal enter-form form-modal" id="enter-form">
    <h2>Вход на сайт</h2>
    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => true,
        'action' => ['auth/login'],
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'inputOptions' => ['class' => 'enter-form-email input input-middle'],
            'labelOptions' => ['class' => 'form-modal-description'],
            'errorOptions' => ['tag' => 'span', 'class' => 'help-block']
        ]
    ]); ?>

    <p>
        <?= $form->field($model, 'email')->textInput(['autofocus' => true]); ?>
    </p>
    <p>
        <?= $form->field($model, 'password')->passwordInput(); ?>
    </p>

    <button class="button" type="submit">Войти</button>
    <p>Вход через VK</p>
    <?= yii\authclient\widgets\AuthChoice::widget([
        'baseAuthUrl' => ['auth/login-vk'],
        'popupMode' => false,
    ]); ?> <?php ActiveForm::end(); ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>