<?php

class Default_Model_Normar
{
    public function campo($nombre,$tipo){
        if(is_string($nombre) && is_numeric($tipo)){
            /* [TIPOS] */
            if($tipo == 1){
                $dato = "`".$nombre."` VARCHAR(255) NULL ,";
            }elseif($tipo == 2){
                $dato = "`".$nombre."` LONGTEXT NULL ,";                
            }elseif($tipo == 3){
                $dato = "`".$nombre."` INT(11) NULL ,";                                
            }elseif($tipo == 4){
                $dato = "`".$nombre."` DATE NULL ,";                                                
            }elseif($tipo == 5){
                $dato = "`".$nombre."` VARCHAR(255) NULL ,";                                
            }elseif($tipo == 6){
                $dato = "`".$nombre."` TIME NULL ,";                                                
            }elseif($tipo == 7){
                $dato = "`".$nombre."` VARCHAR(255) NULL ,";                 
            }elseif($tipo == 8){
                if($nombre == 'nombre'){
                    $dato = "`".$nombre."` VARCHAR(255) NULL , `".$nombre."_slug` VARCHAR(255) NULL ,";
                }else{                
                    $dato = "`".$nombre."` VARCHAR(255) NULL ,";
                }
            }elseif($tipo == 9){
                $dato = "`".$nombre."` LONGTEXT NULL ,";                 
            }
            return $dato;
            
        }
    }
    public function tipos($tipo){
        if(is_numeric($tipo)){
            /* [TIPOS] */
            if($tipo == 1){
                $dato = "VARCHAR(255)";
            }elseif($tipo == 2){
                $dato = "LONGTEXT";                
            }elseif($tipo == 3){
                $dato = "INT(11)";                                
            }elseif($tipo == 4){
                $dato = "DATE";                                                
            }elseif($tipo == 5){
                $dato = "VARCHAR(255)";                                
            }elseif($tipo == 6){
                $dato = "TIME";                                                
            }elseif($tipo == 7){
                $dato = "VARCHAR(255)";                 
            }elseif($tipo == 8){
                $dato = "VARCHAR(255)";
            }elseif($tipo == 9){
                $dato = "LONGTEXT";                 
            }
            return $dato;            
        }
    }
}

