<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Organizer;

/* @var $this yii\web\View */
/* @var $model admin\models\NOW */

$this->title = '通知';
$this->params['breadcrumbs'][] = ['label' => '通知列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;
?>
<div class="row">
    <div class="col-lg-1">

    </div>
    <div class="tk-activity-view col-lg-10">
        <div class="row">
            <h1><?= Html::encode($model->title);?></h1>
        </div>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'title',
                'updated_at'=>
                    [
                        'label'=>'上一次更新时间',
                        'attribute'=>'updated_at',
                        'value' => function ($model) {
                                return date('Y-m-d:H:i:s',($model->updated_at));
                            },
                    ],
                'created_at'=>
                    [
                        'label'=>'发布时间',
                        'attribute'=>'end_at',
                        'value' => function ($model) {
                            return date('Y-m-d:H:i:s',($model->created_at));
                        },
                    ],
            ],
        ]) ?>

    </div>
    <div class="col-lg-1">

    </div>
</div>
<h3>内容:</h3>
<?= $model->content?>

