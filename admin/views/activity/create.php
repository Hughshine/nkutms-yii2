<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;


/* @var $this yii\web\View */
/* @var $model common\models\ActivityForm */

$this->title = '创建一条活动记录';
$this->params['breadcrumbs'][] = ['label' => '活动管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tk-activity-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-9">
            <div class="tk-activity-form">

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'activity_name')->textInput() ?>

                <?= $form->field($model, 'category')->dropDownList(ACT_CATEGORY)?>

                <?= $form->field($model, 'introduction')->widget('common\widgets\ueditor\Ueditor',[
                    'options'=>[
                        'initialFrameWidth' => 900,//宽度
                        'initialFrameHeight' => 550,//高度
                    ]
                ]) ?>
                <?= $form->field($model, 'location')->textInput() ?>

                <?= $form->field($model, 'max_people')->textInput() ?>

                <?= $form->field($model, 'ticketing_start_at_string')->widget(DateTimePicker::classname(),
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

                <?= $form->field($model, 'ticketing_end_at_string')->widget(DateTimePicker::classname(),
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

                <?= $form->field($model, 'start_at_string')->widget(DateTimePicker::classname(),
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

                <?= $form->field($model, 'end_at_string')->widget(DateTimePicker::classname(),
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

                <?= $form->field($model, 'release_by')->textInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('创建', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
        <div class="col-lg-3">
            <h2>注意事项:</h2>
        </div>
    </div>
</div>
