<?php

use yii\helpers\Html;
use \yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Ticket */

$this->title = '创建一个票务记录';
$this->params['breadcrumbs'][] = ['label' => '票务管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <h3>注意:非必要时请勿创建票务信息</h3>
	<div class="row">
		<div class="col-lg-9">
            <div class="ticket-form">

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'user_id')->textInput() ?>

                <?= $form->field($model, 'activity_id')->textInput() ?>

                <?= $form->field($model, 'serial_number')->textInput(['maxlength' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton('确认',
                        [
                            'class' => 'btn btn-success',
                            'data' =>
                                [
                                    'confirm' => '确定操作?',
                                    'method' => 'post',
                                ],
                        ]
                    )
                    ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
		</div>
		<div class="col-lg-3">
			<h2>注意事项</h2>
			<h3>1.请确认活动ID和持有者ID必须存在</h3>
			<h3>2.尽量保证序列号现在及以后不会发生重复</h3>
			<h3>3.你所创建的票务记录是完全独立于活动的相关记录的,既不会计入参与活动人数,也不会更新票务序列号,这意味着如果将票的活动换成了新活动,那么旧活动的当前人数不会减少,新活动的人数不会增多</h3>
			<h2>慎重,可能引起严重后果</h2>
		</div>
	</div>

</div>
