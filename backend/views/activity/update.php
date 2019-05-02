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

/* @var $this yii\web\View */
/* @var $modelForm common\models\ActivityForm */
/* @var $scenario string */

$this->title = '活动信息修改';
$this->params['breadcrumbs'][] = ['label' => '我的发布记录', 'url' => ['mine']];
$this->params['breadcrumbs'][] = ['label' => $modelForm->activity_name, 'url' => ['view','id'=>$modelForm->act_id]];
$this->params['breadcrumbs'][] = '活动信息修改';
?>
<div class="container">
    <div class = "col-lg-9">
        <div class ="panel-body">
            <?php $form = ActiveForm::begin(); ?>

            <?php switch($scenario):
            case 'Update':?>

            <?= $form->field($modelForm, 'activity_name')->textInput() ?>

            <?= $form->field($modelForm, 'category')->dropDownList(ACT_CATEGORY)?>

                <?= $form->field($modelForm, 'introduction')->widget('common\widgets\ueditor\Ueditor',[
                    'options'=>[
                        'initialFrameWidth' => 1050,//宽度
                        'initialFrameHeight' => 550,//高度
                    ]
                ]) ?>
            <?= $form->field($modelForm, 'location')->textInput() ?>

            <?= $form->field($modelForm, 'max_people')->textInput() ?>

                <?= $form->field($modelForm, 'ticketing_start_at_string')->widget(DateTimePicker::classname(),
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

                <?= $form->field($modelForm, 'ticketing_end_at_string')->widget(DateTimePicker::classname(),
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

            <?= $form->field($modelForm, 'start_at_string')->widget(DateTimePicker::classname(),
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

            <?= $form->field($modelForm, 'end_at_string')->widget(DateTimePicker::classname(),
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

            <?php break;
            case 'ChangePicture':?>
                <p>
                    <?php if($modelForm->pic_url):?>
                        <img src= "<?=$modelForm->pic_url?>" width="256px" height="256px" alt="pic">
                    <?php else:?>
                        <img src="/statics/images/activity_default_pic.png" width="256px" height=256px" alt="pic">
                    <?php endif;?>
                </p>
                <p>
                    <?= $form->field($modelForm, 'pic_url')->widget('common\widgets\file_upload\FileUpload',[
                        'config'=>[
                            //图片上传的一些配置，不写调用默认配置
                            //'domain_url' => '@web/images/user/avatar',
                        ]
                    ]) ?>
                    <?= Html::a('不用自定义图片', ['remove-picture','id'=>$modelForm->act_id], ['class' => 'btn btn-warning']) ?>
                </p>
            <?php break;
            default:break;
            endswitch;?>

            <div class="form-group">
                <?= Html::submitButton('修改', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class ="col-lg-3">
        <div class="panel-title box-title">
            <span>注意事项</span>
        </div>
        <div class="panel-body">
            <p>1.xxxx</p>
            <p>2.xxxx</p>
            <p>3.xxxx</p>

        </div>
    </div>
</div>
