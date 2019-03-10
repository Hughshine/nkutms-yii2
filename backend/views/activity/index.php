<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/3/9
 * Time: 10:07
 */
$this->title = '活动列表';
$this->params['breadcrumbs'][] = '活动列表';

use backend\widgets\activity\ActivityWidget;

?>
<div class="row">
    <div class="col-lg-9">
        <?= ActivityWidget::widget(['option'=>'index'])?>
    </div>
    <div class="col-lg-3">
        <div class="box-title">
            图示:
        </div>
        <div class="panel-body border-bottom">
            <span style="font-size:20px"><span class="fa fa-user-o"></span>发布者</span><br/>
            <span style="font-size:20px"><span class="fa fa-group"></span> <span class="fa fa-user-times"></span>人数</span><br/>
            <span style="font-size:20px"><span class="fa fa-location-arrow"></span>活动地点</span><br/>
            <span style="font-size:20px"><span class="fa fa-clock-o"></span>发布时间</span><br/>
            <span style="font-size:20px"><span class="fa fa-ticket"></span>票务开始---结束时间</span><br/>
            <span style="font-size:20px"><span class="fa fa-info-circle"></span>当前状态</span><br/>
        </div>
    </div>
</div>
