<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Organizer;

/* @var $this yii\web\View */
/* @var $model common\models\Notice */

$this->title = '通知';
$this->params['breadcrumbs'][] = ['label' => '通知列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;
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
            <div class="act-title"><?= Html::encode($model->title);?></div>
        </div>
		<div class="intro-title">&nbsp;通知基本信息</div>
		<div class="tb-div">
		<table class="all-table-style">
			<tr>
			<td class="attribute-color">通知标题</td><td class="domain-color"><?=Html::encode($model->title)?></td>
			</tr>
			<tr>
			<td class="attribute-color">总结</td><td class="domain-color"><?=Html::encode($model->summary)?></td>
			</tr>
			<tr>
			<td class="attribute-color">上次更新</td><td class="domain-color"><?=Html::encode(date('Y-m-d:H:i:s',($model->updated_at)))?></td>
			</tr>
			<tr>
			<td class="attribute-color">发布时间</td><td class="domain-color"><?=Html::encode(date('Y-m-d:H:i:s',($model->created_at)))?></td>
			</tr>			
		</table>
		</div>
    </div>
</div>
<div class="row">
<div class="intro-title">&nbsp;通知内容</div>
<div class="intro-content"><?=$model->content ?></div>
</div>

