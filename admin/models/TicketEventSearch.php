<?php

namespace admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TicketEvent;

/**
 * TicketEventSearch represents the model behind the search form of `common\models\TicketEvent`.
 */
class TicketEventSearch extends TicketEvent
{
    /**
     * {@inheritdoc}
     */

    public function rules()
    {
        return [
            [['id', 'ticket_id', 'user_id', 'activity_id', 'update_at', 'operated_by_admin'], 'integer'],
            [['status','user_name','activity_name'], 'safe'],
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
        $query = TicketEvent::find();

        // add conditions that should always apply here
        $query->joinWith(['activity']);
        $query->joinWith(['user']);
        $query->select("{{%tk_ticket_event}}.*,{{%tk_activity}}.activity_name,{{%tk_user}}.user_name");


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $sort = $dataProvider->getSort(); // 获取yii自动生成的排序规则
        $sort->attributes['activity_name'] = [ // 添加活动名的排序规则
            'asc' => ['{{%tk_activity}}.activity_name' => SORT_ASC],
            'desc' => ['{{%tk_activity}}.activity_name' => SORT_DESC],
        ];
        $sort->attributes['user_name'] = [ // 添加组织者名的排序规则
            'asc' => ['{{%tk_user}}.user_name' => SORT_ASC],
            'desc' => ['{{%tk_user}}.user_name' => SORT_DESC],
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
            '{{%tk_ticket_event}}.id' => $this->id,
            'ticket_id' => $this->ticket_id,
            //'user_name' => $this->user_name,
            //'activity_name' => $this->activity_name,
            'update_at' => $this->update_at,
            'operated_by_admin' => $this->operated_by_admin,
        ]);

        return $dataProvider;
    }
}
