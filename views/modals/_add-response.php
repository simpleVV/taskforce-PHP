<?php

/** 
 * @var yii\web\View $this 
 * @var ResponseForm $model
 * */

use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>

<section class="pop-up pop-up--act_response pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Добавление отклика к заданию</h4>
        <p class="pop-up-text">
            Вы собираетесь оставить свой отклик к этому заданию. Пожалуйста,
            укажите стоимость работы и добавьте комментарий, если необходимо.
        </p>
        <div class="addition-form pop-up--form regular-form">
            <?php $form = ActiveForm::begin([
                'enableAjaxValidation' => true,
                'validationUrl' => Url::to(['responses/validate', 'taskId' => $taskId]),
                'action' => Url::to(['responses/create', 'taskId' => $taskId]),
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'control-label'],
                    'errorOptions' => ['tag' => 'span', 'class' => 'help-block']
                ]
            ]); ?>

            <?= $form->field($model, 'comment')->textarea(); ?>
            <?= $form->field($model, 'price'); ?>

            <input type="submit" class="button button--pop-up button--blue" value="Завершить" />
            <?php ActiveForm::end() ?>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>