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

            <?= $form->field($model, 'category')->dropDownList(ACT_CATEGORY)?>

            <?= $form->field($model, 'pic_url')->widget('common\widgets\file_upload\FileUpload',[
                                    'config'=>[
                                    //图片上传的一些配置，不写调用默认配置
                                    //'domain_url' => '@web/images/user/avatar',
                                ]
                            ]) ?>

            <?= $form->field($model, 'introduction')->widget('common\widgets\ueditor\Ueditor',[
                'options'=>[
                    'initialFrameWidth' => 1050,//宽度
                    'initialFrameHeight' => 550,//高度
                ]
            ]) ?>

            <?= $form->field($model, 'location')->textInput() ?>

            <?= $form->field($model, 'max_people')->textInput() ?>

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

            <div class="form-group">
                <?= Html::submitButton('创建', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class ="col-lg-3">
        <div class="panel-title box-title">
            <h2>注意事项</h2>
        </div>
        <div class="panel-body">
            <h3>1.票务开始时间要早于票务结束时间</h3>
            <h3>2.活动开始时间要早于活动结束时间</h3>
            <h3>3.票务结束时间要早于活动开始时间</h3>
            <h3>4.活动地点确认正确</h3>

        </div>
    </div>
</div>
