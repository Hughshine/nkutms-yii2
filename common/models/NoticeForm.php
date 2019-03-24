<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/3/9
 * Time: 10:32
 */
namespace  common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;

/*
 * 通知表单
 * */
class NoticeForm extends ActiveRecord//因为要查询,所以继承ActiveRecord
{
    public $title;
    public $content;

    public $lastError;//用于存放最后一次异常信息

    public function rules()
    {
        return
            [
                //要求必须存在值
                [
                    ['content', 'title',],
                    'required',
                    'on'=>['Create','Update','default',]//对于这些场景有效
                ],

                [['title'], 'string', 'max' => 32,'on'=>['Create','Update','default',]],

                [['content',], 'string', 'max' => 255,'on'=>['Create','Update','Review','default',]],

            ];
    }
    //设置场景值
    public function scenarios()
    {
        return [
            'Create' =>//表示某个场景所用到的信息,没标记出来的不会有影响
                [
                    'title','content',
                    'updated_at','created_at',
                ],
            'Update' =>
                [
                    'title','content',
                    'updated_at',
                ],
            'default'=>
                [
                    'title','content',
                    'updated_at','created_at',
                ],
        ];
    }

    public static function tableName()
    {
        return '{{%tk_notice}}';
    }

    public function attributeLabels()
    {
        return
            [
                'id' => 'ID',
                'title' => '标题',
                'content' => '内容',
                'updated_at' => '上一次编辑时间',
                'created_at' => '创建时间',
            ];
    }

    //以当前表单的信息创建一个活动,返回这个活动的模型
    /*
     * 必须字段为:
     * title content
     * */
    public function create()
    {
        $this->scenario='Create';
        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            if(!$this->validate())throw new \Exception('创建信息需要调整!');

            $model = new Notice();
            $model->title=$this->title;
            $model->summary=$this->getSummary();
            $model->content=$this->content;
            
            if(!$model->save())throw new \Exception('通知发布失败!');

             //此处可以写一个afterCreate方法来处理创建后事务

            $transaction->commit();
            return $model;
        }
        catch(\Exception $e)
        {
            $transaction->rollBack();
            $this->lastError=$e->getMessage();
            Yii::$app->getSession()->setFlash('error', $this->lastError);
            return null;
        }
    }

    //以当前表单的信息更新$activity活动信息,返回是否成功
    //之所以不用update做名字,因为父类有update方法,不想重写
    /*
     * 必须字段为:
     * title content
     * */
    public function infoUpdate($model)
    {
        $this->scenario='Update';
        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            if(!$this->validate())throw new \Exception('更新信息需要调整');

            $model->title=$this->title;
            $model->summary=$this->getSummary();
            $model->content=$this->content;

            if(!$model->save())throw new \Exception('活动信息修改失败!');

            $transaction->commit();
            return true;
        }
        catch(\Exception $e)
        {
            $transaction->rollBack();
            $this->lastError=$e->getMessage();
            Yii::$app->getSession()->setFlash('error', $this->lastError);
            return false;
        }
    }

    //查询结果并返回成列表
    public static function getList($cond,$curPage = 1,$pageSize = 5,$sortOrder=['id'=>SORT_DESC])
    {
        $form=new NoticeForm();
        $select= ['id','title','summary'];
        $query=$form->find()
            ->select($select)
            ->where($cond)
            ->orderBy($sortOrder);
        //获取分页信息
        $res=$form->getPages($query,$curPage,$pageSize);
        return $res;
    }

    //获取分页数据
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

    //获取摘要
    private function getSummary($s=0,$e=90,$char='utf-8')
    {
        if(empty($this->content))
            return null;
        return(mb_substr(str_replace('&nbsp;',' ',
            strip_tags($this->content)),$s,$e,$char));
    }

}