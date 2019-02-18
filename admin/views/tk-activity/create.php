<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model admin\models\TkActivity */

$this->title = '创建一条活动记录';
$this->params['breadcrumbs'][] = ['label' => '活动管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tk-activity-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
