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
            <h1><?= Html::encode($model->activity_name);?></h1>
            <h3><?= $isTicketed?'(已报名参加)':' ' ?></h3>
        </div>

        <?php if($model->pic_url):?>
        <img src= "<?=$model->pic_url?>" width="256px" height="256px" alt="pic">
        <?php else:?>
            <img src="/statics/images/activity_default_pic.png" width="256px" height=256px" alt="pic">
        <?php endif;?>
        <?php if($model->end_at>time()+7*3600):?>
            <?php if(!$isTicketed):?>
            <?= Html::a('参加',
                [
                    'create-ticket',
                    'act_id' => $model->id,
                ],
                [
                    'class' => 'btn btn-primary pull-right',
                    'data' => ['method' => 'post',],
                ]) ?>
            <?php else:?>
                <?= Html::a('取消参加',
                    [
                        'cancel-ticket',
                        'act_id' => $model->id,
                    ],
                    [
                        'class' => 'btn btn-primary pull-right',
                        'data' => ['method' => 'post','confirm'=>'确定取消参与该活动?'],
                    ]) ?>
            <?php endif;?>
        <?php endif;?>
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
                'location',
                'max_people',
                'current_people',
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
                        'attribute'=>'ticketing_start_at',
                        'value' => function ($model) {
                            return date('Y-m-d:H:i:s',($model->ticketing_start_at));
                        },
                    ],
                'ticketing_end_at:'=>
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
                            return date('Y-m-d:H:i:s',($model->updated_at));
                        },
                    ],

            ],
        ]) ?>

    </div>
    <div class="col-lg-1">

    </div>
</div>
<h2>活动介绍</h2>
<?=$model->introduction ?>

