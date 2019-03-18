<?php
namespace common\models;

use yii\base\Model;
use Yii;

/**
 * 用户表单
 */
class UserForm extends Model
{
    public $user_name;
    public $rePassword;
    public $credential;
    public $verifyCode;
    public $email;
    public $password;

    public $lastError;//用于存放最后一个错误信息


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['user_name', 'trim'],
            ['user_name', 'required'],
            ['user_name', 'unique', 'targetClass' => '\common\models\User', 'message' => '这个名字已经被注册'],
            ['user_name', 'string', 'min' => 2, 'max' => 255],

            ['credential', 'trim'],
            ['credential', 'required'],
            ['credential', 'unique', 'targetClass' => '\common\models\User', 'message' => '这个账号已经被注册'],
            ['credential', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => '这个邮箱已经被注册'],

            ['rPassword','compare','compareAttribute'=>'password','message'=>'密码和重复密码不相同'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            [
                'category', 'compare',
                'compareValue'=>0,
                'operator' => '>=','message'=>'分类无效',
                'on'=>['Create','Update'],
            ],
            [
                'category', 'compare',
                'compareValue'=>count(USER_CATEGORY),
                'operator' => '<','message'=>'分类无效',
                'on'=>['Create','Update'],
            ],

            ['verifyCode', 'captcha'],
        ];
    }

    public function scenarios()
    {
        return
            [
                'Create'=>//表示某个场景所用到的信息,没标记出来的不会有影响
                    [
                        'category',
                        'credential',
                        'email',
                        'password',
                        'rePassword',
                        'user_name',
                        'verifyCode',
                        'status',
                    ],
                'Ban'=> ['status',],
                'default'=>
                    [
                        'category',
                        'credential',
                        'email',
                        'password',
                        'rePassword',
                        'user_name',
                        'verifyCode',
                        'status',
                    ],
            ];
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
            ];
    }

    /**
     * Create a user .
     *
     * @return User|null the saved model or null if saving fails
     */
    public function create()
    {
        $this->scenario='Create';
        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            if(!$this->validate())throw new \Exception('注册信息需要调整');

            $model = new User();
            $model->user_name = $this->user_name;
            $model->category=USER_CATEGORY[0];
            $model->credential=$this->credential;
            $model->email=$this->email;
            $model->setPassword($this->password);
            $model->status=User::STATUS_ACTIVE;
            $model->expire_at=0;
            $model->access_token='';
            $model->allowance=2;
            $model->allowance_updated_at=0;
            $model->generateAuthKey();

            if(!$model->save())
            {var_dump($model->errors);throw new \Exception('注册失败!');}
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
}
