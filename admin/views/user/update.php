<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = '用户：'.$model->user_name.'，ID：'.$model->id;
$this->params['breadcrumbs'][] = ['label' => '用户信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '确定';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="user-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'category')->dropDownList(USER_CATEGORY)?>

        <div class="form-group">
            <?= Html::submitButton('更新', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
