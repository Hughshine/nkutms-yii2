<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/3/9
 * Time: 10:07
 */
use yii\bootstrap\ActiveForm;
$this->title = '活动发布';
$this->params['breadcrumbs'][] = '活动发布';

?>

<div class="row">
    <div class = "col-lg-9">
        <div class ="panel-body">
            <?php $form=ActiveForm::begin()?>
                <?= $form->field($model,'activity_name')->textInput(['maxlength'=>true])?>
            <?php $form=ActiveForm::end()?>
        </div>
    </div>
    <div class ="col-lg-3">
    </div>
</div>
