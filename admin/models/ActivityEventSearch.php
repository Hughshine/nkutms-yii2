<?php

namespace admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ActivityEvent;

/**
 * ActivityEventSearch represents the model behind the search form of `common\models\ActivityEvent`.
 */
class ActivityEventSearch extends ActivityEvent
{
    /**
     * {@inheritdoc}
     */

    public function rules()
    {
        return [
            [['id', 'organizer_id', 'activity_id', 'update_at', 'operated_by_admin'], 'integer'],
            [['status','activity_name','org_name'], 'safe'],
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
        $query = ActivityEvent::find();

        // add conditions that should always apply here
        $query->joinWith(['activity']);
        $query->joinWith(['organizer']);
        $query->select("{{%tk_activity_event}}.*,{{%tk_activity}}.activity_name,{{%tk_organizer}}.org_name");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $sort = $dataProvider->getSort(); // 获取yii自动生成的排序规则
        $sort->attributes['activity_name'] = [ // 添加活动名的排序规则
            'asc' => ['{{%tk_activity}}.activity_name' => SORT_ASC],
            'desc' => ['{{%tk_activity}}.activity_name' => SORT_DESC],
        ];
        $sort->attributes['org_name'] = [ // 添加组织者名的排序规则
            'asc' => ['{{%tk_organizer}}.org_name' => SORT_ASC],
            'desc' => ['{{%tk_organizer}}.org_name' => SORT_DESC],
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
            '{{%tk_activity_event}}.id' => $this->id,
            //'organizer_id' => $this->organizer_id,
            //'activity_id' => $this->activity_id,
            'update_at' => $this->update_at,
            'activity_name' => $this->activity_name,
            'org_name' => $this->org_name,
            'operated_by_admin' => $this->operated_by_admin,
        ]);

        $query->andFilterWhere(['like', 'activity_name', $this->activity_name])
            ->andFilterWhere(['like', 'org_name', $this->org_name]);

        return $dataProvider;
    }
}
