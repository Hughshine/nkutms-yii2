<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OrganizerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="organizer-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'org_name') ?>

    <!--<?= $form->field($model, 'auth_key') ?>-->

    <!--<?= $form->field($model, 'password') ?>-->

    <!--<?= $form->field($model, 'password_reset_token') ?>-->

    <?php // echo $form->field($model, 'wechat_id') ?>

    <?php // echo $form->field($model, 'category') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'time_release') ?>

    <?php // echo $form->field($model, 'access_token') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
