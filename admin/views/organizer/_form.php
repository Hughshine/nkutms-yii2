<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Organizer */
/* @var $form yii\widgets\ActiveForm */
/*
组织者创建表单的页面输入项目
*/
?>

<div class="organizer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'org_name')->textInput()?>

    <?= $form->field($model, 'credential')->textInput()?>

	<?= $form->field($model, 'password')->passwordInput() ?>

	<?= $form->field($model, 'category')->dropDownList(['0'=>'校级组织','1'=>'学生社团' ])?>

	<?= $form->field($model, 'status')->dropDownList(['0'=>'无效','10'=>'有效'])?>

    <div class="form-group">
        <?= Html::submitButton('确认', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
