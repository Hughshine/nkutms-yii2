<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Organizer */

$this->title = '组织者：'.$model->org_name.'，ID：'.$model->org_id;
$this->params['breadcrumbs'][] = ['label' => '组织者管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->org_name, 'url' => ['view', 'id' => $model->org_id]];
$this->params['breadcrumbs'][] = '修改密码';
?>
<div class="container">
    <div class="col-lg-9">
        <div class="organizer-form">

            <h3>修改密码:</h3>
            <h2><?= Html::encode($this->title) ?></h2>
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'password')->passwordInput()?>

            <?= $form->field($model, 'rePassword')->passwordInput()?>

            <div class="form-group">
                <?= Html::submitButton('确认修改', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

