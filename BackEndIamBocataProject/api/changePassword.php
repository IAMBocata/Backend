<?php
/**
 * Canvia la password al usuari passat com a paràmetre.
 *
 *  Paràmetres:
 *      - id del usuari
 *      - contrassenya anterior
 *      - contrassenya nova
 *
 * User: yous
 * Date: 22/05/18
 * Time: 19:37
 */

    header('Content-type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');

    include_once ('../controller/UserManager.php');
    include_once ('../controller/SecurityManager.php');
    include_once ('../models/User.php');


    $id = addslashes($_GET['id']);
    $oldPassword = addslashes($_GET['oldpassword']);
    $newPassword = addslashes($_GET['newpassword']);

    $apiKey = $_GET['API_KEY'];

    $arrayToReturn = [];

    $securityManager = new SecurityManager($apiKey);

    if ($securityManager->checkIsConfiableClient()) {

        $userManager = new UserManager($id);

        $arrayToReturn['done'] = $userManager->changePassword($oldPassword, $newPassword);

    } else {
        $arrayToReturn['auth'] = 'failed';
        $arrayToReturn['msg'] = 'Not authorized';
    }

    echo json_encode($arrayToReturn);

?>