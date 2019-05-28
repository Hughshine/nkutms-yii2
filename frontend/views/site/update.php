<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\file_upload\FileUpload;

/* @var $this yii\web\View */
/* @var $model common\models\Organizer */

$this->title = '修改资料';
$this->params['breadcrumbs'][] = ['label' => '我的资料', 'url' => ['view']];
$this->params['breadcrumbs'][] = '修改资料';
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
		.xx-title {
			margin-left:0px;
			font-size:20px;
			margin-top:10px;
			margin-bottom:10px;
			color:#000;
			padding-bottom:10px;
			font-weight:bold;
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
	<div style="width:20px;margin-left:5px;"><a href="/site/update?scenario=ChangeAvatar">修改头像</a></div>
	<div class="sidenv_after"></div>
	<div style="width:20px;margin-left:5px;"><a href="/site/view">我的资料</a></div>
	</div>
</div>
<div class="col-lg-11">
<div class="row">
        <div class="user-form">
            <div class="index-title"><?= Html::encode($this->title) ?></div>
            <?php $form = ActiveForm::begin(); ?>
            <?php switch($scenario):case'ChangeUserName':?>
                        <div class="xx-title">原用户名:<?=Yii::$app->user->identity->user_name?></div>

                        <?= $form->field($model, 'user_name')->textInput()?>	
                    <?php break;case'ChangeAvatar':?>
                        <div style="margin-top:30px;margin-bottom:30px;">
                            <?php if(Yii::$app->user->identity->img_url):?>
                                <img src="<?=Yii::$app->user->identity->img_url?>" width="200px" height="200px" alt="avatar">
                            <?php endif;?>
                        </div>
                        <div style="margin-top:30px;margin-bottom:30px;">
                            <?= $form->field($model, 'img_url')->widget('common\widgets\file_upload\FileUpload',[
                                    'config'=>[
                                    //图片上传的一些配置，不写调用默认配置
                                    //'domain_url' => '@web/images/user/avatar',
                                ]
                            ]) ?>
                            <?= Html::a('不用头像', ['site/update','scenario'=>'RemoveAvatar'], ['class' => 'btn btn-warning']) ?>
                        </div>

                <?php break;case'ChangeEmail':?>

                        <div class="xx-title">原邮箱:<?= Yii::$app->user->identity->email?></div>

                        <?= $form->field($model, 'email')->textInput()?>

                    <?php break;default : break;endswitch; ?>

            <div class="form-group"><?= Html::submitButton('确认', ['class' => 'btn btn-success']) ?></div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>


