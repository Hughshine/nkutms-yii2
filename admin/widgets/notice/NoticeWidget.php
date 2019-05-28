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
	public$option_type='';//组件类型

    public function run()
    {
		switch($this->option_type) {
		case 'all': { //全部消息
        $curPage=Yii::$app->request->get('page',1);
        $cond=['=','status',Notice::STATUS_ACTIVE];
        $res=NoticeForm::getList($cond,$curPage,$this->limit,['updated_at'=>SORT_DESC]);
        $result['title']=$this->title?:"全部通知";
        $result['body']=$res['data']?:[];
        if($this->page)
        {
            $page=new Pagination(['totalCount'=>$res['count'],'pageSize'=>$res['pageSize']]);
            $result['page']=$page;
        }
        return $this->render('index',['data'=>$result,]);
		break;
		}
		case 'latest': { //最新消息
		$curPage=Yii::$app->request->get('page',1);
        $cond=['and',['>','updated_at',\common\models\BaseForm::getTime()-86400*3],['=','status',Notice::STATUS_ACTIVE]];
        $res=NoticeForm::getList($cond,$curPage,$this->limit,['updated_at'=>SORT_DESC]);
        $result['title']=$this->title?:"最新通知";
        $result['body']=$res['data']?:[];
        if($this->page)
        {
            $page=new Pagination(['totalCount'=>$res['count'],'pageSize'=>$res['pageSize']]);
            $result['page']=$page;
        }
        return $this->render('index',['data'=>$result,]);
		break;
		}
		default:break;
		}
		return null;
    }
}