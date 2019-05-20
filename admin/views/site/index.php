<?php

use admin\widgets\notice\NoticeWidget;
use backend\widgets\activity\ActivityWidget;
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = '票务系统管理端';

 ?>

<div class="container">
    <div class="container">
        <div class="col-lg-4">
            <?= Html::a('活动管理', ['activity/index'], ['class' => 'btn-lg btn-primary ']) ?>
            <?= Html::a('票务管理', ['ticket/index'], ['class' => 'btn-lg btn-primary ']) ?>
        </div>
        <div class="col-lg-4">
            <?= Html::a('用户管理', ['user/index'], ['class' => 'btn-lg btn-success ']) ?>
            <?= Html::a('组织者管理', ['organizer/index'], ['class' => 'btn-lg btn-success ']) ?>
        </div>
        <div class="col-lg-4">
        </div>
    </div>
    <br/>
    <div class="row" >
        <div class="col-lg-8">
            <?= ActivityWidget::widget(['option'=>'admin-index'])?>
            <?= ActivityWidget::widget(['option'=>'admin-index-out-of-date'])?>
            <?= ActivityWidget::widget(['option'=>'frontendList','title'=>'已通过'])?>
        </div>
        <div class="col-lg-4">
            <p>
                <?= Html::a('创建通知', ['notice/create'], ['class' => 'btn-lg btn-success ']) ?>
            </p>
            <br/>
            <?= NoticeWidget::widget(['title'=>'已发布通知'])?>
        </div>
    </div>
</div>


