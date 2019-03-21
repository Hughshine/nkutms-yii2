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
<div class="row">
    <div class="col-lg-9">
        <div class="user-form">
            <h1><?= Html::encode($this->title) ?></h1>
            <?php $form = ActiveForm::begin(); ?>
            <?php switch($scenario):case'ChangeUserName':?>
                        <h5>原用户名:<?=Yii::$app->user->identity->user_name?></h5>

                        <?= $form->field($model, 'user_name')->textInput()?>

                    <?php break;case'ChangeAvatar':?>
                        <p>
                            <?php if(Yii::$app->user->identity->img_url):?>
                                <img src="<?= Yii::$app->user->identity->img_url?>" width="128px" height="128px" alt="avatar">
                            <?php endif;?>
                        </p>
                        <p>
                            <?= $form->field($model, 'img_url')->widget('common\widgets\file_upload\FileUpload',[
                                    'config'=>[
                                    //图片上传的一些配置，不写调用默认配置
                                    //'domain_url' => '@web/images/user/avatar',
                                ]
                            ]) ?>
                            <?= Html::a('不用头像', ['update','scenario'=>'RemoveAvatar'], ['class' => 'btn btn-warning']) ?>
                        </p>


                <?php break;case'ChangeEmail':?>

                        <h5>原邮箱:<?= Yii::$app->user->identity->email?></h5>

                        <?= $form->field($model, 'email')->textInput()?>

                    <?php break;default : break;endswitch; ?>

            <div class="form-group"><?= Html::submitButton('确认', ['class' => 'btn btn-success']) ?></div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
    <div class="col-lg-3">
    </div>
</div>


