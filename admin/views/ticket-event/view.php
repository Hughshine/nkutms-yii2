<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;
use common\models\Activity;

/* @var $this yii\web\View */
/* @var $model common\models\TicketEvent */

$this->title = '票务事件：ID：'.$model->id;
$this->params['breadcrumbs'][] = ['label' => '票务事件管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ticket-event-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'ticket_id',
            'user_id'=>
                [
                    'label'=>'持有者',
                    'attribute'=>'user_id',
                    'value'=>
                        function($model)
                        {
                            $act=User::findIdentity_admin($model->user_id);
                            if(!$act)
                            {
                                return '未找到持有者'.'ID:'.$model->user_id;
                            }
                            else
                                return $act->user_name.';ID:'.$act->id;
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
            //'operated_by_admin',
        ],
    ]) ?>

</div>
