<?php

namespace admin\models;

use common\models\Activity;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ActivitySearch represents the model behind the search form of `common\models\Activity`.
 */
class ActivitySearch extends Activity
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category', 'status', 'max_people', 'current_people', 'release_by', 'release_at', 'updated_at'], 'integer'],
            [['activity_name', 'introduction','org_name','start_at', 'end_at'], 'safe'],
            //在integer去掉start_at和end_at并safe中加入start_at和end_at可以按时间区间搜索
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
    /*面向C S D N编程结果如下：
     * 原理未知，用于将release_by替换成对应的组织者名字
     * */
    public function search($params)
    {
        $query = Activity::find();

        // add conditions that should always apply here
        $query->joinWith(['releaseBy']);
        $query->select("{{%tk_activity}}.*,{{%tk_organizer}}.org_name"); 

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>['defaultOrder'=>['release_at'=>SORT_DESC]],//默认按发布时间的降序排序
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
            //'people_current' => $this->people_current,
            //'time_start' => $this->time_start,
            //'time_end' => $this->time_end,
            //'release_by' => $this->release_by,
            'org_name' => $this->org_name, 
            //'time_release' => $this->time_release,
            //'time_lastedit' => $this->time_lastedit,
        ]);

        //注意这里start_at改变了属性:原本在数据库中是int,但在这变成了字符串型
        //因为日期组件将start修改成了字符串型
        if (!empty($this->start_at))
        {
            $query->andFilterCompare('start_at', strtotime(explode('/', $this->start_at)[0]), '>=');//起始时间
            $query->andFilterCompare('start_at', (strtotime(explode('/', $this->start_at)[1]) + 86400), '<');//结束时间
        }
        if (!empty($this->end_at))
        {
            $query->andFilterCompare('end_at', strtotime(explode('/', $this->end_at)[0]), '>=');//起始时间
            $query->andFilterCompare('end_at', (strtotime(explode('/', $this->end_at)[1]) + 86400), '<');//结束时间
        }
        $query->andFilterWhere(['like', 'activity_name', $this->activity_name])
            ->andFilterWhere(['like', 'introduction', $this->introduction]);
        return $dataProvider;
    }
}
