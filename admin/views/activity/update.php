<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model admin\models\NOW */

$this->title = '修改活动状态：'.$model->activity_name;
$this->params['breadcrumbs'][] = ['label' => '活动管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->activity_name, 'url' => ['view', 'id' => $model->act_id]];
$this->params['breadcrumbs'][] = '修改状态';
?>
<div class="tk-activity-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-9">
            <div class="tk-activity-form">

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'activity_name')->textInput()?>

                <?= $form->field($model, 'category')->dropDownList(['0'=>'暂无分类','1'=>'文体活动' ])?>

                <?= $form->field($model, 'introduction')->textInput()?>

                <?= $form->field($model, 'location')->textInput()?>

                <?= $form->field($model, 'max_people')->textInput()?>

                <?= $form->field($model, 'status')->
                dropDownList(['0'=>'未审核','1'=>'通过','2'=>'驳回' ])?>

                <?= $form->field($model, 'release_by')->textInput()?>

                <?= $form->field($model, 'time_start_stamp')->widget(DateTimePicker::classname(),
                    [
                        'options' => ['placeholder' => ''],
                        'pluginOptions' =>
                            [
                                'autoclose' => true,
                                'todayHighlight' => true,
                                //'startDate' =>date('Y-m-d'), //设置今天之前的日期不能选择
                            ]
                    ]);
                ?>

                <?= $form->field($model, 'time_end_stamp')->widget(DateTimePicker::classname(),
                    [
                        'options' => ['placeholder' => ''],
                        'pluginOptions' =>
                            [
                                'autoclose' => true,
                                'todayHighlight' => true,
                                //'startDate' =>date('Y-m-d'), //设置今天之前的日期不能选择
                            ]
                    ]);
                ?>

                <?= $form->field($model, 'ticket_start_stamp')->widget(DateTimePicker::classname(),
                    [
                        'options' => ['placeholder' => ''],
                        'pluginOptions' =>
                            [
                                'autoclose' => true,
                                'todayHighlight' => true,
                                //'startDate' =>date('Y-m-d'), //设置今天之前的日期不能选择
                            ]
                    ]);
                ?>

                <?= $form->field($model, 'ticket_end_stamp')->widget(DateTimePicker::classname(),
                    [
                        'options' => ['placeholder' => ''],
                        'pluginOptions' =>
                            [
                                'autoclose' => true,
                                'todayHighlight' => true,
                                //'startDate' =>date('Y-m-d'), //设置今天之前的日期不能选择
                            ]
                    ]);
                ?>

                <div class="form-group">
                    <?= Html::submitButton('更改', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
        <div class="col-lg-3">
            <h2>注意事项:</h2>
        </div>
    </div>



</div>
