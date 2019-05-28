<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = '我的资料';
$this->params['breadcrumbs'][] = $this->title;

?>
<head>
	<style>
		.index-title {
			margin-left:0px;
			font-size:30px;
			margin-top:10px;
			margin-bottom:10px;
			color:#000;
			padding-bottom:10px;
			font-weight:bold;
			text-align:center;
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
		.sidenv {
			width:38px;
			margin-top:15px;
			margin-left:0px;
			background-color:rgb(139, 60, 112);
			overflow-x:hidden;
			border-radius:4px;
			margin-bottom:100px;
		}
		.sidenv a{
			padding:10px 5px;
			text-decoration:none;
			font-size:15px;
			color:#fff;
			display:block;
			line-height:18px;
			font-weight:bold;
		}
		.sidenv a:hover {
			color:#98d1ec;
			text-align:center;
		}
		.sidenv_after {
			content: '';
			display: block;
			margin-left:4px;
			margin-top:-20px;
			margin-bottom:20px;
			width: 30px;
			height: 30px;
			border-left: 1px solid #DDD;
			border-bottom: 1px solid #DDD;
			-webkit-transform: rotate(-45deg);
			-moz-transform: rotate(-45deg);
			-o-transform: rotate(-45deg);
			transform: rotate(-45deg);
		}
	</style>
</head>
<div class="col-lg-1">
	<div class="sidenv">
		<div style="width:20px;margin-left:5px;"><a href="/site/repassword">修改密码</a></div>
		<div class="sidenv_after"></div>
		<div style="width:20px;margin-left:5px;"><a href="/site/update?scenario=ChangeUserName">修改用户名</a></div>
		<div class="sidenv_after"></div>
		<div style="width:20px;margin-left:5px;"><a href="/site/update?scenario=ChangeEmail">修改邮箱</a></div>
	</div>
</div>
<div class="col-lg-11">

    <div class="index-title"><?= Html::encode($this->title) ?></div>
	<div style="text-align:center;margin-bottom:30px;">
		<?php if(Yii::$app->user->identity->img_url):?>
			<img src= "<?=Yii::$app->user->identity->img_url?>" width="200" height="200" alt="pic"></img>
		<?php endif;?>
	</div>
	
	<!--<a class="btn btn-primary" href="/site/repassword">修改密码</a>>
	<<a class="btn btn-primary" href="/site/update?scenario=ChangeUserName">修改用户名</a>>
	<<a class="btn btn-primary" href="/site/update?scenario=ChangeEmail">修改邮箱</a>>
	<<a class="btn btn-primary" href="/site/update?scenario=ChangeAvatar">修改头像</a>>
	<<a class="btn btn-primary" href="/auth-info/create">请求认证</a>-->	

	
	<div class="intro-title">&nbsp;个人信息</div>	
	<div class="tb-div">
		<table class="all-table-style">
			<tr>
			<td class="attribute-color">名字</td><td class="domain-color"><?=Html::encode(Yii::$app->user->identity->user_name)?></td>
			</tr>
			<tr>
			<td class="attribute-color">邮箱</td><td class="domain-color"><?=Html::encode(Yii::$app->user->identity->email)?></td>
			</tr>
			<tr>
			<td class="attribute-color">账号</td><td class="domain-color"><?=Html::encode(Yii::$app->user->identity->credential)?></td>
			</tr>
			<tr>
			<td class="attribute-color">类别</td><td class="domain-color"><?=Html::encode(USER_CATEGORY[Yii::$app->user->identity->category])?></td>
			</tr>
			<tr>
			<td class="attribute-color">注册时间</td><td class="domain-color"><?=Html::encode(date('Y-m-d:H:i:s',Yii::$app->user->identity->created_at))?></td>
			</tr>
			<tr>
			<td class="attribute-color">上一次资料更新时间</td><td class="domain-color"><?=Html::encode(date('Y-m-d:H:i:s',Yii::$app->user->identity->updated_at))?></td>
			</tr>			
		</table>	
	</div>
</div>