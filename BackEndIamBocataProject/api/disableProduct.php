<?php
/**
 * Deshabilita un producte.
 * User: Josep
 * Date: 15/05/2018
 * Time: 23:45
 */

    header('Content-type: application/json');
    header('Access-Control-Allow-Origin: *');

    include_once ('../controller/UserManager.php');
    include_once ('../models/User.php');
    include_once ('../controller/ProductManager.php');
    include_once ('../controller/BuyManager.php');
    include_once ('../controller/utils/EncoderUtils.php');
    include_once ('../controller/SecurityManager.php');

    $passwordAPI = addslashes($_GET['API_KEY']);
    $idUser = addslashes($_GET['idUser']);
    $idProduct = addslashes($_GET['idProduct']);

    $arrayToReturn = [];

    $securityManager = new SecurityManager($passwordAPI);
    $productManager = new ProductManager();

    if ($securityManager->checkIsConfiableClient()) {

        $user = (new UserManager($idUser))->getThisUser();

        if ($user->getPermissionLevel() > 1) {
            $productManager->disable($idProduct);
            $arrayToReturn['done'] = true;
        } else {
            $arrayToReturn['auth'] = 'failed';
            $arrayToReturn['msg'] = 'Not authorized';
        }
    } else {
        $arrayToReturn['auth'] = 'failed';
        $arrayToReturn['msg'] = 'Not authorized';
    }

    echo json_encode($arrayToReturn);
?>