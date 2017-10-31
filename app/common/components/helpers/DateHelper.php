<?php

namespace common\components\helpers;

/**
 * Хелпер для работы с датами
 * @package common\components\helpers
 */
class DateHelper
{
    /**
     * Возвращает возраст по дате рождения
     * @param $bdate
     * @return null|string
     */
    public static function getAge($bdate)
    {
        $date = null;
        if (!empty($bdate)) {
            try {
                $diff = (new \DateTime())->diff(new \DateTime($bdate));
                $date = sprintf("%d", $diff->y);
            } catch (\Exception $e) {

            };
        }

        return $date;
    }
}