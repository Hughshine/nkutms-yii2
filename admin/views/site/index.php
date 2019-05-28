<?php

use admin\widgets\auth_info\AuthInfoWidget;
use admin\widgets\notice\NoticeWidget;
use admin\widgets\report\ReportWidget;
use backend\widgets\activity\ActivityWidget;
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = '票务系统管理端';

 ?>
<head>
	<style>
		.site-container {
			width:90%;
			height:100%;
		}
		.site-center {
			margin-right:30px;
		}
	</style>
</head>
<div class="site-center">
<div class="site-center">
    <div class="container">
        <div class="col-lg-4">
            <?= Html::a('活动管理', ['activity/index'], ['class' => 'btn-lg btn-primary ']) ?>
            <?= Html::a('票务管理', ['ticket/index'], ['class' => 'btn-lg btn-primary ']) ?>
            <?= Html::a('组织反馈', ['report/index'], ['class' => 'btn-lg btn-primary ']) ?>
        </div>
        <div class="col-lg-4">
            <?= Html::a('用户管理', ['user/index'], ['class' => 'btn-lg btn-warning ']) ?>
            <?= Html::a('组织者管理', ['organizer/index'], ['class' => 'btn-lg btn-warning ']) ?>
        </div>
        <div class="col-lg-4">
        </div>
    </div>
    <br/>
    <div class="row" >
        <div class="col-lg-8">
            <?= ActivityWidget::widget(['option'=>'admin-index'])?>
            <?= ActivityWidget::widget(['option'=>'admin-index-out-of-date'])?>
            <?= ActivityWidget::widget(['option'=>'frontendList','title'=>'<font color="#339933">已通过活动</font>',])?>
        </div>
        <div class="col-lg-4">
            <p>
                <?= Html::a('创建通知', ['notice/create'], ['class' => 'btn-lg btn-success ']) ?>
            </p>
            <br/>
            <div class="row">
                <?= NoticeWidget::widget(['title'=>'已发布通知','limit'=>2])?>
            </div>
        </div>
    </div>
</div>
</div>

