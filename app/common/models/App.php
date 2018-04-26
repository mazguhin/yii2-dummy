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
        if (is_array($error)) {
            $errorKeys = array_keys($error);
            $errorKey = (!empty($errorKeys[0])) ? $errorKeys[0] : null;
            $info = isset(self::$errors[$errorKey]) ? self::$errors[$errorKey] : 'Возникла ошибка на сервере';
        } else {
            $info = isset(self::$errors[$error]) ? self::$errors[$error] : 'Возникла ошибка на сервере';
            $errorKey = $error;
        }

        $prepare = [
            'success' => false,
            'code' => $errorKey,
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

        if (!empty($code) && isset(self::$info[$code])) {
            $response = array_merge($response, [
                'info' => self::$info[$code],
                'code' => $code
            ]);
        }
        return $response;
    }

    /**
     * Вернуть csrf поле
     * @return string
     */
    public static function csrf_field()
    {
        return '<input type="hidden" name="'.Yii::$app->request->csrfParam.'" value="'.Yii::$app->request->csrfToken.'" />';
    }

    /**
     * Сформировать ответ
     * @param $error
     * @return string
     */
    public static function getJSONResponse($error, $text = null){
        $data = self::makeErrorResponse($error, $text);

        if(!empty($data)){
            header("Content-type: application/json; charset=utf-8");
            echo Json::encode($data);
        }
    }
}