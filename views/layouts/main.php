<?php

/** @var yii\web\View $this */
/** @var string $content */

use yii\helpers\Html;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>

    <header class="page-header">
        <nav class="main-nav">
            <a href="#" class="header-logo">
                <img class="logo-image" src="../../img/logotype.png" width="227" height="60" alt="taskforce" />
            </a>
            <?php var_dump(Yii::$app->controller->id) ?>
            <?php if (false) : ?>
                <div class="nav-wrapper">
                    <ul class="nav-list">
                        <li class="list-item list-item--active">
                            <a class="link link--nav">Новое</a>
                        </li>
                        <li class="list-item">
                            <a href="#" class="link link--nav">Мои задания</a>
                        </li>
                        <li class="list-item">
                            <a href="#" class="link link--nav">Создать задание</a>
                        </li>
                        <li class="list-item">
                            <a href="#" class="link link--nav">Настройки</a>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>

        </nav>

        <?php if (false) : ?>
            <div class="user-block">
                <a href="#">
                    <img class="user-photo" src="../../img/man-glasses.png" width="55" height="55" alt="Аватар" />
                </a>
                <div class="user-menu">
                    <p class="user-name">Василий</p>
                    <div class="popup-head">
                        <ul class="popup-menu">
                            <li class="menu-item">
                                <a href="#" class="link">Настройки</a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="link">Связаться с нами</a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="link">Выход из системы</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </header>

    <main class="main-content container">
        <?= $content; ?>
    </main>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>