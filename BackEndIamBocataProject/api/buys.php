<?php
/**
 * Retorna el historial de compres del usuari passat com a paràmetre
 *
 * User: Josep
 * Date: 17/05/2018
 * Time: 22:48
 */
    header('Content-type: application/json; charset=utf-8');

    include_once ('../controller/SecurityManager.php');
    include_once ('../controller/UserManager.php');
    include_once ('../controller/BuyManager.php');

    $passwordAPI = addslashes($_GET['API_KEY']);
    $userId = addslashes($_GET['userId']);

    $arrayToReturn = [];

    $securityManager = new SecurityManager($passwordAPI);
    $userManager = new UserManager($userId);

    if ($securityManager->checkIsConfiableClient()) {

        $user = $userManager->getThisUser();

        $arrayToReturn['buys'] = (new BuyManager())->getBuysOfThisUser($user);
    } else {
        // JSON NOT AUTHORIZED
        $arrayToReturn['fail'] = 'No autorizado a realizar esta petición';
    }

    echo json_encode($arrayToReturn);
?>