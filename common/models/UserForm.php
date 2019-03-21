<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * 用户表单
 */

class UserForm extends ActiveRecord
{
    public $user_name;
    public $user_id;//用于管理端修改密码时向页面传递参数
    public $rePassword;
    public $credential;
    public $category;
    public $verifyCode;
    public $email;
    public $password;
    public $oldPassword;
    public $img_url;

    public $lastError;//用于存放最后一个错误信息


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['user_name', 'trim','on'=>['Create','SignUp','ChangeUserName'],],
            ['user_name', 'required','on'=>['Create','SignUp','ChangeUserName'],],
            [
                'user_name', 'string', 'min' => 2, 'max' => 255,
                'on'=>['Create','SignUp','ChangeUserName'],
            ],

            ['credential', 'trim','on'=>['Create','SignUp'],],
            ['credential', 'required','on'=>['Create','SignUp'],],
            [
                'credential', 'unique',
                'targetClass' => '\common\models\User',
                'message' => '这个账号已经被注册',
                'on'=>['Create','SignUp',],
            ],
            ['credential', 'string', 'min' => 5, 'max' => 255,'on'=>['Create','SignUp'],],

            ['email', 'trim','on'=>['Create','SignUp','ChangeEmail'],],
            ['email', 'required','on'=>['Create','SignUp','ChangeEmail'],],
            ['email', 'email','on'=>['Create','SignUp','ChangeEmail'],],
            ['email', 'string', 'max' => 255,'on'=>['Create','SignUp','ChangeEmail'],],
            [
                'email', 'unique',
                'targetClass' => '\common\models\User', 'message' => '这个邮箱已经被注册',
                'on'=>['Create','SignUp'],
            ],
            ['email', 'validateEmail','on'=>['ChangeEmail',]],

            ['rePassword','required','on'=>['RePassword','RePasswordByAdmin']],
            [
                'rePassword','compare','compareAttribute'=>'password',
                'message'=>'密码和重复密码不相同',
                'on'=>['RePassword','RePasswordByAdmin'],
            ],

            ['oldPassword','required','on'=>['RePassword',]],
            ['oldPassword', 'validatePassword','on'=>['RePassword',]],

            ['password', 'required','on'=>['RePassword','RePasswordByAdmin']],
            ['password', 'string', 'min' => 6,'on'=>['RePassword','RePasswordByAdmin']],


            [
                'category', 'compare',
                'compareValue'=>0,
                'operator' => '>=','message'=>'分类无效',
                'on'=>['Create','ChangeCategory'],
            ],
            [
                'category', 'compare',
                'compareValue'=>count(USER_CATEGORY),
                'operator' => '<','message'=>'分类无效',
                'on'=>['Create','ChangeCategory'],
            ],

            ['verifyCode', 'captcha','on'=>['SignUp',]],
        ];
    }

    public function scenarios()
    {
        return
            [
                'Create'=>//表示某个场景所用到的信息,没标记出来的不会有影响
                    [
                        'category',
                        'created_at',
                        'credential',
                        'email',
                        'password',
                        'rePassword',
                        'status',
                        'updated_at',
                        'user_name',
                        'img_url',
                    ],
                'SignUp'=>//SignUp与Create的区别是SignUp需要验证验证码
                    [
                        'category',
                        'created_at',
                        'credential',
                        'email',
                        'password',
                        'rePassword',
                        'status',
                        'updated_at',
                        'user_name',
                        'verifyCode',
                        'img_url',
                    ],
                'ChangeStatus'=> ['status','updated_at'],
                'ChangeUserName'=> ['user_name','updated_at'],
                'ChangeEmail'=> ['email','updated_at'],
                'ChangeAvatar'=> ['img_url','updated_at'],
                'RemoveAvatar'=> ['img_url','updated_at'],
                //这个场景会将img_url置为null,再存入数据库
                'ChangeCategory'=> ['category','updated_at'],
                'RePassword'=> ['password','rePassword','oldPassword','updated_at'],
                'RePasswordByAdmin'=> ['password','rePassword','updated_at'],
                'default'=>
                    [
                        'category',
                        'credential',
                        'email',
                        'password',
                        'rePassword',
                        'oldPassword',
                        'user_name',
                        'verifyCode',
                        'status',
                        'created_at',
                        'updated_at',
                        'img_url',
                    ],
            ];
    }

    //rules中调用的验证旧密码的函数
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors())
        {
            $model=Yii::$app->user->identity;
            if (!$model || !$model->validatePassword($this->oldPassword))
                $this->addError($attribute, '旧密码不正确');

        }
    }

    //验证修改邮箱时的规则
    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors())
        {
            $user=static::findOne(['email' => $this->email,]);
            if ($user)
            {
                if(Yii::$app->user->id!=$user->id)
                    $this->addError($attribute, '这个邮箱已被注册');
                else
                    $this->addError($attribute, '与原来邮箱相同');
            }

        }
    }

    public static function tableName()
    {
        return 'tk_user';
    }

    public function attributeLabels()
    {
        return
            [
                'user_name'=>'用户名',
                'password'=>'密码',
                'rePassword'=>'重复密码',
                'email'=>'邮箱',
                'rememberMe'=>'记住登录状态',
                'credential'=>'账号',
                'verifyCode' => '验证码',
                'oldPassword'=>'旧密码',
                'img_url'=>'头像(可选)',
            ];
    }

    /**
     * create a user .
     *
     * @return User|null the saved model or null if saving fails
     */
    //需要传入$scenario作为场景变量,接受的参数必须为'Create'或'SignUp'
    //区别是Create不需要填写验证码且可以规定用户类别
    /*
     * 需要的字段为:
     * user_name,credential,category,email,password,rePassword
     * */
    public function create($scenario)
    {
        $this->scenario=$scenario;
        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            if($scenario!='Create'&&$scenario!='SignUp')
                throw new \Exception('场景参数错误');
            if(!$this->validate())throw new \Exception('注册信息需要调整');

            $model = new User();
            $model->user_name = $this->user_name;
            $model->category=$this->category;
            $model->credential=$this->credential;
            $model->email=$this->email;
            $model->setPassword($this->password);
            //默认参数
            $model->status=User::STATUS_ACTIVE;
            $model->expire_at=0;
            $model->access_token='';
            $model->allowance=2;
            $model->allowance_updated_at=0;
            $model->img_url=$this->img_url;
            $model->generateAuthKey();


            if(!$model->save())throw new \Exception('注册失败!');

            //此处可以写一个afterCreate方法来处理创建后事务

            $transaction->commit();
            return $model;
        }
        catch(\Exception $e)
        {
            $transaction->rollBack();
            $this->lastError=$e->getMessage();
            Yii::$app->getSession()->setFlash('warning', $this->lastError);
            return null;
        }
    }


    //以该表单的信息更新一个已存在的模型$model,返回是否修改成功
    /*
     *  场景的必须字段为:
     *  ChangeStatus:status
     *  ChangeCategory:category
     *  ChangeUserName:user_name
     *  ChangeEmail:email
     * */
    public function infoUpdate($model,$scenario)
    {
        switch($scenario)
        {
            case 'ChangeStatus':
            case 'ChangeCategory':
            case 'ChangeUserName':
            case 'ChangeAvatar':
            case 'RemoveAvatar':
            case 'ChangeEmail':
                $this->scenario=$scenario;break;
            default:
                Yii::$app->getSession()->setFlash('warning', '场景参数错误');
                return false;
        }
        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            if(!$this->validate())throw new \Exception('修改信息需要调整');

            $model->user_name = $this->user_name;
            $model->email=$this->email;
            if($scenario=='RemoveAvatar')
                $model->img_url=null;
            else
                $model->img_url=$this->img_url;

            if(!$model->save())throw new \Exception('修改失败!');

            $transaction->commit();
            return true;
        }
        catch(\Exception $e)
        {
            $transaction->rollBack();
            $this->lastError=$e->getMessage();

            Yii::$app->getSession()->setFlash('warning', $this->lastError);
            return false;
        }
    }


    //向数据库更新该模型对应的修改的密码,返回是否修改成功
    /*
     * 必须的字段:password,rePassword,
     * 第二个参数为true时oldPassword也是必须的
     * */
    public function RePassword($model,$validateOldPassword=true)
    {
        $this->scenario=($validateOldPassword)?'RePassword':'RePasswordByAdmin';
        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            if(!$this->validate())throw new \Exception('修改信息需要调整');
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

    //改变用户状态功能,即封号和解封返回是否修改成功
    /*
     * 必须的字段为:
     * status
     * */
    public function changeStatus($model)
    {
        $this->scenario='ChangeStatus';
        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            if(!$this->validate())throw new \Exception('状态无效');
            $model->status=$this->status;

            if(!$model->save())throw new \Exception('修改失败!');

            $transaction->commit();
            return true;
        }
        catch(\Exception $e)
        {
            $transaction->rollBack();
            $this->lastError=$e->getMessage();
            Yii::$app->getSession()->setFlash('warning', $this->lastError);
            return false;
        }
    }
}
