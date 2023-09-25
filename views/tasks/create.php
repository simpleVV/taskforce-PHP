<?php

/** 
 * @var yii\web\View $this
 * @var Task $model
 * @var Category[] $categories 
 * 
 */

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\assets\DropzoneAsset;
use app\assets\DropzoneInputAsset;
use app\assets\AutoCompleteAsset;

$this->params['main_class'] = 'main-content--center';
$this->title = 'Публикация нового задания';

DropzoneAsset::register($this);
DropzoneInputAsset::register($this);
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
    <?= $form->field($model, 'title')
        ->textInput();
    ?>
    <?= $form->field($model, 'description')
        ->textarea();
    ?>
    <?= $form->field($model, 'categoryId')
        ->dropDownList(
            ArrayHelper::map($categories, 'id', 'name')
        );
    ?>

    <?= $form->field($model, 'location')
        ->textInput([
            'class' => 'location-icon',
            'id' => 'autocomplete',
            'placeholder' => 'Город, улица, дом'
        ]);
    ?>

    <?= $form->field($model, 'city', ['template' => '{input}'])
        ->hiddenInput();
    ?>
    <?= $form->field($model, 'lat', ['template' => '{input}'])
        ->hiddenInput();
    ?>
    <?= $form->field($model, 'long', ['template' => '{input}'])
        ->hiddenInput();
    ?>
    <div class="half-wrapper">
        <?= $form->field($model, 'price')
            ->textInput([
                'class' => 'budget-icon'
            ]);
        ?>
        <?= $form->field($model, 'dtExpire')
            ->input('date');
        ?>
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