<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = '修改密码';
$this->params['breadcrumbs'][] = ['label' => '我的资料', 'url' => ['view']];
$this->params['breadcrumbs'][] = $this->title;
?>
<head>
<style>
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
</style>
</head>
<div class="col-lg-1">
	<div class="sidenv">
		<div style="width:20px;margin-left:5px;"><a href="/site/repassword">修改密码</a></div>
		<div class="sidenv_after"></div>
		<div style="width:20px;margin-left:5px;"><a href="/site/update?scenario=ChangeUserName">修改用户名</a></div>
		<div class="sidenv_after"></div>
		<div style="width:20px;margin-left:5px;"><a href="/site/update?scenario=ChangeEmail">修改邮箱</a></div>
		<div class="sidenv_after"></div>
		<div style="width:20px;margin-left:5px;"><a href="/site/view">我的资料</a></div>
	</div>
</div>
<div class="col-lg-11">
<div class="col-lg-9">
    <div class="index-title">修改密码</div>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'oldPassword')->passwordInput()?>

    <?= $form->field($model, 'password')->passwordInput()?>

    <?= $form->field($model, 'rePassword')->passwordInput()?>

    <div class="form-group">
        <?= Html::submitButton('确认修改', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<div class="col-lg-3"></div>
</div>
