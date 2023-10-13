<?php

/** @var yii\web\View $this */
/** @var string $content */

use yii\helpers\Html;
use yii\widgets\Menu;
use yii\helpers\Url;
use app\assets\AppAsset;

AppAsset::register($this);

$user = Yii::$app->user->identity;

?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language; ?>">

<head>
    <meta charset="<?= Yii::$app->charset; ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title); ?></title>
    <?php $this->head() ?>

</head>

<body>
    <?php $this->beginBody() ?>

    <header class="page-header">
        <nav class="main-nav">
            <a href="<?= Url::toRoute(['tasks/index']); ?>" class="header-logo">
                <img class="logo-image" src="../../img/logotype.png" width="227" height="60" alt="taskforce" />
            </a>

            <?php if (Yii::$app->controller->action->id !== 'signup') : ?>

                <div class="nav-wrapper">

                    <?= Menu::widget([
                        'options' => ['class' => 'nav-list'],
                        'activeCssClass' => 'list-item--active',
                        'itemOptions' => ['class' => 'list-item'],
                        'linkTemplate' => '<a href="{url}" class="link link--nav">{label}</a>',
                        'items' => [
                            [
                                'label' => 'Новое',
                                'url' => ['tasks/index']
                            ],
                            [
                                'label' => 'Мои задания',
                                'active' => $this->title === 'Мои задания',
                                'url' => [
                                    'tasks/view-my', 'status' => $user->is_performer
                                        ? 'in_progress'
                                        : 'new',
                                ],
                            ],
                            [
                                'label' => 'Создать задание',
                                'url' => ['tasks/create'],
                                'visible' => !$user->is_performer
                            ],
                            [
                                'label' => 'Настройки',
                                'active' => $this->title === 'Настройки',
                                'url' => ['user/settings', 'id' => $user->id]
                            ]
                        ],

                    ]); ?>

                </div>

            <?php endif; ?>

        </nav>

        <?php if (Yii::$app->controller->action->id !== 'signup') : ?>
            <div class="user-block">
                <a href="#">
                    <img class="user-photo" src=<?= $user->avatar_path; ?> width="55" height="55" alt="Аватар" />
                </a>
                <div class="user-menu">
                    <p class="user-name"><?= $user->name; ?></p>
                    <div class="popup-head">

                        <?= Menu::widget([
                            'options' => ['class' => 'popup-menu'],
                            'itemOptions' => ['class' => 'menu-item'],
                            'linkTemplate' => '<a href="{url}" class="link">{label}</a>',
                            'items' => [
                                ['label' => 'Настройки', 'url' => Url::toRoute(['user/settings', 'id' => $user->id])],
                                ['label' => 'Связаться с нами', 'url' => ['user/settings']],
                                ['label' => 'Выход из системы', 'url' => Url::toRoute(['auth/logout'])]
                            ]
                        ]); ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>

    </header>

    <main class="main-content container <?= $this->params['main_class'] ?? '' ?>">

        <?= $content; ?>

    </main>
    <div class="overlay"></div>

    <?php $this->endBody() ?>

</body>

</html>

<?php $this->endPage() ?>