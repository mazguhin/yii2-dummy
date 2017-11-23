<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cron_log".
 *
 * @property integer $id
 * @property integer $created_at
 * @property string $process_title
 * @property string $process_description
 * @property string $message
 * @property string $time
 */
class CronLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cron_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'process_title', 'process_description', 'message', 'time'], 'required'],
            [['created_at'], 'integer'],
            [['message'], 'string'],
            [['time'], 'number'],
            [['process_title', 'process_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Дата создания',
            'process_title' => 'Название процесса',
            'process_description' => 'Описание процесса',
            'message' => 'Сообщение',
            'time' => 'Время выполнения',
        ];
    }

    /**
     * @inheritdoc
     * @return \common\queries\CronLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\queries\CronLogQuery(get_called_class());
    }

    /**
     * Вставить запись о кроне
     * @param $title
     * @param $description
     * @param $time
     * @param $message
     */
    public static function add($title, $description, $time, $message){
        $model = new self;
        $model->created_at = time();
        $model->process_title = $title;
        $model->process_description = $description;
        $model->time = $time;
        $model->message = $message;

        $model->save(false);
    }
}
