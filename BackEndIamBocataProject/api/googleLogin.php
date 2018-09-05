<?php
    /**
     * Script de login fet amb usuari de google.
     * User: yous
     * Date: 13/04/18
     * Time: 16:41
     */

    header('Content-type: application/json; charset=utf-8');

    include_once ('../controller/google/GoogleLogin.php');
    include_once ('../models/User.php');
    include_once ('../controller/ProductManager.php');
    include_once ('../controller/ParameterManager.php');
    include_once ('../controller/SessionManager.php');
    include_once ('../controller/BuyManager.php');
    include_once ('../controller/utils/EncoderUtils.php');
    include_once ('../controller/SecurityManager.php');

    $name = addslashes($_GET['googleName']);
    $googleId = addslashes($_GET['googleId']);
    $mail = addslashes($_GET['mail']);
    $passwordAPI = addslashes($_GET['API_KEY']);
    $photoUrl = addslashes($_GET['photoUrl']);

    $arrayToReturn = [];

    $googleUser = new User();
    $googleUser->setIsGoogleUser(true);
    $googleUser->setGoogleId($googleId);
    $googleUser->setMail($mail);
    $googleUser->setName($name);
    $googleUser->setPhotoUrl($photoUrl);

    $login = new GoogleLogin($googleUser);
    $securityManager = new SecurityManager($passwordAPI);

    if ($securityManager->checkIsConfiableClient()) {

        $googleUser = $login->loginUserOrRegisterIfNotExists();

        if ($googleUser == null) {
            $arrayToReturn['auth'] = false;
            echo json_encode($arrayToReturn);
            die();
        }

        $arrayToReturn['userdata'] = ObjectToArrayConvertor::userToArray($googleUser);
        $arrayToReturn['products'] = (new ProductManager())->getAllProductsInArray($googleUser);
        $arrayToReturn['buys'] = (new BuyManager())->getBuysOfThisUser($googleUser);
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