<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TicketEvent */

$this->title = 'Create Ticket Event';
$this->params['breadcrumbs'][] = ['label' => 'Ticket Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-event-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
