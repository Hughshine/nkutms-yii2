<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/3/16
 * Time: 20:19
 */
namespace admin\models;

use Yii;
use yii\db\ActiveRecord;

/*
 * 组织者表单
 * */
class AdminForm extends ActiveRecord
{
    public $admin_id;//用于给validatePassword方法传递模型实例
    public $admin_name;

    //修改密码所用到的
    public $password;
    public $rePassword;
    public $oldPassword;

    public $lastError;//用于存放最后一次异常信息

    public function rules()
    {
        return
            [
                [['admin_name'], 'required','on'=>['Update']],
                [['admin_name',], 'string', 'min' => 1,'on'=>['Update']],
                [['password','rePassword',], 'string', 'min' => 6,'on'=>['RePassword']],
                [['password','rePassword','oldPassword'], 'required','on'=>['RePassword']],
                //重复密码必须与密码相等
                ['rePassword','compare','compareAttribute'=>'password','message'=>'密码和重复密码不相同','on'=>['RePassword']],
                ['oldPassword', 'validatePassword','on'=>['RePassword']],

            ];
    }

    public static function tableName()
    {
        return 'tk_admin';
    }

    //设置场景值
    public function scenarios()
    {
        return
            [
            'Update'=>
                [
                    'admin_name',
                    'updated_at',
                ],
            'RePassword' =>
                [
                    'password',
                    'rePassword',
                    'oldPassword',
                    'updated_at',
                ],
             'default'=>
                 [
                     'admin_name',
                     'password',
                     'rePassword',
                     'oldPassword',
                     'updated_at',
                 ],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors())
        {
            $admin=Admin::findIdentity($this->admin_id);
            if (!$admin || !$admin->validatePassword($this->oldPassword))
                $this->addError($attribute, '旧密码不正确');
        }
    }

    public function attributeLabels()
    {
        return
            [
                'password'=>'密码',
                'rePassword'=>'重复密码',
                'oldPassword'=>'旧密码',
            ];
    }

    /*
     * 向数据库更新该模型对应的修改的密码
     * 注意:需要先往$this->admin_id,$this->admin_name写入相应的数据
     * */
    public function rePassword($model)
    {
        $this->admin_id=$model->id;
        $this->scenario='RePassword';
        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            if(!$this->validate())throw new \Exception('数据不符合要求');
            $model->setPassword($this->password);
            if(!$model->save())throw new \Exception('密码修改失败!');
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
}
