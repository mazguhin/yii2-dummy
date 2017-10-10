<?php
/**
 * Created by PhpStorm.
 * User: Olga
 * Date: 07.08.2017
 * Time: 15:51
 */

namespace common\components;

use common\models\Event;
use common\models\MailTypes;
use common\models\Participant;
use common\models\Mail;
use Yii;
use yii\db\Exception;
use yii\db\connection;


/**
 * @package common\components
 */
class MailAPI
{
    const TIMESTAMP_FORMAT = 'Y-m-d H:i:s';
    const DATE_FORMAT = 'Y-m-d';

    private static $from_name = 'Мисс Казино 2017';
    private static $from_email = 'no-reply@regalrewards.ru';

    protected $errors = array(
        401 => 'Не найден почтовый шаблон',
        402 => 'Письмо не утверждено для отправки'
    );

    protected static $hard_limit = 1000;

    protected function get_template_path(){
//        return $_SERVER['DOCUMENT_ROOT'] . '/../../common/mail/MailTemplates/';
        return Yii::getAlias('@common').DIRECTORY_SEPARATOR . 'mail/MailTemplates/';
    }

    //получить шаблон по его названию
    protected function get_template_by_name($name_template){

        if(file_exists($template_path = $this->get_template_path() . $name_template . '.phtml')){
//        if(file_exists($template_path = $this->get_template_path() . 'main_template.php')){
            return $template_path;
        }else {
            throw new \yii\base\Exception(401);
        }
    }

    //$type - объект модели MailTypes или код шаблона
    public function get_message_from_template($type, $fields){
        try{
            //если передали название, находим соответствующий тип в таблице
            if(is_string($type) || is_int($type)){
                $type = MailTypes::findOne(['id' => $type]);
                if(empty($type)){
                    return false;
                }
            }
            $fields["template_name"] = $type->template;
            $template_path = $this->get_template_by_name($type->template);
            foreach ($fields as $k => $v) {
                ${$k} = $v;
            }
            ob_start();
            @include($template_path);
            return ob_get_clean();
        }catch (Exception $e) {
            return false;
        }

        return false;
    }

    //сгенерировать письмо для отправки и сохранить в БД
    //$participant_id - ид участника
    //$type - ид типа шаблона сообщения (может быть, переделать на ид типа?)
    //$fields - поля для шаблона, которые необходимо заменить
    public function generate_mail($participant_id, $type_id, $fields){
        if(empty($participant_id) || empty($type_id)){
            return false;
        }

        $mail_type = MailTypes::findOne(['id' => $type_id]);
        if(empty($mail_type)){
            return false;
        }

        //проверка, что такое письмо уже было зарегестрировано:
        $mail = Mail::findOne(['participant_id' => $participant_id, 'type_id'=> $type_id]);
        if(empty($mail)){
            $new_mail = new Mail();

//            if($mail_type->custom_text){
//                $fields = array_merge(["custom_text"=>$mail_type->custom_text], $fields);
//            }

            if(!array_key_exists("name", $fields)){
                $participant = Participant::findOne(['id' => $participant_id]);
                $fields = array_merge(["name"=>$participant->first_name], $fields);
            }

            $new_mail->message = $this->get_message_from_template($mail_type, $fields);
            $new_mail->type_id = $type_id;
            $new_mail->participant_id = $participant_id;
            $new_mail->created_at = time();
            $new_mail->title = $mail_type->title;
            $new_mail->approved = $mail_type->auto_approve;

            try{
                $new_mail->save(false);
            }catch (Exception $e){
                return $e;
            }
        }

        return true;
    }

    /**
     * @param $id
     * @param array $overwrite_fields
     * @param bool $set_sent_status
     * @return bool
     * @throws \yii\base\Exception
     */
    public function send($id, $overwrite_fields = array(), $set_sent_status = true)
    {
        $mail = Mail::findOne(['id' => $id]);

        if($mail->approved!=1){
            return false;
        }

        if (empty($mail)) {
            return false;
        }

        if (!isset($overwrite_fields['email']) && !empty($mail->sent)) {
            return false;
        }

        $to_name = '';
        if (!empty($mail->participant_id)) {
            $participant = Participant::findOne(['id' => $mail->participant_id]);
            if (!empty($participant)) {
                $to_name = $participant->first_name;
            }
        }

        if (is_array($overwrite_fields)) {
            foreach ($overwrite_fields as $k => $v) {
                $mail[$k] = $v;
            }
        }

        $smtp_header = array(
            'category' => $mail->type_id,
        );

        $params = array(
            'to'        => $participant->email,
            'toname'    => $to_name,
            'from'      => self::$from_email,
            'fromname'  => self::$from_name,
            'subject'   => $mail->title,
            'html'      => $mail->message,
            'x-smtpapi' => json_encode($smtp_header),
        );

        $curl_handler = curl_init(Yii::$app->params['mail']['sendgrid_endpoint']);
        curl_setopt($curl_handler, CURLOPT_SSLVERSION, 6);
        curl_setopt($curl_handler, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . Yii::$app->params['mail']['sendgrid_api_key']));
        curl_setopt($curl_handler, CURLOPT_POST, true);
        curl_setopt($curl_handler, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl_handler, CURLOPT_HEADER, false);
        curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl_handler);
        curl_close($curl_handler);

        $response = json_decode($response, true);

        if ($set_sent_status) {
            $mail['sent'] = $response['message'] == 'success';
            $mail['sent_at'] = date(self::TIMESTAMP_FORMAT);
            $mail->save();
        }

        if (empty($response)) {
            $event = new Event();
            $event->type = 'mail-send-error';
            $event->content = array('id' => $id);
            $event->save();
        }

        return $response['message'] == 'success';
    }

    public function send_batch($limit = 100)
    {
        $mails = Mail::find()->Where('locked=0 and approved = 1 and sent = 0')->limit(min(self::$hard_limit, intval($limit)))->all();

        if(!empty($mails)){

            foreach ($mails as $mail) {
                $mail->locked = true;
                $mail->save();
            }

            foreach ($mails as $mail) {
                $this->send($mail->id);
            }
        }

        return true;
    }

    /**
     * Отписаться от рассылки
     * @param string $email Адрес ящика
     * @return mixed
     */
    public static function unsubscribe($email){
        $params = [
            'api_user' => Yii::$app->params['mail']['api_user'],
            'api_key' => Yii::$app->params['mail']['api_pass'],
            'email' => $email
        ];

        $curl_handler = curl_init(Yii::$app->params['mail']['sendgrid_unsubscibe']);
        curl_setopt($curl_handler, CURLOPT_SSLVERSION, 6);
        curl_setopt($curl_handler, CURLOPT_POST, true);
        curl_setopt($curl_handler, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl_handler, CURLOPT_HEADER, false);
        curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl_handler);
        curl_close($curl_handler);

        if($response){
            $response = json_decode($response, true);

            if(isset($response['message']) && $response['message']){
                return $response['message'];
            }
        }

        return false;
    }

    /**
     * Посылка письма с купоном
     * @param $coupon
     * @param int $type
     * @return bool
     */
    public function sendCoupon($coupon, $type = 3)
    {
        if (empty($coupon)) {
            return false;
        }

        if (empty($coupon->email)) {
            return false;
        }

        $smtp_header = array(
            'category' => $type,
        );

        $params = array(
            'to'        => $coupon->email,
            'from'      => self::$from_email,
            'fromname'  => "Сайт casinosochi.ru",
            'subject'   => "Сочи Казино и Курорт: купон на розыгрыш автомобиля",
            'html'      => $this->get_message_from_template($type, [$coupon]),
            'x-smtpapi' => json_encode($smtp_header),
        );

        $curl_handler = curl_init(Yii::$app->params['mail']['sendgrid_endpoint']);
        curl_setopt($curl_handler, CURLOPT_SSLVERSION, 6);
        curl_setopt($curl_handler, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . Yii::$app->params['mail']['sendgrid_api_key']));
        curl_setopt($curl_handler, CURLOPT_POST, true);
        curl_setopt($curl_handler, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl_handler, CURLOPT_HEADER, false);
        curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl_handler);
        curl_close($curl_handler);

        $response = json_decode($response, true);
        return true;
    }
}