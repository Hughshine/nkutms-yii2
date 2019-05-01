<?php
namespace admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use admin\models\LoginForm;
use admin\models\AdminForm;
use admin\models\Admin;
use common\exceptions\ProjectException;

/**
 * Site controller
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
                        'actions' => ['login'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','error'],
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index','error','repassword'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => ['logout' => ['post'],],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) 
            return $this->render('site/login');
        return $this->render('index');
    }

    /**
     * 管理员自己修改密码
     */
    public function actionRepassword()
    {
        if (Yii::$app->user->isGuest)
            return $this->render('site/login');

        $model = Admin::findIdentity_admin(Yii::$app->user->id);
        if(!$model)
        {
            Yii::$app->session->setFlash('warning','找不到ID为'.Yii::$app->user->id.'的管理者');
            return $this->redirect('index');
        }

        $form =new AdminForm();
        $form ->admin_name=$model->admin_name;
        $form ->admin_id=$model->id;

        try
        {
            if ($form->load(Yii::$app->request->post()) &&$form->rePassword($model))
            {
                Yii::$app->getSession()->setFlash('success', '密码修改成功');
                return $this->redirect('index');
            }
        }
        catch (ProjectException $exception)
        {
            Yii::$app->session->setFlash('warning',$exception->getExceptionMsg());
        }
        catch (\Exception $exception)
        {
            Yii::$app->session->setFlash('warning','未知异常'.$exception->getMessage());
        }

        return $this->render('password', ['model' => $form,]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        Yii::$app->log->targets['debug'] = null;
        $this->layout='login.php';
        if (!Yii::$app->user->isGuest)
            return $this->goHome();

        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post()) && $form->login())
            return $this->goBack();
        else
        {
            $form->password = '';
            return $this->render('login', ['model' => $form,]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
