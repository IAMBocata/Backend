<?php
/**
 * Permet ingressar diners al compte d'un usuari.
 *
 *  Paràmetres:
 *      - iduser
 *      - id del usuari que solicita l'ingrés
 *      - Diners a ingressar
 *      - API key
 *
 * User: yous
 * Date: 16/04/18
 * Time: 16:42
 */
    include_once ('../controller/UserManager.php');
    include_once ('../controller/utils/EncoderUtils.php');
    include_once ('../controller/SecurityManager.php');

    header('Content-type: application/json');
    header('Access-Control-Allow-Origin: *');

    $idUser = addslashes($_GET['iduser']);
    $idUserApplicant = addslashes($_GET['iduserapplicant']);
    $moneyAdded = addslashes($_GET['money']);
    $passwordAPI = $_GET['API_KEY'];

    $arrayToReturn = [];

    $securityManager = new SecurityManager($passwordAPI);

    if ($securityManager->checkIsConfiableClient()) {

        $userManager = new UserManager($idUser);

        $arrayToReturn['done'] = $userManager->addMoney($moneyAdded, $idUserApplicant); // devuelve true o false;

    } else {
        // JSON NOT AUTHORIZED
        $arrayToReturn['fail'] = 'No autorizado a realizar esta petición';
    }

    echo json_encode(EncoderUtils::utf8ize($arrayToReturn));
?>