<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/3/9
 * Time: 21:40
 */

namespace admin\widgets\report;

use common\models\ReportForm;
use Yii;
use yii\base\Widget;
use yii\data\Pagination;

class ReportWidget extends Widget
{
    public$title='';//列表的标题
    public$limit=5;//每页的数量
    public$more=false;//是否显示更多
    public$page=true;//是否显示分页

    public function run()
    {
        $curPage=Yii::$app->request->get('page',1);
        $cond=[];
        $res=ReportForm::getList($cond,$curPage,$this->limit,['tot'=>SORT_DESC]);
        $result['title']=$this->title?:"反馈信息";
        $result['body']=$res['data']?:[];
        if($this->page)
        {
            $page=new Pagination(['totalCount'=>$res['count'],'pageSize'=>$res['pageSize']]);
            $result['page']=$page;
        }
        return $this->render('index',['data'=>$result,'view'=>$this->more]);
    }
}