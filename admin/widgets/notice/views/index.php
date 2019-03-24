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
                    <h1><a href="<?=Url::to(['notice/view','id'=>$list['id']])?>"><?=$list['title']?></a></h1>
                    <span class="activity-tags">
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
                <a href="<?=Url::to(['notice/view','id'=>$list['id']])?>"><button class="btn btn-success no-radius btn-sm pull-right ">了解详情</button></a>
            </div>
        </div>
        <?php endforeach;?>
    </div>
</div>
<?php if($this->context->page):?>
    <div class="page"><?=LinkPager::widget(['pagination' => $data['page']]);?></div>
<?php endif;?>