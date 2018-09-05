<?php

/**
 * Classe per accedir a les dades de les categories a la BBDD.
 *
 * User: Josep
 * Date: 10/04/2018
 * Time: 0:34
 */

include_once('Dao.php');

class CategoryDao extends Dao {

    /**
     * CategoryDao constructor.
     */
    public function CategoryDao() {
        parent::Dao();
    }

    /**
     * Retorna una llista amb els noms de les categories existents.
     *
     * @return array
     */
    public function findAllNames() {

        $arrayToReturn = [];
        $index = 0;

        $query = "SELECT * FROM CATEGORIES";

        $result = parent::query($query);

        while ($categoryName = $result->fetch_assoc()) {
            $arrayToReturn[$index] = $categoryName['NAME'];
            $index++;
        }

        return $arrayToReturn;
    }

    /**
     * Inserta una nova categoria a la BBDD.
     *
     * @param $category
     */
    public function insert($category) {
        $query = "INSERT INTO CATEGORIES (NAME) VALUES ('$category')";
        parent::query($query);
    }

}