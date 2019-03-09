<?php
namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;
use backend\models\PasswordForm;

/**
 * Site controller
 */
class SiteController extends BaseController
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
                        'actions' => ['logout', 'index','error','create','password'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
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
        if (Yii::$app->user->isGuest) {
            return $this->redirect('site/login');
        }
        $this->viewAction();
        return $this->render('index');
    }

    /*
     * 修改密码
     * */
    public function actionPassword()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('site/login');
        }
        $this->viewAction();
        $model = Yii::$app->user->identity;
        $passwordForm =new PasswordForm($model);
        if ($passwordForm->load(Yii::$app->request->post()) &&$passwordForm->repassword())
        {
            return $this->redirect('index');
        }
        return $this->render('password', [
            'model' => $passwordForm,
        ]);
    }
    /*
     public function actionRepassword()
    {
        if (Yii::$app->user->isGuest)
            return $this->render('site/login');
        $model = Yii::$app->user->identity;
        $passwordform =new AdminPasswordForm($model);
        if ($passwordform->load(Yii::$app->request->post()) &&$passwordform->repassword($model))
        {
            return $this->redirect('index');
        }
        return $this->render('repassword', [
            'model' => $passwordform,
        ]);
    }
     */

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $this->layout='main-login.php';
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())&&$model->login()) {
            return $this->redirect('index');
        } else {
            $model->password = '';
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }


    private function viewAction()
    {
        $this->layout='main.php';
        $view=Yii::$app->view;
        $org=Yii::$app->user->identity;
        $view->params['org_name']=$org->org_name;
        $view->params['created_at']=$org->created_at;
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
