<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 20.09.2017
 * Time: 9:47
 */

namespace common\models;

use Yii;

class App
{

    // коды ошибок и расшифровки
    public static $errors = [
        400 => 'Введены неверные данные',
        404 => 'Запись не найдена',
        405 => 'Метод не поддерживается',
        500 => 'Возникла ошибка на сервере',
    ];

    // коды информационных сообщений и расшифровки
    public static $info = [

    ];

    /**
     * Сформировать ответ с ошибкой
     * @param $error
     * @return array
     */
    public static function makeErrorResponse($error, $text = null)
    {
        $info = isset(self::$errors[$error]) ? self::$errors[$error] : 'Возникла ошибка на сервере';
        $prepare = [
            'success' => false,
            'code' => $error,
            'error' => $info
        ];

        if (isset($text)) {
            $prepare['error'] = $text;
        }

        return $prepare;
    }

    /**
     * Сформировать успешный ответ с информацией
     * @param $data
     * @param null $code
     * @return array
     */
    public static function makeResponse($data, $code = null)
    {
        $response = array_merge($data, ['success' => true]);

        if (!empty($code) && isset(self::$errors[$code])) {
            $response = array_merge($response, [
                'info' => self::$errors[$code],
                'code' => $code
            ]);
        }

        return $response;
    }
}