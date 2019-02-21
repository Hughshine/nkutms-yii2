<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Organizer */
/* @var $form yii\widgets\ActiveForm */
/*
管理员修改组织者密码的表单页面项目
*/
?>

<div class="organizer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'password')->passwordInput()?>

    <?= $form->field($model, 'repassword')->passwordInput()?>

    <div class="form-group">
        <?= Html::submitButton('确认修改', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
