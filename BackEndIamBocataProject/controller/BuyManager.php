<?php

/**
 * Classe per gestionar compres.
 * User: yous
 * Date: 20/04/18
 * Time: 17:48
 */
include_once (dirname(__FILE__) . '/../bbdd/ProductDao.php');
include_once (dirname(__FILE__) . '/../bbdd/UserDao.php');
include_once (dirname(__FILE__) . '/../bbdd/BuyDao.php');
include_once(dirname(__FILE__) . '/Notificator.php');

class BuyManager {

    private $checkoutObjects;
    private $user;
    private $totalBD;
    private $dateDelivery;

    private $userDao;
    private $productDao;
    private $buyDao;

    /**
     * BuyManager constructor.
     */
    public function __construct() {

        $this->userDao = new UserDao();
        $this->productDao = new ProductDao();
        $this->buyDao = new BuyDao();

        $args = func_get_args();
        $nargs = func_num_args();

        switch ($nargs) {

            case 3:
                self::constructForBuy($args[0], $args[1], $args[2]);
                break;


        }
    }

    /**
     * Constructor per comprar.
     *
     * @param $checkout
     * @param $iduser
     * @param $dateDelivery
     */
    private function constructForBuy($checkout, $iduser, $dateDelivery) {
        $this->dateDelivery = $dateDelivery;
        $this->user = $this->userDao->findThisUser($iduser);

        $productsAndQuantities = explode(',', $checkout);

        $index = 0;
        $totalBD = 0;

        // Parsea lo que viene de la app y hace el total de la compra

        foreach ($productsAndQuantities as $productAndQuantity) {

            $paq = explode('-', $productAndQuantity);

            $prod = $this->productDao->findThisProduct($paq[0]);

            $checkout = new CheckOut($prod, $paq[1]);
            $totalBD += $checkout->getTotal();

            $this->checkoutObjects[$index] = $checkout;
            $index++;
        }

        $this->totalBD = (float) $totalBD;
    }

    /**
     * Comprova si el total coincideix amb els preus de la BBDD.
     *
     * @param $total
     * @return bool
     */
    public function checkIfTotalIsRight($total) {

        if (strcmp((string) $total, (string) $this->totalBD) == 0) {
            return true;
        }

        return false;
    }

    /**
     * Es comprova que l'usuari tingui suficients diners per comprar
     *
     * @return bool
     */
    public function checkCanBuy() {
        if ($this->user->getMoney() >= $this->totalBD)
            return true;

        return false;
    }

    /**
     * Realitza una compra.
     *
     * @param $token
     */
    public function buy($token) {
        $this->userDao->substractMoney($this->totalBD, $this->user);
        $this->buyDao->buy($this->checkoutObjects, $this->totalBD, $this->user, $this->dateDelivery, $token);
    }

    /**
     * Retorna totes les compres que no estiguin finalitzades o cancel·lades.
     *
     * @return array
     */
    public function getStartedBuys() {
        return $this->buyDao->findAllStarteds();
    }

    /**
     * Retorna les compres de l'usuari passat com a paràmetre.
     *
     * @param User $u
     * @return array
     */
    public function getBuysOfThisUser(User $u) {
        return $this->buyDao->getBuysOfThisUser($u);
    }

    /**
     * Finalitza una compra.
     *
     * @param $idBuy
     * @param User $user
     * @return bool
     */
    public function endBuy($idBuy, User $user) {

        if ($user->getPermissionLevel() < 2) {
            return false;
        }

        $this->buyDao->endBuy($idBuy);

        $token = $this->buyDao->getToken($idBuy);

        Notificator::notify($token, 'Comanda llesta per ser recollida');

        return true;
    }

    /**
     * Comença una compra.
     *
     * @param $idBuy
     * @param User $user
     * @return bool
     */
    public function startBuy($idBuy, User $user) {

        if ($user->getPermissionLevel() < 2) {
            return false;
        }

        $this->buyDao->startBuy($idBuy);

        $token = $this->buyDao->getToken($idBuy);

        Notificator::notify($token, 'La teva comanda comença a preparar-se');

        return true;
    }

    /**
     * Retorna el TOP 10 productes que més es compren.
     *
     * @return array
     */
    public function topBuys() {
        return $this->buyDao->topBuys();
    }

    /**
     * Retorna el TOP 10 usuaris que més compren.
     *
     * @return array
     */
    public function topUsers() {
        return $this->buyDao->topUsersBuying();
    }

    /**
     * Retorna les compres de la setmana actual, dividit per els diferents dies.
     *
     * @return array
     */
    public function buysOfThisWeek() {
        return $this->buyDao->buysOfThisWeek();
    }

    /**
     * Retorna les compres del mes actual, dividit per els diferents dies.
     *
     * @return array
     */
    public function buysOfThisMonth() {
        return $this->buyDao->buysOfThisMonth();
    }

    /**
     * Retorna les compres de l'any actual, dividit per els diferents mesos.
     *
     * @return array
     */
    public function buysOfThisYear() {
        return $this->buyDao->buysOfThisYear();
    }

    /**
     * Retorna totes les compres realizades des de sempre dividides per les diferents hores del dia.
     *
     * @return array
     */
    public function hourOfBuys() {
        return $this->buyDao->hourOfBuys();
    }

    /**
     * Retorna els diners facturats avui, aquesta setmana, aquest mes i aquest any.
     *
     * @return array
     */
    public function moneyOfDifferentTimes() {

        $toReturn = array();

        $toReturn['today'] = $this->buyDao->totalOfToday();
        $toReturn['week'] = $this->buyDao->totalOfWeek();
        $toReturn['month'] = $this->buyDao->totalOfMonth();
        $toReturn['year'] = $this->buyDao->totalOfYear();

        return $toReturn;
    }

    /**
     * Cancel·la una compra.
     *
     * @param $idBuy
     * @param User $user
     * @return bool
     */
    public function cancelBuy($idBuy, User $user) {

        if ($user->getPermissionLevel() < 2) {
            return false;
        }

        $buy = $this->buyDao->getThisBuy($idBuy, $user);

        if ($buy == null) {
            return false;
        } else {

            if ($this->buyDao->cancelBuy($buy)) {

                Notificator::notify($buy->getToken(), 'La teva comanda no ha pogut realitzar-se. Se t\'han retornat els diners');
                return true;
            }
        }

        return false;
    }

}