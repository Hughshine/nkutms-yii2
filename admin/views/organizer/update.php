<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Organizer */

$this->title = '组织者：'.$model->org_name.'，ID：'.$model->org_id;
$this->params['breadcrumbs'][] = ['label' => '组织者管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->org_name, 'url' => ['view', 'id' => $model->org_id]];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="row">
    <div class="col-lg-9">
        <div class="organizer-form">

            <h1><?= Html::encode($this->title) ?></h1>
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'org_name')->textInput()?>

            <?= $form->field($model, 'category')->dropDownList(ORG_CATEGORY)?>

            <?= $form->field($model, 'status')->dropDownList(['0'=>'无效','10'=>'有效'])?>

            <div class="form-group"><?= Html::submitButton('确认', ['class' => 'btn btn-success']) ?></div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
    <div class="col-lg-3">
        <h2>注意事项:</h2>
        <h3>组织者的数据库允许重名,但为了避免混淆,请不要设置一样的名字</h3>
    </div>
</div>


