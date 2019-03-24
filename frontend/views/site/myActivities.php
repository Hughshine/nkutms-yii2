<?php

/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;
use backend\widgets\activity\ActivityWidget;
?>
<!DOCTYPE html>
<html lang=<?= Yii::$app->language ?> >
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="">
    <meta name="author" content="">

    <title>南开票务系统</title>

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="<?=Url::to('@web/template/css/bootstrap.min.css');?>"  type="text/css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?=Url::to('@web/template/css/style.css');?>">

    <!-- Custom Fonts -->
    <link rel="stylesheet" href="<?=Url::to('@web/template/font-awesome/css/font-awesome.min.css');?>"  type="text/css">
    <link rel="stylesheet" href="<?=Url::to('@web/template/fonts/font-slider.css');?>" type="text/css">

    <!-- jQuery and Modernizr-->
    <script src="<?=Url::to('@web/template/js/jquery-2.1.1.js');?>"></script>

    <!-- Core JavaScript Files -->
    <script src="<?=Url::to('@web/template/js/bootstrap.min.js');?>"></script>

</head>

<body>
<!--Top-->
<nav id="top">
    <div class="container">
        <div class="row">
            <div class="col-xs-1"></div>
            <div class="col-xs-5">
            </div>
            <div class="col-xs-5">
                <?php if(Yii::$app->user->isGuest):?>
                    <ul class="top-link">
                        <li>
                            <?= Html::a('   登录', ['site/login',],['class' => 'fa fa-sign-in',]) ?>
                        </li>
                        <li>
                            <?= Html::a('   注册', ['site/signup',],['class' => 'fa fa-user-plus',]) ?>
                        </li>
                    </ul>
                <?php else:?>
                    <ul class ="pull-right">
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <?php if(Yii::$app->user->identity->img_url) :?>
                                    <img src= "<?=Yii::$app->user->identity->img_url?>"
                                         width="32px"
                                         height="32px"
                                         alt="avatar">
                                <?php else:?>
                                欢迎: <?= Yii::$app->user->identity->user_name?>
                                <?php endif;?>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <?= Html::a('个人资料', ['site/view'],['class' => 'fa fa-user',]) ?>
                                </li>
                                <li>
                                    <?= Html::a('修改密码', ['site/repassword'],
                                        ['class' => 'fa fa-user-secret',]) ?>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <?= Html::a('退出', ['site/logout',],
                                        [
                                            'class' => 'fa fa-sign-out',
                                            'data' => ['method' => 'post',],
                                        ]) ?>
                                </li>
                            </ul>
                        </li>
                        <!--<li><a href="account.html"><span class="glyphicon glyphicon-user"></span> My Account</a></li>
                        <li><a href="contact.html"><span class="glyphicon glyphicon-envelope"></span> Contact</a></li-->
                    </ul>
                <?php endif;?>
            </div>
            <div class="col-xs-1"></div>
        </div>
    </div>
</nav>
<!--Header-->
<header class="container">
    <div class="row">
        <div class="col-md-4">
            <div id="logo" style="margin-bottom: 0px;margin-top: 0px;">
                <img src="<?=Url::to('@web/template/images/nklogo.png');?>" width="200px" height="50px" alt="logo"/>
            </div>
        </div>
        <div class="col-md-5">
            <!--form class="form-search">
                <input type="text" class="input-medium search-query">
                <button type="submit" class="btn"><span class="glyphicon glyphicon-search"></span></button>
            </form-->
        </div>
        <div class="col-md-3">
        </div>
    </div>
</header>
<!--Navigation-->
<nav id="menu" class="navbar">
    <div class="container">
        <div class="navbar-header"><span id="heading" class="visible-xs">Categories</span>
            <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav">
                <li>
                    <?= Html::a('主页', ['site/index',]) ?>
                </li>
                <!--li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">活动</a>
                    <div class="dropdown-menu">
                        <div class="dropdown-inner">
                            <ul class="list-unstyled">
                                <?= Html::a('分类1', ['activity/index']) ?>
                                <?= Html::a('分类2', ['activity/index']) ?>
                            </ul>
                        </div>
                    </div>
                </li-->
                <li><?= Html::a('活动', ['activity/index',]) ?></li>
                <li><?= Html::a('通知', ['notice/index',]) ?></li>
            </ul>
        </div>
    </div>
</nav>
<div class="row">
    <div class="col-lg-9">
        <?= ActivityWidget::widget(['option'=>'userActivities'])?>
    </div>
    <div class="col-lg-3">
        <div class="box-title">
            图示:
        </div>
        <div class="panel-body border-bottom">
            <span style="font-size:20px"><span class="fa fa-user-o"></span>发布者</span><br/>
            <span style="font-size:20px"><span class="fa fa-group"></span> <span class="fa fa-user-times"></span>人数</span><br/>
            <span style="font-size:20px"><span class="fa fa-location-arrow"></span>活动地点</span><br/>
            <span style="font-size:20px"><span class="fa fa-clock-o"></span>发布时间</span><br/>
            <span style="font-size:20px"><span class="fa fa-ticket"></span>票务开始---结束时间</span><br/>
            <span style="font-size:20px"><span class="fa fa-info-circle"></span>当前状态</span><br/>
        </div>
    </div>
</div>