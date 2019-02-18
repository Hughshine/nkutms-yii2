<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TkActivitySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tk-activity-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'activity_name') ?>

    <?= $form->field($model, 'category') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'introduction') ?>

    <?php // echo $form->field($model, 'max_people') ?>

    <?php // echo $form->field($model, 'current_people') ?>

    <?php // echo $form->field($model, 'start_at') ?>

    <?php // echo $form->field($model, 'end_at') ?>

    <?php // echo $form->field($model, 'release_by') ?>

    <?php // echo $form->field($model, 'release_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
