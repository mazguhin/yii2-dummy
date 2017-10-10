<?php

namespace common\components;

/**
 * Created by PhpStorm.
 * User: skripov.in
 * Date: 21.07.2017
 * Time: 13:58
 */
class Formatter extends \yii\i18n\Formatter
{
    /**
     * Formats the value as an HTML-encoded plain text with newlines converted into breaks.
     * @param string $value the value to be formatted.
     * @return string the formatted result.
     */
    public function asJson($value)
    {
        if ($value === null) {
            return $this->nullDisplay;
        }

        return \json_decode($value);
    }
}