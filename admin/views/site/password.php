<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = '修改管理员密码:'.$model->admin_name;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-lg-8">
<div class="admin-form">

	<h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'oldPassword')->passwordInput()?>

    <?= $form->field($model, 'password')->passwordInput()?>

    <?= $form->field($model, 'rePassword')->passwordInput()?>

    <div class="form-group">
        <?= Html::submitButton('确认修改', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
