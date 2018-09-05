<?php

/**
 * Classe on es generen QR's.
 * User: yous
 * Date: 13/04/18
 * Time: 17:50
 */

    include_once(dirname(__FILE__) . '/../../libs/qrGenerator/phpqrcode/qrlib.php');
    include_once(dirname(__FILE__) . '/../../constants/ConstantsPaths.php');

    class QRGenerator {

        /**
         * Genera QR per l'usuari amb id passat com a paràmetre.
         *
         * @param $id
         * @return string
         */
        public static function generateQR($id) {

            $fileName = 'qr' . $id . '.png';
            $pathFromProject = ConstantsPaths::PATH_QR_IMAGES . $fileName;
            $completePath = dirname(__FILE__) . '/../../' . $pathFromProject;

            QRcode::png($id, $completePath, QR_ECLEVEL_L, 14, 2, false);

            return $pathFromProject;
        }

    }

?>