<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/5/1
 * Time: 10:18
 */

namespace common\models;
use yii\db\ActiveRecord;
use common\exceptions\ValidateException;
use yii\db\ActiveQuery;

class BaseForm extends ActiveRecord//因为要查询,所以继承ActiveRecord
{


    /**
     * 获取分页数据
     * @param $query ActiveQuery
     * @param int $curPage
     * @param int $pageSize
     * @param null $search
     * @return array
     */
    public function getPages($query,$curPage=1,$pageSize=10,$search=null)
    {
        if($search)$query=$query->andFilterWhere($search);
        $data['count']=$query->count();
        if(!$data['count'])
            return ['count'=>0,'curPage'=>1,'pageSize'=>$pageSize,'start'=>0,'end'=>0,'data'=>[]];
        //防止页数超过总数
        $curPage=(ceil($data['count']/$pageSize)<$curPage)?
            ceil($data['count']/$pageSize):$curPage;
        $data['curPage']=$curPage;
        //每页显示条数
        $data['pageSize']=$pageSize;
        //起始页
        $data['start']=($curPage-1)*$pageSize+1;
        //末页
        $data['end']=(ceil($data['count']/$pageSize)==$curPage)?
            $data['count']:($curPage-1)*$pageSize+$pageSize;
        //取数据
        $data['data']=$query
            ->offset(($curPage-1)*$pageSize)
            ->limit($pageSize)
            ->asArray()->all();
        return $data;
    }

    /**
     * 抛出一个验证异常,由于有多处用到相同的这个代码,所以为了简洁,写成一个函数
     * @param string $msg 信息
     * @throws ValidateException
     */
    protected function throwValidateException($msg)
    {
        $exception=new ValidateException($msg);
        $exception->errors=new \ArrayObject();
        $all=$this->errors;
        foreach ($all as $each)
            $exception->errors->append($each);
        throw $exception;
    }

}