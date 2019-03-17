<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Organizer */
$this->title = '注册组织者';
$this->params['breadcrumbs'][] = ['label' => '组织者管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = '注册';

?>
<div class="row">
    <div class="col-lg-9">
        <div class="organizer-form">

            <h2><?= Html::encode($this->title) ?></h2>

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'org_name')->textInput()?>

            <?= $form->field($model, 'credential')->textInput()?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rePassword')->passwordInput() ?>

            <?= $form->field($model, 'category')->dropDownList(['0'=>'校级组织','1'=>'学生社团' ])?>

            <?= $form->field($model, 'status')->dropDownList(['0'=>'无效','10'=>'有效'])?>

            <div class="form-group">
                <?= Html::submitButton('确认', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
    <div class="col-lg-3">
        <h2>注意事项:</h2>
        <h3>组织者的数据库允许重名,但为了避免混淆,请不要设置一样的名字</h3>
    </div>
</div>


