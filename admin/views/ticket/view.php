<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Activity;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\Ticket */

$this->title = '票务详情    ID:'.$model->id;
$this->params['breadcrumbs'][] = ['label' => '票务管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="container">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?php switch($model->status):
            case 0 :
        ?>
        <?= Html::a('退回该票',
                    [
                        'changestatus',
                        'id' => $model->id,
                        'status'=>\common\models\Ticket::STATUS_WITHDRAW
                    ],
            [
                'class' => 'btn btn-warning',
                'data' =>
                [
                    'confirm' => '确定退回这张票?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php break;?>
        <?php case 1:?>
            <?= Html::a('置为有效',
                [
                    'changestatus',
                    'id' => $model->id,
                    'status'=>\common\models\Ticket::STATUS_VALID
                ],
                [
                    'class' => 'btn btn-success',
                    'data' =>
                        [
                            'confirm' => '确定将这张票置为有效?',
                            'method' => 'post',
                        ],
                ]) ?>
        <?php break;
            default:break;
        endswitch;?>
        <?= Html::a('修改信息', ['update', 'id' => $model->id], ['class' => 'btn btn-danger pull-right',]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id'=>
            [
                'label'=>'ID',
                'attribute'=>'id',
            ],
            'user_id',
            'user_name'=>
            [
                'label'=>'持有者名称',
                'attribute'=>'user_name',
                'value'=>
                function($model)
                {
                    $user=User::findIdentity_admin($model->user_id);
                    if(!$user)
                    {
                        return '未找到活动';
                    }
                    else
                        return $user->user_name;
                },
            ],
            'activity_id',
            'activity_name'=>
            [
                'label'=>'活动名称',
                'attribute'=>'activity_name',
                'value'=>
                function($model)
                {
                    $act=Activity::findIdentity_admin($model->activity_id);
                    if(!$act)
                    {
                        return '未找到活动'.'ID:';
                    }
                    else
                        return $act->activity_name;
                },
            ],
            'created_at'=>
            [
                'label'=>'记录创建时间',
                'attribute'=>'create_at',
                'value' => function ($data) {
                        return date('Y-m-d:H:i:s',($data->created_at));
                    },
            ],
            'updated_at'=>
                [
                    'label'=>'更新时间',
                    'attribute'=>'updated_at',
                    'value' => function ($data) {
                        return date('Y-m-d:H:i:s',($data->updated_at));
                    },
                ],
            'serial_number',
            'status'=>
            [
                'label'=>'状态',
                'attribute'=>'status',
                'value'=>
                function($model)
                {
                    switch($model->status)
                    {
                        case 0:return '有效';
                        case 1:return '已退回';
                        case 2:return '已过期';
                        default:return '未知';
                    }
                },
            ],
        ],
    ]) ?>

</div>
