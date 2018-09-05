<?php
/**
 * Canvia el valor dels paràmetres del sistema.
 *
 * User: yous
 * Date: 25/05/18
 * Time: 15:50
 */

    header('Content-type: application/json');
    header('Access-Control-Allow-Origin: *');

    include_once ('../controller/SecurityManager.php');
    include_once ('../controller/ParameterManager.php');
    include_once ('../controller/UserManager.php');

    $passwordAPI = addslashes($_GET['API_KEY']);
    $idUser = addslashes($_GET['idUser']);

    $hourOpen = addslashes($_GET['hourOpen']);
    $hourClose = addslashes($_GET['hourClose']);
    $marginMin = addslashes($_GET['marginMin']);
    $running = addslashes($_GET['running']);

    $arrayToReturn = array();

    $securityManager = new SecurityManager($passwordAPI);

    if ($securityManager->checkIsConfiableClient()) {

        $user = (new UserManager($idUser))->getThisUser();
        $parameterManger = new ParameterManager();

        if ($user == null) {
            $arrayToReturn['done'] = false;
        } else {
            $arrayToReturn['done'] = $parameterManger->updateParameters($user, $hourOpen, $hourClose, $marginMin, $running);
        }

    } else {
        $arrayToReturn['done'] = false;
    }

    echo json_encode($arrayToReturn);

?>