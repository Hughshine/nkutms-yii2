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

<!--css样式-->
<head>
	<style>
		.box-title
		{
			color:#0c475b;
			font-size:20px;
		}
		.list-line {
			margin-top:0px;
			border-bottom:3px solid #3c8dbc;
		}
		.column-flex {
			display:flex;
			flex-direction:column;
			width:100%;
			height:100%;
			justify-content:center;
			align-items:center;
		}
		.row-flex {
			display:flex;
			flex-direction:row;
			justify-content:center;
			align-items:center;
			height:100%;
			width:100%;
		}
		.index-title {
			margin-left:0px;
			font-size:30px;
			margin-top:10px;
			margin-bottom:10px;
			color:#000;
			padding-bottom:10px;
			font-weight:bold;
		}
		.act-title {
			margin-left:0px;
			font-size:40px;
			margin-top:10px;
			margin-bottom:10px;
			color:#000;
			font-weight:bold;
		}
		.situation-style {
			font-size:20px;
			margin-left:25px;
		}
		.cc-top {
			margin-top:20px;
			margin-bottom:20px;
		}
		.activity-content {
			margin-bottom:0;
			font-size:20px;
		}
		.line-height {
			margin-top:10px;
			margin-bottom:10px;
			line-height:10px;
			margin-left:10px;
			margin-right:10px;
		}
		.button-style {
			font-size:18px;
			background-color:rgb(139, 60, 112);
			color:#fff;
			border-radius:10px;
			line-height:40px;
		}
		.info-style {
			font-size:18px;
			color:#000;
		}
	</style>
</head>

<div class="panel">
    <div class="index-title">
        <span>&nbsp <?=$data['title']?></span>
        <!--< ?php if($this->context->more):?>
        <span class="pull-right"><a href="< ?=$data['more']?>" class="font-12">更多»&nbsp&nbsp</a></span>
        < ?php endif;?>-->
	</div>
	<div class="list-line"></div>
    <div class="new-list">
    <?php foreach ($data['body'] as $list):?>
        <div class="row-flex box-title">
            <div class="col-lg-2">	
				<div class='column-flex'>
                <?php if($list['pic_url']):?>
                    <img src="<?=$list['pic_url'] ?>" width="110" height="110" alt="pictrue">
                <?php else:?>
                    <img src="/statics/images/activity_default_pic.png" width="110" height="110" alt="pictrue">
                <?php endif;?>
				</div>
            </div>
            <div class="col-lg-8">
				<div class='cc-top'>
                <div class="column-flex">
				<div class="row-flex">
                    <div class="act-title" id="card-a"><a style="color:#000;" onMouseOver="this.style.color='#2EAFD9';" onMouseOut="this.style.color='#000';" href="<?=Url::to(['activity/view','id'=>$list['id']])?>"><?=$list['activity_name']?></a></div>
					<div class='situation-style'><span class="fa fa-info-circle"></span>
					<?php if($list ['ticketing_end_at']<\common\models\BaseForm::getTime()):?>
                        <font color="#696969">已过期</font>
                <?php else:?>
                    <?php if($list['current_people']<$list['max_people']):?>
                        <?php if($option=='mine'):?>
                            <?=$statusString[$list['status']]?>
                        <?php else:?>
                            <font color="#4682b4">正常</font>
                        <?php endif;?>
                    <?php else:?>
                        <font color="#8b0000">人数已满</font>
                    <?php endif;?>
                <?php endif;?>
                </div>
				</div>
                    <span class="activity-tags">
                        <div class="fa fa-user-o line-height info-style"><a style="color:#000;" onMouseOver="this.style.color='#2EAFD9';" onMouseOut="this.style.color='#000';" href="<?=Url::to(['site/view','id'=>$list['release_by']])?>"><?=$list['releaseBy']['org_name']?></a></div>
                        <?php if($list['current_people']>=$list['max_people']):?>
                            <div class="fa fa-user-times line-height info-style"><?=$list['current_people']?>/<?=$list['max_people']?></div>
                        <?php else:?>
                            <div class="fa fa-group line-height info-style"><?=$list['current_people']?>/<?=$list['max_people']?></div>
                        <?php endif;?>
                        <div class="fa fa-location-arrow line-height info-style"><?=$list['location']?></div>
                        <br/>
                        <div class="fa fa-clock-o line-height info-style"><?=date('Y-m-d H:i:s',$list['release_at'])?>&nbsp;</div>
                        <!--浏览量<span class="glyphicon glyphicon-eye-open"></span>< ? =isset($list['extend']['browser'])?$list['extend']['browser']:0?>&nbsp;-->
                        <!--评论数<span class="glyphicon glyphicon-comment"></span><a href="< ? =Url::to(['post/detail','id'=>$list['id']])?>">< ? =isset($list['extend']['comment'])?$list['extend']['comment']:0?></a>-->
                    </span>
					<div class="fa fa-ticket line-height info-style"><?=date('m-d H:i:s',$list['ticketing_start_at'])?>---<?=date('m-d H:i:s',$list['ticketing_end_at'])?></div>
                    <div class="activity-content">简介:<?=$list['summary']?></div>
                </div>
				</div>
            </div>
            <div class="col-lg-2">
                <a href="<?=Url::to(['activity/view','id'=>$list['id']])?>"><button class="button-style">了解详情</button></a>
            </div>
        </div>
		<div class="list-line"></div>
        <?php endforeach;?>
    </div>
</div>
<?php if($this->context->page):?>
    <div class="page"><?=LinkPager::widget(['pagination' => $data['page']]);?></div>
<?php endif;?>