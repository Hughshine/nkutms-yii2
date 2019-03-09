<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/3/9
 * Time: 10:32
 */
namespace  backend\models;
/*
 * 活动表单模型
 * */

use Yii;
use yii\base\Model;

class ActivityFrom extends Model
{
    public $activity_name;
    public function rules()
    {
        return[
            [['$activity_name',],'required'],
        ];
    }

    public function attributeLabels()
    {
        return
        [
          'activity_name'=>'活动名称',
        ];
    }

}