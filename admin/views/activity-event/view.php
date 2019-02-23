<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Activity;
use common\models\Organizer;
//use admin\models\Admin;

/* @var $this yii\web\View */
/* @var $model common\models\ActivityEvent */

$this->title = '活动事件：ID:'.$model->id;
$this->params['breadcrumbs'][] = ['label' => '活动事件管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="activity-event-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'organizer_id'=>
                [
                    'label'=>'发布者',
                    'attribute'=>'organizer_id',
                    'value'=>
                        function($model)
                        {
                            $org=Organizer::findIdentity_admin($model->organizer_id);
                            if(!$org)
                            {
                                return '未找到活动'.'ID:'.$model->organizer_id;
                            }
                            else
                                return $org->org_name.';ID:'.$org->id;
                        },
                ],
            'activity_id'=>
                [
                    'label'=>'活动名字',
                    'attribute'=>'activity_id',
                    'value'=>
                        function($model)
                        {
                            $act=Activity::findIdentity_admin($model->activity_id);
                            if(!$act)
                            {
                                return '未找到活动'.'ID:'.$model->activity_id;
                            }
                            else
                                return $act->activity_name.';ID:'.$act->id;
                        },
                ],
            'status'=>
            [
                'label'=>'状态',
                'attribute'=>'status',
                'value'=>
                function($model)
                {
                    if ($model->status==0)
                        return '正常';
                    return '无效';
                },
                'filter'=>['0'=>'正常','1'=>'无效'],
            ],
            'update_at:datetime',
            /*'operated_by_admin'=>
                [
                    'label'=>'上一次修改的管理者',
                    'attribute'=>'operated_by_admin',
                    'value'=>
                        function($model)
                        {
                            if($model->operated_by_admin==-1)return '无';
                            $admin=Admin::findIdentity_admin($model->operated_by_admin);
                            if(!$admin)
                            {
                                return '未找到管理者'.'ID:'.$model->operated_by_admin;
                            }
                            else
                                return $admin->admin_name.';ID:'.$admin->id;
                        },
                ],*/
        ],
    ]) ?>

</div>
