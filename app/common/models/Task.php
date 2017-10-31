<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "task".
 *
 * @property integer $id
 * @property string $title
 */
class Task extends \common\modules\dummyapi\models\BaseApiModel
{
    // поля разрешенные для показа
    public static $allowDisplayFields = [
        'id',
        'title',
    ];

    // поля разрешенные для редактирования
    public static $allowEditFields = [
        'title',
    ];

    // поля без шифрования
    public static $filterFields = [
        'id',
        'title',
    ];

    // зашифрованные поля
    public static $cryptFilterFields = [

    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task';
    }

    public function formName()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['id'], 'integer'],
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
            'title' => 'Title',
        ];
    }

    /**
     * @inheritdoc
     * @return \common\queries\TaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\queries\TaskQuery(get_called_class());
    }
}
