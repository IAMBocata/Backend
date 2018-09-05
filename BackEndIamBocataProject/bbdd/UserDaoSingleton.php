<?php

/**
 * UserDaoSingleton
 *
 * User: Josep
 * Date: 07/04/2018
 * Time: 13:55
 */
class UserDaoSingleton {

    private static $userDao;

    public static function getUserDao() {

        if (self::$userDao == null) {
            self::$userDao = new UserDao();
        }

        return self::$userDao;
    }

}