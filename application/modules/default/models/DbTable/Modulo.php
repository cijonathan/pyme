<?php

class Default_Model_DbTable_Modulo extends Zend_Db_Table_Abstract
{
    protected $_name = 'modulo';
    
    public function agregar($datos){
        if(is_array($datos)){
            if($this->existe($datos['nombre_modulo_slug'],$datos['id_empresa'])){
                if($this->insert($datos)){
                    return $this->getAdapter()->lastInsertId();
                }
            }else{
                return -1;
            }
        }
    }
    public function listar($id){
        if(is_numeric($id) && $id>0){
            $consulta = $this->select()->setIntegrityCheck(false)
                    ->from(array('m'=>$this->_name),'*')
                    ->joinInner(array('e'=>'estado_sistema'),'e.id_estado = m.id_estado',array('nombre_estado'=>'e.nombre_estado'))
                    ->where('id_empresa = ?',$id)
                    ->order('nombre_modulo ASC');
            
            $datos = array();            
            
            foreach($this->fetchAll($consulta) as $retorno){
                $fila = new stdClass();
                $fila->id_modulo = $retorno->id_modulo;
                $fila->nombre_modulo = $retorno->nombre_modulo;
                $fila->nombre_modulo_slug = $retorno->nombre_modulo_slug;
                $fila->nombre_estado = $retorno->nombre_estado;
                $fila->id_empresa = $retorno->id_empresa;
                $fila->id_estado = $retorno->id_estado;
                $datos[] = $fila;
            }
            return $datos;
        }
    }
    public function obtener($id){
        if(is_numeric($id) && $id>0){
            $consulta = $this->select()->setIntegrityCheck(false)
                    ->from(array('m'=>$this->_name),array(
                    'id_modulo'=>'m.id_modulo',
                    'nombre_modulo'=>'m.nombre_modulo',
                    'nombre_modulo_slug'=>'m.nombre_modulo_slug',
                    'id_empresa'=>'m.id_empresa',
                    'id_estado'=>'m.id_estado'))
                    ->where('id_modulo = ?',$id);
            $retorno = $consulta->query()->fetch();
            $fila = new stdClass();
            $fila->id_modulo = $retorno['id_modulo'];
            $fila->nombre_modulo = $retorno['nombre_modulo'];
            $fila->nombre_modulo_slug = $retorno['nombre_modulo_slug'];
            $fila->id_empresa = $retorno['id_empresa'];
            $fila->id_estado = $retorno['id_estado'];     
            return $fila;
        }
    }
    public function crearmodulo($id_empresa,$id_modulo){        
        if(is_numeric($id_empresa) && is_numeric($id_modulo)){
            /* [DATOS] */
            $proyecto = new Default_Model_DbTable_Proyecto();
            $datosproyecto = $proyecto->obtener($id_empresa);
            $modulo = new Default_Model_DbTable_Modulo();
            $datosmodulo = $modulo->obtener($id_modulo);
            $campo = new Default_Model_DbTable_Campo();
            $datoscampo = $campo->listar($id_modulo);            
            /* [INSTANCIAR BASE] */
            $base = $this->basepersonalizado($id_empresa);
            /* [LISTAR CAMPO] */
            $normar = new Default_Model_Normar();
            $estructura = "CREATE  TABLE IF NOT EXISTS `".$datosproyecto->basededatos_empresa."`.`".$datosmodulo->nombre_modulo_slug."` (";
            $estructura .="`id_".$datosmodulo->nombre_modulo_slug."` INT UNSIGNED NULL AUTO_INCREMENT ,";
            foreach($datoscampo as $retorno){
                $estructura.= $normar->campo($retorno->nombre_slug,$retorno->id_tipo);
            }
            $estructura .="id_idioma INT(11) NULL,";
            $estructura .="id_estado INT(11) NULL,";
            $estructura .="  PRIMARY KEY (`id_".$datosmodulo->nombre_modulo_slug."`)";
            $estructura .=") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
            /* [CREAR ESTRUCTURA QUERY] */
            if($base->query($estructura)){
                return true;
            }
        }
    }
    public function actualizarestado($id_modulo,$id_estado){
        if(is_numeric($id_estado) && is_numeric($id_modulo)){           
            if($this->update(array('id_estado'=>$id_estado),'id_modulo = '.$id_modulo)){
                return true;
            }
        }
    }
    public function actualizarmodulo($id_empresa,$id_modulo){
        /*
         * ALTER TABLE `noticias` ADD `test` INT NOT NULL AFTER `id_estado` 
         */
        if(is_numeric($id_empresa) && is_numeric($id_modulo)){
            /* [DATOS] */
            $proyecto = new Default_Model_DbTable_Proyecto();
            $datosproyecto = $proyecto->obtener($id_empresa);
            $modulo = new Default_Model_DbTable_Modulo();
            $datosmodulo = $modulo->obtener($id_modulo);
            $campo = new Default_Model_DbTable_Campo();
            $datoscampo = $campo->listar($id_modulo);   
            /* [INSTANCIAR BASE] */
            $base = $this->basepersonalizado($id_empresa);
            $campos = array_keys($base->describeTable($datosmodulo->nombre_modulo_slug));
            foreach($datoscampo as $retorno){
                if(!in_array($retorno->nombre_slug, $campos,true)){
                    /* [NORMAR TIPO]*/
                    $normar = new Default_Model_Normar();
                    $tipo = $normar->tipos($retorno->id_tipo);
                    /* [CREAR CONSULTA] */                       
                    $consulta = "ALTER TABLE `".$datosmodulo->nombre_modulo_slug."` ADD `".$retorno->nombre_slug."` ".$tipo." NULL AFTER `".$campo_final."`";
                    /* 
                     * ALTER TABLE `nameoftable` MODIFY COLUMN `columnname1` TINYTEXT AFTER `columnname2`;
                     */
                    if(!$base->query($consulta)){                        
                        return false;
                    }
                }else{
                    $campo_final = $retorno->nombre_slug;
                }
            }
            return true;
        }               
    }
    private function existe($nombre,$id_empresa){
        if(is_string($nombre) && is_numeric($id_empresa)){
            /* [CONSULTA EXISTENCIA] */
            $existe = $this->select()->setIntegrityCheck(false)
                    ->from($this->_name,'COUNT(*) as total')
                    ->where('nombre_modulo_slug = "'.$nombre.'" and id_empresa='.$id_empresa);
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