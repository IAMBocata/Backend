<?php

/**
 * Classe per converir objectes a arrays
 * User: yous
 * Date: 7/04/18
 * Time: 12:19
 */
include_once(dirname(__FILE__) . '/../../models/Product.php');
include_once(dirname(__FILE__) . '/../../models/User.php');

class ObjectToArrayConvertor {

    /**
     * Converteix un producte a un array.
     *
     * @param Product $product
     * @return mixed
     */
    public static function productToArray(Product $product) {

        $arrayToReturn['id'] = $product->getId();
        $arrayToReturn['name'] = $product->getName();
        $arrayToReturn['description'] = $product->getDescription();
        $arrayToReturn['price'] = $product->getPrice();
        $arrayToReturn['category'] = $product->getCategory();
        $arrayToReturn['photoPath'] = $product->getPhotoPath();
        $arrayToReturn['photoPathMobile'] = $product->getPhotoPathMobile();
        $arrayToReturn['dateAdded'] = $product->getDateAdded();
        //$arrayToReturn['ingredients'] = $product->getIngredients();
        $arrayToReturn['ingredients'] = $product->getIngredientsInArray();
        $arrayToReturn['liked'] = $product->getLiked();
        $arrayToReturn['oftheday'] = $product->getOftheday();
        $arrayToReturn['enabled'] = $product->getEnabled();

        return $arrayToReturn;
    }

    /**
     * Converteix un array de productes a un array de arrays
     *
     * @param $productsArray
     * @return array
     */
    public static function arrayOfProductsToArrayOfArray($productsArray) {

        $arrayToReturn = [];
        $index = 0;

        foreach ($productsArray as $prod) {
            $arrayToReturn[$index] = self::productToArray($prod);
            $index++;
        }

        return $arrayToReturn;
    }

    /**
     * Converteix un usuari a un array.
     *
     * @param User $user
     * @return mixed
     */
    public static function userToArray(User $user) {

        $arrayToReturn['id'] = $user->getId();
        $arrayToReturn['googleId'] = $user->getGoogleId();
        $arrayToReturn['name'] = $user->getName();
        $arrayToReturn['mail'] = $user->getMail();
        $arrayToReturn['photoUrl'] = $user->getPhotoUrl();
        $arrayToReturn['qrPhotoUrl'] = $user->getQrPhotoUrl();
        $arrayToReturn['registerDate'] = $user->getRegisterDate();
        $arrayToReturn['money'] = $user->getMoney();
        $arrayToReturn['isGoogleUser'] = $user->getIsGoogleUser();
        $arrayToReturn['permissionLevel'] = $user->getPermissionLevel();
        $arrayToReturn['enabled'] = $user->getIsEnabled();

        return $arrayToReturn;
    }

    /**
     * Converteix un array de usuaris a un array de arrays
     *
     * @param $userArray
     * @return array
     */
    public static function arrayOfUsersToArrayOfArray($userArray) {

        $arrayToReturn = [];
        $index = 0;

        foreach ($userArray as $user) {
            $arrayToReturn[$index] = self::userToArray($user);
            $index++;
        }

        return $arrayToReturn;
    }

}


?>