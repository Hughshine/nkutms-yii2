<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Organizer */
/* @var $form yii\widgets\ActiveForm */
/*管理员修改组织者信息表单页面项目
 * */
?>
<div class="organizer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'org_name')->textInput()?>

	<?= $form->field($model, 'category')->dropDownList(['0'=>'校级组织','1'=>'学生社团' ])?>

	<?= $form->field($model, 'status')->dropDownList(['0'=>'无效','10'=>'有效'])?>

    <div class="form-group">
        <?= Html::submitButton('确认', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
