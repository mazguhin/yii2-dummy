<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mail".
 *
 * @property string $id
 * @property integer $participant_id
 * @property string $message
 * @property string $created_at
 * @property integer $sent
 * @property string $sent_at
 * @property string $title
 * @property integer $approved
 * @property integer $locked
 * @property integer $ad_id
 * @property integer $type_id
 *
 * @property Participant $participant Участник
 */
class Mail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['participant_id', 'sent', 'approved', 'locked', 'type_id'], 'integer'],
            [['message'], 'required'],
            [['message'], 'string'],
            [['created_at', 'sent_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'participant_id' => 'ID участника',
            'message' => 'Сообщение',
            'created_at' => 'Создано',
            'sent' => 'Sent',
            'sent_at' => 'Sent At',
            'title' => 'Заголовок',
            'approved' => 'Одобрен',
            'locked' => 'Заблокирован',
            'type_id' => 'ID типа',
        ];
    }

    /**
     * @inheritdoc
     * @return \common\queries\MailQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\queries\MailQuery(get_called_class());
    }

    /**
     * Участник
     * @return \yii\db\ActiveQuery
     */
    public function getParticipant(){
        return $this->hasOne(Participant::className(), ['id' => 'participant_id']);
    }
}
