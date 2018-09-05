<?php

/**
 * Classe per gestionar usuaris.
 * User: yous
 * Date: 16/04/18
 * Time: 16:45
 */

include_once (dirname(__FILE__) . '/../bbdd/UserDao.php');
include_once (dirname(__FILE__) . '/../constants/ConstantsPaths.php');
include_once (dirname(__FILE__) . '/qr/QRGenerator.php');
require_once (dirname(__FILE__) . '/Mailer.php');

class UserManager {

    private $idUser;
    private $userDao;
    private $mailer;

    /**
     * UserManager constructor.
     * @param $idUser
     */
    public function __construct($idUser) {
        $this->idUser = $idUser;
        $this->userDao = new UserDao();
        $this->mailer = new Mailer();
    }

    /**
     * Afegeix els diners passats com a paràmetre al usuari indicat.
     *
     * @param $moneyAdded
     * @param $idUserApplicant
     * @return bool
     */
    public function addMoney($moneyAdded, $idUserApplicant) {

        $userApplicant = $this->userDao->findThisUser($idUserApplicant);

        if ($userApplicant->getPermissionLevel() < 2 && $moneyAdded <= 0) {
            return false;
        }

        $this->userDao->addMoneyToThisUser($this->idUser, $moneyAdded);
        return true;
    }

    /**
     * Retorna l'usuari
     *
     * @return null|User
     */
    function getThisUser() {
        return $this->userDao->findThisUser($this->idUser);
    }

    /**
     * Login
     *
     * @param $mail
     * @param $password
     * @return null|User
     */
    public function login($mail, $password) {
        return $this->userDao->loginAndGetData($mail, $password);
    }

    /**
     * Habilita un usuari.
     */
    public function enable() {
        $this->userDao->enable($this->idUser);
    }

    /**
     * Deshabilita un usuari.
     */
    public function disable() {
        $this->userDao->disable($this->idUser);
    }

    /**
     * Retorna una llista amb tots els usuaris.
     *
     * @return array
     */
    public function getAllUsers() {
        return $this->userDao->findAll();
    }

    /**
     * Crea un nou usuari.
     *
     * @param User $u
     * @return bool
     */
    public function createUser(User $u) {

        $userApplicant = $this->getThisUser();

        if ($userApplicant == null || $userApplicant->getPermissionLevel() < 2) {
            return false;
        }

        $u->setPhotoUrl(ConstantsPaths::DEFAULT_IMAGE_USERS);

        $this->userDao->insertUser($u);

        $u = $this->userDao->completeThisUser($u);

        if ($u == null) {
            return false;
        }

        $pathQr = QRGenerator::generateQR($u->getId());
        $u->setQrPhotoUrl($pathQr);

        $this->userDao->addQrColumnToThisUser($u);

        $this->mailer->sendRegisterMail($u->getName(), $u->getMail());

        return true;
    }

    /**
     * Canvia la password a un usuari.
     *
     * @param $oldPassword
     * @param $newPassword
     * @return bool|null
     */
    public function changePassword($oldPassword, $newPassword) {

        $userApplicant = $this->getThisUser();

        $loginUser = $this->userDao->loginAndGetData($userApplicant->getMail(), $oldPassword);

        if ($loginUser == null) {
            return null;
        }

        $this->userDao->changePassword($loginUser, password_hash($newPassword, PASSWORD_DEFAULT));

        return true;
    }

    /**
     * Retorna el nombre d'usuaris del sistema.
     *
     * @return array
     */
    public function numberOfUsers() {
        return $this->userDao->numberOfUsers();
    }

    /**
     * Canvia la password a un usuari.
     *
     * @param $newPassword
     * @param $idUserApplicant
     * @return bool|mysqli_result
     */
    public function resetPassword($newPassword, $idUserApplicant) {

        if ((new UserManager($idUserApplicant))->getThisUser()->getPermissionLevel() < 2) {
            return false;
        }

        return $this->userDao->changePassword($this->getThisUser(), password_hash($newPassword, PASSWORD_DEFAULT));
    }

}