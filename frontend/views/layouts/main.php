<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

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
    <!--title><?= Html::encode($this->title) ?></title-->
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<!--div class="wrap">
    < ?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $leftMenus = [
        ['label' => '主页', 'url' => ['/site/index']],
        ['label' => '活动', 'url' => ['/activity/index']],
        ['label' => '公告', 'url' => ['/information/index']],
        //['label' => 'About', 'url' => ['/site/about']],
        //['label' => 'Contact', 'url' => ['/site/contact']],
    ];
    $rightMenus=[];
    if (Yii::$app->user->isGuest)
        {
            $rightMenus[] = ['label' => '注册', 'url' => ['/site/signup']];
            $rightMenus[] = ['label' => '登录', 'url' => ['/site/login']];
        }
    else
        {
             $rightMenus[]=
                 [
                     'label'=>Yii::$app->user->identity->user_name,
                     //'url'=>['site/logout'],
                     'items'=>
                     [
                         ['label'=>'<i class="fa fa-user"></i>个人中心', 'url'=>['/site/view'], 'linkOptions'=>['data-method'=>'post']],
                         ['label'=>'<i class="fa fa-sign-out"></i>退出', 'url'=>['/site/logout'], 'linkOptions'=>['data-method'=>'post']],
                     ],
                 ];
        }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left'],
        'items' => $leftMenus,
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels'=>false,
        'items' => $rightMenus,
    ]);
    NavBar::end();
    ?-->

    <div class="container">
        <?= Breadcrumbs::widget(
            [
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
<!--/div-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
