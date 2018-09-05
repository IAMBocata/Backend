<?php
/**
 * Retorna tots els usuaris del sistema, amb les seves dades.
 * User: yous
 * Date: 10/04/18
 * Time: 15:09
 */

    header('Content-type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');

    include_once ('../controller/UserManager.php');
    include_once ('../models/User.php');
    include_once ('../controller/BuyManager.php');
    include_once ('../controller/utils/EncoderUtils.php');
    include_once ('../controller/utils/ObjectToArrayConvertor.php');
    include_once ('../controller/SecurityManager.php');


    $passwordAPI = htmlentities(addslashes($_GET['API_KEY']));
    $idUser = htmlentities(addslashes($_GET['idUser']));

    $arrayToReturn = [];

    $securityManager = new SecurityManager($passwordAPI);
    $productManager = new UserManager($idUser);

    if ($securityManager->checkIsConfiableClient()) {

        $user = $productManager->getThisUser();

        if ($user->getPermissionLevel() > 1) {
            $arrayToReturn = ObjectToArrayConvertor::arrayOfUsersToArrayOfArray($productManager->getAllUsers());
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