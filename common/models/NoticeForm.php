<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/3/9
 * Time: 10:32
 */
namespace  common\models;

use common\exceptions\ValidateException;
use Yii;

/*
 * 通知表单
 * */
class NoticeForm extends BaseForm
{
    public $title;
    public $content;

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

    /**以当前表单的信息创建一个活动,返回这个活动的模型
     * 必须字段为:
     * title content
     * @return Notice
     * @throws ValidateException
     * @throws \Exception
     */
    public function create()
    {
        $this->scenario='Create';
        $model = new Notice();
        $model->title=$this->title;
        $model->summary=$this->getSummary();
        $model->content=$this->content;
        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            if(!$this->validate())
                $this->throwValidateException('NoticeForm::create:创建信息需要调整');

            if(!$model->save())
                $this->throwValidateException('NoticeForm::create:模型创建失败');

             //此处可以写一个afterCreate方法来处理创建后事务

            $transaction->commit();
            return $model;
        }
        catch(ValidateException $exception)
        {
            $transaction->rollBack();
            throw $exception;
        }
        catch(\Exception $exception)
        {
            $transaction->rollBack();
            throw $exception;
        }
    }

    /**以当前表单的信息更新$activity活动信息,返回是否成功
     * 必须字段为:
     * title content
     * @param $model Notice
     * @return bool
     * @throws ValidateException
     * @throws \Exception
     */
    public function infoUpdate($model)
    {
        $this->scenario='Update';
        $transaction=Yii::$app->db->beginTransaction();
        $model->title=$this->title;
        $model->summary=$this->getSummary();
        $model->content=$this->content;
        try
        {
            if(!$this->validate())
                $this->throwValidateException('NoticeForm::infoUpdate:更新信息需要调整');

            if(!$model->save())
                $this->throwValidateException('NoticeForm::infoUpdate:模型创建失败');

            $transaction->commit();
            return true;
        }
        catch(ValidateException $exception)
        {
            $transaction->rollBack();
            throw $exception;
        }
        catch(\Exception $exception)
        {
            $transaction->rollBack();
            throw $exception;
        }
    }

    /**
     * 按条件查询并返回列表
     * @param array $cond 条件
     * @param integer $curPage
     * @param integer $pageSize
     * @param array $sortOrder
     * @return array
     */
    public static function getList($cond, $curPage = 1, $pageSize = 5, $sortOrder = ['id' => SORT_DESC])
    {
        $model=new NoticeForm();
        $select= ['id','title','summary','content',
            'updated_at','created_at'
            ];
        $query=$model->find()
            ->select($select)
            ->where($cond)
            ->orderBy($sortOrder);
        $res=$model->getPages($query,$curPage,$pageSize);
        return $res;
    }

    /**从content里获取摘要存放到summary字段里
     * @param int $s
     * @param int $e
     * @param string $char
     * @return string|null
     */
    private function getSummary($s=0,$e=90,$char='utf-8')
    {
        if(empty($this->content))
            return null;
        return(mb_substr(str_replace('&nbsp;',' ',
            strip_tags($this->content)),$s,$e,$char));
    }




}