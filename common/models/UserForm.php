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

    public $is_api;

    public $wechat_id;//TODO 添加api相关属性rules

    public $lastError;//用于存放最后一个错误信息


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['user_name', 'trim','on'=>['Create','SignUp','ChangeUserName'],],
            ['user_name', 'required','on'=>['Create','SignUp','ChangeUserName','default',],],
            [
                'user_name', 'string', 'min' => 2, 'max' => 255,
                'on'=>['Create','SignUp','ChangeUserName','default',],
            ],

            ['credential', 'trim','on'=>['Create','SignUp'],],
            ['credential', 'required','on'=>['Create','SignUp','default',],],
            [
                'credential', 'unique',
                'targetClass' => '\common\models\User',
                'message' => '这个账号已经被注册',
                'on'=>['Create','SignUp','default',],
            ],
            ['credential', 'string', 'min' => 5, 'max' => 255,'on'=>['Create','SignUp','default',],],

            ['email', 'trim','on'=>['Create','SignUp','ChangeEmail'],],
            ['email', 'required','on'=>['Create','SignUp','ChangeEmail'],],
            ['email', 'email','on'=>['Create','SignUp','ChangeEmail'],],
            ['email', 'string', 'max' => 255,'on'=>['Create','SignUp','ChangeEmail','default',],],
            [
                'email', 'unique',
                'targetClass' => '\common\models\User', 'message' => '这个邮箱已经被注册',
                'on'=>['Create','SignUp','default',],
            ],
            ['email', 'validateEmail','on'=>['ChangeEmail','default',]],

            ['rePassword','required','on'=>['RePassword','RePasswordByAdmin','default',]],
            [
                'rePassword','compare','compareAttribute'=>'password',
                'message'=>'密码和重复密码不相同',
                'on'=>['RePassword','RePasswordByAdmin','default',],
            ],

            ['oldPassword','required','on'=>['RePassword','default',]],
            ['oldPassword', 'validatePassword','on'=>['RePassword','default',]],

            ['password', 'required','on'=>['RePassword','RePasswordByAdmin','default',]],
            ['password', 'string', 'min' => 6,'on'=>['RePassword','RePasswordByAdmin','default',]],


            [
                'category', 'compare',
                'compareValue'=>0,
                'operator' => '>=','message'=>'分类无效',
                'on'=>['Create','ChangeCategory','default',],
            ],
            [
                'category', 'compare',
                'compareValue'=>count(USER_CATEGORY),
                'operator' => '<','message'=>'分类无效',
                'on'=>['Create','ChangeCategory','default',],
            ],

            ['verifyCode', 'captcha','on'=>['SignUp','default',]],
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

            if($this->img_url)
            {
                if(!$this->setImg())
                    throw new \Exception('图片上传失败,请稍后重试');
            }
            else
                $this->img_url=null;

            $model = new User();
            $model->user_name = $this->user_name;
            $model->wechat_id = $this->wechat_id;

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
            if($this->is_api) Yii::$app->getSession()->setFlash('warning', $this->lastError);
            return $e->getMessage();//null
        }
    }


    //以该表单的信息更新一个已存在的模型$model,返回是否修改成功
    /*
     *  场景的必须字段为:
     *  ChangeStatus:status
     *  ChangeCategory:category
     *  ChangeUserName:user_name
     *  ChangeEmail:email
     *  ChangeAvatar:img_url
     *  RemoveAvatar: img_url允许为空
     * */
    public function infoUpdate($model,$scenario)
    {
        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            switch($scenario)//过滤无效场景
            {
                case 'ChangeStatus':
                case 'ChangeCategory':
                case 'ChangeUserName':
                case 'ChangeEmail':
                case 'ChangeAvatar':
                case 'RemoveAvatar':
                    $this->scenario=$scenario;break;
                default:
                    throw new \Exception('场景参数错误');
                    break;
            }
            if(!$this->validate())throw new \Exception('修改信息需要调整');

            switch($scenario)
            {
                case 'ChangeStatus':
                    $model->status = $this->status;break;
                case 'ChangeCategory':
                    $model->category = $this->category;break;
                case 'ChangeUserName':
                    $model->user_name = $this->user_name;break;
                case 'ChangeEmail':
                    $model->email = $this->email;break;
                case 'ChangeAvatar':
                {
                    //如果没改头像就不做动作
                    if($this->img_url!=$model->img_url)
                    {
                        $this->credential=$model->credential;
                        if(!$this->setImg()) throw new \Exception('图片上传失败,请稍后重试');
                        //删除原有的图像文件
                        $oldFile=BASE_PATH.$model->img_url;
                        if($model->img_url&&file_exists($oldFile))unlink($oldFile);
                        $model->img_url=$this->img_url;
                    }
                    break;
                }
                case 'RemoveAvatar':
                {
                    //删除原有的图像文件
                    $oldFile=BASE_PATH.$model->img_url;
                    if($model->img_url&&file_exists($oldFile))unlink($oldFile);
                    $model->img_url=null;
                    break;
                }
                default:break;
            }
            if(!$model->save())throw new \Exception('修改失败!');
            $transaction->commit();
            return true;
        }
        catch(\Exception $e)
        {
            $transaction->rollBack();
            $this->lastError=$e->getMessage();
            if($this->is_api) Yii::$app->getSession()->setFlash('warning', $this->lastError);
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
            if($this->is_api) Yii::$app->getSession()->setFlash('error', $this->lastError);
            return false;
        }
    }

    //将表单的img_url正确处理,返回是否处理成功
    /*
     * 主要是将上传的文件复制到用户的文件夹下,
     * 因为这个图片上传组件只要一点击图片就会将图片上传到服务器,
     * 在表单中重复选择图片会导致多张图片上传至服务器,这样会有很多的无效图片
     * 我想的解决方案是,将真正用得到的图片放到另一个目录下
     * 服务器定期清理组件所指定的upload_files/temp里的文件夹,这样就可以省去很多空间,
     * 注意:由于这里用了credential字段来建立文件夹,所以需要提前向$form里写入
     * credential
     * */
    private function setImg()
    {
        if($this->img_url&&$this->credential)
        {
            //这里的文件处理搞得我脑阔有点疼
            $newDir=BASE_PATH.'/upload_files/user/'.$this->credential;
            $oldDir=BASE_PATH.$this->img_url;
            $fileName=substr($this->img_url,25);
            if(!file_exists($newDir)) mkdir($newDir,0777,true);
            if(file_exists($newDir)&&copy($oldDir,$newDir.'/'.$fileName))
            {
                if($this->img_url&&file_exists($oldDir))unlink($oldDir);
                $this->img_url='/upload_files/user/'.$this->credential.$fileName;
                return true;
            }
        }
        return false;
    }

}
