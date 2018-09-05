<?php

/**
 * Classe per accedir a les dades dels usuaris a la BBDD.
 *
 * User: yous
 * Date: 6/04/18
 * Time: 17:29
 */

include_once('Dao.php');

class UserDao extends Dao {

    /**
     * UserDao constructor.
     */
    public function UserDao() {
        parent::Dao();
    }

    /**
     * Inserta un nou usuari a la BBDD.
     *
     * @param User $user
     */
    public function insertUser(User $user) {

        $query = "INSERT INTO USERS (NAME, PASSWORD, MAIL, PHOTOPATH, REGISTER_DATE, MONEY, PERMISSION_LEVEL, " .
            " ENABLED, IS_GOOGLE_USER) VALUES ('" . $user->getName() . "', '" .$user->getEncryptedPassword() .
            "', '" . $user->getMail() . "', '" . $user->getPhotoUrl() . "', NOW(), 0, ".
            $user->getPermissionLevel() . ", TRUE, FALSE)";

        parent::query($query);
    }

    /**
     * Completa amb les dades de la bbdd el usuari passat com a paràmetre.
     *
     * @param User $user
     * @return null|User
     */
    public function completeThisUser(User $user) {

        $query = "SELECT * FROM USERS WHERE NAME = '" . $user->getName() . "'";

        $response = parent::query($query);

        if ($userResponse = $response->fetch_assoc()) {
            $user->setId($userResponse['ID_USER']);
            $user->setRegisterDate($userResponse['REGISTER_DATE']);
            $user->setMoney($userResponse['MONEY']);
            $user->setPermissionLevel($userResponse['PERMISSION_LEVEL']);
            $user->setIsEnabled(true);
            return $user;
        }

        return null;
    }

    /**
     * Inserta un nou usuari de Google a la BBDD
     *
     * @param User $user
     */
    public function insertGoogleUser(User $user) {

        $query = "INSERT INTO USERS (NAME, MAIL, GOOGLE_ID, PHOTOPATH, REGISTER_DATE, MONEY, PERMISSION_LEVEL, ENABLED, IS_GOOGLE_USER) " .
            " VALUES ('" . $user->getName() . "', '" . $user->getMail() . "', '" . $user->getGoogleId() . "', '" . $user->getPhotoUrl() .
            "', NOW(), 0.00, 1, TRUE, TRUE)";
        
        parent::query($query);
    }

    /**
     * Completa amb les dades de la bbdd el usuari de Google passat com a paràmetre.
     *
     * @param User $user
     * @return null|User
     */
    public function completeThisGoogleUser(User $user) {

        $query = "SELECT * FROM USERS WHERE GOOGLE_ID = '" . $user->getGoogleId() . "'";

        $response = parent::query($query);

        if ($userResponse = $response->fetch_assoc()) {
            $user->setId($userResponse['ID_USER']);
            $user->setRegisterDate($userResponse['REGISTER_DATE']);
            $user->setMoney($userResponse['MONEY']);
            $user->setPermissionLevel($userResponse['PERMISSION_LEVEL']);
            $user->setIsEnabled(true);
            return $user;
        }

        return null;
    }

    /**
     * Afegeix el camp de QRPHOTO_PATH a la BBDD de l'usuari passat com a paràmetre
     *
     * @param User $user
     */
    public function addQrColumnToThisUser(User $user) {

        $query = "UPDATE USERS SET QRPHOTOPATH='" . $user->getQrPhotoUrl() . "' WHERE ID_USER=" . $user->getId();

        parent::query($query);
    }

    /**
     * Comprova que es pot fer login i retorna les seves dades en cas de que sigui positiu.
     *
     * @param $mail
     * @param $password
     * @return null|User
     */
    public function loginAndGetData($mail, $password) {

        $query = "SELECT * FROM USERS WHERE MAIL LIKE '" . $mail . "' AND ENABLED=1";

        $response = parent::query($query);

        if ($userResponse = $response->fetch_assoc()) {

            $userObject = new User();
            $userObject->setName($userResponse['NAME']);
            $userObject->setMail($userResponse['MAIL']);
            $userObject->setPhotoUrl($userResponse['PHOTOPATH']);
            $userObject->setQrPhotoUrl($userResponse['QRPHOTOPATH']);
            $userObject->setPermissionLevel($userResponse['PERMISSION_LEVEL']);
            $userObject->setId($userResponse['ID_USER']);
            $userObject->setMoney($userResponse['MONEY']);
            $userObject->setRegisterDate($userResponse['REGISTER_DATE']);

            if ( strcasecmp($userResponse['IS_GOOGLE_USER'], "0") ) {
                $userObject->setIsGoogleUser(true);
            } else {
                $userObject->setIsGoogleUser(false);
            }

            if (strcasecmp($userResponse['ENABLED'], "0")) {
                $userObject->setIsEnabled(true);
            } else {
                $userObject->setIsEnabled(false);
            }

            if (password_verify($password, $userResponse['PASSWORD'])) {
                return $userObject;
            }
        }

        return null;
    }

    /**
     * Retorna tots els usuaris de la BBDD.
     *
     * @return array
     */
    public function findAll() {

        $arrayOfUsers = [];
        $index = 0;

        $query = "SELECT * FROM USERS";

        $response = parent::query($query);

        while ($userResponse = $response->fetch_assoc()) {

            $user = new User();

            $user->setId($userResponse['ID_USER']);
            $user->setPhotoUrl($userResponse['PHOTOPATH']);
            $user->setQrPhotoUrl($userResponse['QRPHOTOPATH']);
            $user->setMail($userResponse['MAIL']);
            $user->setName($userResponse['NAME']);
            $user->setGoogleId($userResponse['GOOGLE_ID']);
            $user->setRegisterDate($userResponse['REGISTER_DATE']);
            $user->setMoney($userResponse['MONEY']);
            $user->setPermissionLevel($userResponse['PERMISSION_LEVEL']);

            if (strcasecmp($userResponse['ENABLED'], "0")) {
                $user->setIsEnabled(true);
            } else {
                $user->setIsEnabled(false);
            }

            if (strcasecmp($userResponse['IS_GOOGLE_USER'], "0")) {
                $user->setIsGoogleUser(true);
            } else {
                $user->setIsGoogleUser(false);
            }

            $arrayOfUsers[$index] = $user;

            $index++;
        }

        return $arrayOfUsers;
    }

    /**
     * Retorna l'usuari que té la ID passada com a paràmetre
     *
     * @param $id
     * @return null|User
     */
    public function findThisUser($id) {

        $query = "SELECT * FROM USERS WHERE ID_USER=" . $id;

        $response = parent::query($query);

        if ($userResponse = $response->fetch_assoc()) {

            $user = new User();
            $user->setId($userResponse['ID_USER']);
            $user->setGoogleId($userResponse['GOOGLE_ID']);
            $user->setPhotoUrl($userResponse['PHOTOPATH']);
            $user->setQrPhotoUrl($userResponse['QRPHOTOPATH']);
            $user->setName($userResponse['NAME']);
            $user->setMail($userResponse['MAIL']);
            $user->setRegisterDate($userResponse['REGISTER_DATE']);
            $user->setMoney($userResponse['MONEY']);
            $user->setPermissionLevel($userResponse['PERMISSION_LEVEL']);

            if (strcasecmp($userResponse['ENABLED'], "0")) {
                $user->setIsEnabled(true);
            } else {
                $user->setIsEnabled(false);
            }

            if (strcasecmp($userResponse['IS_GOOGLE_USER'], "0")) {
                $user->setIsGoogleUser(true);
            } else {
                $user->setIsGoogleUser(false);
            }

            return $user;
        }

        return null;
    }

    /**
     * Comprova que existeixi l'usuari de google passat com a paràmetre.
     *
     * @param User $googleUser
     * @return null|User
     */
    public function checkIfThisGoogleUserExists(User $googleUser) {

        $query = "SELECT * FROM USERS WHERE GOOGLE_ID=".$googleUser->getGoogleId() . " AND MAIL='" . $googleUser->getMail()
        . "' AND NAME='" . $googleUser->getName() . "' AND IS_GOOGLE_USER=TRUE";

        $response = parent::query($query);

        if ($userResponse = $response->fetch_assoc()) {

            $googleUser->setId($userResponse['ID_USER']);
            $googleUser->setQrPhotoUrl($userResponse['QRPHOTOPATH']);
            $googleUser->setRegisterDate($userResponse['REGISTER_DATE']);
            $googleUser->setMoney($userResponse['MONEY']);
            $googleUser->setPermissionLevel($userResponse['PERMISSION_LEVEL']);

            if (strcasecmp($userResponse['ENABLED'], "0")) {
                $googleUser->setIsEnabled(true);
            } else {
                $googleUser->setIsEnabled(false);
            }

            return $googleUser;
        }

        return null;
    }

    /**
     * Afegeix els diners passats com a paràmetre a l'usuari amb id igual al que està passat com a paràmetre.
     *
     * @param $id
     * @param $moneyAdded
     */
    public function addMoneyToThisUser($id, $moneyAdded) {

        $query = "UPDATE USERS SET MONEY=MONEY+" . $moneyAdded . " WHERE ID_USER=" . $id;
        parent::query($query);
    }

    /**
     * Resta els diners passats com a paràmetre a l'usuari amb id igual al que està passat com a paràmetre.
     *
     * @param $money
     * @param User $user
     */
    public function substractMoney($money, User $user) {
        $query = "UPDATE USERS SET MONEY=MONEY-$money WHERE ID_USER=" . $user->getId();
        parent::query($query);
    }

    /**
     * Habilita un usuari.
     *
     * @param $id
     */
    public function enable($id) {
        $query = "UPDATE USERS SET ENABLED=TRUE WHERE ID_USER=". $id;
        parent::query($query);
    }

    /**
     * Deshabilita un usuari.
     *
     * @param $id
     */
    public function disable($id) {
        $query = "UPDATE USERS SET ENABLED=FALSE WHERE ID_USER=". $id;
        parent::query($query);
    }

    /**
     * Canvia la password a l'usuari passat com a paràmetre.
     *
     * @param User $loginUser
     * @param $newPassword
     * @return bool|mysqli_result
     */
    public function changePassword(User $loginUser, $newPassword) {
        $query = "UPDATE USERS SET PASSWORD='$newPassword' WHERE ID_USER=". $loginUser->getId();
        return parent::query($query);
    }

    /**
     * Retorna el nombre d'usuaris que hi ha a la BBDD.
     *
     * @return array
     */
    public function numberOfUsers() {

        $toReturn = array();

        for ($i = 1; $i <= 12 ; $i++) {
            $query = "SELECT COUNT(*) AS NUM_USERS FROM `USERS` U WHERE MONTH(REGISTER_DATE) < $i AND YEAR(REGISTER_DATE) = YEAR(NOW())";

            $result = parent::query($query);

            if ($row = $result->fetch_assoc()) {
                $toReturn[] = $row['NUM_USERS'];
            } else {
                $toReturn[] = 0;
            }
        }

        return $toReturn;
    }

}