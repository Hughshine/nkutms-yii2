<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = '我的资料';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('修改密码', ['repassword'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('修改用户名', ['update','scenario'=>'ChangeUserName'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('修改邮箱', ['update','scenario'=>'ChangeEmail'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('修改头像', ['update','scenario'=>'ChangeAvatar'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => Yii::$app->user->identity,
        'attributes' => [
            'id',
            'user_name',
            'email',
            'credential',
            'category'=>
            [
                'label'=>'类别',
                'attribute'=>'category',
                'value'=>(Yii::$app->user->identity->category>=count(USER_CATEGORY)||Yii::$app->user->identity->category<0)
                        ?'未知':
                     USER_CATEGORY[Yii::$app->user->identity->category],
            ],
            'credential',
            'created_at'=>
            [
                'label'=>'注册时间',
                'attribute'=>'created_at',
                'value' => date('Y-m-d:H:i:s',(Yii::$app->user->identity->created_at)),
            ],
            'updated_at'=>
            [
                'label'=>'上一次资料更新时间',
                'attribute'=>'updated_at',
                'value' => date('Y-m-d:H:i:s',(Yii::$app->user->identity->updated_at)),
            ],
        ],
    ]) ?>

</div>
