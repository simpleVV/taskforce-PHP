<?php

/** 
 * @var yii\web\View $this 
 * @var ResponseForm $model
 * */

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\helpers\HtmlHelper;

$starElements = HtmlHelper::getStarElements(0, true, 'big');
?>

<section class="pop-up pop-up--completion pop-up--close">
  <div class="pop-up--wrapper">
    <h4>Завершение задания</h4>
    <p class="pop-up-text">
      Вы собираетесь отметить это задание как выполненное. Пожалуйста,
      оставьте отзыв об исполнителе и отметьте отдельно, если возникли
      проблемы.
    </p>
    <div class="completion-form pop-up--form regular-form">
      <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => true,
        'validationUrl' => Url::to(['reviews/validate']),
        'action' => Url::to(['reviews/create', 'taskId' => $taskId]),
        'fieldConfig' => [
          'template' => "{label}\n{input}\n{error}",
          'labelOptions' => ['class' => 'control-label'],
          'errorOptions' => ['tag' => 'span', 'class' => 'help-block']
        ]
      ]); ?>
      <?= $form->field($model, 'description')->textarea(); ?>
      <?= $form->field($model, 'rate', [
        'labelOptions' => ['class' => 'completion-head control-label'],
        'template' => "{label}{input}$starElements{error}"
      ])
        ->hiddenInput(); ?>

      <input type="submit" class="button button--pop-up button--blue" value="Завершить" />
      <?php ActiveForm::end() ?>
    </div>
    <div class="button-container">
      <button class="button--close" type="button">Закрыть окно</button>
    </div>
  </div>
</section>