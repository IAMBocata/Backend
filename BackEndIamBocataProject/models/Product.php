<?php

/**
 * Class Product entity
 */
class Product implements JsonSerializable {

    private $id;
    private $name;
    private $description;
    private $photoPath;
    private $photoFile;
    private $photoPathMobile;
    private $price;
    private $priceoftheday;
    private $ingredients;
    private $dateAdded;
    private $category;
    private $liked;
    private $oftheday;
    private $enabled;

    /**
     * Product constructor.
     *
     */
    public function Product() {

        $args = func_get_args();
        $nargs = func_num_args();

        switch ($nargs) {
            case 2:
                self::constructNamePrice($args[0], $args[1]);
                break;
            case 3:
                self::constructIdNamePrice($args[0], $args[1], $args[2]);
                break;
            case 8:
                self::constructProductAllArgs($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7]);
                break;
        }
    }

    private function constructIdNamePrice($id, $name, $price) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    // // ID_PRODUCT | NAME    | DESCRIPTION | PRICE | DATE_ADDED| INGREDIENTS | CATEGORY
    public function constructProductAllArgs($id, $name, $description, $price, $photoPath, $dateAdded, $ingredients, $category) {

        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->photoPath = $photoPath;
        $this->price = $price;
        $this->ingredients = $ingredients;
        $this->dateAdded = $dateAdded;
        $this->category = $category;
        $this->photoPathMobile = "";
    }

    private function constructNamePrice($name, $price) {
        $this->name = $name;
        $this->price = $price;
    }

    public function getIngredientsInArray() {
        return explode('-', $this->ingredients);
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

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getPhotoPath() {
        return $this->photoPath;
    }

    /**
     * @return mixed
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getIngredients() {
        return $this->ingredients;
    }

    /**
     * @return mixed
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * @return mixed
     */
    public function getPhotoPathMobile()
    {
        return $this->photoPathMobile;
    }

    /**
     * @param mixed $photoPathMobile
     */
    public function setPhotoPathMobile($photoPathMobile)
    {
        $this->photoPathMobile = $photoPathMobile;
    }

    /**
     * @return mixed
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * @param mixed $dateAdded
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @param mixed $ingredients
     */
    public function setIngredients($ingredients)
    {
        $this->ingredients = $ingredients;
    }

    /**
     * @param mixed $liked
     */
    public function setLiked($liked)
    {
        $this->liked = $liked;
    }

    /**
     * @param mixed $photoPath
     */
    public function setPhotoPath($photoPath)
    {
        $this->photoPath = $photoPath;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getLiked()
    {
        return $this->liked;
    }

    /**
     * @return mixed
     */
    public function getOftheday()
    {
        return $this->oftheday;
    }


    /**
     * @param mixed $oftheday
     */
    public function setOftheday($oftheday)
    {
        $this->oftheday = $oftheday;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return mixed
     */
    public function getPhotoFile()
    {
        return $this->photoFile;
    }

    /**
     * @param mixed $photoFile
     */
    public function setPhotoFile($photoFile)
    {
        $this->photoFile = $photoFile;
    }

    /**
     * @param mixed $priceoftheday
     */
    public function setPriceoftheday($priceoftheday)
    {
        $this->priceoftheday = $priceoftheday;
    }

    /**
     * @return mixed
     */
    public function getPriceoftheday()
    {
        return $this->priceoftheday;
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
            'description' => $this->description,
            'photoPath' => $this->photoPath,
            'price' => $this->price,
            'ingredients' => self::getIngredientsInArray(),
            'dateAdded' => $this->dateAdded,
            'category' => $this->category,
            'oftheday' => $this->oftheday
        ];
    }
}

?>