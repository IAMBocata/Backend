<?php

/**
 * Singleton Product DAO
 * User: Josep
 * Date: 07/04/2018
 * Time: 13:51
 */
class ProductDaoSingleton {

    private static $productDao;

    public static function getProductDao() {

        if (self::$productDao == null) {
            self::$productDao = new ProductDao();
        }

        return self::$productDao;
    }

}