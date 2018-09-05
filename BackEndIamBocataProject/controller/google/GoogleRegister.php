<?php

/**
 * Classe per gestionar registres de usuaris de google.
 * User: yous
 * Date: 13/04/18
 * Time: 17:33
 */

require_once( dirname(__FILE__) . '/../qr/QRGenerator.php' );
require_once (dirname(__FILE__) . '/../Mailer.php');

class GoogleRegister {

    private $user;
    private $userDao;

    /**
     * GoogleRegister constructor.
     * @param User $user
     * @param UserDao $userDao
     */
    public function __construct(User $user, UserDao $userDao) {
        $this->user = $user;
        $this->userDao = $userDao;
    }

    /**
     * Crea un nou usuari de google a la BBDD
     *
     * @return null|User
     */
    public function register() {
        $this->userDao->insertGoogleUser($this->user);
        $this->user = $this->userDao->completeThisGoogleUser($this->user);
        $path = $this->generateQR($this->user->getId());
        $this->user->setQrPhotoUrl($path);
        $this->userDao->addQrColumnToThisUser($this->user);
        (new Mailer())->sendRegisterMail($this->user->getName(), $this->user->getMail());
        return $this->user;
    }

    /**
     * Genera un QR.
     *
     * @param $id
     * @return string
     */
    private function generateQR($id) {
        return QRGenerator::generateQR($id);
    }

}

?>