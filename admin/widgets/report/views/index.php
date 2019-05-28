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
            <div class="col-lg-12">
                <div class="col-lg-4 btn-group">
                    <b>id</b>
                </div>
                <div class="col-lg-4 btn-group">
                    <b>账号</b>
                </div>
                <div class="col-lg-4 btn-group">
                    <b>不良行为</b>
                </div>
            </div>
        </div>
    </div>
    <div class="new-list">
    <?php foreach ($data['body'] as $list):?>
        <div class="panel-body border-bottom box-title">
            <div class="col-md-10">
                <div class="col-lg-4 btn-group">
                    <p class="pull-left"><?=$list['user_id']?></p>
                </div>
                <div class="col-lg-4 btn-group">
                    <p class="pull-right"><?=$list['uc']?></p>
                </div>
                <div class="col-lg-4 btn-group">
                    <p class="pull-right"><?=$list['tot']?></p>
                </div>
            </div>
            <div class="col-md-2">
                <?php if($view):?>
                <a href="<?=Url::to(['report/index','user_id'=>$list['user_id']])?>"><button class="btn btn-success no-radius btn-sm pull-right ">查看</button></a>
                <?php endif;?>
            </div>
        </div>
        <?php endforeach;?>
    </div>
</div>
<?php if($this->context->page):?>
    <div class="page"><?=LinkPager::widget(['pagination' => $data['page']]);?></div>
<?php endif;?>