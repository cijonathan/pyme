<?php

class Default_Model_DbTable_Relacion extends Zend_Db_Table_Abstract
{
    protected $_name = 'modulo_relacion';
    
    public function listar($id){
        if(is_numeric($id)){
            /* [INSTANCEAR BASE PERSONALIZADA] */
            $base = $this->base();    
            /* [CONSULTA] */
            $consulta = $base->select()->from(array('mr'=>$this->_name),'*')
                    ->joinInner(array('m'=>'modulo'),'mr.id_padre = m.id_modulo',array('nombre_padre'=>'m.nombre_modulo',null))
                    #->joinInner(array('mrt'=>'modulo_relacion_tipo'),'mr.id_relacion = mrt.id_tipo',array('nombre_relacion'=>'mrt.nombre_tipo'))
                    #->joinInner(array('mrc'=>'modulo_relacion_cardinalidad'),'mr.id_cardinalidad = mrc.id_cardinalidad',array('nombre_cardinalidad'=>'mrc.nombre_cardinalidad'))
                    ->where('m.id_empresa = ?',$id)
                    ->order('mr.id_relacion ASC');
            $datos = array();
            foreach($base->fetchAll($consulta) as $retorno){
                $fila = new stdClass();
                $fila->id_relacion = $retorno->id_relacion;
                $fila->nombre_padre = $this->gethijo($retorno->id_padre);
                $fila->nombre_hijo = $this->gethijo($retorno->id_hijo);
                $fila->nombre_relacion = $this->getrelacion($retorno->id_tipo);
                $fila->nombre_cardinalidad = $this->getcardinalidad($retorno->id_cardinalidad);
                $datos[] = $fila;
            }            
            return $datos;
        }
    }
    public function listarelacion(){
        $consulta = $this->select()->setIntegrityCheck(false)
                ->from(array('r'=>'modulo_relacion_tipo'),array('id_relacion'=>'r.id_tipo','nombre_relacion'=>'r.nombre_tipo'))  
                ->order('r.nombre_tipo ASC');
        return $this->fetchAll($consulta);
    }   
    public function eliminarelacion($id){
        if(is_numeric($id)){
            $base = $this->base();
            if($base->delete('modulo_relacion_tipo','id_tipo = '.$id)){
                return true;
            }else{
                return false;
            }
        }
    }    
    public function agregar($datos){
        if(is_array($datos)){
            $base = $this->base();
            if($base->insert('modulo_relacion_tipo', $datos)){
                return true;
            }else{
                return false;
            }
        }
    }
    public function eliminar($id){
        if(is_numeric($id)){   
            /* [DATOS] */
            $modulo = new Default_Model_DbTable_Modulo();
            $datos = $this->obtener($id);
            /* [DATOS DE LOS MODULOS] */
            $datoshijo = $modulo->obtener($datos->id_hijo);
            $datospadre = $modulo->obtener($datos->id_padre);
            /* [COMBINACIONES] */
            if($datos->id_cardinalidad == 1 || $datos->id_cardinalidad == 3){
                $estructura = "ALTER TABLE `".$datoshijo->nombre_modulo_slug."` DROP `id_".$datospadre->nombre_modulo_slug."`;";
                $estructura .= "ALTER TABLE `".$datoshijo->nombre_modulo_slug."` DROP INDEX `fk_".$datoshijo->nombre_modulo_slug."_".$datospadre->nombre_modulo_slug."`";
            }elseif($datos->id_cardinalidad == 2){
                $estructura = "DROP TABLE `".$datospadre->nombre_modulo_slug."_has_".$datoshijo->nombre_modulo_slug."`;";
            }else{
                return false;
            }
            /* [BASE PERSONALIZADA TABLA] */
            $base = $this->basepersonalizado($datoshijo->id_empresa);
            if($base->query($estructura)){
                if($this->delete('id_relacion = '.$id)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }        
    }
    public function obtener($id){
        $consulta = $this->select()->setIntegrityCheck(false)
                ->from($this->_name,'*')
                ->where('id_relacion = ?',$id);
        return $consulta->query()->fetch(Zend_Db::FETCH_OBJ);
    }
    private function getrelacion($id){
        if(is_numeric($id)){
            /* [INSTANCEAR BASE PERSONALIZADA] */
            $base = $this->base();    
            /* [CONSULTA] */
            $consulta = $base->select()->from(array('mrt'=>'modulo_relacion_tipo'),array('nombre_relacion'=>'mrt.nombre_tipo'))
                    ->where('id_tipo = ?',$id);
            $dato = $consulta->query()->fetch();
            return $dato->nombre_relacion;
        }
    }
    private function getcardinalidad($id){
        if(is_numeric($id)){
            /* [INSTANCEAR BASE PERSONALIZADA] */
            $base = $this->base();    
            /* [CONSULTA] */
            $consulta = $base->select()->from(array('mrc'=>'modulo_relacion_cardinalidad'),array('nombre_cardinalidad'=>'mrc.nombre_cardinalidad'))
                    ->where('id_cardinalidad = ?',$id);
            $dato = $consulta->query()->fetch();
            return $dato->nombre_cardinalidad;
        }
    }        
    private function gethijo($id){
        if(is_numeric($id)){
            /* [INSTANCEAR BASE PERSONALIZADA] */
            $base = $this->base();    
            /* [CONSULTA] */
            $consulta = $base->select()->from(array('m'=>'modulo'),array('nombre_modulo'=>'m.nombre_modulo'))
                    ->where('id_modulo = ?',$id);
            $dato = $consulta->query()->fetch();
            return $dato->nombre_modulo;
        }
    }
    
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
            /* [DATOS PERSONALIZADOS] */
            $empresa = new Default_Model_DbTable_Proyecto();
            $datosempresa = $empresa->obtener($modulopadre->id_empresa);
            /* [ULTIMO CAMPO] */
            $columnas = array_keys($base->describeTable($modulohijo->nombre_modulo_slug));
            $campo_final = $columnas[(count($columnas)-2)-1];
            /* [TIPO CARDINALIDAD] */
            if($cardinalidad == 1 || $cardinalidad == 3){
                /* [CREACION DE CAMPO] */
                $estructura = "ALTER TABLE `".$modulohijo->nombre_modulo_slug."` ADD `id_".$modulopadre->nombre_modulo_slug."` INT NULL AFTER `".$campo_final."`;";                
                $estructura .= "ALTER TABLE `".$modulohijo->nombre_modulo_slug."` ";
                $estructura .= "ADD CONSTRAINT `fk_".$modulohijo->nombre_modulo_slug."_".$modulopadre->nombre_modulo_slug."` FOREIGN KEY (`id_".$modulopadre->nombre_modulo_slug."`) REFERENCES `".$modulopadre->nombre_modulo_slug."` (`id_".$modulopadre->nombre_modulo_slug."`) ON DELETE CASCADE ON UPDATE CASCADE;";
            }elseif($cardinalidad == 2){
                $estructura = "CREATE  TABLE IF NOT EXISTS `".$datosempresa->basededatos_empresa."`.`".$modulopadre->nombre_modulo_slug."_has_".$modulohijo->nombre_modulo_slug."` (";
                $estructura .= "`id_".$modulopadre->nombre_modulo_slug."` INT NULL ,";
                $estructura .= "`id_".$modulohijo->nombre_modulo_slug."` INT NULL ,";
                $estructura .= " PRIMARY KEY (`id_".$modulohijo->nombre_modulo_slug."`, `id_".$modulopadre->nombre_modulo_slug."`) ,";
                $estructura .= " INDEX `fk_".$modulohijo->nombre_modulo_slug."_".$modulopadre->nombre_modulo_slug."` (`id_".$modulopadre->nombre_modulo_slug."` ASC) ,";
                $estructura .= " INDEX `fk_".$modulopadre->nombre_modulo_slug."_".$modulohijo->nombre_modulo_slug."` (`id_".$modulohijo->nombre_modulo_slug."` ASC) ,";
                $estructura .= " CONSTRAINT `fk_".$modulohijo->nombre_modulo_slug."_".$modulopadre->nombre_modulo_slug."`";
                    $estructura .= " FOREIGN KEY (`id_".$modulohijo->nombre_modulo_slug."` )";
                    $estructura .= " REFERENCES `".$datosempresa->basededatos_empresa."`.`".$modulohijo->nombre_modulo_slug."` (`id_".$modulohijo->nombre_modulo_slug."` )";
                    $estructura .= " ON DELETE NO ACTION";
                    $estructura .= " ON UPDATE NO ACTION,";
                $estructura .= " CONSTRAINT `fk_".$modulopadre->nombre_modulo_slug."_".$modulohijo->nombre_modulo_slug."`";
                    $estructura .= " FOREIGN KEY (`id_".$modulopadre->nombre_modulo_slug."` )";
                    $estructura .= " REFERENCES `".$datosempresa->basededatos_empresa."`.`".$modulopadre->nombre_modulo_slug."` (`id_".$modulopadre->nombre_modulo_slug."` )";
                    $estructura .= " ON DELETE NO ACTION";
                    $estructura .= " ON UPDATE NO ACTION)";
                $estructura .= " ENGINE = MyISAM";
                
            }else{
                return false;
            }
            /* [EJECUTAR] */
            if($base->query($estructura)){
                return true;
            }else{
                return false;
            }
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