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
        <!--< ?php if($this->context->more):?>
        <span class="pull-right"><a href="< ?=$data['more']?>" class="font-12">更多»&nbsp&nbsp</a></span>
        < ?php endif;?>-->
    </div>
    <div class="new-list">
    <?php foreach ($data['body'] as $list):?>
        <div class="panel-body border-bottom box-title">
            <div class="col-lg-8">
                <div class="col-lg-10 btn-group">
                    <h1><a href="<?=Url::to(['activity/view','id'=>$list['id']])?>"><?=$list['activity_name']?></a></h1>
                    <span class="activity-tags">
                        <span class="fa fa-user-o"></span><a href="<?=Url::to(['site/view','id'=>$list['release_by']])?>"><?=$list['releaseBy']['org_name']?></a>&nbsp;
                        <?php if($list['current_people']>=$list['max_people']):?>
                            <span class="fa fa-user-times"></span><?=$list['current_people']?>/<?=$list['max_people']?>
                        <?php else:?>
                            <span class="fa fa-group"></span><?=$list['current_people']?>/<?=$list['max_people']?>
                        <?php endif;?>
                        <span class="fa fa-location-arrow"></span><?=$list['location']?>
                        <br/>
                        <span class="fa fa-clock-o"></span><?=date('Y-m-d H:i:s',$list['release_at'])?>&nbsp;
                        <!--浏览量<span class="glyphicon glyphicon-eye-open"></span>< ? =isset($list['extend']['browser'])?$list['extend']['browser']:0?>&nbsp;-->
                        <!--评论数<span class="glyphicon glyphicon-comment"></span><a href="< ? =Url::to(['post/detail','id'=>$list['id']])?>">< ? =isset($list['extend']['comment'])?$list['extend']['comment']:0?></a>-->
                    </span>
                    <p class="activity-content"><?=$list['introduction']?></p>

                </div>
                <div class="col-lg-2 label-img-size">
                    <!--<a href="#" class="activity-label">
                        <img src="< ? =($list['label_img']?\Yii::$app->params['upload_url'].$list['label_img']:\Yii::$app->params['default_label_img'])?>" alt="< ? =$list['title']?>">
                    </a>-->
                </div>
            </div>
            <div class="col-lg-4">
                <a href="<?=Url::to(['activity/view','id'=>$list['id']])?>"><button class="btn btn-success no-radius btn-sm pull-right ">了解详情</button></a>
                <span class="fa fa-ticket"></span><?=date('m-d H:i:s',$list['ticketing_start_at'])?>
                <br/>---<?=date('m-d H:i:s',$list['ticketing_end_at'])?>

                <br/>
                <h3 align="right"><span class="fa fa-info-circle"></span>
                <?php if($list ['ticketing_end_at']<time()+7*3600):?>
                        <font color="#696969">票务结束</font>
                <?php else:?>
                    <?php if($list['current_people']<$list['max_people']):?>
                        <?php if($option=='mine'):?>
                            <?=$statusString[$list['status']]?>
                        <?php else:?>
                            <font color="#4682b4">票务正常</font>
                        <?php endif;?>
                    <?php else:?>
                        <font color="#8b0000">人数已满</font>
                    <?php endif;?>
                <?php endif;?>
                </h3>
                </div>
        </div>
        <?php endforeach;?>
    </div>
</div>
<?php if($this->context->page):?>
    <div class="page"><?=LinkPager::widget(['pagination' => $data['page']]);?></div>
<?php endif;?>