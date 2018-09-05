<?php
/**
 * Retorna una llista amb les categories
 * User: Josep
 * Date: 16/05/2018
 * Time: 0:47
 */

    header('Content-type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');

    include_once ('../controller/CategoryManager.php');
    include_once ('../controller/SecurityManager.php');
    include_once ('../controller/UserManager.php');

    $passwordAPI = addslashes($_GET['API_KEY']);
    $idUser = addslashes( $_GET['idUser']);

    $arrayToReturn = [];

    $securityManager = new SecurityManager($passwordAPI);
    $categoryManager = new CategoryManager();

    if ($securityManager->checkIsConfiableClient()) {

        $user = (new UserManager($idUser))->getThisUser();

        if ($user->getPermissionLevel() > 1) {
            $arrayToReturn = $categoryManager->getAllCategories();
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