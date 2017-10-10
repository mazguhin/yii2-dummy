<?php
/**
 * Created by PhpStorm.
 * User: skripov.in
 * Date: 22.09.2017
 * Time: 10:17
 */

namespace common\components\helpers;

use Yii;

class UtilHelper
{
    /**
     * Является ли окружение продом
     * @return bool
     */
    public static function isProd(){
        return !file_exists(Yii::getAlias('@common') . '/config/main-local.php');
    }
}