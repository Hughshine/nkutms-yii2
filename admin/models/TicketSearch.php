<?php

namespace admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Ticket;

/**
 * TicketSearch represents the model behind the search form of `common\models\Ticket`.
 */
class TicketSearch extends Ticket
{
    public $activity_name;
    public $user_name;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'activity_id',  'serial_number', 'status',], 'integer'],
            [['created_at','activity_name','user_name'],'safe'],//加入这句gridview的搜索框就可以实现了
            //在integer去掉created_at并safe中加入created_at可以按区域搜索
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Ticket::find();

        // add conditions that should always apply here
        $query->joinWith(['activity']);
        $query->joinWith(['user']);
        $query->select("{{%tk_ticket}}.*,{{%tk_activity}}.activity_name,{{%tk_user}}.user_name");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>['defaultOrder'=>['created_at'=>SORT_DESC]],//默认按创建时间的降序排序
        ]);

        $sort = $dataProvider->getSort(); // 获取yii自动生成的排序规则
        $sort->attributes['activity_name'] = [ // 添加活动名的排序规则
            'asc' => ['{{%tk_activity}}.activity_name' => SORT_ASC],
            'desc' => ['{{%tk_activity}}.activity_name' => SORT_DESC],
            'label'=>'活动名称',
        ];
        $sort->attributes['user_name'] = [ // 添加用户名的排序规则
            'asc' => ['{{%tk_user}}.user_name' => SORT_ASC],
            'desc' => ['{{%tk_user}}.user_name' => SORT_DESC],
            'label'=>'持有者名称',
        ];
        $dataProvider->setSort($sort); // 设置排序规则

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            '{{%tk_ticket}}.id' => $this->id,
            //'{{%tk_ticket}}.user_id' => $this->user_id,
            //'activity_id' => $this->activity_id,
            'user_name'=>$this->user_name,
            'activity_name'=>$this->activity_name,
            //'created_at' => $this->created_at,
            'serial_number' => $this->serial_number,
            '{{%tk_ticket}}.status' => $this->status,
        ]);

        //注意这里start_at改变了属性:原本在数据库中是int,但在这变成了字符串型
        //因为日期组件将start修改成了字符串型
        if (!empty($this->created_at))
        {
            $query->andFilterCompare('tk_ticket.created_at', strtotime(explode('/', $this->created_at)[0]), '>=');//起始时间
            $query->andFilterCompare('tk_ticket.created_at', (strtotime(explode('/', $this->created_at)[1]) + 86400), '<');//结束时间
        }
        $query->andFilterWhere(['like', 'activity_name', $this->activity_name]);
        $query->andFilterWhere(['like', 'user_name', $this->user_name]);



        return $dataProvider;
    }
}
