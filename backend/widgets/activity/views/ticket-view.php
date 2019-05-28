<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/3/9
 * Time: 21:41
 */
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="container">
    <div class="panel">
        <div class="panel-title box-title">
            <h2><span>&nbsp <?=$data['title']?></span></h2>
            <div class="container">
                <div class="col-md-1">
                    <h4><span class="fa fa-info-circle">id</span></h4>
                </div>
                <div class="col-md-2">
                    <h4><span class="fa fa-user-circle">用户昵称</span></h4>
                </div>
                <div class="col-md-2">
                    <h4><span class="fa fa-address-book">用户账号</span></h4>
                </div>
                <div class="col-md-3">
                    <h4><span class="fa fa-envelope">用户邮箱</span></h4>
                </div>
                <div class="col-md-2">
                    <h4><span class="fa fa-ticket">参与时间</span></h4>
                </div>
                <div class="col-md-2">
                    <h4><span class="fa fa-dedent">序列号</span></h4>
                </div>
            </div>
        </div>
        <div class="new-list">
            <?php if(count($data['body'])!=0):?>
                <?php foreach ($data['body'] as $list):?>
                    <div class="panel-body border-bottom box-title">
                        <div class="col-md-1">
                            <span class="fa fa-info-circle"></span><?=$list['id']?>
                        </div>
                        <div class="col-md-2">
                            <span class="fa fa-user-circle"></span><?=$list['user_name']?>
                        </div>
                        <div class="col-md-2">
                            <span class="fa fa-address-book"></span><?=$list['credential']?>
                        </div>
                        <div class="col-md-3">
                            <span class="fa fa-envelope"></span><?=$list['email']?>
                        </div>
                        <div class="col-md-2">
                            <span class="fa fa-ticket"></span><?=date('m-d H:i:s',$list['created_at'])?>
                        </div>
                        <div class="col-md-2">
                            <span class="fa fa-dedent"></span><?=$list['serial_number']?>
                        </div>
                    </div>
                <?php endforeach;?>
            <?php else:?>
                <div class="panel-title box-title">
                    <h3><span>&nbsp 暂无</span></h3>
                </div>
            <?php endif;?>
        </div>
    </div>
    <?php if($this->context->page):?>
        <div class="page"><?=LinkPager::widget(['pagination' => $data['page']]);?></div>
    <?php endif;?>
</div>
