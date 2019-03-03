<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use admin\models\TkActivity;

/**
 * TkActivitySearch represents the model behind the search form of `common\models\TkActivity`.
 */
class TkActivitySearch extends TkActivity
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category', 'status', 'max_people', 'current_people', 'start_at', 'end_at', 'release_by', 'release_at', 'updated_at'], 'integer'],
            [['activity_name', 'introduction','org_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
    /*面向CSDN编程结果如下：
     * 原理未知，用于将release_by替换成对应的组织者名字
     * */
    public function search($params)
    {
        $query = TkActivity::find();

        // add conditions that should always apply here
        $query->joinWith(['organizer']); 
        $query->select("{{%tk_activity}}.*,{{%tk_organizer}}.org_name"); 

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $sort = $dataProvider->getSort(); // 获取yii自动生成的排序规则
        $sort->attributes['org_name'] = [ // 添加用户名的排序规则
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
            'id' => $this->id,
            '{{%tk_activity}}.category' => $this->category,
            '{{%tk_activity}}.status' => $this->status,
            //'max_people' => $this->max_people,
            //'people_corrent' => $this->people_corrent,
            //'time_start' => $this->time_start,
            //'time_end' => $this->time_end,
            //'release_by' => $this->release_by,
            'org_name' => $this->org_name, 
            //'time_release' => $this->time_release,
            //'time_lastedit' => $this->time_lastedit,
        ]);

        $query->andFilterWhere(['like', 'activity_name', $this->activity_name])
            ->andFilterWhere(['like', 'introduction', $this->introduction]);

        return $dataProvider;
    }
}
