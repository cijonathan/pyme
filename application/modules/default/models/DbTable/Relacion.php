<?php

class Default_Model_DbTable_Relacion extends Zend_Db_Table_Abstract
{
    protected $_name = 'modulo_relacion';
    
    public function listarTipo(){
        /* [INSTANCEAR BASE PERSONALIZADA] */
        $base = $this->base();
        /* [CONSULTA] */
        $consulta = $base->select()->from('modulo_relacion_tipo','*')
                ->order('nombre_tipo ASC');
        return $base->fetchAll($consulta);               
    }
    public function listarCardinalidad(){
        /* [INSTANCEAR BASE PERSONALIZADA] */
        $base = $this->base();
        /* [CONSULTA] */
        $consulta = $base->select()->from('modulo_relacion_cardinalidad','*')
                ->order('nombre_cardinalidad ASC');
        return $base->fetchAll($consulta);           
    }
    public function crear($datos){
        if(is_array($datos)){
            if($this->insert($datos)){
                $datos = (object)$datos;
                if($this->crearestructura($datos->id_cardinalidad, $datos->id_padre, $datos->id_hijo)){
                    return true;
                }else{
                    return false;
                }
            }
        }
    }
    private function crearestructura($cardinalidad,$padre,$hijo){
        if(is_numeric($cardinalidad) && is_numeric($padre) && is_numeric($hijo)){
            /* [OBTENER DATOS] */
            $modulo = new Default_Model_DbTable_Modulo();
            $modulopadre = $modulo->obtener($padre);
            $modulohijo = $modulo->obtener($hijo);      
            /* [INSTANCEAR BASE PERSONALIZADA] */
            $base = $this->basepersonalizado($modulopadre->id_empresa);
            /* [ULTIMO CAMPO] */
            $columnas = array_keys($base->describeTable($modulohijo->nombre_modulo_slug));
            $campo_final = $columnas[(count($columnas)-2)-1];
            /* [TIPO CARDINALIDAD] */
            if($cardinalidad == 1 || $cardinalidad == 3){
                /* [CREACION DE CAMPO] */
                $consulta = "ALTER TABLE `".$modulohijo->nombre_modulo_slug."` ADD `id_".$modulopadre->nombre_modulo_slug."` INT NULL AFTER `".$campo_final."`";                
                /* [CLAVE FOREANA]*/
                /*$estructura .="INDEX `fk_".$modulopadre->nombre_modulo_slug."_relacion_".$modulohijo->nombre_modulo_slug."_galeria` (`id_".$datosmodulo->nombre_modulo_slug."` ASC) ,";
                $estructura .="CONSTRAINT `fk_".$datosmodulo->nombre_modulo_slug."_relacion_".$datosmodulo->nombre_modulo_slug."_galeria`";
                $estructura .="FOREIGN KEY (`id_".$datosmodulo->nombre_modulo_slug."` )";
                $estructura .="REFERENCES `".$datosproyecto->basededatos_empresa."`.`".$datosmodulo->nombre_modulo_slug."` (`id_".$datosmodulo->nombre_modulo_slug."` )";
                $estructura .=" ON DELETE NO ACTION ON UPDATE NO ACTION";*/
            }elseif($cardinalidad == 2){
                
            }else{
                return false;
            }
            echo $consulta;
        }
    }
    private function base(){
        /* [BASE DE DATOS PERSONALIZADA] */
        $config = new Zend_Config_Ini('../application/configs/application.ini', 'production');
        $db = Zend_Db::factory('Pdo_Mysql', $config->resources->db->params);
        $db->setFetchMode(Zend_Db::FETCH_OBJ);        
        return $db;        
    }    
    private function basepersonalizado($id_empresa){                
        if($id_empresa>0 && is_numeric($id_empresa)){     
            /* [OBTENER DATOS DE CONEXION] */
            $empresa = new Default_Model_DbTable_Proyecto();
            $datos = $empresa->obtener($id_empresa);            
            /* [INSTANCIA DE CONEXION] */
            $conexion = array(
                'host'       => $datos->servidor_empresa,
                'username'   => $datos->usuario_empresa,
                'password'   => $datos->clave_empresa,
                'dbname'     => $datos->basededatos_empresa,
                'persistent' => true
            );
            #var_dump($conexion); die;
            /* [CONEXION] */
            $db = Zend_Db::factory('Pdo_Mysql',$conexion);  
            $db->setFetchMode(Zend_Db::FETCH_OBJ); 
            return $db;
        }
    }    
}