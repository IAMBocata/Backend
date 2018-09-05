<?php

/**
 * Classe per gestionar els productes
 * User: yous
 * Date: 13/04/18
 * Time: 19:12
 */
    include_once (dirname(__FILE__) . '/../bbdd/ProductDao.php');
    include_once (dirname(__FILE__) . '/../bbdd/LikeDao.php');
    include_once (dirname(__FILE__) . '/utils/ObjectToArrayConvertor.php');
    include_once (dirname(__FILE__) . '/../constants/ConstantsPaths.php');
    include_once (dirname(__FILE__) . '/ImageAlmacenator.php');

    class ProductManager {

        private $productDao;
        private $likeDao;

        /**
         * ProductManager constructor.
         */
        public function __construct() {
            $this->productDao = new ProductDao();
            $this->likeDao = new LikeDao();
        }

        /**
         * Retorna tots els productes amb categoria en un array.
         *
         * @param User $u
         * @return array
         */
        public function getAllProductsInArray(User $u) {
            $products = $this->productDao->findAllEnabledsWithLike($u);

            return ObjectToArrayConvertor::arrayOfProductsToArrayOfArray($products);
        }

        /**
         * Retorna tots els productes en un array.
         *
         * @return array
         */
        public function getAllProducts() {
            $products = $this->productDao->findAll();
            return ObjectToArrayConvertor::arrayOfProductsToArrayOfArray($products);
        }

        /**
         * Crea un nou like.
         *
         * @param User $user
         * @param Product $product
         */
        public function like(User $user, Product $product) {
            $this->likeDao->like($user, $product);
        }

        /**
         * Retira un like existent.
         *
         * @param User $user
         * @param Product $product
         */
        public function dislike(User $user, Product $product) {
            $this->likeDao->dislike($user, $product);
        }

        /**
         * Habilita un producte.
         *
         * @param $idProduct
         */
        public function enable($idProduct) {
            $this->productDao->enableProduct($idProduct);
        }

        /**
         * Deshabilita un producte.
         *
         * @param $idProduct
         */
        public function disable($idProduct) {
            $this->productDao->disableProduct($idProduct);
        }

        /**
         * Defineix un nou producte del dia.
         *
         * @param $idProduct
         */
        public function setOfTheDay($idProduct) {
            $this->productDao->newProductOfTheDay($idProduct);
        }

        /**
         * Crea un nou producte.
         *
         * @param Product $newProduct
         * @param User $user
         * @return bool
         */
        public function newProduct(Product $newProduct, User $user) {

            if ($user->getPermissionLevel() < 2) {
                return false;
            }

            $file = $newProduct->getPhotoFile();

            $this->productDao->insertProduct($newProduct);

            $newProduct = $this->productDao->completeProduct($newProduct);

            if ($newProduct == null) {
                return false;
            }

            $imageAlmacenator = new ImageAlmacenator($file['name'], $file['tmp_name'], $file['size'],
                                    $file['type'], ConstantsPaths::PATH_PRODUCT_IMAGES);

            $imageAlmacenator->setImageName($newProduct->getId());

            if ($imageAlmacenator->uploadPhoto()) {
                $this->productDao->setPhotoPath($newProduct, $imageAlmacenator->getTargetPath());
            } else {
                return false;
            }

            return true;
        }

        /**
         * Retorna el TOP 10 productes amb mes likes.
         *
         * @return array
         */
        public function topLikes() {
            return $this->likeDao->topLikes();
        }

    }

?>