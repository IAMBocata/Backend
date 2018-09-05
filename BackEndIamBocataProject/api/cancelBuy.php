<?php
/**
 * Cancela la compra passada com a paràmetre.
 * User: Josep
 * Date: 27/05/2018
 * Time: 15:20
 */
    header('Content-type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');

    include_once ('../controller/SecurityManager.php');
    include_once ('../controller/BuyManager.php');
    include_once ('../controller/UserManager.php');

    $idBuy = addslashes($_GET['idBuy']);
    $idUser = addslashes($_GET['idUser']);
    $apiKey = addslashes($_GET['API_KEY']);

    $arrayToReturn = array();

    if ((new SecurityManager($apiKey))->checkIsConfiableClient()) {
        $user = (new UserManager($idUser))->getThisUser();
        $arrayToReturn['done'] = (new BuyManager())->cancelBuy($idBuy, $user);
    } else {
        $arrayToReturn['done'] = false;
    }

    echo json_encode($arrayToReturn);
?>