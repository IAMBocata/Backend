<?php

    /**
     * Classe per gestionar logins de usuaris de google.
     * User: yous
     * Date: 13/04/18
     * Time: 16:42
     */

    require_once('../constants/ConstantsSecurity.php');
    include_once('../bbdd/UserDao.php');
    include_once('../models/User.php');
    include_once('GoogleRegister.php');

    class GoogleLogin {

        private $googleUser;

        private $userDao;

        /**
         * GoogleLogin constructor.
         * @param User $googleUser
         */
        function __construct(User $googleUser) {
            $this->googleUser = $googleUser;
            $this->userDao = new UserDao();
        }

        /**
         * Login.
         *
         * @return null|User
         */
        public function loginUserOrRegisterIfNotExists() {

            $user = $this->userDao->checkIfThisGoogleUserExists($this->googleUser);

            if ($user == null) {
                return (new GoogleRegister($this->googleUser, $this->userDao))->register();
            }

            if (!$user->getIsEnabled()) {
                return null;
            }

            return $user;
        }

    }

?>