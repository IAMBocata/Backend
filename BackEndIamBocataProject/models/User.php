<?php

/**
 * User entity
 * User: yous
 * Date: 6/04/18
 * Time: 17:07
 */
class User implements JsonSerializable {

    private $id;
    private $name;
    private $password;
    private $mail;
    private $photoUrl;
    private $qrPhotoUrl;
    private $registerDate;
    private $money;
    private $isGoogleUser;
    private $permissionLevel;
    private $isEnabled;
    private $googleId;

    /**
     * User constructor.
     * @param $name
     * @param $password
     * @param $mail
     * @param $photoUrl
     * @param $qrPhotoUrl
     */
    public function __construct() {
    }

    public function getEncryptedPassword() {
        return password_hash($this->password, PASSWORD_DEFAULT);
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getMail() {
        return $this->mail;
    }

    /**
     * @param mixed $mail
     */
    public function setMail($mail) {
        $this->mail = $mail;
    }

    /**
     * @return mixed
     */
    public function getPhotoUrl() {
        return $this->photoUrl;
    }

    /**
     * @param mixed $photoUrl
     */
    public function setPhotoUrl($photoUrl) {
        $this->photoUrl = $photoUrl;
    }

    /**
     * @return mixed
     */
    public function getQrPhotoUrl() {
        return $this->qrPhotoUrl;
    }

    /**
     * @param mixed $qrPhotoUrl
     */
    public function setQrPhotoUrl($qrPhotoUrl) {
        $this->qrPhotoUrl = $qrPhotoUrl;
    }

    /**
     * @return mixed
     */
    public function getRegisterDate() {
        return $this->registerDate;
    }

    /**
     * @param mixed $registerDate
     */
    public function setRegisterDate($registerDate) {
        $this->registerDate = $registerDate;
    }

    /**
     * @return mixed
     */
    public function getMoney() {
        return $this->money;
    }

    /**
     * @param mixed $money
     */
    public function setMoney($money) {
        $this->money = $money;
    }

    /**
     * @param mixed $isGoogleUser
     */
    public function setIsGoogleUser($isGoogleUser)
    {
        $this->isGoogleUser = $isGoogleUser;
    }

    /**
     * @return mixed
     */
    public function getIsGoogleUser()
    {
        return $this->isGoogleUser;
    }

    /**
     * @param mixed $permissionLevel
     */
    public function setPermissionLevel($permissionLevel)
    {
        $this->permissionLevel = $permissionLevel;
    }

    /**
     * @return mixed
     */
    public function getPermissionLevel()
    {
        return $this->permissionLevel;
    }

    /**
     * @return mixed
     */
    public function getIsEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * @param mixed $isEnabled
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;
    }

    /**
     * @param mixed $googleId
     */
    public function setGoogleId($googleId)
    {
        $this->googleId = $googleId;
    }

    /**
     * @return mixed
     */
    public function getGoogleId()
    {
        return $this->googleId;
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
            'name' => $this->name,
            'googleId' => $this->googleId,
            'mail' => $this->mail
        ];
    }

}