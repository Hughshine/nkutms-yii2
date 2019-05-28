<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/3/9
 * Time: 21:41
 */
use yii\helpers\Url;
use yii\widgets\LinkPager;

/**
 * @var data array
 * @var $view bool
 */

?>
<div class="panel">
    <div class="panel-title box-title">
        <h3><span>&nbsp <?=$data['title']?></span></h3>
        <div class="panel-body border-bottom">
            <div class="col-lg-10">
                <div class="col-lg-4 btn-group" align="center">
                    <b>id</b>
                </div>
                <div class="col-lg-4 btn-group" align="center">
                    <b>账号</b>
                </div>
                <div class="col-lg-4 btn-group" align="center">
                    <b>认证请求</b>
                </div>
            </div>
        </div>
    </div>
    <div class="new-list">
    <?php foreach ($data['body'] as $list):?>
        <div class="panel-body border-bottom box-title">
            <div class="col-md-10">
                <div class="col-lg-4 btn-group" align="center">
                    <b><?=$list['user_id']?></b>
                </div>
                <div class="col-lg-4 btn-group" align="center">
                    <b><?=$list['uc']?></b>
                </div>
                <div class="col-lg-4 btn-group" align="center">
                    <b><?=$list['cat']?></b>
                </div>
            </div>
            <div class="col-md-2">
                <a href="<?=Url::to(['auth-info/view','id'=>$list['aid']])?>"><button class="btn btn-success no-radius btn-sm pull-right ">查看</button></a>
            </div>
        </div>
        <?php endforeach;?>
    </div>
</div>
<?php if($this->context->page):?>
    <div class="page"><?=LinkPager::widget(['pagination' => $data['page']]);?></div>
<?php endif;?>