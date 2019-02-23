<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Organizer;

/*除了注释一些不需要的项之外，不敢动。。。
 *
 * */
/**
 * OrganizerSearch represents the model behind the search form of `common\models\Organizer`.
 */
class OrganizerSearch extends Organizer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //修改记录，在此加了credential
            [['id', 'category', 'status', 'signup_at','updated_at','credential',], 'integer'],
            [['org_name', 'auth_key', 'password', 'password_reset_token','credential', 'wechat_id', 'access_token'], 'safe'],
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
        $query = Organizer::find();

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
            'category' => $this->category,
            'status' => $this->status,
            //修改记录：添加了下一行
            'credential' => $this->credential,
            //'signup_time' => $this->signup_time,
        ]);

        //修改记录：将不需要的行注释掉
        $query->andFilterWhere(['like', 'org_name', $this->org_name])
            /*->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password', $this->password])*/
            ->andFilterWhere(['like', 'wechat_id', $this->wechat_id])
            /*->andFilterWhere(['like', 'access_token', $this->access_token])*/;

        return $dataProvider;
    }
}
