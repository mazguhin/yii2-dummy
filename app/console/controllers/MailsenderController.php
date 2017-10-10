<?php
/**
 * Created by PhpStorm.
 * User: Olga
 * Date: 09.08.2017
 * Time: 15:35
 */

namespace console\controllers;

use common\components\MailAPI;
//use common\models\CronLog;
use yii\console\Controller;


/**
 * Консольный обработчик для рассылки писем
 * @package console\controllers
 */
class MailsenderController extends Controller
{
   public function actionSendmail(){
        //if is prod
//        sleep(60);

       $start = microtime(true);
       $mail = new MailAPI();
       $result = $mail->send_batch();

       if($result){
           $message = 'Success send';
       } else {
           $message = 'Failed';
       }


        $time = (microtime(true) - $start);

//        CronLog::add('MailSender::sendmail', 'Отправка писем в очереди', $time, $message);

    }

}