<?php

namespace admin\widgets\auth_info;

use common\models\AuthInfoForm;
use Yii;
use yii\base\Widget;
use yii\data\Pagination;

class AuthInfoWidget extends Widget
{
    public$title='';//列表的标题
    public$limit=5;//每页的数量
    public$more=false;//是否显示更多
    public$page=true;//是否显示分页

    public function run()
    {
        $curPage=Yii::$app->request->get('page',1);
        $cond=[];
        $res=AuthInfoForm::getList($cond,$curPage,$this->limit,['a.created_at'=>SORT_DESC]);
        $result['title']=$this->title?:"待审核认证请求";
        $result['body']=$res['data']?:[];
        if($this->page)
        {
            $page=new Pagination(['totalCount'=>$res['count'],'pageSize'=>$res['pageSize']]);
            $result['page']=$page;
        }
        if(count($result['body'])>0)
            return $this->render('index',['data'=>$result,'view'=>$this->more]);
        else
            return null;
    }
}