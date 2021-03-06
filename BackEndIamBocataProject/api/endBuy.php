<?php
/**
 * Acaba una compra
 * User: yous
 * Date: 19/05/18
 * Time: 11:01
 */

    header('Content-type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');

    include_once ('../controller/UserManager.php');
    include_once ('../controller/SecurityManager.php');
    include_once ('../controller/BuyManager.php');
    include_once ('../models/User.php');

    $idBuy  = addslashes($_GET['idBuy']);
    $idUser = addslashes($_GET['idUser']);
    $apiKey = addslashes($_GET['API_KEY']);

    $arrayToReturn = [];

    $securityManager = new SecurityManager($apiKey);

    if ($securityManager->checkIsConfiableClient()) {

        $user = (new UserManager($idUser))->getThisUser();

        $arrayToReturn['done'] = (new BuyManager())->endBuy($idBuy, $user);

    } else {
        $arrayToReturn['auth'] = false;
    }

    echo json_encode($arrayToReturn);

?>