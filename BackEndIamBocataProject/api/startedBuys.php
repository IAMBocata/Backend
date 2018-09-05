<?php
/**
 * Retorna aquelles compres que no estiguin ni cancel·lades ni finalitzades.
 *
 * User: yous
 * Date: 24/04/18
 * Time: 15:45
 */

    header('Content-type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');

    include_once ('../controller/SecurityManager.php');
    include_once ('../controller/BuyManager.php');
    include_once ('../controller/utils/EncoderUtils.php');

    $passwordAPI = $_GET['API_KEY'];

    $arrayToReturn = [];

    $securityManager = new SecurityManager($passwordAPI);
    $buyManager = new BuyManager();

    if ($securityManager->checkIsConfiableClient()) {

        $arrayToReturn = $buyManager->getStartedBuys();

    } else {
        // JSON NOT AUTHORIZED
        $arrayToReturn['fail'] = 'No autorizado a realizar esta petición';
    }

    echo json_encode($arrayToReturn);

?>