<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "event".
 *
 * @property string $id
 * @property string $type
 * @property string $timestamp
 * @property string $content
 */
class Event extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['timestamp'], 'safe'],
            [['content'], 'string'],
            [['type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'timestamp' => 'Timestamp',
            'content' => 'Content',
        ];
    }

    /**
     * @inheritdoc
     * @return \common\queries\EventQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\queries\EventQuery(get_called_class());
    }
}
