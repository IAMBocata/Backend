<?php

/**
 * Classe per gestionar categories.
 * User: Josep
 * Date: 16/05/2018
 * Time: 0:35
 */
include_once ('../bbdd/CategoryDao.php');

class CategoryManager {

    private $categoryDao;

    /**
     * CategoryManager constructor.
     */
    public function __construct() {
        $this->categoryDao = new CategoryDao();
    }

    /**
     * Crea una nova categoria a la BBDD.
     *
     * @param $category
     */
    public function create($category) {
        $this->categoryDao->insert($category);
    }

    /**
     * Retorna una llista amb totes les categories.
     *
     * @return array
     */
    public function getAllCategories() {
        return $this->categoryDao->findAllNames();
    }
}