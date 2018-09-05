<?php

/**
 * Classe per gestionar els paràmetres de tot el sistema.
 * User: Josep
 * Date: 24/05/2018
 * Time: 22:46
 */
require_once(dirname(__FILE__) . '/../bbdd/ParametersDao.php');
require_once(dirname(__FILE__) . '/../constants/ConstantsParameters.php');

class ParameterManager {

    private $parameterDao;

    /**
     * ParameterManager constructor.
     */
    public function __construct() {
        $this->parameterDao = new ParametersDao();
    }

    /**
     * Retorna una llista de tots els paràmetres amb els seus respectius valors.
     *
     * @return array
     */
    public function getParameters() {
        return $this->parameterDao->getAllParameters();
    }

    /**
     * Actualitza els paràmetres del sistema.
     *
     * @param User $user
     * @param $hourOpen
     * @param $hourClose
     * @param $marginMin
     * @param $running
     * @return bool
     */
    public function updateParameters(User $user, $hourOpen, $hourClose, $marginMin, $running) {

        if ($user->getPermissionLevel() < 2) {
            return false;
        }

        try {
            if (intval($hourOpen) == 0 or intval($hourClose) == 0
                    or intval($marginMin) == 0) {
                return false;
            }

        } catch (Exception $e) {
            return false;
        }

        return $this->parameterDao->updateParameter(ConstantsParameters::TIME_OPEN, $hourOpen) &&
            $this->parameterDao->updateParameter(ConstantsParameters::TIME_CLOSE, $hourClose) &&
            $this->parameterDao->updateParameter(ConstantsParameters::MARGIN_MIN, $marginMin) &&
            $this->parameterDao->updateParameter(ConstantsParameters::RUNNING, $running);
    }

}