<?php

/**
 * Classe per enviar notificacions.
 * User: yous
 * Date: 22/05/18
 * Time: 16:06
 */

include_once (dirname(__FILE__) . '/../constants/ConstantsSecurity.php');

class Notificator {

    /**
     * Envia notificacions.
     *
     * @param $token
     * @param $textNoti
     */
    public static function notify($token, $textNoti) {

        $msg = array (
            'body' 	=> $textNoti,
            'title'	=> 'IAM Bocata');

        $fields = array (
            'to'		=> $token,
            'notification'	=> $msg );

        $headers = array (
            'Authorization: key=' . ConstantsSecurity::GOOGLE_API_KEY,
            'Content-Type: application/json' );

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
    }

}

?>