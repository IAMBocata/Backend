<?php
/**
 * Retorna el valor dels diferents paràmetres del sistema.
 * User: yous
 * Date: 25/05/18
 * Time: 17:08
 */

    header('Content-type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');

    include_once ('../controller/ParameterManager.php');
    include_once ('../controller/SecurityManager.php');

    $passwordAPI = $_GET['API_KEY'];

    $arrayToReturn = [];

    $securityManager = new SecurityManager($passwordAPI);

    if ($securityManager->checkIsConfiableClient()) {
        $arrayToReturn = (new ParameterManager())->getParameters();
    } else {
        $arrayToReturn['done'] = false;
    }

    echo json_encode($arrayToReturn);
?>