<?php
/**
 * Deshabilita un producte.
 * User: Josep
 * Date: 16/05/2018
 * Time: 0:05
 */

    header('Content-type: application/json');
    header('Access-Control-Allow-Origin: *');

    include_once ('../models/User.php');
    include_once ('../controller/UserManager.php');
    include_once ('../controller/BuyManager.php');
    include_once ('../controller/utils/EncoderUtils.php');
    include_once ('../controller/SecurityManager.php');

    $passwordAPI = addslashes($_GET['API_KEY']);
    $idUser = addslashes($_GET['idUser']);
    $idUserApplicant = addslashes($_GET['iduserapplicant']);

    $arrayToReturn = [];

    $securityManager = new SecurityManager($passwordAPI);
    $userManager = new UserManager($idUserApplicant);

    if ($securityManager->checkIsConfiableClient()) {

        $user = $userManager->getThisUser();

        if ($user->getPermissionLevel() > 1) {
            (new UserManager($idUser))->disable();
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