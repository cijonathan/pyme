<?php

class Default_Model_DbTable_Campo extends Zend_Db_Table_Abstract
{
    protected $_name = 'campo';
    
    public function listar($id_modulo){
        if($id_modulo>0 && is_numeric($id_modulo)){
            $consulta = $this->select()->setIntegrityCheck(false)
                    ->from(array('c'=>$this->_name),array('nombre_campo_slug'=>'c.nombre_campo_slug','listado_campo'=>'c.listado_campo','nombre_campo'=>'c.nombre_campo','id_campo'=>'c.id_campo'))
                    ->joinLeft(array('cv'=>'campo_validacion'),'cv.id_validacion = c.id_validacion',array('nombre_validacion'=>'cv.nombre_validacion'))
                    ->joinLeft(array('ct'=>'campo_tipo'),'ct.id_tipo = c.id_tipo',array('nombre_tipo'=>'ct.nombre_tipo','id_tipo'=>'ct.id_tipo'))
                    ->joinLeft(array('es'=>'estado_sistema'),'es.id_estado = c.id_estado',array('nombre_estado'=>'es.nombre_estado'))
                    ->where('c.id_modulo = ?',$id_modulo)
                    ->order('c.orden_campo ASC');
            $datos = array();
            foreach($this->fetchAll($consulta) as $retorno){
                $fila = new stdClass();
                $fila->id_campo = $retorno->id_campo;
                $fila->nombre_campo = $retorno->nombre_campo;
                $fila->nombre_slug = $retorno->nombre_campo_slug;
                $fila->nombre_validacion = $retorno->nombre_validacion;
                $fila->nombre_tipo = $retorno->nombre_tipo;
                $fila->id_tipo = $retorno->id_tipo;
                $fila->nombre_estado = $retorno->nombre_estado;
                $fila->listado_campo = ($retorno->listado_campo == 1)?'Si':'No';
                $datos[] = $fila;
            }
            return $datos;
        }
    }
    public function obtener($id_campo){
        if(is_numeric($id_campo)){
            $consulta = $this->select()->setIntegrityCheck(false)
                    ->from(array('c'=>$this->_name),'*')
                    ->where('id_campo = ?',$id_campo);
            $retorno = $consulta->query()->fetch();
            /* [LISTAR] */
            $fila = new stdClass();
            $fila->id_campo = $retorno['id_campo'];
            $fila->nombre_campo = $retorno['nombre_campo'];
            $fila->nombre_campo_slug = $retorno['nombre_campo_slug'];
            $fila->orden_campo = $retorno['listado_campo'];
            $fila->id_validacion = $retorno['id_validacion'];
            $fila->id_tipo = $retorno['id_tipo'];
            $fila->id_modulo = $retorno['id_modulo'];
            $fila->id_estado = $retorno['id_estado'];            
            return $fila;
        }
    }
    public function agregar($datos){
        if(is_array($datos)){
            /* los <select></select> los "-" los Zend_Form no los acepta */
            if($datos['id_tipo'] == 3){
                $datos['nombre_campo_slug'] = str_replace('-','',$datos['nombre_campo_slug']);
            }            
            if($this->existe($datos['nombre_campo_slug'],$datos['id_modulo'])){
                if($this->insert($datos)){
                    return true;
                }
            }else{
                return false;
            }
        }
    }   
    public function eliminar($id_campo,$id_modulo,$id_empresa){       
        if(is_numeric($id_campo) && is_numeric($id_modulo) && is_numeric($id_empresa)){
            /* [ELIMINAR REGISTRO EN PYME] */
            $empresa = new Default_Model_DbTable_Proyecto();
            $datosempresa = $empresa->obtener($id_empresa);
            $modulo = new Default_Model_DbTable_Modulo();
            $datosmodulo = $modulo->obtener($id_modulo);
            $datoscampo = $this->obtener($id_campo);
            /* [DATOS] */
            $basededatos = (string)$datosempresa->basededatos_empresa;
            $tabla = (string)$datosmodulo->nombre_modulo_slug;
            $campo = (string)trim($datoscampo->nombre_campo_slug);
            /* [CREAR CONSULTA] */
            $consulta = "USE $basededatos;";
            $consulta .= "ALTER TABLE $tabla DROP $campo;";          
            /* [INSTANCIA BASE] */
            $base = $this->basepersonalizado($id_empresa);
            /* [OBTENER COLUMNAS] */
            $columnas = array_keys($base->describeTable($tabla));
            /* [EJECUTAR] */
            if(in_array($campo,$columnas)){
                if($base->query($consulta)){
                    if($this->delete('id_campo = '.$id_campo)){
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
    public function tabla($id,$tabla){
        if(is_numeric($id) && $id>0 && is_string($tabla)){
            $base = $this->basepersonalizado($id);
            $datos = $base->listTables();            
            if(in_array($tabla,$datos)){
                return true;
            }else{
                return false;
            }            
        }
    }
    private function existe($nombre,$id_modulo){
        if(is_string($nombre) && is_numeric($id_modulo)){
            /* [CONSULTA EXISTENCIA] */
            $existe = $this->select()->setIntegrityCheck(false)
                    ->from($this->_name,'COUNT(*) as total')
                    ->where('nombre_campo_slug  = "'.$nombre.'" and id_modulo = '.$id_modulo);
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