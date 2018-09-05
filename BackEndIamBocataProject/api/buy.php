<?php
    /**
     * Fa una compra.
     *
     *  Parametres:
     *      - checkout amb productes i quantitat
     *      - id del usuari que compra
     *      - api key
     *      - token del dispositiu per poden enviar notificacions
     *      - data de recollida del producte
     *
     *
     * User: yous
     * Date: 20/04/18
     * Time: 17:43
     */
    header('Content-type: application/json; charset=utf-8');

    include_once ('../controller/SecurityManager.php');
    include_once ('../controller/BuyManager.php');

    $checkout = addslashes($_GET['checkout']); // checkout=23-3,12-1 (idproduct-quantity)
    $iduser = addslashes($_GET['iduser']);
    $apiKey = addslashes($_GET['API_KEY']);
    $token = addslashes($_GET['token']);
    $dateDelivery = addslashes($_GET['DELIVERY_DATE']);

    $arrayToReturn = [];

    $buyManager = new BuyManager($checkout, $iduser, $dateDelivery);
    $securityManager = new SecurityManager($apiKey);

    if ($securityManager->checkIsConfiableClient()) {


        if ($buyManager->checkCanBuy()) {
            $buyManager->buy($token);
            $arrayToReturn['done'] = true;
        } else {
            $arrayToReturn['fail'] = "Not enough money.";
        }

    } else {
        // JSON NOT AUTHORIZED
        $arrayToReturn['fail'] = 'No autorizado a realizar esta petición';
    }

    echo json_encode($arrayToReturn);



?>