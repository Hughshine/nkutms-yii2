<?php
namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Organizer;
use common\models\OrganizerForm;
use backend\models\LoginForm;
use yii\web\NotFoundHttpException;
use yii\web\Controller;

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
                        'actions' => ['logout', 'index','error','password','view'],
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
        $form =new OrganizerForm();
        $form->org_id=$model->id;
        $form->org_name=$model->org_name;
        if ($form->load(Yii::$app->request->post()) &&$form->rePassword($model))
        {
            Yii::$app->getSession()->setFlash('success', '密码修改成功!');
            return $this->redirect('index');
        }
        return $this->render('password', [
            'model' => $form,
        ]);
    }

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

    public function actionView()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('site/login');
        }
        $this->viewAction();
        return $this->render('view', [
            'model' => Yii::$app->user->identity,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Organizer::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
