<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use admin\models\Organizer;

/* @var $this yii\web\View */
/* @var $model admin\models\TkActivity */

$this->title = $model->activity_name;
$this->params['breadcrumbs'][] = ['label' => '活动管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tk-activity-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('修改状态', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除此活动', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
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
            'max_people',
            'current_people',
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
                        return '未找到发布者'.'ID:'.$model->release_by;
                    }
                    else
                        return $organizer->org_name.';ID:'.$organizer->id;
                },
            ],
            'release_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
