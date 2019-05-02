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
<div class="panel">
    <div class="panel-title box-title">
        <h2><span>&nbsp <?=$data['title']?></span></h2>
    </div>
    <div class="new-list">
    <?php if(count($data['body'])!=0):?>
    <?php foreach ($data['body'] as $list):?>
        <div class="panel-body border-bottom box-title">
            <div class="col-lg-2">
                <?php if($list['pic_url']):?>
                    <img src="<?=$list['pic_url'] ?>" width="100%" height=100%" alt="pictrue">
                <?php else:?>
                    <img src="/statics/images/activity_default_pic.png" width="100%" height=100%" alt="pictrue">
                <?php endif;?>
            </div>
            <div class="col-lg-6">
                <div class="col-lg-10 btn-group">
                    <h1><a href="<?=Url::to(['activity/view','id'=>$list['id']])?>"><?=$list['activity_name']?></a></h1>
                    <span class="activity-tags">
                        <span class="fa fa-user-o"></span><a href="<?=Url::to(['organizer/view','id'=>$list['release_by']])?>"><?=$list['releaseBy']['org_name']?></a>&nbsp;
                        <span class="fa fa-location-arrow"></span><?=$list['location']?>
                        <br/>
                        <span class="fa fa-clock-o"></span>申请提交时间:<?=date('Y-m-d H:i:s',$list['release_at'])?>&nbsp;
                        <br/>
                        <span class="fa fa-clock-o" style="color:deeppink">过期时间:<?=date('Y-m-d H:i:s',$list['ticketing_start_at'])?></span>&nbsp;
                        <!--浏览量<span class="glyphicon glyphicon-eye-open"></span>< ? =isset($list['extend']['browser'])?$list['extend']['browser']:0?>&nbsp;-->
                        <!--评论数<span class="glyphicon glyphicon-comment"></span><a href="< ? =Url::to(['post/detail','id'=>$list['id']])?>">< ? =isset($list['extend']['comment'])?$list['extend']['comment']:0?></a>-->
                    </span>
                    <p class="activity-content"><?=$list['summary']?></p>

                </div>
                <div class="col-lg-2 label-img-size">
                    <!--<a href="#" class="activity-label">
                        <img src="< ? =($list['label_img']?\Yii::$app->params['upload_url'].$list['label_img']:\Yii::$app->params['default_label_img'])?>" alt="< ? =$list['title']?>">
                    </a>-->
                </div>
            </div>
            <div class="col-lg-4">
                <a href="<?=Url::to(['activity/view','id'=>$list['id']])?>"><button class="btn btn-success no-radius btn-more pull-right ">详情</button></a>
                <span class="fa fa-ticket"></span><?=date('m-d H:i:s',$list['ticketing_start_at'])?>
                <br/>---<?=date('m-d H:i:s',$list['ticketing_end_at'])?>

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