<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Organizer;

/* @var $this yii\web\View */
/* @var $model common\models\Activity */
/* @var $serialNumber */
/* @var $isTicketed */

$this->title = '修改活动信息';
$this->params['breadcrumbs'][] = ['label' => '活动列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->activity_name;
?>

<head>
	<style>
		.act-title {
			text-align:center;
			font-size:50px;
			font-weight:bold;
			color:rgb(139, 60, 112);
			margin-top:0;
			margin-bottom:20px;
		}
		.act-info-one {
			text-align:center;
			font-size:20px;
			font-weight:bold;
			color:dodgerblue;
			margin:10px 0px;
		}
		.column-flex {
			display:flex;
			flex-direction:column;
			width:100%;
			height:100%;
			justify-content:center;
			align-items:center;
		}
		.row-flex {
			display:flex;
			flex-direction:row;
			justify-content:center;
			align-items:center;
			height:100%;
			width:100%;
		}
		.check-button {
			color:#fff;
			background-color:#428bca;
			border-color:#357ebd;
			font-size:20px;
			border-radius:10px;
			line-height:40px;
			padding:0 40px;
		}
		.tb-div {
			margin:20px 0px;
		}
		table.all-table-style {
			border-width:1px;
			padding:10px;
			border-style:solid;
			border-color:#a9c6c9;
			width:100%;
			font-size:20px;
		}
		table.all-table-style td {
			border-width:1px;
			padding:10px;
			border-style: solid;
			border-color: #a9c6c9;
		}
		.attribute-color {
			background-color:#f0f2f5;
			color:#000;
		}
		.domain-color {
			background-color:#e2e5e9;
			color:#000;
		}
		.intro-title {
			font-size:30px;
			color:#000;
			font-weight:bold;
			border-left-width:7px;
			border-left-style:solid;
			border-left-color:rgb(139, 60, 112);
		}
		.intro-content {
			margin-top:15px;
			font-size:20px;
			color:#000;
		}
	</style>
</head>
<div class="row">
    <div class="tk-activity-view">
        <div class="row">
            <div class="act-title"><?= Html::encode($model->activity_name);?></div>
		<div class="row-flex">
            <div class="act-info-one"><?= $model->ticketing_start_at>\common\models\BaseForm::getTime()?"<div style='color:#888888'>(票务尚未开始)</div>":'' ?></div>
            <div class="act-info-one"><?= $isTicketed?"<div style='color:darkgreen'>(已报名参加)&emsp;&emsp;</div>":'' ?></div>
            <div class="act-info-one"><?= $isTicketed?"<div style='color:black'>你的序列号:$serialNumber</div>":'' ?></div>
		</div>
        </div>
		<div style="text-align:center;">
        <?php if($model->pic_url):?>
            <img src= "<?=$model->pic_url?>" width="200px" height="200px" alt="pic">
        <?php else:?>
            <img src="/statics/images/activity_default_pic.png" width="200px" height="200px" alt="pic">
        <?php endif;?>
		</div>
		<div style="text-align:center;margin-bottom:20px;margin-top:20px;">
        <?php if($model->ticketing_end_at>\common\models\BaseForm::getTime()&&$model->ticketing_start_at<\common\models\BaseForm::getTime()):?>
            <?php if(!$isTicketed):?>
			<button class="check-button">
            <?= Html::a('参加',
                [
                    'create-ticket',
                    'act_id' => $model->id,
                ],
                [
                    'class' => 'check-button',
                    'data' => ['method' => 'post',],
                ]) ?>
			</button>
            <?php else:?>
			<button class="check-button">
                <?= Html::a('取消参加',
                    [
                        'cancel-ticket',
                        'act_id' => $model->id,
                    ],
                    [
                        'class' => 'check-button',
                        'data' => ['method' => 'post','confirm'=>'确定取消参与该活动?'],
                    ]) ?>
			</button>
            <?php endif;?>
        <?php endif;?>
		</div>
		<div class="intro-title">&nbsp;活动基本信息</div>
		<div class="tb-div">
		<table class="all-table-style">
			<tr>
			<td class="attribute-color">活动名称</td><td class="domain-color"><?=Html::encode($model->activity_name)?></td><td class="attribute-color">活动类别</td><td class="domain-color"><?=$model->category?></td>
			</tr>
			<tr>
			<td class="attribute-color">发布者</td><td class="domain-color"><?=Html::encode($model->release_by)?></td><td class="attribute-color">活动地点</td><td class="domain-color"><?=Html::encode($model->location)?></td>
			</tr>
			<tr>
			<td class="attribute-color">当前人数</td><td class="domain-color"><?=Html::encode($model->current_people)?></td><td class="attribute-color">最大人数</td><td class="domain-color"><?=Html::encode($model->max_people)?></td>
			</tr>
			<tr>
			<td class="attribute-color">报名开始时间</td><td class="domain-color"><?=Html::encode(date('Y-m-d:H:i:s',($model->ticketing_start_at)))?></td><td class="attribute-color">报名结束时间</td><td class="domain-color"><?=date('Y-m-d:H:i:s',($model->ticketing_end_at))?></td>
			</tr>
			<tr>
			<td class="attribute-color">活动开始时间</td><td class="domain-color"><?=Html::encode(date('Y-m-d:H:i:s',($model->start_at)))?></td><td class="attribute-color">活动结束时间</td><td class="domain-color"><?=date('Y-m-d:H:i:s',($model->end_at))?></td>
			</tr>			
		</table>
		</div>
    </div>
</div>
<div class="row">
<div class="intro-title">&nbsp;活动介绍</div>
<div class="intro-content"><?=$model->introduction ?></div>

</div>