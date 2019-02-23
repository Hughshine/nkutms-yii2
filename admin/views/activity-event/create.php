<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ActivityEvent */

$this->title = 'Create Activity Event';
$this->params['breadcrumbs'][] = ['label' => 'Activity Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-event-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
