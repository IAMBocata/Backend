<?php

/**
 * Gestiona les sessions
 * User: Josep
 * Date: 28/05/2018
 * Time: 18:18
 */
include_once (dirname(__FILE__) . '/../bbdd/SessionDao.php');

class SessionManager {

    private $sessionDao;

    /**
     * SessionManager constructor.
     */
    public function __construct() {
        $this->sessionDao = new SessionDao();
    }

    /**
     * Inserta una nova sessió a la BBDD
     *
     * @param User $u
     * @param $ip
     * @param $type
     * @return bool|mysqli_result
     */
    public function insertSession(User $u, $ip, $type) {
        return $this->sessionDao->insertSession($u, $ip, $type);
    }
}