<?php

namespace backend\controllers;

use common\exceptions\ProjectException;
use Yii;
use common\models\Notice;
use common\models\NoticeForm;
use admin\models\NoticeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NoticeController implements the CRUD actions for Notice model.
 */
class NoticeController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' =>
                [
                    'class'=>\yii\filters\AccessControl::className(),
                    'only'=>['*'],
                    //'except'=>[],//除了什么方法之外
                    'rules'=>
                        [
                            [//未登录用户不能访问这个控制器里的方法
                                'allow'=>false,
                                'actions'=>['*'],//所有方法不可访问
                                'roles'=>['?'],//未登录用户
                            ],
                            [//登录用户能访问这个控制器里的方法
                                'allow'=>true,
                                //可访问的页面名字
                                'actions'=>['view'],
                                'roles'=>['@'],//登录用户
                            ],
                        ],
                ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Displays a single Notice model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id),]);
    }

    /**
     * Finds the Notice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Notice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Notice::findOne($id)) !== null)
            return $model;
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
