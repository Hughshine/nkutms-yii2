<?php
namespace admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use admin\models\LoginForm;
use admin\models\AdminForm;

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
     * 修改密码
     */
    public function actionRepassword()
    {
        if (Yii::$app->user->isGuest)
            return $this->render('site/login');
        $model = Yii::$app->user->identity;
        $form =new AdminForm();
        $form ->admin_name=$model->admin_name;
        $form ->admin_id=$model->id;
        if ($form->load(Yii::$app->request->post()) &&$form->rePassword($model))
        {
            Yii::$app->getSession()->setFlash('success', '密码修改成功');
            return $this->redirect('index');
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
        if (!Yii::$app->user->isGuest) {
            
            return $this->goHome();
        }

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
