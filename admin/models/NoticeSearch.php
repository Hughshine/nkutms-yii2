<?php

namespace admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Notice;

/**
 * NoticeSearch represents the model behind the search form of `common\models\Notice`.
 */
class NoticeSearch extends Notice
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', ], 'integer'],
            [['title', 'content','status','updated_at','created_at'], 'safe'],
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
        $query = Notice::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);

        if (!empty($this->created_at))
        {
            $query->andFilterCompare('created_at', strtotime(explode('/', $this->created_at)[0]), '>=');//起始时间
            $query->andFilterCompare('created_at', (strtotime(explode('/', $this->created_at)[1]) + 86400), '<');//结束时间
        }
        if (!empty($this->updated_at))
        {
            $query->andFilterCompare('updated_at', strtotime(explode('/', $this->updated_at)[0]), '>=');//起始时间
            $query->andFilterCompare('updated_at', (strtotime(explode('/', $this->updated_at)[1]) + 86400), '<');//结束时间
        }

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
