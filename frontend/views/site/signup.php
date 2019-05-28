<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = '注册';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">




    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-5">
            <h1><?= Html::encode($this->title) ?></h1>
            <p>请填写以下信息以注册:</p>
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'user_name')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'credential')->textInput()?>

                <?= $form->field($model, 'email')->textInput()?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rePassword')->passwordInput() ?>

                <!--?= $form->field($model, 'img_url')->widget('common\widgets\file_upload\FileUpload',[
                    'config'=>[
                        //图片上传的一些配置，不写调用默认配置
                        //'domain_url' => '@web/images/user/avatar',
                    ]
                ]) ?-->

                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton( '注册', ['class' =>'btn btn-success', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
