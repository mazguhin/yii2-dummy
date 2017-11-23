<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CronLog;

/**
 * CronLogSearch represents the model behind the search form about `common\models\CronLog`.
 */
class CronLogSearch extends CronLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at'], 'integer'],
            [['process_title', 'process_description', 'message'], 'safe'],
            [['time'], 'number'],
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
        $query = CronLog::find();

        $query->orderBy('id desc');

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
            'created_at' => $this->created_at,
            'time' => $this->time,
        ]);

        $query->andFilterWhere(['like', 'process_title', $this->process_title])
            ->andFilterWhere(['like', 'process_description', $this->process_description])
            ->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}
