<?php

/**
 * Classe per accedir a les dades dels prodcutes a la BBDD.
 *
 * User: yous
 * Date: 6/04/18
 * Time: 19:34
 */

include_once(dirname(__FILE__) . '/Dao.php');
include_once(dirname(__FILE__) . '/LikeDao.php');
include_once(dirname(__FILE__) . '/../models/Product.php');
include_once(dirname(__FILE__) . '/../models/User.php');

class ProductDao extends Dao {

    private $likeDao;

    /**
     * ProductDao constructor.
     */
    public function ProductDao() {
        parent::Dao();
        $this->likeDao = new LikeDao();
    }

    /**
     * Inserta un nou producte a la BBDD.
     *
     * @param Product $product
     */
    public function insertProduct(Product $product) {

        $query = "INSERT INTO PRODUCTS (NAME, DESCRIPTION, PRICE, PRICE_OFTHEDAY, DATE_ADDED, INGREDIENTS, ID_CATEGORY, ENABLED, OFTHEDAY) " .
            "VALUES ('" . $product->getName() . "', '". $product->getDescription() . "', " . $product->getPrice() .
            ", " . $product->getPriceoftheday() . ", NOW(), '" . $product->getIngredients() . "', " . $product->getCategory() . ", TRUE, FALSE)";

        parent::query($query);
    }

    /**
     * Retorna totes les dades de un producte passat com a paràmetre.
     *
     * @param Product $product
     * @return null|Product
     */
    public function completeProduct(Product $product) {

        $query = "SELECT * FROM PRODUCTS WHERE NAME LIKE '" . $product->getName() . "'";

        $result = parent::query($query);

        while ($prodResponse = $result->fetch_assoc()) {

            $product->setId($prodResponse['ID_PRODUCT']);
            $product->setName($prodResponse['NAME']);
            $product->setPrice($prodResponse['PRICE']);
            $product->setIngredients($prodResponse['INGREDIENTS']);
            $product->setDateAdded($prodResponse['DATE_ADDED']);

            if (strcmp($prodResponse['ENABLED'], "1") == 0) {
                $product->setEnabled(true);
            } else {
                $product->setEnabled(false);
            }

            if (strcmp($prodResponse['OFTHEDAY'], "1") == 0) {
                $product->setOftheday(true);
            } else {
                $product->setOftheday(false);
            }

            return $product;
        }

        return null;
    }

    /**
     * Retorna tots els productes amb les seves dades.
     *
     * @return array
     */
    public function findAll() {

        $arrayToReturn = [];

        $query = "SELECT P.ID_PRODUCT, P.NAME, P.DESCRIPTION, P.PRICE, P.PHOTOPATH, P.DATE_ADDED AS DATE, P.INGREDIENTS, " .
            "C.NAME AS CATEGORY, P.ENABLED, P.OFTHEDAY, P.PRICE_OFTHEDAY FROM PRODUCTS P JOIN CATEGORIES C ON P.ID_CATEGORY=C.ID_CATEGORY";

        $result = parent::query($query);

        $indexProduct = 0;

        while ($userResponse = $result->fetch_assoc()) {

            $product = new Product($userResponse['ID_PRODUCT'], $userResponse['NAME'], $userResponse['DESCRIPTION'], $userResponse['PRICE'],
                $userResponse['PHOTOPATH'], $userResponse['DATE'], $userResponse['INGREDIENTS'], $userResponse['CATEGORY']);

            if (strcmp($userResponse['ENABLED'], "1") == 0) {
                $product->setEnabled(true);
            } else {
                $product->setEnabled(false);
            }

            if (strcmp($userResponse['OFTHEDAY'], "1") == 0) {
                $product->setOftheday(true);
                $product->setPrice($userResponse['PRICE_OFTHEDAY']);
            } else {
                $product->setOftheday(false);
            }

            $arrayToReturn[$indexProduct] = $product;

            $indexProduct++;
        }

        return $arrayToReturn;
    }

    /**
     * Retorna tots els productes que estiguin habilitats, amb el camp extra de categoria.
     *
     * @param User $u
     * @return array
     */
    public function findAllEnabledsWithLike(User $u) {

        $query = "SELECT P.ID_PRODUCT, P.NAME, P.DESCRIPTION, P.PRICE, P.PHOTOPATH, P.DATE_ADDED AS DATE, P.INGREDIENTS, " .
            " P.OFTHEDAY, C.NAME AS CATEGORY, P.PRICE_OFTHEDAY FROM PRODUCTS P JOIN CATEGORIES C ON P.ID_CATEGORY=C.ID_CATEGORY WHERE P.ENABLED=1";

        $arrayToReturn = [];

        $result = parent::query($query);

        $indexProduct = 0;

        while ($userResponse = $result->fetch_assoc()) {

            $product = new Product($userResponse['ID_PRODUCT'], $userResponse['NAME'], $userResponse['DESCRIPTION'], $userResponse['PRICE'],
                $userResponse['PHOTOPATH'], $userResponse['DATE'], $userResponse['INGREDIENTS'], $userResponse['CATEGORY']);

            if (strcmp($userResponse['OFTHEDAY'], "0") == 0) {
                $product->setOftheday(false);
            } else {
                $product->setOftheday(true);
                $product->setPrice($userResponse['PRICE_OFTHEDAY']);
            }

            $product->setLiked($this->likeDao->checkLike($u, $product));

            $arrayToReturn[$indexProduct] = $product;

            $indexProduct++;
        }

        return $arrayToReturn;
    }

    /**
     * Retorna el producte amb id passat com a paràmetre.
     *
     * @param $id
     * @return null|Product
     */
    public function findThisProduct($id) {

        $query = "SELECT * FROM PRODUCTS WHERE ENABLED=1 AND ID_PRODUCT=" . $id;

        $prodToReturn = null;

        $result = parent::query($query);

        if ($userResponse = $result->fetch_assoc()){
            $prodToReturn = new Product($userResponse['ID_PRODUCT'], $userResponse['NAME'], $userResponse['PRICE']);
        }

        return $prodToReturn;
    }

    /**
     * Habilita un producte.
     *
     * @param $id
     */
    public function enableProduct($id) {
        $query = "UPDATE PRODUCTS SET ENABLED=TRUE WHERE ID_PRODUCT=" . $id;
        parent::query($query);
    }

    /**
     * Deshabilita un producte.
     *
     * @param $id
     */
    public function disableProduct($id) {
        $query = "UPDATE PRODUCTS SET ENABLED=FALSE WHERE ID_PRODUCT=" . $id;
        parent::query($query);
    }

    /**
     * Defineix el nou producte del dia.
     *
     * @param $id
     */
    public function newProductOfTheDay($id) {
        $query = "UPDATE PRODUCTS SET OFTHEDAY=FALSE";
        parent::query($query);

        $query = "UPDATE PRODUCTS SET OFTHEDAY=TRUE WHERE ID_PRODUCT=" . $id;
        parent::query($query);
    }

    /**
     * Completa el camp PHOTO_PATH de la BBDD del producte passat com a paràmetre
     *
     * @param Product $newProduct
     * @param $targetPath
     */
    public function setPhotoPath(Product $newProduct, $targetPath) {
        $query = "UPDATE PRODUCTS SET PHOTOPATH='$targetPath' WHERE ID_PRODUCT=" . $newProduct->getId();
        parent::query($query);
    }

}