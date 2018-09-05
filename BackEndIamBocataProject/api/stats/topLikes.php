<?php
/**
 * Retorna el TOP 10 productes amb mes likes, amb la quantitat de likes
 * User: yous
 * Date: 23/05/18
 * Time: 20:31
 */

    header('Content-type: application/json');
    header('Access-Control-Allow-Origin: *');

    include_once (dirname(__FILE__) . '/../../controller/ProductManager.php');
    include_once (dirname(__FILE__) . '/../../controller/SecurityManager.php');

    $apiKey = $_GET['API_KEY'];

    $arrayToReturn = array();

    $securityManager = new SecurityManager($apiKey);

    if ($securityManager->checkIsConfiableClient()) {

        $productManager = new ProductManager();

        $arrayToReturn = $productManager->topLikes();
    }

    echo json_encode($arrayToReturn);

?>