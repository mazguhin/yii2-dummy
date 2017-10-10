<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.08.2017
 * Time: 10:02
 */

namespace common\components\helpers;


use Yii;

class HashHelper
{
    public static function hash($file) {
        if (empty($file)) {
            return '';
        }

        $file  = Yii::getAlias('@frontend_web') . $file;

        if (empty($file) || !file_exists($file)) {
            return '';
        }

        return hash_file('crc32', $file);
    }
}