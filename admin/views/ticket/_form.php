<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Ticket */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ticket-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'activity_id')->textInput() ?>

    <?= $form->field($model, 'serial_number')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('确认', 
            [
                'class' => 'btn btn-success',
                'data' => 
                [
                    'confirm' => '确定操作?',
                    'method' => 'post',
                ],
            ]
        ) 
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
