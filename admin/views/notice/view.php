<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Notice */

$this->title = '通知'.$model->title;
$this->params['breadcrumbs'][] = ['label' => '通知管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="container">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        <?php if($model->status==\common\models\Notice::STATUS_DELETED):?>
            <?=Html::a('发布', ['change-status', 'id' => $model->id,'isVisible'=>true], ['class' => 'btn btn-primary'])?>
        <?php else:?>
            <?=Html::a('撤回', ['change-status', 'id' => $model->id,'isVisible'=>false], ['class' => 'btn btn-warning'])?>
        <?php endif;?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'status'=>
                [
                    'label'=>'是否对用户可见',
                    'attribute'=>'updated_at',
                    'value' => function ($model) {
                        return $model->status==\common\models\Notice::STATUS_ACTIVE?'是':'否';
                    },
                ],
            'updated_at'=>
                [
                    'label'=>'上一次编辑时间',
                    'attribute'=>'updated_at',
                    'value' => function ($model) {
                        return date('Y-m-d:H:i:s',($model->updated_at));
                    },
                ],
            'created_at'=>
                [
                    'label'=>'记录创建时间',
                    'attribute'=>'created_at',
                    'value' => function ($model) {
                        return date('Y-m-d:H:i:s',($model->created_at));
                    },
                ],
        ],
    ]) ?>

    <h3>内容:</h3>
    <?= $model->content?>

</div>
