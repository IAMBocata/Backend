<?php
/**
 * Retorna la quantitat d'usuaris que ha tingut el sistema durant els diferents mesos, per tal de veure el creixement.
 * User: Josep
 * Date: 28/05/2018
 * Time: 18:47
 */
    header('Content-type: application/json');
    header('Access-Control-Allow-Origin: *');

    include_once (dirname(__FILE__) . '/../../controller/UserManager.php');
    include_once (dirname(__FILE__) . '/../../controller/SecurityManager.php');

    $apiKey = $_GET['API_KEY'];

    $arrayToReturn = array();

    $securityManager = new SecurityManager($apiKey);

    if ($securityManager->checkIsConfiableClient()) {

        $userManager = new UserManager(0);

        $arrayToReturn = $userManager->numberOfUsers();
    }

    echo json_encode($arrayToReturn);
?>