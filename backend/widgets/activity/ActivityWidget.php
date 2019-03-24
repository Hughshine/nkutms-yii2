<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/3/9
 * Time: 21:40
 */

namespace backend\widgets\activity;

use common\models\Ticket;
use Yii;
use yii\base\Widget;
use common\models\Activity;
use yii\helpers\Url;
use yii\data\Pagination;
use common\models\ActivityForm;

class ActivityWidget extends Widget
{
    public$title='';//列表的标题
    public$limit=5;//每页的数量
    public$more=false;//是否显示更多
    public$page=true;//是否显示分页
    public$option='';//组件类型

    public function run()
    {
        switch($this->option)
        {
            case 'index':
                {
                    $curPage=Yii::$app->request->get('page',1);
                    $cond=['=','status',Activity::STATUS_APPROVED];
                    $res=ActivityForm::getList($cond,$curPage,$this->limit,['ticketing_end_at'=>SORT_DESC]);
                    $result['title']=$this->title?:"最新活动";
                    $result['more']=Url::to(['activity/index']);
                    $result['body']=$res['data']?:[];
                    if($this->page)
                    {
                        $page=new Pagination(['totalCount'=>$res['count'],'pageSize'=>$res['pageSize']]);
                        $result['page']=$page;
                    }
                    return $this->render('index',['data'=>$result,'option'=>$this->option]);
                    break;
                }
            case 'mine':
                {
                    $curPage=Yii::$app->request->get('page',1);
                    $cond=['=','release_by',Yii::$app->user->id];
                    $res=ActivityForm::getList($cond,$curPage,$this->limit,['release_at'=>SORT_DESC]);
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
                        '<font color="#FF0000">被驳回</font>',
                        '<font color="black">已取消</font>');
                    return $this->render('index',['data'=>$result,'option'=>$this->option,'statusString'=>$status]);
                    break;
                }
            case 'frontendList':
                {
                    $curPage=Yii::$app->request->get('page',1);
                    $cond=['=','status',Activity::STATUS_APPROVED];
                    $res=ActivityForm::getList($cond,$curPage,$this->limit,['release_at'=>SORT_DESC]);
                    $result['title']=$this->title?:"最新活动";
                    $result['body']=$res['data']?:[];
                    if($this->page)
                    {
                        $page=new Pagination(['totalCount'=>$res['count'],'pageSize'=>$res['pageSize']]);
                        $result['page']=$page;
                    }
                    return $this->render('index',['data'=>$result,'option'=>$this->option]);
                    break;
                }
            case 'userActivities':
                {
                    $curPage=Yii::$app->request->get('page',1);

                    if(Yii::$app->user->isGuest)return null;

                    //查询用户有多少个有效票id
                    $user_query=Ticket::find()->select('activity_id')
                                            ->where
                                            ([
                                                'and',
                                                ['user_id'=>Yii::$app->user->id],
                                                ['status'=>Ticket::STATUS_VALID]
                                            ])
                                            ->asArray()->all();
                    $user_activities_id=[];
                    foreach ( $user_query as $each)
                    {
                        $user_activities_id[]=$each['activity_id'];
                    }
                    $cond=['in', 'id',$user_activities_id];
                    $res=ActivityForm::getList($cond,$curPage,$this->limit,['release_at'=>SORT_DESC]);
                    $result['title']=$this->title?:"我参加的活动";
                    $result['body']=$res['data']?:[];
                    if($this->page)
                    {
                        $page=new Pagination(['totalCount'=>$res['count'],'pageSize'=>$res['pageSize']]);
                        $result['page']=$page;
                    }
                    return $this->render('index',['data'=>$result,'option'=>$this->option]);
                    break;
                }
            default:break;
        }
        return null;
    }
}