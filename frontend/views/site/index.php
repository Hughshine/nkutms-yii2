<?php

/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;
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
                            <?= Html::a('   登录', ['site/login',],
                                [
                                    'class' => 'fa fa-sign-in',
                                    'data' => ['method' => 'post',],
                                ]) ?>
                        </li>
                        <li>
                            <?= Html::a('   注册', ['site/signup',],
                                [
                                    'class' => 'fa fa-user-plus',
                                    'data' => ['method' => 'post',],
                                ]) ?>
                        </li>
                    </ul>
                <?php else:?>
                    <ul class ="pull-right">
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <?php if(Yii::$app->user->identity->img_url) :?>
                                    <img src= "<?= Yii::$app->user->identity->img_url?>"
                                         width="32px"
                                         height="32px"
                                         alt="avatar">
                                <?php else:?>
                                欢迎: <?= Yii::$app->user->identity->user_name?>
                                <?php endif;?>
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><?= Html::a('个人资料', ['view'],
                                        [
                                            'class' => 'fa fa-user',
                                            'data' => ['method' => 'post',],
                                        ]) ?>
                                </li>
                                <li>
                                    <?= Html::a('修改密码', ['repassword'],
                                        [
                                            'class' => 'fa fa-user-secret',
                                            'data' => ['method' => 'post',],
                                        ]) ?>
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
            <form class="form-search">
                <input type="text" class="input-medium search-query">
                <button type="submit" class="btn"><span class="glyphicon glyphicon-search"></span></button>
            </form>
        </div>
        <div class="col-md-3">
            <?php if(!Yii::$app->user->isGuest):?>
            <div id="cart" class="pull-left">
                <?= Html::a('参与的活动:0', ['index'],
                    [
                        'class' => 'btn btn-1 fa fa-hand-paper-o',
                        'data' => ['method' => 'post',],
                    ]) ?>
            </div>
            <?php endif;?>
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
                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">活动</a>
                    <div class="dropdown-menu">
                        <div class="dropdown-inner">
                            <ul class="list-unstyled">
                                <li><a href="category.html">分类1</a></li>
                                <li><a href="category.html">分类2</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li><?= Html::a('公告', ['site/information',]) ?></li>
                <li><?= Html::a('组织', ['site/organization',]) ?></li>
            </ul>
        </div>
    </div>
</nav>
<!--//////////////////////////////////////////////////-->
<!--///////////////////HomePage///////////////////////-->
<!--//////////////////////////////////////////////////-->
<div id="page-content" class="home-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-10">
                <!-- Carousel -->
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators hidden-xs">
                        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                    </ol>
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner">
                        <div class="item active">
                            <img src="<?=Url::to('@web/template/images/main-banner1-1903x600.jpg');?>" width="1800px" height="280px" alt="First slide">
                            <!-- Static Header -->
                            <div class="header-text hidden-xs">
                                <div class="col-md-12 text-center">
                                </div>
                            </div><!-- /header-text -->
                        </div>
                        <div class="item">
                            <img src="<?=Url::to('@web/template/images/main-banner2-1903x600.jpg');?>" width="1800px" height="280px"alt="Second slide">
                            <!-- Static Header -->
                            <div class="header-text hidden-xs">
                                <div class="col-md-12 text-center">
                                </div>
                            </div><!-- /header-text -->
                        </div>
                        <div class="item">
                            <img src="<?=Url::to('@web/template/images/main-banner3-1903x600.jpg');?>" width="1800px" height="280px"alt="Third slide">
                            <!-- Static Header -->
                            <div class="header-text hidden-xs">
                                <div class="col-md-12 text-center">
                                </div>
                            </div><!-- /header-text -->
                        </div>
                    </div>
                    <!-- Controls -->
                    <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                    </a>
                </div><!-- /carousel -->
            </div>
            <div class="col-lg-1"></div>
        </div>
        <div class="row">
            <div class="banner">
                <div class="col-sm-4">
                    <img src="<?=Url::to('@web/template/images/null.jpg');?>" alt=""/>
                </div>
                <div class="col-sm-4">
                    <img src="<?=Url::to('@web/template/images/null.jpg');?>" alt=""/>
                </div>
                <div class="col-sm-4">
                    <img src="<?=Url::to('@web/template/images/null.jpg');?>" alt=""/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="heading"><h2>SPECIAL PRODUCTS</h2></div>
                <div class="products">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="product">
                            <div class="image"><a href="product.html"><img src="<?=Url::to('@web/template/images/null.jpg');?>" alt=""/></a></div>
                            <div class="buttons">
                                <a class="btn cart" href="#"><span class="fa fa-ticket"></span></a>
                                <a class="btn wishlist" href="#"><span class="glyphicon glyphicon-heart"></span></a>
                                <a class="btn compare" href="#"><span class="glyphicon glyphicon-transfer"></span></a>
                            </div>
                            <div class="caption">
                                <div class="name"><h3><a href="product.html">Aliquam erat volutpat</a></h3></div>
                                <div class="price">$122<span>$98</span></div>
                                <div class="rating"><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star-empty"></span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="product">
                            <div class="image"><a href="product.html"><img src="<?=Url::to('@web/template/images/null.jpg');?>" alt=""/></a></div>
                            <div class="buttons">
                                <a class="btn cart" href="#"><span class="fa fa-ticket"></span></a>
                                <a class="btn wishlist" href="#"><span class="glyphicon glyphicon-heart"></span></a>
                                <a class="btn compare" href="#"><span class="glyphicon glyphicon-transfer"></span></a>
                            </div>
                            <div class="caption">
                                <div class="name"><h3><a href="product.html">Aliquam erat volutpat</a></h3></div>
                                <div class="price">$122<span>$98</span></div>
                                <div class="rating"><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="product">
                            <div class="image"><a href="product.html"><img src="<?=Url::to('@web/template/images/null.jpg');?>" alt=""/></a></div>
                            <div class="buttons">
                                <a class="btn cart" href="#"><span class="fa fa-ticket"></span></a>
                                <a class="btn wishlist" href="#"><span class="glyphicon glyphicon-heart"></span></a>
                                <a class="btn compare" href="#"><span class="glyphicon glyphicon-transfer"></span></a>
                            </div>
                            <div class="caption">
                                <div class="name"><h3><a href="product.html">Aliquam erat volutpat</a></h3></div>
                                <div class="price">$122<span>$98</span></div>
                                <div class="rating"><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="product">
                            <div class="image"><a href="product.html"><img src="<?=Url::to('@web/template/images/null.jpg');?>" /></a></div>
                            <div class="buttons">
                                <a class="btn cart" href="#"><span class="fa fa-ticket"></span></a>
                                <a class="btn wishlist" href="#"><span class="glyphicon glyphicon-heart"></span></a>
                                <a class="btn compare" href="#"><span class="glyphicon glyphicon-transfer"></span></a>
                            </div>
                            <div class="caption">
                                <div class="name"><h3><a href="product.html">Aliquam erat volutpat</a></h3></div>
                                <div class="price">$122<span>$98</span></div>
                                <div class="rating"><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="banner">
                <div class="col-sm-6">
                    <img src="<?=Url::to('@web/template/images/null.jpg');?>" alt="" />
                </div>
                <div class="col-sm-6">
                    <img src="<?=Url::to('@web/template/images/null.jpg');?>" alt="" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="heading"><h2>FEATURED PRODUCTS</h2></div>
                <div class="products">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="product">
                            <div class="image"><a href="product.html"><img src="<?=Url::to('@web/template/images/null.jpg');?>" alt=""/></a></div>
                            <div class="buttons">
                                <a class="btn cart" href="#"><span class="fa fa-ticket"></span></a>
                                <a class="btn wishlist" href="#"><span class="glyphicon glyphicon-heart"></span></a>
                                <a class="btn compare" href="#"><span class="glyphicon glyphicon-transfer"></span></a>
                            </div>
                            <div class="caption">
                                <div class="name"><h3><a href="product.html">Aliquam erat volutpat</a></h3></div>
                                <div class="price">$122<span>$98</span></div>
                                <div class="rating"><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star-empty"></span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="product">
                            <div class="image"><a href="product.html"><img src="<?=Url::to('@web/template/images/null.jpg');?>" /></a></div>
                            <div class="buttons">
                                <a class="btn cart" href="#"><span class="fa fa-ticket"></span></a>
                                <a class="btn wishlist" href="#"><span class="glyphicon glyphicon-heart"></span></a>
                                <a class="btn compare" href="#"><span class="glyphicon glyphicon-transfer"></span></a>
                            </div>
                            <div class="caption">
                                <div class="name"><h3><a href="product.html">Aliquam erat volutpat</a></h3></div>
                                <div class="price">$122<span>$98</span></div>
                                <div class="rating"><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="product">
                            <div class="image"><a href="product.html"><img src="<?=Url::to('@web/template/images/null.jpg');?>" /></a></div>
                            <div class="buttons">
                                <a class="btn cart" href="#"><span class="fa fa-ticket"></span></a>
                                <a class="btn wishlist" href="#"><span class="glyphicon glyphicon-heart"></span></a>
                                <a class="btn compare" href="#"><span class="glyphicon glyphicon-transfer"></span></a>
                            </div>
                            <div class="caption">
                                <div class="name"><h3><a href="product.html">Aliquam erat volutpat</a></h3></div>
                                <div class="price">$122<span>$98</span></div>
                                <div class="rating"><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span><span class="glyphicon glyphicon-star-empty"></span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="product">
                            <div class="image"><a href="product.html"><img src="<?=Url::to('@web/template/images/null.jpg');?>" /></a></div>
                            <div class="buttons">
                                <a class="btn cart" href="#"><span class="fa fa-ticket"></span></a>
                                <a class="btn wishlist" href="#"><span class="glyphicon glyphicon-heart"></span></a>
                                <a class="btn compare" href="#"><span class="glyphicon glyphicon-transfer"></span></a>
                            </div>
                            <div class="caption">
                                <div class="name"><h3><a href="product.html">Aliquam erat volutpat</a></h3></div>
                                <div class="price">$122<span>$98</span></div>
                                <div class="rating"><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<footer>
    <div class="container">
        <div class="wrap-footer">
            <div class="row">
                <div class="col-md-3 col-footer footer-1">
                    <img src="<?=Url::to('@web/template/images/null.jpg');?>" alt=""/>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                </div>
                <div class="col-md-3 col-footer footer-2">
                    <div class="heading"><h4>Customer Service</h4></div>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Delivery Information</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms & Conditions</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-md-3 col-footer footer-3">
                    <div class="heading"><h4>My Account</h4></div>
                    <ul>
                        <li><a href="#">My Account</a></li>
                        <li><a href="#">Brands</a></li>
                        <li><a href="#">Gift Vouchers</a></li>
                        <li><a href="#">Specials</a></li>
                        <li><a href="#">Site Map</a></li>
                    </ul>
                </div>
                <div class="col-md-3 col-footer footer-4">
                    <div class="heading"><h4>联系我们</h4></div>
                    <ul>
                        <li><span class="glyphicon glyphicon-home"></span>California, United States 3000009</li>
                        <li><span class="glyphicon glyphicon-earphone"></span>+91 8866888111</li>
                        <li><span class="glyphicon glyphicon-envelope"></span>infor@yoursite.com</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    Copyright &copy; 2015.Company name All rights reserved.
                </div>
                <div class="col-md-6">
                </div>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
