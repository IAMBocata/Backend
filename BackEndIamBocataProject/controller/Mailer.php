<?php

/**
 * Classe per enviar mail.
 * User: yous
 * Date: 24/05/18
 * Time: 19:19
 */
/*
require_once (dirname(__FILE__) . '/../libs/PHPMailer/PHPMailer.php');
require_once (dirname(__FILE__) . '/../libs/PHPMailer/Exception.php');
require_once (dirname(__FILE__) . '/../constants/ConstantsSecurity.php');
*/

class Mailer {

    private $mailer;

    /**
     * Mailer constructor.
     */
    public function __construct() {
        /*
        $this->mailer = new \PHPMailer\PHPMailer(true);
        $this->mailer->isSMTP();

        $this->mailer->Host     = ConstantsSecurity::MAIL_HOSTNAME;
        $this->mailer->Username = ConstantsSecurity::MAIL_USERNAME;
        $this->mailer->Password = ConstantsSecurity::MAIL_PASSWORD;

        $this->mailer->SMTPAuth = true;
        $this->mailer->SMTPSecure = 'tls';
        */
    }

    /**
     * Envia un mail.
     *
     * @param $name
     * @param $mail
     */
    public function sendRegisterMail($name, $mail) {
        /*

        $this->mailer->From = "admin@iambocata.cat";
        $this->mailer->FromName = "IAM Bocata";

        $this->mailer->addAddress($mail);
        $this->mailer->Subject = "Benvingut a IAM Bocata";
        $this->mailer->Body = "Hola $name, \n\nBenvingut a IAM Bocata!! \n\nIAM Bocata.";

        return $this->mailer->send(); */

        // the message
        $msg = "Hola $name!\n\nBenvingut/Benvinguda a IAM Bocata! Aqui va un mensaje guapo".
            "\n\nAtentament,\nInstitut Ausiàs March\nhttp://iam.cat\n";

        // use wordwrap() if lines are longer than 70 characters
        $msg = wordwrap($msg,70);

        $headers = 'From: IAM Bocata <admin@iambocata.cat>';

        // send email
        mail($mail, "Benvingut a IAM Bocata!", $msg, $headers);
    }

}