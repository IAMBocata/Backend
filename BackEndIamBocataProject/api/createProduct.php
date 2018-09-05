<?php
/**
 * Crea un producte
 * User: Josep
 * Date: 17/05/2018
 * Time: 13:15
 */
    header('Content-type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');

    include_once ('../controller/UserManager.php');
    include_once ('../controller/SecurityManager.php');
    include_once ('../controller/ProductManager.php');
    include_once ('../models/Product.php');
    include_once ('../models/User.php');

    $arrayToReturn = [];

    // user que crea el producto
    $userId = htmlentities(addslashes($_POST['userId']));

    // product data
    $name       =   addslashes($_POST['name']);
    $price =        addslashes($_POST['price']);
    $priceoftheday= addslashes($_POST['priceoftheday']);
    $category =     addslashes($_POST['category']);
    $ingredients =  addslashes($_POST['ingredients']);

    // api key
    $apiKey =   $_POST['API_KEY'];

    // File variables
    $genericFile =  $_FILES['productImage'];
    /*
    $name =         $_FILES['productImage']['name'];
    $fileImage =    $_FILES['productImage']['tmp_name'];
    $fileType =     $_FILES['productImage']['type'];
    $fileSize =     $_FILES['productImage']['size']; */

    $securityManager = new SecurityManager($apiKey);
    $userManager = new UserManager($userId);
    $productManager = new ProductManager();

    if ($securityManager->checkIsConfiableClient()) {

        $user = $userManager->getThisUser();

        $newProduct = new Product();
        $newProduct->setName($name);
        $newProduct->setPrice($price);
        $newProduct->setCategory($category);
        $newProduct->setIngredients($ingredients);
        $newProduct->setPhotoFile($genericFile);
        $newProduct->setDescription($name);
        $newProduct->setPriceoftheday($priceoftheday);

        // insert with product manager
        if ($productManager->newProduct($newProduct, $user)) {
            $arrayToReturn['done'] = true;
        } else {
            $arrayToReturn['done'] = false;
        }
    } else {
        $arrayToReturn['auth'] = false;
    }

    echo json_encode($arrayToReturn);
?>