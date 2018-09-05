<?php

/**
 * Classe per accedir a les dades dels paràmetres a la BBDD.
 *
 * User: Josep
 * Date: 24/05/2018
 * Time: 22:39
 */
include_once (dirname(__FILE__) . '/Dao.php');

class ParametersDao extends Dao {

    /**
     * ParametersDao constructor.
     */
    public function __construct() {
        parent::Dao();
    }

    /**
     * Retorna el valor de un paràmetre del sistema.
     *
     * @param $parameterName
     * @return null
     */
    public function getParameterValue($parameterName) {

        $query = "SELECT 'VALUE' FROM PARAMETERS WHERE NAME='$parameterName'";

        $result = parent::query($query);

        while ($row = $result->fetch_assoc()) {
            return $row['VALUE'];
        }

        return null;
    }

    /**
     * Canvia el valor de un paràmetre del sistema.
     *
     * @param $parameterName
     * @param $parameterValue
     * @return bool|mysqli_result
     */
    public function updateParameter($parameterName, $parameterValue) {

        $query = "UPDATE PARAMETERS SET VALUE='$parameterValue' WHERE NAME='$parameterName'";

        return parent::query($query);
    }

    /**
     * Retorna tots els paràmetres del sistema amb els seus valors.
     *
     * @return array
     */
    public function getAllParameters() {

        $query = "SELECT * FROM PARAMETERS";

        $result = parent::query($query);

        $arrayToReturn = array();

        while ($row = $result->fetch_assoc()) {
            $arrayToReturn[$row['NAME']] = $row['VALUE'];
        }

        return $arrayToReturn;
    }
}