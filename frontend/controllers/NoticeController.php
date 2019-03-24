<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/3/21
 * Time: 20:35
 */

/*
 * 通知控制器
 * */
namespace frontend\controllers;

use common\models\Notice;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;

class NoticeController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' =>
                    [
                        [
                            'actions' => ['index','view',],
                            'allow' => true,
                            'roles' => ['@','?'],
                        ],
                        [
                            'actions' => [],
                            'allow' => false,
                            'roles' => ['?',],
                        ],
                        [
                            'actions' => [],
                            'allow' => true,
                            'roles' => ['@',],
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
    /*
     * 通知列表
     * */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /*
     * 通知详情页面调用
     * */
    public function actionView($id)
    {
        $notice=Notice::findOne(['id'=>$id]);
        if(!$notice)return $this->redirect('index');
        return $this->render('view', ['model' => $notice]);
    }

}