<?php

/**
 * Classe amb funcions amb utilitats.
 * User: yous
 * Date: 10/04/18
 * Time: 15:36
 */
class EncoderUtils {

    /**
     * Codifica en UTF-8 al array passat com a paràmetre.
     *
     * @param $d
     * @return array|string
     */
    public static function utf8ize($d) {
        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = self::utf8ize($v);
            }
        } else if (is_string ($d)) {
            return utf8_encode($d);
        }
        return $d;
    }

    /**
     * Converteix a json el objecte o array passat
     *
     * @param $item
     * @return string
     */
    public static function json_encode_objs($item){
        if(!is_array($item) && !is_object($item)){
            return json_encode(self::utf8ize($item));
        }else{
            $pieces = array();
            foreach($item as $k=>$v){
                $pieces[] = "\"$k\":". self::json_encode_objs($v);
            }
            return '{'.implode(',',$pieces).'}';
        }
    }
}