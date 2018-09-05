<?php
/**
 * Login amb usuari i password per la app.
 * User: yous
 * Date: 11/05/18
 * Time: 16:31
 */

    header('Content-type: application/json; charset=utf-8');
    include_once ('../controller/UserManager.php');
    include_once ('../models/User.php');
    include_once ('../controller/ProductManager.php');
    include_once ('../controller/ParameterManager.php');
    include_once ('../controller/SessionManager.php');
    include_once ('../controller/BuyManager.php');
    include_once ('../controller/utils/EncoderUtils.php');
    include_once ('../controller/SecurityManager.php');

    $mail = addslashes($_GET['mail']);
    $passwordAPI = addslashes($_GET['API_KEY']);
    $password = addslashes($_GET['password']);

    $arrayToReturn = [];

    $userManager = new UserManager(null);
    $securityManager = new SecurityManager($passwordAPI);

    if ($securityManager->checkIsConfiableClient()) {

        $user = $userManager->login($mail, $password);

        if ($user == null) {
            $arrayToReturn['auth'] = false;
            echo json_encode($arrayToReturn);
            die();
        }

        $arrayToReturn['userdata'] = ObjectToArrayConvertor::userToArray($user);
        $arrayToReturn['products'] = (new ProductManager())->getAllProductsInArray($user);
        $arrayToReturn['buys'] = (new BuyManager())->getBuysOfThisUser($user);
        $arrayToReturn['parameters'] = (new ParameterManager())->getParameters();

        $type = 'BROWSER';

        if (isMobile()) {
            $type = 'MOBILE';
        }

        (new SessionManager())->insertSession($googleUser, $_SERVER['REMOTE_ADDR'], $type);

    } else {
        // JSON NOT AUTHORIZED
        $arrayToReturn['fail'] = 'No autorizado a realizar esta petición';
    }

    echo json_encode($arrayToReturn);


    function isMobile() {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|tablet)/i", $_SERVER["HTTP_USER_AGENT"]);
    }
?>