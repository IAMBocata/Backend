<?php
/**
 * Crea un usuari.
 *
 * User: yous
 * Date: 18/05/18
 * Time: 16:29
 */

    header('Content-type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');

    include_once ('../controller/UserManager.php');
    include_once ('../controller/SecurityManager.php');
    include_once ('../models/User.php');

    $idUserApplicant = htmlentities(addslashes($_GET['idUserApplicant']));

    $name = addslashes($_GET['name']);
    $mail = addslashes($_GET['mail']);
    $password = addslashes($_GET['password']);
    $permissionLevel = addslashes($_GET['permissionLevel']);

    $apiKey = $_GET['API_KEY'];

    $arrayToReturn = [];

    $securityManager = new SecurityManager($apiKey);

    if ($securityManager->checkIsConfiableClient()) {

        $userToCreate = new User();
        $userToCreate->setName($name);
        $userToCreate->setMail($mail);
        $userToCreate->setPassword($password);
        $userToCreate->setPermissionLevel($permissionLevel);

        $userManager = new UserManager($idUserApplicant);

        $arrayToReturn['done'] = $userManager->createUser($userToCreate);

    } else {
        $arrayToReturn['auth'] = 'failed';
        $arrayToReturn['msg'] = 'Not authorized';
    }

    echo json_encode($arrayToReturn);
?>