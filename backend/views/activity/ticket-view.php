<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Organizer;

/* @var $this yii\web\View */
/* @var $model common\models\Activity */

$this->title = '活动参与信息';
$this->params['breadcrumbs'][] = ['label' => '活动列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '活动:'.$model->activity_name, 'url' => ['view','id'=>$model->id]];
$this->params['breadcrumbs'][] = '参与信息';
?>
<div class="container">
    <?=\backend\widgets\activity\ActivityWidget::widget(['option'=>'ticket-list','act_id'=>$model->id])?>
</div>


