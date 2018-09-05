<?php
/**
 * Script per fer like a un producte
 * User: yous
 * Date: 26/04/18
 * Time: 9:51
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

        $productManager->like($u, $p);
        $arrayToReturn['done'] = true;
    } else {
        // JSON NOT AUTHORIZED
        $arrayToReturn['fail'] = 'No autorizado a realizar esta petición';
    }


    echo json_encode($arrayToReturn);
?>