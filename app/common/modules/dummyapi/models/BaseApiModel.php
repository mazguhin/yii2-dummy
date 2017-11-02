<?php

namespace common\modules\dummyapi\models;

use common\components\helpers\DateHelper;

class BaseApiModel extends \yii\db\ActiveRecord
{
    // поля разрешенные для показа
    public static $allowDisplayFields = [];

    // поля разрешенные для редактирования
    public static $allowEditFields = [];

    // поля без шифрования
    public static $filterFields = [];

    // зашифрованные поля
    public static $cryptFilterFields = [];

    /**
     * Фильтрует полученный массив в соответствии с разрешенными полями
     * @param $data
     * @return bool
     */
    public static function filterAllowDisplayFields($data)
    {
        try {
            foreach ($data as $key => $value) {
                if (!in_array($key, static::$allowDisplayFields)) {
                    unset($data[$key]);
                }
            }
        } catch (\Exception $e) {
            return false;
        }

        return $data;
    }
}