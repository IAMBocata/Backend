<?php
    /**
     * Login pel cpanel
     *
     * User: yous
     * Date: 15/05/18
     * Time: 08:31
     */

    header('Content-type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');

    include_once ('../controller/UserManager.php');
    include_once ('../models/User.php');
    include_once ('../controller/ProductManager.php');
    include_once ('../controller/BuyManager.php');
    include_once ('../controller/utils/EncoderUtils.php');
    include_once ('../controller/SecurityManager.php');

    $mail = addslashes($_GET['mail']);
    $passwordAPI = addslashes( $_GET['API_KEY']);
    $password = addslashes($_GET['password']);

    $arrayToReturn = [];

    $userManager = new UserManager(null);
    $securityManager = new SecurityManager($passwordAPI);

    if ($securityManager->checkIsConfiableClient()) {

        $user = $userManager->login($mail, $password);

        if ($user == null || $user->getPermissionLevel() < 2) {
            $arrayToReturn['auth'] = false;
            echo json_encode($arrayToReturn);
            die();
        }

        $arrayToReturn['userdata'] = ObjectToArrayConvertor::userToArray($user);

    } else {
        // JSON NOT AUTHORIZED
        $arrayToReturn['fail'] = 'No autorizado a realizar esta petición';
    }

    echo json_encode($arrayToReturn);
?>
