<?php
/**
 * Login que retorna true o false.
 * User: yous
 * Date: 27/04/18
 * Time: 11:45
 */

    header('Content-type: application/json');

    include_once('../models/User.php');
    include_once ('../controller/SecurityManager.php');
    include_once ('../controller/UserManager.php');

    $mail = addslashes($_GET['mail']);
    $password = addslashes($_GET['passwd']);
    $passwordAPI = addslashes($_GET['API_KEY']);

    $arrayToReturn = [];

    $securityManager = new SecurityManager($passwordAPI);
    $userManager = new UserManager(null);

    if ($securityManager->checkIsConfiableClient()) {

        $u = $userManager->login($mail, $password);

        if ($u == null) {
            $arrayToReturn['login'] = false;
        } else {
            $arrayToReturn['login'] = true;
        }

    } else {
        // JSON NOT AUTHORIZED
        $arrayToReturn['fail'] = 'No autorizado a realizar esta petición';
    }

    echo json_encode($arrayToReturn);

?>