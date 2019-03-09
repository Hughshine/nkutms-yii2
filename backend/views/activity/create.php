<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/3/9
 * Time: 10:07
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\datetime\DateTimePicker;
$this->title = '活动发布';
$this->params['breadcrumbs'][] = '活动发布';

?>

<div class="row">
    <div class = "col-lg-9">
        <div class ="panel-body">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'activity_name')->textInput() ?>

            <?= $form->field($model, 'category')->dropDownList(['0'=>'暂无分类','1'=>'文体活动' ])?>

            <?= $form->field($model, 'introduction')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'location')->textInput() ?>

            <?= $form->field($model, 'max_people')->textInput() ?>

            <?= $form->field($model, 'current_serial')->textInput() ?>

            <?= $form->field($model, 'time_start_stamp')->widget(DateTimePicker::classname(),
                [
                    'options' => ['placeholder' => ''],
                    'pluginOptions' =>
                        [
                            'autoclose' => true,
                            'todayHighlight' => true,
                            'startDate' =>date('Y-m-d'), //设置今天之前的日期不能选择
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
                            'startDate' =>date('Y-m-d'), //设置今天之前的日期不能选择
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
                            'startDate' =>date('Y-m-d'), //设置今天之前的日期不能选择
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
                            'startDate' =>date('Y-m-d'), //设置今天之前的日期不能选择
                        ]
                ]);
            ?>

            <div class="form-group">
                <?= Html::submitButton('创建', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class ="col-lg-3">
    </div>
</div>
