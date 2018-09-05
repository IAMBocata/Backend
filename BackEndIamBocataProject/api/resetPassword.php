<?php
/**
 * Script per fer reset de password a un usuari.
 * User: Josep
 * Date: 28/05/2018
 * Time: 20:52
 */
    header('Content-type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');

    include_once ('../controller/UserManager.php');
    include_once ('../controller/SecurityManager.php');
    include_once ('../models/User.php');


    $id = addslashes($_GET['id']);
    $idUserApplicant = addslashes($_GET['idUserApplicant']);
    $newPassword = addslashes($_GET['newpassword']);

    $apiKey = $_GET['API_KEY'];

    $arrayToReturn = [];

    $securityManager = new SecurityManager($apiKey);

    if ($securityManager->checkIsConfiableClient()) {

        $userManager = new UserManager($id);

        $arrayToReturn['done'] = $userManager->resetPassword( $newPassword, $idUserApplicant);

    } else {
        $arrayToReturn['auth'] = 'failed';
        $arrayToReturn['msg'] = 'Not authorized';
    }

    echo json_encode($arrayToReturn);

?>