<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/3/16
 * Time: 20:19
 */
namespace admin\models;

use common\exceptions\ProjectException;
use common\exceptions\ValidateException;
use common\models\BaseForm;
use Yii;

/*
 * 管理者表单
 * */
class AdminForm extends BaseForm
{
    public $admin_id;//用于给validatePassword方法传递模型实例
    public $admin_name;

    //修改密码所用到的
    public $password;
    public $rePassword;
    public $oldPassword;

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

    /**
     * 验证密码是否正确的函数,需要提前向admin_id字段写入对应的id
     * @param $attribute
     */
    public function validatePassword($attribute)
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

    /**
     * 向数据库更新该模型对应的修改的密码
     * 注意:需要先往$this->admin_id写入相应的数据
     * @param Admin $model
     * @return bool
     * @throws ValidateException
     * @throws \Exception
     */
    public function rePassword($model)
    {
        $this->admin_id=$model->id;//为了给验证旧密码的时候传入信息指明是哪个admin修改密码
        $this->scenario='RePassword';

        if(!$this->validate())
            $this->throwValidateException('AdminForm::rePassword:表单数据验证异常');

        $model->setPassword($this->password);

        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            if(!$model->save())
                $this->throwValidateException('AdminForm::rePassword:保存验证异常');

            $transaction->commit();
            return true;
        }
        catch (ValidateException $exception)
        {
            throw $exception;
        }
        catch(\Exception $exception)
        {
            throw $exception;
        }
    }
}
