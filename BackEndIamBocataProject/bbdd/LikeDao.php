<?php

/**
 * Classe per accedir a les dades dels likes a la BBDD.
 *
 * User: yous
 * Date: 26/04/18
 * Time: 10:14
 */
class LikeDao extends Dao {

    /**
     * LikeDao constructor.
     */
    function __construct() {
        parent::Dao();
    }

    /**
     * Crea un nou like.
     *
     * @param User $user
     * @param Product $product
     */
    public function like(User $user, Product $product) {

        $query = "INSERT INTO LIKES (ID_USER, ID_PRODUCT) VALUES (" . $user->getId() . ", " . $product->getId() . ")";

        parent::query($query);
    }

    /**
     * Elimina un like existent.
     *
     * @param User $user
     * @param Product $product
     */
    public function dislike(User $user, Product $product) {

        $query = "DELETE FROM LIKES WHERE ID_USER=" . $user->getId() . " AND ID_PRODUCT=" . $product->getId();

        parent::query($query);
    }

    /**
     * Comprova si un usuari té likejat un producte.
     *
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function checkLike(User $user, Product $product) {

        $query = "SELECT * FROM LIKES WHERE ID_USER=" . $user->getId() . " AND ID_PRODUCT=" . $product->getId();

        $result = parent::query($query);

        if ($likeResponse = $result->fetch_assoc()) {
            return true;
        }

        return false;
    }

    /**
     * Retorna el TOP 10 productes més likejats.
     *
     * @return array
     */
    public function topLikes() {

        $query = "SELECT COUNT(ID_PRODUCT) AS LIKES, (SELECT NAME FROM PRODUCTS WHERE ID_PRODUCT=L.ID_PRODUCT) AS NAME FROM LIKES L GROUP BY ID_PRODUCT";

        $result = parent::query($query);

        $toReturn = array();

        while ($row = $result->fetch_assoc()) {
            $toReturn[] = array(
                'likes' => $row['LIKES'],
                'product' => $row['NAME']
            );
        }

        return $toReturn;
    }
}