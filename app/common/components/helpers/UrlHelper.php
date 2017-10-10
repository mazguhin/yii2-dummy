<?php
/**
 * Created by PhpStorm.
 * User: skripov.in
 * Date: 11.09.2017
 * Time: 12:46
 */

namespace common\components\helpers;

/**
 * Сниппеты для работы с сcылками
 * @package common\components\helpers
 */
class UrlHelper
{
    /**
     * Получить ссылку на файл с хешем
     * @param string $url ссылка на файл
     * @return string
     */
    public static function getFileUrl($url){
        $hash = HashHelper::hash($url);

        return $url . ($hash ? "?v={$hash}" : '');
    }
}