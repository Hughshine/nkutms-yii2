<?php
namespace frontend\controllers;

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
use common\models\User;
use common\models\UserForm;

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
                                'signup','index','error','request-password-reset',
                                'reset-password','contact'
                            ],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' =>
                            [
                                'my-activities',
                            ],
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['captcha','reset-password','upload','login','contact'],
                        'allow' => true,
                        'roles' => ['?','@'],
                    ],
                    [
                        'actions' => ['logout','view','repassword','error','index','update','my-activities'],
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
                          'imagePathFormat' =>"/upload_files/temp/images/{yyyy}{mm}{dd}{time}{rand:6}",
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

    public function actionMyActivities()
    {
        if(Yii::$app->user->isGuest)
            return $this->redirect('index');
        return $this->render('myActivities');
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
            return $this->redirect(['view']);
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
        return $this->render('contact');
        /*$model = new ContactForm();
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
        }*/
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
            if($model=$form->create('SignUp'))
            {
                if(Yii::$app->getUser()->login($model))
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
        $model=Yii::$app->user->identity;
        switch($scenario)//检测场景参数是否错误
        {
            case 'ChangeUserName':
            case 'ChangeEmail':
            case 'ChangeAvatar':$form->scenario=$scenario;break;
            case 'RemoveAvatar'://当删除头像场景时,要求是点击即完成,不需要也不会有表单提交动作
                if($form->infoUpdate($model,$scenario))
                {
                    Yii::$app->getSession()->setFlash('success', '修改成功');
                    return $this->redirect(['view']);
                }
                else
                    return $this->render('update', ['model' => $form,'scenario'=>$scenario]);
                break;
            default:
                Yii::$app->getSession()->setFlash('warning', '场景参数错误');
                return $this->redirect(['view']);
        }
        $form->user_name=$model->user_name;
        $form->email=$model->email;
        $form->category=$model->category;
        if ($form->load(Yii::$app->request->post())&&$form->infoUpdate($model,$scenario))
        {
            Yii::$app->getSession()->setFlash('success', '修改成功');
            return $this->redirect(['view']);
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
}
