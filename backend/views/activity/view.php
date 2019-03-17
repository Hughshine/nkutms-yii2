<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Organizer;

/* @var $this yii\web\View */
/* @var $model admin\models\NOW */

$this->title = '修改活动信息';
$this->params['breadcrumbs'][] = ['label' => '活动列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->activity_name;
?>
<div class="row">
    <div class="col-lg-1">

    </div>
    <div class="tk-activity-view col-lg-10">
        <div class="row">
            <h1><?= Html::encode($model->activity_name) ?>
            <?php if($model->release_by==Yii::$app->user->id
                &&$model->status!=\common\models\Activity::STATUS_APPROVED
                &&$model->status!=\common\models\Activity::STATUS_CANCEL
                &&$model->start_at>time()+7*3600):?>
                    <?= Html::a('修改信息', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('取消该活动',
                        [
                            'cancel',
                            'id' => $model->id,
                        ],
                        [
                            'class' => 'btn btn-danger pull-right',
                            'data' =>
                                [
                                    'confirm' => '取消后这条活动记录作废,无法修改!确定取消?',
                                    'method' => 'post',
                                ],
                        ]) ?>
            <?php endif;?>
            </h1>
        </div>
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
                                return ($model->status==1)?'文体活动':'暂无分类';
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
                        'attribute'=>'updated_at',
                        'value' => function ($model) {
                                return date('Y-m-d:H:i:s',($model->updated_at));
                            },
                    ],
                'end_at'=>
                    [
                        'label'=>'活动结束时间',
                        'attribute'=>'updated_at',
                        'value' => function ($model) {
                            return date('Y-m-d:H:i:s',($model->updated_at));
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
                                {
                                    return '未找到发布者';
                                }
                                else
                                    return $organizer->org_name;
                            },
                    ],
                'ticketing_start_at:'=>
                    [
                        'label'=>'票务开始时间',
                        'attribute'=>'updated_at',
                        'value' => function ($model) {
                            return date('Y-m-d:H:i:s',($model->updated_at));
                        },
                    ],
                'ticketing_end_at:'=>
                    [
                        'label'=>'票务结束时间',
                        'attribute'=>'updated_at',
                        'value' => function ($model) {
                            return date('Y-m-d:H:i:s',($model->updated_at));
                        },
                    ],
                'release_at'=>
                    [
                        'label'=>'发布时间',
                        'attribute'=>'updated_at',
                        'value' => function ($model) {
                            return date('Y-m-d:H:i:s',($model->updated_at));
                        },
                    ],

            ],
        ]) ?>

    </div>
    <div class="col-lg-1">

    </div>
</div>

