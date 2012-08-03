<?php

class Default_Model_DbTable_Componente extends Zend_Db_Table_Abstract
{
    protected $_name = 'componente';
    
    public function listar(){
        $consulta = $this->select()->setIntegrityCheck(false)
                ->from(array('c'=>$this->_name),'*');
        $datos = array();
        foreach($this->fetchAll($consulta) as $retorno){
            $fila = new stdClass();
            $fila->id_componente = $retorno->id_componente;
            $fila->nombre_componente = $retorno->nombre_componente;
            $datos[] = $fila;
        }
        return $datos;
    }
    public function listarModulo($id_modulo){
        if($id_modulo>0 && is_numeric($id_modulo)){
            $consulta = $this->select()->setIntegrityCheck(false)
                    ->from(array('mc'=>'modulo_has_componente'),array('id_componente'=>'mc.id_componente'))
                    ->joinInner(array('c'=>'componente'),'mc.id_componente = c.id_componente ',array('nombre_componente'=>'c.nombre_componente'))
                    ->where('mc.id_modulo = ?',$id_modulo);
            return $this->fetchAll($consulta);
        }
    }
    public function agregarcomponente($datos){
        if(is_array($datos)){
            if($this->insert($datos)){
                return true;
            }else{
                return false;
            }
        }
    }
    public function eliminarcomponente($id){
        if(is_numeric($id)){
            if($this->delete('id_componente = '.$id)){
                return true;
            }else{
                return false;
            }
        }
    }    
    public function agregar($datos){
        if(is_array($datos)){
            $datos = (object)$datos;
            $base = $this->base();
            if($this->existe($datos->id_modulo, $datos->id_componente)){
                $datos = (array)$datos;
                if($base->insert('modulo_has_componente', $datos)){
                    if($this->crearRelacion($datos)){
                        $tamano = new Default_Model_DbTable_Tamano();
                        if($tamano->inicial($datos['id_modulo'])){
                            return true;
                        }else{
                            return false;
                        }
                        exit;
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }                
            }else{
                return false;
            }
        }
    }
    public function eliminar($datos){
        if(is_array($datos)){
            $datos = (object)$datos;
            /* [BASE PERSONALIZADA]  */
            $base = $this->base();
            if($base->delete('modulo_has_componente','id_modulo = '.$datos->id_modulo.' and id_componente = '.$datos->id_componente)){
                /* [OBTENER DATOS] */
                $modulo = new Default_Model_DbTable_Modulo();
                $datosmodulo = $modulo->obtener($datos->id_modulo);
                $proyecto = new Default_Model_DbTable_Proyecto();
                $datosproyecto = $proyecto->obtener($datosmodulo->id_empresa);
                /* [EJECUTAR CONSULTA] */
                $basepersonalizada = $this->basepersonalizado($datosproyecto->id_empresa);                
                if($datos->id_componente == 1){
                    if($basepersonalizada->query("DROP TABLE ".$datosmodulo->nombre_modulo_slug."_galeria")){
                        $tamano = new Default_Model_DbTable_Tamano();
                        if($tamano->eliminar($datos->id_modulo)){
                            return true;
                        }else return false;
                    }else{
                        return false;
                    }
                }elseif($datos->id_componente == 2){
                    if($basepersonalizada->query("DROP TABLE ".$datosmodulo->nombre_modulo_slug."_archivo")){
                        return true;
                    }else{
                        return false;
                    }                        
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    }
    private function crearRelacion($datos){
        if(is_array($datos)){
            $datos = (object)$datos;
            /* [OBTENER DATOS] */
            $modulo = new Default_Model_DbTable_Modulo();
            $datosmodulo = $modulo->obtener($datos->id_modulo);
            $proyecto = new Default_Model_DbTable_Proyecto();
            $datosproyecto = $proyecto->obtener($datosmodulo->id_empresa);
            /* [ESTRUCTURA SQL GALERIA] */
            if($datos->id_componente == 1){
                $estructura = "CREATE  TABLE IF NOT EXISTS `".$datosproyecto->basededatos_empresa."`.`".$datosmodulo->nombre_modulo_slug."_galeria` (";
                $estructura .="`id_galeria` INT(11) NULL  AUTO_INCREMENT,";
                $estructura .="`id_".$datosmodulo->nombre_modulo_slug."` INT(11) NULL,";
                $estructura .="`id_tipo` INT(11) NULL,";
                $estructura .="`descripcion_galeria` VARCHAR(255) NULL,";
                $estructura .="`ruta_chica_galeria` VARCHAR(255) NULL,";
                $estructura .="`ruta_mediana_galeria` VARCHAR(255) NULL,";
                $estructura .="`ruta_grande_galeria` VARCHAR(255) NULL,";
                $estructura .="`orden_galeria` INT(11) NULL,";
                $estructura .="  PRIMARY KEY (`id_galeria`),";
                /* [CLAVE FOREANA]*/
                $estructura .="INDEX `fk_".substr($datosmodulo->nombre_modulo_slug."_relacion_".$datosmodulo->nombre_modulo_slug,0,50)."_galeria` (`id_".$datosmodulo->nombre_modulo_slug."` ASC) ,";
                $estructura .="CONSTRAINT `fk_".substr($datosmodulo->nombre_modulo_slug."_relacion_".$datosmodulo->nombre_modulo_slug,0,50)."_galeria`";
                $estructura .="FOREIGN KEY (`id_".$datosmodulo->nombre_modulo_slug."` )";
                $estructura .="REFERENCES `".$datosproyecto->basededatos_empresa."`.`".$datosmodulo->nombre_modulo_slug."` (`id_".$datosmodulo->nombre_modulo_slug."` )";
                $estructura .=" ON DELETE NO ACTION ON UPDATE NO ACTION";
                
            }elseif($datos->id_componente == 2){
                $estructura = "CREATE  TABLE IF NOT EXISTS `".$datosproyecto->basededatos_empresa."`.`".$datosmodulo->nombre_modulo_slug."_archivo` (";
                $estructura .="`id_archivo` INT(11) NULL  AUTO_INCREMENT,";
                $estructura .="`id_".$datosmodulo->nombre_modulo_slug."` INT(11) NULL,";
                $estructura .="`nombre_archivo` VARCHAR(255) NULL,";                
                $estructura .="`peso_archivo` VARCHAR(255) NULL,";                
                $estructura .="`formato_archivo` VARCHAR(255) NULL,";                
                $estructura .="`ruta_archivo` VARCHAR(255) NULL,";  
                $estructura .="`orden_archivo` INT(11) NULL,";                
                $estructura .="  PRIMARY KEY (`id_archivo`),";
                /* [CLAVE FOREANA]*/
                $estructura .="INDEX `fk_".substr($datosmodulo->nombre_modulo_slug."_relacion_".$datosmodulo->nombre_modulo_slug,0,50)."_archivo` (`id_".$datosmodulo->nombre_modulo_slug."` ASC) ,";
                $estructura .="CONSTRAINT `fk_".substr($datosmodulo->nombre_modulo_slug."_relacion_".$datosmodulo->nombre_modulo_slug,0,50)."_archivo`";
                $estructura .="FOREIGN KEY (`id_".$datosmodulo->nombre_modulo_slug."` )";
                $estructura .="REFERENCES `".$datosproyecto->basededatos_empresa."`.`".$datosmodulo->nombre_modulo_slug."` (`id_".$datosmodulo->nombre_modulo_slug."` )";
                $estructura .=" ON DELETE NO ACTION ON UPDATE NO ACTION";                
            }elseif($datos->id_componente == 3){
                $estructura = "CREATE  TABLE IF NOT EXISTS `".$datosproyecto->basededatos_empresa."`.`".$datosmodulo->nombre_modulo_slug."_video` (";
                $estructura .="`id_video` INT(11) NULL  AUTO_INCREMENT,";
                $estructura .="`id_".$datosmodulo->nombre_modulo_slug."` INT(11) NULL,";  
                $estructura .="`nombre_video` VARCHAR(255) NULL,";
                $estructura .="`url_video` VARCHAR(255) NULL,";                
                $estructura .="`orden_archivo` INT(11) NULL,";                
                $estructura .="  PRIMARY KEY (`id_video`),";  
                /* [CLAVE FOREANA]*/
                $estructura .="INDEX `fk_".substr($datosmodulo->nombre_modulo_slug."_relacion_".$datosmodulo->nombre_modulo_slug,0,50)."_video` (`id_".$datosmodulo->nombre_modulo_slug."` ASC) ,";
                $estructura .="CONSTRAINT `fk_".substr($datosmodulo->nombre_modulo_slug."_relacion_".$datosmodulo->nombre_modulo_slug,0,50)."_video`";
                $estructura .="FOREIGN KEY (`id_".$datosmodulo->nombre_modulo_slug."` )";
                $estructura .="REFERENCES `".$datosproyecto->basededatos_empresa."`.`".$datosmodulo->nombre_modulo_slug."` (`id_".$datosmodulo->nombre_modulo_slug."` )";
                $estructura .=" ON DELETE NO ACTION ON UPDATE NO ACTION";                     
            }
            $estructura .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
            /* [EJECUTAR CONSULTA] */
            $base = $this->basepersonalizado($datosproyecto->id_empresa);
            if($base->query($estructura)){
                return true;
            }else{
                return false;
            }
        }
    }
    private function existe($id_modulo,$id_componente){
        if(is_numeric($id_modulo) && is_numeric($id_componente)){
            /* [CONSULTA EXISTENCIA] */
            $existe = $this->select()->setIntegrityCheck(false)
                    ->from('modulo_has_componente','COUNT(*) as total')
                    ->where('id_modulo = "'.$id_modulo.'" and id_componente = '.$id_componente);
            $retorno = $existe->query()->fetch();
            if((int)$retorno['total'] == 1){
                return false;
            }else{
                return true;
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