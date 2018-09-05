<?php

/**
 * Classe per accedir a les dades de les sessions a la BBDD.
 *
 * User: Josep
 * Date: 28/05/2018
 * Time: 18:19
 */
include_once(dirname(__FILE__) . '/Dao.php');

class SessionDao extends Dao {

    /**
     * SessionDao constructor.
     */
    public function __construct() {
        parent::Dao();
    }

    /**
     * Inserta una nova sessió a la BBDD.
     *
     * @param User $user
     * @param $ip
     * @param $type
     * @return bool|mysqli_result
     */
    public function insertSession(User $user, $ip, $type) {

        $query = "INSERT INTO SESSIONS (ID_USER, LOGINDATE, IP, DEVICE) VALUES (".
            $user->getId() .", NOW(), '$ip', '$type')";

        return parent::query($query);
    }

}