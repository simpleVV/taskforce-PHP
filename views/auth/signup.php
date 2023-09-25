<?php

/** 
 * @var yii\web\View $this
 * @var User $model
 * @var City $cities
 */

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$this->title = 'Регистрация пользователя';
$this->params['main_class'] = 'container--registration';

?>

<div class="center-block">
    <div class="registration-form regular-form">
        <?php $form = ActiveForm::begin([
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'control-label'],
                'errorOptions' => ['tag' => 'span', 'class' => 'help-block']
            ]
        ]); ?>
        <h3 class="head-main head-task">Регистрация нового пользователя</h3>

        <?= $form->field($model, 'name')
            ->textInput();
        ?>
        <div class="half-wrapper">
            <?= $form->field($model, 'email')
                ->input('email');
            ?>
            <?= $form->field($model, 'cityId')
                ->dropDownList(
                    ArrayHelper::map($cities, 'id', 'name'),
                    [
                        'prompt' => 'Выбрать'
                    ]
                );
            ?>
        </div>
        <div class="half-wrapper">
            <?= $form->field($model, 'password')
                ->passwordInput();
            ?>
        </div>
        <div class="half-wrapper">
            <?= $form->field($model, 'passwordRepeat')
                ->passwordInput();
            ?>
        </div>
        <?= $form->field($model, 'isPerformer')
            ->checkbox([
                'labelOptions' => [
                    'class' => 'control-label checkbox-label'
                ],
                'template' => "{label}\n{input}"
            ]);
        ?>
        <input type="submit" class="button button--blue" value="Создать аккаунт" />
        <?php ActiveForm::end(); ?>
    </div>
</div>