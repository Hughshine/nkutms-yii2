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
		.a-box-title
		{
			color:#0c475b;
			font-size:20px;
		}
		.a-list-line {
			margin-top:0px;
			border-bottom:3px solid #3c8dbc;
		}
		.a-column-flex {
			display:flex;
			flex-direction:column;
			width:100%;
			height:100%;
			justify-content:center;
			align-items:center;
		}
		.a-row-flex {
			display:flex;
			flex-direction:row;
			justify-content:space-between;
			align-items:center;
			height:100%;
			width:100%;
		}
		.a-index-title {
			margin-left:0px;
			font-size:30px;
			margin-top:10px;
			margin-bottom:10px;
			color:#000;
			padding-bottom:10px;
			font-weight:bold;
		}
		.a-act-title {
			margin-left:0px;
			font-size:40px;
			margin-top:20px;
			margin-bottom:20px;
			color:#000;
			font-weight:bold;
		}
		.a-situation-style {
			font-size:20px;
			margin-left:25px;
		}
		.a-cc-top {
			margin-top:20px;
			margin-bottom:20px;
		}
		.a-activity-content {
			margin-bottom:0;
			font-size:20px;
		}
		.a-line-height {
			margin-top:20px;
			margin-bottom:20px;
			line-height:10px;
			margin-left:0px;
			margin-right:10px;
		}
		.a-button-style {
			font-size:20px;
			background-color:rgb(139, 60, 112);
			color:#fff;
			border-radius:10px;
			line-height:40px;
		}
		.a-info-style {
			font-size:20px;
			color:#000;
		}
	</style>
</head>

<div class="panel">
    <div class="a-index-title">
        <span>&nbsp <?=$data['title']?></span>	
        <!--< ?php if($this->context->more):?>
        <span class="pull-right"><a href="< ?=$data['more']?>" class="font-12">更多»&nbsp&nbsp</a></span>
        < ?php endif;?>-->
    </div>
	<div class="a-list-line"></div>
    <div class="a-new-list">
    <?php foreach ($data['body'] as $list):?>
        <div class="a-row-flex a-box-title">
            <div class="col-lg-10">
                <div class="a-cc-top">
                    <div class="a-act-title"><a style="color:#000;" onMouseOver="this.style.color='#2EAFD9';" onMouseOut="this.style.color='#000';" href="<?=Url::to(['notice/view','id'=>$list['id']])?>"><?=$list['title']?></a></div>
                    <div class="a-line-height a-info-style"><span class="fa fa-bookmark"></span>总结:<?=$list['summary']?></div>
                    <div class="a-line-height a-info-style"><span class="fa fa-calendar-check-o"></span>发布时间:<?=date('m-d H:i:s',$list['created_at'])?></div>
					<div class="a-line-height a-info-style"><span class="fa fa-calendar-check-o"></span>上一次更新时间:<?=date('m-d H:i:s',$list['updated_at'])?></div>
                </div>
                <div class="col-lg-2 label-img-size">
                    <!--<a href="#" class="activity-label">
                        <img src="< ? =($list['label_img']?\Yii::$app->params['upload_url'].$list['label_img']:\Yii::$app->params['default_label_img'])?>" alt="< ? =$list['title']?>">
                    </a>-->
                </div>
            </div>
            <div class="col-lg-2">
                <a href="<?=Url::to(['notice/view','id'=>$list['id']])?>"><button class="a-button-style">了解详情</button></a>
            </div>
        </div>
		<div class="a-list-line"></div>
        <?php endforeach;?>
    </div>
</div>
<?php if($this->context->page):?>
    <div class="page"><?=LinkPager::widget(['pagination' => $data['page']]);?></div>
<?php endif;?>