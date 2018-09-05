<?php
/**
 * Retorna els productes del sistema.
 * User: yous
 * Date: 10/04/18
 * Time: 15:48
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

    $arrayToReturn = [];

    $securityManager = new SecurityManager($passwordAPI);
    $productManager = new ProductManager();

    if ($securityManager->checkIsConfiableClient()) {

        $user = (new UserManager($idUser))->getThisUser();

        if ($user->getPermissionLevel() > 1) {
            $arrayToReturn = $productManager->getAllProducts();
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