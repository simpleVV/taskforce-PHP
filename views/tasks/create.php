<?php

/** 
 * @var yii\web\View $this
 * @var Task $task
 * @var Category[] $categories 
 * 
 */

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use app\assets\DropzoneAsset;
use app\assets\AutoCompleteAsset;

$this->title = 'Публикация нового задания';
$this->params['main_class'] = 'main-content--center';

DropzoneAsset::register($this);
AutoCompleteAsset::register($this);
?>

<div class="add-task-form regular-form">
    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'control-label'],
            'errorOptions' => ['tag' => 'span', 'class' => 'help-block']
        ]
    ]); ?>

    <h3 class="head-main head-main">Публикация нового задания</h3>
    <?= $form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'description')->textarea() ?>
    <?= $form->field($model, 'category_id')->dropDownList(
        ArrayHelper::map($categories, 'id', 'name')
    ); ?>

    <?= $form->field($model, 'location')->textInput([
        'class' => 'location-icon',
        'id' => 'autocomplete',
        'placeholder' => 'Город, улица, дом'
    ]) ?>

    <?= $form->field($model, 'city', ['template' => '{input}'])->hiddenInput(); ?>
    <?= $form->field($model, 'lat', ['template' => '{input}'])->hiddenInput(); ?>
    <?= $form->field($model, 'long', ['template' => '{input}'])->hiddenInput(); ?>
    <div class="half-wrapper">
        <?= $form->field($model, 'price')->textInput([
            'class' => 'budget-icon'
        ]) ?>
        <?= $form->field($model, 'dt_expire')->input('date'); ?>
    </div>
    <p class="form-label">Файлы</p>
    <div class="new-file">
        <p class="add-file dz-clickable">новый файл</p>
    </div>
    <div class="files-previews">
    </div>
    <input type="submit" class="button button--blue" value="Опубликовать" />
    <?php ActiveForm::end(); ?>
</div>

<?php
$uploadUrl = Url::toRoute(['tasks/upload']);
$this->registerJs(<<<JS
var myDropzone = new Dropzone('.new-file', {
    maxFiles: 4,
    url: "$uploadUrl", 
    previewsContainer: ".files-previews",
    sending: function (none, xhr, formData) {
        formData.append('_csrf', $('input[name=_csrf]').val());
    }
});
JS, View::POS_READY);
?>