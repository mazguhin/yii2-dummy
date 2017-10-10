<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Mail;

/**
 * MailSearch represents the model behind the search form about `common\models\Mail`.
 */
class MailSearch extends Mail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'participant_id', 'sent', 'approved', 'locked', 'type_id'], 'integer'],
            [['email', 'message', 'created_at', 'sent_at', 'title'], 'safe'],
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
    public function search($params, $withParticipants = null)
    {
        $query = Mail::find();

        // подтягиваем участников
        if ($withParticipants) {
            $query = $query->with('participant');
        }

        $query->orderBy('id desc');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ],
            ]
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
            'participant_id' => $this->participant_id,
            'created_at' => $this->created_at,
            'sent' => $this->sent,
            'sent_at' => $this->sent_at,
            'approved' => $this->approved,
            'locked' => $this->locked,
            'type_id' => $this->type_id,
        ]);

        $query->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
