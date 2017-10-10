<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mail_types".
 *
 * @property integer $id
 * @property string $name
 * @property string $template
 * @property string $title
 * @property integer $auto_approve
 * @property string $comment
 * @property string $custom_text
 */
class MailTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mail_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'template', 'title', 'auto_approve'], 'required'],
            [['auto_approve'], 'integer'],
            [['name', 'template'], 'string', 'max' => 100],
            [['title', 'comment'], 'string', 'max' => 255],
//            ['custom_text', 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название шаблона',
            'template' => 'Файл шаблона',
            'title' => 'Заголовок письма',
            'auto_approve' => 'Утверждено по умолчанию',
            'comment' => 'Пояснения',
//            'custom_text' => 'Дополнительный текст'
        ];
    }

    /**
     * @inheritdoc
     * @return \common\queries\MailTypesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\queries\MailTypesQuery(get_called_class());
    }
}
