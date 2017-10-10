<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 27.12.2016
 * Time: 22:59
 */
namespace common\components\helpers;

/**
 * Помошник с операциями по тексту
 * @package common\components\helpers
 */
class TextHelper
{
    /**
     * Из кириллицы в латиницу
     * @param $str
     * @return mixed
     */
    public static function translate($str){
        $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', ' ', ',', '.');
        $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya', '_', '_i', '');
        return str_replace($rus, $lat, $str);
    }

    /**
     * Очистить строку от спец символов
     * @param $string
     * @return mixed
     */
    public static function clearUrl($string){
        $string = mb_strtolower(TextHelper::translate($string));

        return preg_replace('/[^a-z0-9_-]/', '', $string);
    }

    /**
     * Убрать из строки с телефоном лишние символы
     * @param string $phone Телефон
     * @return string
     */
    public static function preparePhone($phone){
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if(strlen($phone)===11) {
            return substr($phone, 1);
        }

        return $phone;
    }

    /**
     * Сгенерировать случайную строку
     * @param int $length
     * @return string
     */
    public static function generateRandomString($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghklmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
}