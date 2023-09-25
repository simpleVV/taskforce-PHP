<?php

/** 
 * @var yii\web\View $this
 * @var Category[] $categories 
 * @var User $model
 * @var AvatarUpload $avatar
 * 
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
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
    <h3 class="head-main head-regular">Мой профиль</h3>
    <div class="photo-editing">
        <div>
            <p class="form-label">Аватар</p>
            <img class="avatar-preview" src=<?= $user->avatar_path ?> width="83" height="83" />
        </div>

        <?= $form->field($avatar, 'file')
            ->fileInput([
                'value' => 'Сменить аватар',
                'hidden' => true,
                'id' => 'button-input',
            ])->label(false);
        ?>

        <label for="button-input" class="button button--black">
            Сменить аватар</label>
    </div>
    <?= $form->field($model, 'name')
        ->textInput([
            'value' => $user->name
        ]);
    ?>
    <div class="half-wrapper">
        <?= $form->field($model, 'email')
            ->input('email', [
                'value' => $user->email
            ]);
        ?>
        <?= $form->field($model, 'bdDate')
            ->input('date', [
                'value' => $user->bd_date
            ]);
        ?>
    </div>
    <div class="half-wrapper">
        <?= $form->field($model, 'phone')
            ->input('tel', [
                'value' => $user->phone
            ]);
        ?>
        <?= $form->field($model, 'telegram')
            ->textInput([
                'value' => $user->telegram
            ]);
        ?>
    </div>
    <?= $form->field($model, 'about')
        ->textarea([
            'value' => $user->about
        ]);
    ?>

    <div class="form-group">
        <?php if ($user->is_performer) : ?>
            <p class="form-label">Выбор специализаций</p>
            <div class="checkbox-profile">
                <?= HTML::activeCheckboxList($model, 'categoryId', ArrayHelper::map($categories, 'id', 'name'), [
                    'tag' => null,
                    'itemOptions' => [
                        'labelOptions' => [
                            'class' => 'control-label'
                        ],
                    ]
                ]); ?>
            </div>
        <?php endif; ?>

        <input type="submit" class="button button--blue" value="Сохранить" />
        <?php ActiveForm::end(); ?>
    </div>