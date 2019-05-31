<?php

namespace admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;
/*同理，没怎么敢动
 * 将不需要的行注释掉
 *
 * */
/**
 * UserSearch represents the model behind the search form of `common\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at', 'category', ], 'integer'],
            [['user_name', 'auth_key', 'password', 'wechat_id', 'access_token','email','credential'], 'safe'],
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
    public function search($params)
    {
        $query = User::find();

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
            'email' => $this->email,
            //'signup_at' => $this->signup_at,
            //'updated_at' => $this->updated_at,
            'category' => $this->category,
            //'credential' => $this->credential,
        ]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name])
            /*->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password', $this->password])*/
            ->andFilterWhere(['like', 'wechat_id', $this->wechat_id])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'credential', $this->credential])
            /*->andFilterWhere(['like', 'access_token', $this->access_token])*/;

        return $dataProvider;
    }
}
