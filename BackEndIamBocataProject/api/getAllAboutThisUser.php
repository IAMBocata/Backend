<?php
/**
 * Se li passa un id i retorna totes les dades de l'usuari amb aquell id.
 * User: yous
 * Date: 10/04/18
 * Time: 15:56
 */
    header('Content-type: application/json; charset=utf-8');

    include_once('../controller/UserManager.php');
    include_once('../controller/utils/EncoderUtils.php');
    include_once('../controller/utils/ObjectToArrayConvertor.php');
    include_once('../controller/SecurityManager.php');

    $idUser = addslashes($_GET['iduser']);
    $apiKey = addslashes($_GET['API_KEY']);

    $user = (new UserManager($idUser, null))->getThisUser();
    $securityManager = new SecurityManager($apiKey);

    $jsonArrayToPrint = [];

    if ($securityManager->checkIsConfiableClient()) {
        if ($user == null) {
            $jsonArrayToPrint['errorcode'] = 404;
            $jsonArrayToPrint['msg'] = "User not found.";
        } else {
            $jsonArrayToPrint = ObjectToArrayConvertor::userToArray($user);
        }
    } else {
        $jsonArrayToPrint['fail'] = 'No autorizado a realizar esta petición';
    }

    echo json_encode($jsonArrayToPrint);

?>