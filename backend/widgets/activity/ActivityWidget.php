<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/3/9
 * Time: 21:40
 */

namespace backend\widgets\activity;

use Yii;
use yii\base\Widget;
use common\models\Activity;
use yii\helpers\Url;
use yii\data\Pagination;
use backend\models\ActivityFrom;

class ActivityWidget extends Widget
{
    public$title='';//列表的标题
    public$limit=5;//每页的数量
    public$more=false;//是否显示更多
    public$page=true;//是否显示分页
    public$option='';//是否显示分页

    public function run()
    {
        //或许可以改成switch?php有switch吗?
        if($this->option=='index')
        {
            $curPage=Yii::$app->request->get('page',1);
            $cond=['=','status',Activity::STATUS_APPROVED];
            $res=ActivityFrom::getList($cond,$curPage,$this->limit,['ticketing_end_at'=>SORT_DESC]);
            $result['title']=$this->title?:"最新活动";
            $result['more']=Url::to(['activity/index']);
            $result['body']=$res['data']?:[];
            if($this->page)
            {
                $page=new Pagination(['totalCount'=>$res['count'],'pageSize'=>$res['pageSize']]);
                $result['page']=$page;
            }
            return $this->render('index',['data'=>$result,'option'=>'index']);
        }
        else if($this->option=='mine')
        {
            $curPage=Yii::$app->request->get('page',1);
            $cond=['=','release_by',Yii::$app->user->id];
            $res=ActivityFrom::getList($cond,$curPage,$this->limit,['release_at'=>SORT_DESC]);
            $result['title']=$this->title?:"我的活动记录";
            $result['more']=Url::to(['activity/index']);
            $result['body']=$res['data']?:[];
            if($this->page)
            {
                $page=new Pagination(['totalCount'=>$res['count'],'pageSize'=>$res['pageSize']]);
                $result['page']=$page;
            }
            $status=array('<font color="gray">未审核</font>',
                                    '<font color="#339933">已通过</font>',
                                    '<font color="#FF0000">被驳回</font>');
            return $this->render('index',['data'=>$result,'option'=>'mine','statusString'=>$status]);
        }
        return null;
    }
}