<?php

class Default_Model_Miscelaneos
{
    public function Amigable($valor,$codificacion = false){
        /* [CODIFICACIÓN] */
        $nombre = trim($valor);
        $nombre = strtolower(preg_replace('/\s+/','-',$nombre));
        if($codificacion){
            $nombre = utf8_decode($nombre);
        }
        $datos = array(
        'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'C'=>'C', 'c'=>'c', 'C'=>'C', 'c'=>'c',
        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'S',
        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
        'ÿ'=>'y', 'R'=>'R', 'r'=>'r', ','=>'','á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u'
        );
        $nombre = strtr($nombre,$datos);
        $nombre = preg_replace('/[^A-Za-z0-9-]+/','',$nombre);    
        $nombre = strtolower($nombre);
        return $nombre;
    }
    public function ClaveAleatoria($tamano = 7){
            $clave = '';
            list($usec, $sec) = explode(' ', microtime());
            mt_srand((float) $sec + ((float) $usec * 100000));
           
            $inputs = array_merge(range('z','a'),range(0,9),range('A','Z'));
     
            for($i=0; $i<$tamano; $i++)
            {
                $clave .= $inputs{mt_rand(0,61)};
            }
            return (string)$clave;
    }
}

