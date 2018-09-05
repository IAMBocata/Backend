<?php
/**
 * Treu el like de un producte
 * User: yous
 * Date: 27/04/18
 * Time: 11:24
 */

    include_once ('../controller/ProductManager.php');
    include_once ('../controller/SecurityManager.php');

    header('Content-type: application/json; charset=utf-8');

    $productId = addslashes($_GET['productId']);
    $userId = addslashes($_GET['userId']);
    $passwordAPI = addslashes($_GET['API_KEY']);

    $arrayToReturn = [];

    $securityManager = new SecurityManager($passwordAPI);
    $productManager = new ProductManager();

    if ($securityManager->checkIsConfiableClient()) {

        $u = new User();
        $u->setId($userId);
        $p = new Product();
        $p->setId($productId);

        $productManager->dislike($u, $p);
        $arrayToReturn['done'] = true;
    } else {
        // JSON NOT AUTHORIZED
        $arrayToReturn['fail'] = 'No autorizado a realizar esta petición';
    }


    echo json_encode($arrayToReturn);


?>