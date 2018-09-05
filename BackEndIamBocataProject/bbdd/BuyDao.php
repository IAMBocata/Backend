<?php

/**
 * Classe per accedir a les dades de les compres a la BBDD.
 * User: yous
 * Date: 20/04/18
 * Time: 18:20
 */

include_once('Dao.php');

class BuyDao extends Dao {

    /**
     * BuyDao constructor.
     */
    public function BuyDao() {
        parent::Dao();
    }

    /**
     * Realitza una compra.
     *
     * @param array $checkoutObjects
     * @param $totalBD
     * @param User $user
     * @param $dateDelivery
     * @param $token
     */
    public function buy(array $checkoutObjects, $totalBD, User $user, $dateDelivery, $token) {

        $idBuy = self::getLastId()+1;

        for ($i = 0; $i < count($checkoutObjects); $i++) {
            self::insertCheckOutObject($checkoutObjects[$i], $i, $dateDelivery, $user, $idBuy, $token);
        }
    }

    /**
     * Inserta una row de checkout (Part de una compra) a la BBDD
     *
     * @param CheckOut $checkOut
     * @param $i
     * @param $dateDelivery
     * @param $user
     * @param $idBuy
     * @param $token
     */
    private function insertCheckOutObject(CheckOut $checkOut, $i, $dateDelivery, $user, $idBuy, $token) {
        $query = "INSERT INTO BUY (ID_BUY, ID_CHECKOUT, STATE, BUYDATE, DATE_DELIVERY, ID_USER, ID_PRODUCT, QUANTITY, TOTAL, TOKEN) " .
            "VALUES (" . $idBuy . ", " . ($i+1) . ", 'SENDED', NOW(), '" . $dateDelivery . "', " . $user->getId() . ", " .
            $checkOut->getProduct()->getId() . ", " . $checkOut->getQuantity() . ", " . $checkOut->getTotal() . ",  '$token')";

        parent::query($query);
    }

    /**
     * Retorna el id de la última compra.
     *
     * @return int
     */
    private function getLastId() {

        $query = "SELECT MAX(ID_BUY) AS MAXID FROM BUY";

        $result = parent::query($query);

        if ($maxId = $result->fetch_assoc()){
            return $maxId['MAXID'];
        }

        return 1;

    }

    /**
     * Retorna totes les compres que no estiguin finalitzades o cancel·lades.
     *
     * @return array
     */
    public function findAllStarteds() {

        $query = "SELECT B.ID_BUY, B.ID_CHECKOUT, B.STATE, B.BUYDATE, B.DATE_DELIVERY, B.QUANTITY, B.TOTAL, " .
            "P.NAME, P.PRICE, U.NAME AS USERNAME FROM BUY B JOIN PRODUCTS P ON P.ID_PRODUCT=B.ID_PRODUCT JOIN USERS U " .
            "ON U.ID_USER=B.ID_USER WHERE STATE='STARTED' OR STATE='SENDED' ORDER BY DATE_DELIVERY ASC";

        $buyList = [];

        $b = null;

        $result = parent::query($query);

        $idBuy = 0;

        while ($row = $result->fetch_assoc()) {

            if ($idBuy != $row['ID_BUY']) {

                $idBuy = $row['ID_BUY'];

                $u = new User();
                $u->setName($row['USERNAME']);

                $b = new Buy();
                $b->setBuydate($row['BUYDATE']);
                $b->setDeliveryDate($row['DATE_DELIVERY']);
                $b->setId($idBuy);
                $b->setUser($u);
                $b->setState($row['STATE']);

                $buyList[] = $b;
            }

            $p = new Product($row['NAME'], $row['PRICE']);

            $checkOut = new CheckOut($p, $row['QUANTITY']);
            $b->addCheckOut($checkOut);

        }

        return $buyList;
    }

    /**
     * Retorna tot el historial de compres de un usuari.
     *
     * @param User $u
     * @return array
     */
    public function getBuysOfThisUser(User $u) {

        $idBuys = self::getIdBuysOfThisUser($u);

        if (count($idBuys) == 0) {
            return [];
        }

        $buys = [];
        $index = 0;

        foreach ($idBuys as $idBuy) {
            $buys[$index] = $this->getThisBuy($idBuy, $u);
            $index++;
        }

        return $buys;
    }

    /**
     * Retorna el id de totes les compres de un usuari.
     *
     * @param User $u
     * @return array
     */
    private function getIdBuysOfThisUser(User $u) {

        $query = "SELECT ID_BUY FROM BUY WHERE ID_USER = " . $u->getId() . " GROUP BY ID_BUY ORDER BY ID_BUY DESC";

        $result = parent::query($query);

        $buysId = [];
        $index = 0;

        while ($row = $result->fetch_assoc()) {
            $buysId[$index] = (int) $row['ID_BUY'];
            $index++;
        }

        return $buysId;
    }

    /**
     * Retorna la compra que té el id passat com a paràmetre.
     *
     * @param $idBuy
     * @param User $u
     * @return Buy
     */
    public function getThisBuy($idBuy, User $u) {

        $query = "SELECT * FROM BUY WHERE ID_BUY = " . $idBuy;

        $result = parent::query($query);

        $first = true;

        $buyToReturn = new Buy();
        $buyToReturn->setUser($u);
        $buyToReturn->setId($idBuy);

        $checkOuts = [];

        while ($row = $result->fetch_assoc()) {

            if ($first) {
                $buyToReturn->setBuydate($row['BUYDATE']);
                $buyToReturn->setDeliveryDate($row['DATE_DELIVERY']);
                $buyToReturn->setState($row['STATE']);
                $buyToReturn->setToken($row['TOKEN']);
                $buyToReturn->setIdUser($row['ID_USER']);
            }

            $checkOut = new CheckOut(new Product(), $row['QUANTITY']); // El 'new Product()' no val per res, pero aixi no peta
            $checkOut->setIdProduct($row['ID_PRODUCT']);

            $checkOuts[] = $checkOut;
        }

        $buyToReturn->setCheckOutObjects($checkOuts);

        return $buyToReturn;
    }

    /**
     * Finaliza una compra.
     * @param $idBuy
     */
    public function endBuy($idBuy) {
        $query = "UPDATE BUY SET STATE='ENDED' WHERE ID_BUY=$idBuy";
        parent::query($query);
    }

    /**
     * Retorna el token de una compra
     *
     * @param $idBuy
     * @return null
     */
    public function getToken($idBuy) {

        $query = "SELECT TOKEN FROM BUY WHERE ID_BUY='$idBuy'";

        $result = parent::query($query);

        if ($row = $result->fetch_assoc()) {

            return $row['TOKEN'];
        }

        return null;
    }

    /**
     * Comença una compra
     *
     * @param $idBuy
     */
    public function startBuy($idBuy) {
        $query = "UPDATE BUY SET STATE='STARTED' WHERE ID_BUY=$idBuy";
        parent::query($query);
    }

    /**
     * Retorna el TOP 10 productes que més es compren.
     *
     * @return array
     */
    public function topBuys() {
        $query = "SELECT (SELECT NAME FROM PRODUCTS P WHERE P.ID_PRODUCT=B.ID_PRODUCT) " .
                    "AS 'PRODUCT', COUNT(ID_PRODUCT) AS 'VENTAS' FROM BUY B GROUP BY ID_PRODUCT LIMIT 10";

        $result = parent::query($query);

        $toReturn = array();

        while ($row = $result->fetch_assoc()) {
            $toReturn[] = array(
                'product' => $row['PRODUCT'],
                'buys' => $row['VENTAS']
            );
        }

        return $toReturn;
    }

    /**
     * Retorna el TOP 10 usuaris que més compren.
     *
     * @return array
     */
    public function topUsersBuying() {
        $query = "SELECT COUNT(ID_USER) AS BUYS, (SELECT NAME FROM USERS WHERE ID_USER=B.ID_USER) " .
                        "AS USER FROM BUY B GROUP BY ID_USER LIMIT 10";

        $result = parent::query($query);

        $toReturn = array();

        while ($row = $result->fetch_assoc()) {
            $toReturn[] = array(
                'user' => $row['USER'],
                'buys' => $row['BUYS']
            );
        }

        return $toReturn;
    }

    /**
     * Retorna les compres de la setmana actual, dividit per els diferents dies.
     *
     * @return array
     */
    public function buysOfThisWeek() {
        $query = "SELECT COUNT(*) AS 'BUYS', DAYOFWEEK(`BUYDATE`) AS 'DAY' FROM `BUY` WHERE WEEK(BUYDATE)"
                    . " = WEEK(NOW()) GROUP BY DAYOFWEEK(`BUYDATE`)";

        $result = parent::query($query);

        $toReturn = array();

        while ($row = $result->fetch_assoc()) {
            $toReturn[] = array(
                'day' => $row['DAY'],
                'buys' => $row['BUYS']
            );
        }

        return $toReturn;
    }

    /**
     * Retorna les compres del mes actual, dividit per els diferents dies.
     *
     * @return array
     */
    public function buysOfThisMonth() {
        $query = "SELECT COUNT(*) AS 'BUYS', DAYOFMONTH(`BUYDATE`) AS 'DAY' FROM `BUY` WHERE MONTH(BUYDATE) " .
                        " = MONTH(NOW()) GROUP BY DAYOFMONTH(`BUYDATE`)";

        $result = parent::query($query);

        $toReturn = array();

        while ($row = $result->fetch_assoc()) {
            $toReturn[] = array(
                'day' => $row['DAY'],
                'buys' => $row['BUYS']
            );
        }

        return $toReturn;
    }

    /**
     * Retorna les compres de l'any actual, dividit per els diferents mesos.
     *
     * @return array
     */
    public function buysOfThisYear() {
        $query = "SELECT COUNT(*) AS 'BUYS', MONTH(`BUYDATE`) AS 'MONTH' FROM `BUY` WHERE YEAR(BUYDATE) = " .
            "YEAR(NOW()) GROUP BY MONTH(`BUYDATE`)";

        $result = parent::query($query);

        $toReturn = array();

        while ($row = $result->fetch_assoc()) {
            $toReturn[] = array(
                'month' => $row['MONTH'],
                'buys' => $row['BUYS']
            );
        }

        return $toReturn;
    }

    /**
     * Retorna els diners generats avui.
     *
     * @return null | int
     */
    public function totalOfToday() {

        $query = "SELECT SUM(TOTAL) AS TOTAL_TODAY FROM BUY WHERE DAY(BUYDATE) = DAY(NOW()) AND " .
                        " MONTH(BUYDATE) = MONTH(NOW()) AND YEAR(BUYDATE) = YEAR(NOW()) ";

        $result = parent::query($query);

        if ($row = $result->fetch_assoc()) {
            return $row['TOTAL_TODAY'];
        }

        return null;
    }

    /**
     * Retorna els diners generats aquesta setmana.
     *
     * @return null
     */
    public function totalOfWeek() {

        $query = "SELECT SUM(TOTAL) AS TOTAL_WEEK FROM BUY WHERE YEAR(BUYDATE) = YEAR(NOW()) " .
                        " AND WEEKOFYEAR(NOW()) = WEEKOFYEAR(BUYDATE)";

        $result = parent::query($query);

        if ($row = $result->fetch_assoc()) {
            return $row['TOTAL_WEEK'];
        }

        return null;
    }

    /**
     * Retorna els diners generats aquest mes.
     *
     * @return null
     */
    public function totalOfMonth() {

        $query = "SELECT SUM(TOTAL) AS TOTAL_MONTH FROM BUY WHERE" .
            " MONTH(BUYDATE) = MONTH(NOW()) AND YEAR(BUYDATE) = YEAR(NOW()) ";

        $result = parent::query($query);

        if ($row = $result->fetch_assoc()) {
            return $row['TOTAL_MONTH'];
        }

        return null;
    }

    /**
     * Retorna els diners generats aquest any.
     *
     * @return null
     */
    public function totalOfYear() {

        $query = "SELECT SUM(TOTAL) AS TOTAL_YEAR FROM BUY WHERE YEAR(BUYDATE) = YEAR(NOW()) ";

        $result = parent::query($query);

        if ($row = $result->fetch_assoc()) {
            return $row['TOTAL_YEAR'];
        }

        return null;
    }

    /**
     * Retorna totes les compres realizades des de sempre dividides per les diferents hores del dia.
     *
     * @return array
     */
    public function hourOfBuys() {

        $query = "SELECT COUNT(*) AS BUYS, HOUR(BUYDATE) AS HOUR FROM `BUY` WHERE 1 GROUP BY HOUR(BUYDATE)";

        $result = parent::query($query);

        $toReturn = array();

        while ($row = $result->fetch_assoc()) {
            $toReturn[] = array(
                'hour' => $row['HOUR'],
                'buys' => $row['BUYS']
            );
        }

        return $toReturn;

    }

    /**
     * Cancel·la una compra.
     *
     * @param Buy $buy
     * @return bool|mysqli_result
     */
    public function cancelBuy(Buy $buy) {

        $queryCancel = "UPDATE BUY SET STATE='CANCELED' WHERE ID_BUY=" . $buy->getId();

        if (!parent::query($queryCancel)) {
            return false;
        }

        $queryUpdate = "UPDATE USERS SET MONEY = MONEY + (SELECT SUM(TOTAL) FROM BUY WHERE ID_BUY=" . $buy->getId() .
            " AND STATE<>'ENDED') WHERE ID_USER=" . $buy->getIdUser();

        return parent::query($queryUpdate);

    }

}