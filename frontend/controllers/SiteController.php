<?php
namespace frontend\controllers;

use common\models\User;
use common\models\UserForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\ContactForm;

/**
 * Site controller
 * 在此 site controller相当于user controller和web controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' =>
                            [
                                'signup','login','index','error','request-password-reset',
                                'reset-password'
                            ],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['captcha','reset-password','upload'],
                        'allow' => true,
                        'roles' => ['?','@'],
                    ],
                    [
                        'actions' => ['logout','view','repassword','error','index','update'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' =>
                [
                    'class' => VerbFilter::className(),
                    'actions' => ['logout' => ['post'],],
                ],
        ];
    }

    /**
     * {@inheritdoc}
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [//验证码组件
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'upload'=>[//图片上传组件
                'class' => 'common\widgets\file_upload\UploadAction',     //这里扩展地址别写错
                'config' =>
                    [
                          'imagePathFormat' => "/images/{yyyy}{mm}{dd}{time}{rand:6}",
                    ]
            ],
        ];
    }
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView()
    {
        if(Yii::$app->user->isGuest)
            return $this->redirect('index');
        return $this->render('view');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest)
            return $this->goHome();

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()))
            {
                $user=User::findByCredential($model->credential);
                if(!$user)
                {
                    $model->password = '';
                    Yii::$app->getSession()->setFlash('warning', '此账号已被封禁或不存在');
                    return $this->render('login', ['model' => $model,]);
                }
                else
                {
                    if($model->login()) return $this->goBack();
                    else
                    {
                        $model->password = '';
                        return $this->render('login', ['model' => $model,]);
                    }
                }

            }
        else
            {
                $model->password = '';
                return $this->render('login', ['model' => $model,]);
            }
    }

    //修改密码功能
    public function actionRepassword()
    {
        $model = Yii::$app->user->identity;
        $form =new UserForm();
        /*注意:需要先往$this->user_id,$this->user_name写入相应的数据
        因为页面显示需要id和名字数据,而传递的模型是表单模型而不是实例模型,所以需要补充数据*/
        $form->user_name=$model->user_name;
        $form->user_id=$model->id;
        if ($form->load(Yii::$app->request->post()) &&$form->rePassword($model,true))
        {
            Yii::$app->getSession()->setFlash('success', '密码修改成功');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('password', ['model' => $form,]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    //在contact页面填写表单并向邮箱发送邮件,目前不需要
    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $form = new UserForm();
        if ($form->load(Yii::$app->request->post()))
        {
            $form->category=0;
            if($form->img_url&&$form->credential)//如果选择了图片且有了账号
            {
                if(!$this->setImg($form))
                {
                    Yii::$app->getSession()->setFlash('warning', '图片上传失败,请稍后重试');
                    return $this->render('signup', ['model' => $form,]);
                }
            }
            else $form->img_url=null;
            if ($model = $form->create('SignUp'))
            {
                if (Yii::$app->getUser()->login($model))
                {
                    Yii::$app->getSession()->setFlash('success', '注册成功');
                    return $this->goHome();
                }
            }

        }
        return $this->render('signup', ['model' => $form,]);
    }

    //修改用户资料
    //$scenario:'ChangeCategory':'ChangeUserName':'ChangeEmail':'ChangeAvatar'
    public function actionUpdate($scenario)
    {
        $form =new UserForm();
        switch($scenario)
        {
            case 'ChangeUserName':
            case 'ChangeEmail':
            case 'ChangeAvatar':
            case 'RemoveAvatar':
                $form->scenario=$scenario;break;
            default:
                Yii::$app->getSession()->setFlash('warning', '场景参数错误');
                return $this->redirect(['view']);
        }
        $model = Yii::$app->user->identity;
        $form->user_name=$model->user_name;
        $form->email=$model->email;
        $form->category=$model->category;
        $form->img_url=$model->img_url;
        if($scenario=='RemoveAvatar')
        {
            //删除原有的图像文件
            $oldFile=strtr(Yii::$app->basePath, '\\', '/').'/web'.$model->img_url;
            if($model->img_url&&file_exists($oldFile))unlink($oldFile);
            if($form->infoUpdate($model,$scenario))
            {
                Yii::$app->getSession()->setFlash('success', '修改成功');
                return $this->redirect(['view']);
            }
        }
        if ($form->load(Yii::$app->request->post()))
        {
            if($scenario=='ChangeAvatar')
            {
                //如果没改头像就不去调用update方法
                if($form->img_url==$model->img_url)
                {
                    Yii::$app->getSession()->setFlash('success', '修改成功');
                    return $this->redirect(['view']);
                }
                $form->credential=$model->credential;
                if(!$this->setImg($form))
                {
                    Yii::$app->getSession()->setFlash('warning', '图片上传失败,请稍后重试');
                    return $this->redirect(['view']);
                }
                //删除原有的图像文件
                $oldFile=strtr(Yii::$app->basePath, '\\', '/').'/web'.$model->img_url;
                if($model->img_url&&file_exists($oldFile))unlink($oldFile);
            }
            if($form->infoUpdate($model,$scenario))
            {
                Yii::$app->getSession()->setFlash('success', '修改成功');
                return $this->redirect(['view']);
            }
        }
        return $this->render('update', ['model' => $form,'scenario'=>$scenario]);
    }


    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    //将表单的img_url正确处理,返回是否处理成功
    /*
     * 主要是将上传的文件复制到用户的文件夹下,
     * 因为这个图片上传组件只要一点击图片就会将图片上传到服务器,
     * 在表单中重复选择图片会导致多张图片上传至服务器,这样会有很多的无效图片
     * 我想的解决方案是,将真正用得到的图片放到另一个目录下
     * 服务器定期清理组件所指定的web/images里的文件夹,这样就可以省去很多空间,
     * 注意:由于这里用了credential字段来建立文件夹,所以需要提前向$form里写入
     * credential
     * */
    private function setImg($form)
    {
        if($form->img_url)
        {
            //这里的文件处理搞得我脑阔有点疼
            $newDir=strtr(Yii::$app->basePath, '\\', '/').'/web/user/'.$form->credential;
            $oldDir=strtr(Yii::$app->basePath, '\\', '/').'/web'.$form->img_url;
            $fileName=substr($form->img_url,8);
            if(!file_exists($newDir)) mkdir($newDir,0777,true);
            if(file_exists($newDir)&&copy($oldDir,$newDir.'/'.$fileName))
            {
                $form->img_url='/user/'.$form->credential.'/'.$fileName;
                return true;
            }
        }
        return false;
    }
}
