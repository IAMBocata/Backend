<?php

/**
 * Classe per gestionar temes de seguretat
 * User: yous
 * Date: 16/04/18
 * Time: 20:43
 */
include_once dirname(__FILE__) . '/../constants/ConstantsSecurity.php';

class SecurityManager {

    private $apiKey;

    /**
     * SecurityManager constructor.
     * @param $apiKey
     */
    function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    /**
     * Comprova que la api key del client es correcta.
     *
     * @return bool
     */
    public function checkIsConfiableClient() {

        if (strcmp($this->apiKey, ConstantsSecurity::PASSWORD_API) == 0) {
            return true;
        }

        return false;
    }
}