<?php

/**
 * Buy entity.
 * User: yous
 * Date: 24/04/18
 * Time: 15:12
 */
include_once('CheckOut.php');

class Buy implements JsonSerializable {

    private $id;
    private $user;
    private $idUser;
    private $checkOutObjects;
    private $buydate;
    private $deliveryDate;
    private $state;
    private $token;

    /**
     * Buy constructor.
     */
    function __construct() {
        $this->checkOutObjects = [];
    }

    /**
     * @param CheckOut $checkOut
     */
    public function addCheckOut(CheckOut $checkOut) {
        $this->checkOutObjects[] = $checkOut;
        // array_push() ??
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getBuydate()
    {
        return $this->buydate;
    }

    /**
     * @return mixed
     */
    public function getCheckOutObjects()
    {
        return $this->checkOutObjects;
    }

    /**
     * @return mixed
     */
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $buydate
     */
    public function setBuydate($buydate)
    {
        $this->buydate = $buydate;
    }

    /**
     * @param mixed $checkOutObjects
     */
    public function setCheckOutObjects($checkOutObjects)
    {
        $this->checkOutObjects = $checkOutObjects;
    }

    /**
     * @param mixed $deliveryDate
     */
    public function setDeliveryDate($deliveryDate)
    {
        $this->deliveryDate = $deliveryDate;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param mixed $idUser
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'checkouts' => $this->checkOutObjects,
            'buydate' => $this->buydate,
            'deliverDate' => $this->deliveryDate,
            'state' => $this->state
        ];
    }
}