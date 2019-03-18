<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Organizer;

/* @var $this yii\web\View */
/* @var $model admin\models\NOW */

$this->title = $model->activity_name;
$this->params['breadcrumbs'][] = ['label' => '活动管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tk-activity-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if($model->status!=common\models\Activity::STATUS_CANCEL):?>
            <?= Html::a('修改信息', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php endif;?>
        <?php switch($model->status):
            case \common\models\Activity::STATUS_UNAUDITED :?>
                <?= Html::a('通过该活动',
                [
                    'review',
                    'id' => $model->id,
                    'status'=>\common\models\Activity::STATUS_APPROVED
                ],
                [
                    'class' => 'btn btn-success',
                    'data' =>
                        [
                            'confirm' => '确定通过?',
                            'method' => 'post',
                        ],
                ]) ?>
            <?php case \common\models\Activity::STATUS_APPROVED :?>
                <?= Html::a('驳回该活动',
                [
                    'review',
                    'id' => $model->id,
                    'status'=>\common\models\Activity::STATUS_REJECTED
                ],
                [
                    'class' => 'btn btn-warning',
                    'data' =>
                        [
                            'confirm' => '确定驳回?',
                            'method' => 'post',
                        ],
                ]) ?>
                <?php break;?>
            <?php case \common\models\Activity::STATUS_REJECTED :?>
                <?= Html::a('通过该活动',
                    [
                        'review',
                        'id' => $model->id,
                        'status'=>\common\models\Activity::STATUS_APPROVED
                    ],
                    [
                        'class' => 'btn btn-success',
                        'data' =>
                            [
                                'confirm' => '确定通过?',
                                'method' => 'post',
                            ],
                    ]) ?>
                <?php break;
            default:break;
        endswitch;?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'activity_name',
            'category'=>
            [
                'label'=>'活动类别',
                'attribute'=>'category',
                'value'=>
                function($model)
                {
                    if($model->category>=count(ACT_CATEGORY))
                        return '未知';
                    return ACT_CATEGORY[$model->category];
                },
            ],
            'status'=>
            [
                'label'=>'状态',
                'attribute'=>'status',
                'value'=>
                    function($model)
                    {
                        switch($model->status)
                        {
                            case \common\models\Activity::STATUS_UNAUDITED :return '未审核';
                            case \common\models\Activity::STATUS_APPROVED :return '已通过';
                            case \common\models\Activity::STATUS_REJECTED :return '被驳回';
                            case \common\models\Activity::STATUS_CANCEL :return '已取消';
                            default: return '未知';
                        }
                    },
            ],
            'introduction',
            'location',
            'max_people',
            'current_people',
            'current_serial',
            'start_at'=>
            [
                'label'=>'活动开始时间',
                'attribute'=>'start_at',
                'value' => function ($model) {
                        return date('Y-m-d:H:i:s',($model->start_at));
                    },
            ],
            'end_at'=>
            [
                'label'=>'活动结束时间',
                'attribute'=>'end_at',
                'value' => function ($model) {
                        return date('Y-m-d:H:i:s',($model->end_at));
                    },
            ],
            'release_by'=>
            [
                'label'=>'发布者',
                'attribute'=>'release_by',
                'value'=>
                function($model)
                {
                    $organizer=Organizer::findIdentity_admin($model->release_by);
                    if(!$organizer)
                        return '未找到发布者'.'ID:'.$model->release_by;
                    else
                        return $organizer->org_name.';ID:'.$organizer->id;
                },
            ],
            'ticketing_start_at'=>
            [
                'label'=>'票务开始时间',
                'attribute'=>'ticketing_start_at',
                'value' => function ($model) {
                        return date('Y-m-d:H:i:s',($model->ticketing_start_at));
                    },
            ],
            'ticketing_end_at'=>
            [
                'label'=>'票务结束时间',
                'attribute'=>'ticketing_end_at',
                'value' => function ($model) {
                        return date('Y-m-d:H:i:s',($model->ticketing_end_at));
                    },
            ],
            'release_at'=>
            [
                'label'=>'发布时间',
                'attribute'=>'release_at',
                'value' => function ($model) {
                        return date('Y-m-d:H:i:s',($model->release_at));
                    },
            ],
            'updated_at'=>
            [
                'label'=>'记录更新时间',
                'attribute'=>'updated_at',
                'value' => function ($model) {
                        return date('Y-m-d:H:i:s',($model->updated_at));
                    },
            ],

        ],
    ]) ?>

</div>
