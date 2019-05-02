<?php

/* @var $this yii\web\View */

$this->title = '票务系统组织端';
use yii\helpers\Html;
use backend\widgets\activity\ActivityWidget;
use admin\widgets\notice\NoticeWidget;
?>

<div class="container">
    <div class="container">
        <?= Html::a('发布活动', ['activity/create'], ['class' => 'btn-lg btn-primary ']) ?>
        <?= Html::a('我的活动', ['activity/mine'], ['class' => 'btn-lg btn-success ']) ?>
    </div>
    <br/>
    <div class="row" >
        <div class="col-lg-8">
            <?= ActivityWidget::widget(['option'=>'frontendList'])?>
        </div>
        <div class="col-lg-4">
            <?= NoticeWidget::widget(['title'=>'通知'])?>
        </div>
    </div>
</div>

