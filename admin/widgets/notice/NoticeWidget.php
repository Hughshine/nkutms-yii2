<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/3/9
 * Time: 21:40
 */

namespace admin\widgets\notice;

use common\models\Notice;
use common\models\NoticeForm;
use Yii;
use yii\base\Widget;
use yii\helpers\Url;
use yii\data\Pagination;

class NoticeWidget extends Widget
{
    public$title='';//列表的标题
    public$limit=5;//每页的数量
    public$more=false;//是否显示更多
    public$page=true;//是否显示分页
    public$option='user';

    public function run()
    {
        $curPage=Yii::$app->request->get('page',1);
        $cond=['=','status',Notice::STATUS_ACTIVE];
        $res=NoticeForm::getList($cond,$curPage,$this->limit,['updated_at'=>SORT_DESC]);
        $result['title']=$this->title?:"通知";
        $result['body']=$res['data']?:[];
        if($this->page)
        {
            $page=new Pagination(['totalCount'=>$res['count'],'pageSize'=>$res['pageSize']]);
            $result['page']=$page;
        }
        return $this->render('index',['data'=>$result,]);
    }
}