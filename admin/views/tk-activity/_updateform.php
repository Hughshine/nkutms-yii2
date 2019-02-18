<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model admin\models\TkActivity */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tk-activity-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->
    	dropDownList(['0'=>'未审核','1'=>'通过','2'=>'驳回' ])?>

    <div class="form-group">
        <?= Html::submitButton('更改', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
