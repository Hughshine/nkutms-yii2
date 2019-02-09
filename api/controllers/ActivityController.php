<?php
namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use common\models\Activity;

class ActivityController extends ActiveController
{
	public $modelClass = 'common\models\Activity';

	public function actions()
	{
		$actions = parent::actions();
		unset($actions['index']);
		return $actions;
	}

	public function actionIndex()
	{
		$modelClass = $this->modelClass;

		//TODO: asArray
		return new ActiveDataProvider(
			[
				'query' => $modelClass::find()
				->where(['and',
					['category' => 0],
					['status' => 0],])
				->orderBy('release_at DESC'),//根据发布时间逆序排序
				
				'pagination' => ['pageSize'=>5],
			]
		);
	}
	// public function actionValid()
	// {
	// 	return $customer = Activity::find() //暂时没有问题
	// 	->where(['and',
	// 	['category' => 0],
	// 	['status' => 0],
	// 	])
	// 	->orderBy('release_at DESC')//根据发布时间逆序排序
	// 	// ->asArray() //会破坏fields
	// 	->all();
	// }

	public function actionSearch()
	{

		$request = Yii::$app->request;
		/*
			根据传入的活动名称、类别、状态进行搜索 根据发布时间逆序排序
			其中活动名称是相似检索
		 */
		$sql_name = $request->post('name','');   
		$sql_category = $request->post('category',0);   
		$sql_status = $request->post('status',0);   


		return new ActiveDataProvider(
			[
				'query' => Activity::find() //暂时没有问题
						->where(['and', 
						['like','name',$sql_name],
						['category' => $sql_category],
						['status' => $sql_status],
						])
						->orderBy('release_at DESC'),//根据发布时间逆序排序
				
				'pagination' => ['pageSize'=>5],
			]
		);
		
		// return $customer = Activity::find() //暂时没有问题
		// ->where(['and', 
		// ['like','name',$sql_name],
		// ['category' => $sql_category],
		// ['status' => $sql_status],
		// ])
		// ->orderBy('release_at DESC')//根据发布时间逆序排序
		// // ->asArray() //会破坏fields
		// ->all();
	}
}