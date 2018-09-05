<?php

include_once(dirname(__FILE__) . '/../constants/ConstantsDB.php');
include_once(dirname(__FILE__) . '/../models/User.php');
include_once(dirname(__FILE__) . '/../models/Buy.php');
include_once(dirname(__FILE__) . '/../models/Category.php');
include_once(dirname(__FILE__) . '/../models/CheckOut.php');
include_once(dirname(__FILE__) . '/../models/Product.php');

/**
 * Classe de la que hereden tots els DAO's.
 * Té la instància de MySQLi amb la que es fa la connexió a la BBDD.
 *
 * User: yous
 * Date: 6/04/18
 * Time: 17:12
 */
abstract class Dao {

    private $DAO; //instancia donde vamos a enchufar el objeto

    /**
     * Dao constructor.
     */
    public function Dao () { //constructor del objeto

        //Crea el objeto en la instancia $DAO y realiza la connex
        $this->DAO = new mysqli(ConstantsDB::DB_SERVER, ConstantsDB::DB_USER, ConstantsDB::DB_PASSWD, ConstantsDB::DB_NAME);

        //Si no se puede connectar con la BBDD, sale este error, con codigo
        if ($this->DAO->connect_errno){
            echo "Connect failed: " . mysqli_connect_error();
            exit();
        }

        //establece la codificación con la que vamos a trabajar
        $this->DAO->set_charset(ConstantsDB::DB_CHARSET);
    }

    /**
     * Métode per fer una query. Utilitzat des de les classes filles.
     *
     * @param $sql
     * @return bool|mysqli_result
     */
    public function query($sql){
        $result = $this->DAO->query($sql);
        return $result;
    }

    /**
     * Retorna la instància de MySQLi.
     *
     * @return mysqli
     */
    public function getDao(){
        return $this->DAO;
    }


}