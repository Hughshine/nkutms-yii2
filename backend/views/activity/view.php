<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Organizer;

/* @var $this yii\web\View */
/* @var $model admin\models\TkActivity */

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
                &&$model->start_at>time()+7*3600):?>
                <?= Html::a('修改信息', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
                                if($model->status==0)
                                    return '未审核';
                                return ($model->status==1)?'已通过':'被驳回';
                            },
                    ],
                'introduction',
                'location',
                'max_people',
                'current_people',
                'current_serial',
                'start_at:datetime',
                'end_at:datetime',
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
                'ticketing_start_at:datetime',
                'ticketing_end_at:datetime',
                'release_at:datetime',

            ],
        ]) ?>

    </div>
    <div class="col-lg-1">

    </div>
</div>

