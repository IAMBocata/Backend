<?php
/**
 * Retorna el TOP 10 productes mes venguts, amb la quantitat de ventes
 * User: yous
 * Date: 23/05/18
 * Time: 19:28
 */

    header('Content-type: application/json');
    header('Access-Control-Allow-Origin: *');

    include_once (dirname(__FILE__) . '/../../controller/BuyManager.php');
    include_once (dirname(__FILE__) . '/../../controller/SecurityManager.php');

    $apiKey = $_GET['API_KEY'];

    $arrayToReturn = array();

    $securityManager = new SecurityManager($apiKey);

    if ($securityManager->checkIsConfiableClient()) {

        $buyManager = new BuyManager();

        $arrayToReturn = $buyManager->topBuys();
    }

    echo json_encode($arrayToReturn);
?>