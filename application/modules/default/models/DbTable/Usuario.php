<?php

class Default_Model_DbTable_Usuario extends Zend_Db_Table_Abstract
{

    protected $_name = 'usuario_sistema';
    
    public function listar($id_usuario){
        $consulta = $this->select()->setIntegrityCheck(false)
                ->from(array('u'=>$this->_name),array('id_usuario'=>'u.id_usuario','email_usuario'=>'u.email_usuario'))
                ->where('id_usuario <> ?', $id_usuario)    
                ->order('u.id_usuario DESC');
        $datos = array();
        foreach($this->fetchAll($consulta) as $retorno){
            $fila = new stdClass();
            $fila->id_usuario = $retorno->id_usuario;
            $fila->email_usuario = $retorno->email_usuario;
            $datos[] = $fila;
        }
        return $datos;
    }
    public function agregar($datos){
        if(is_array($datos)){
            if($this->insert($datos)){
                return true;              
            }
        }
    }
    public function eliminar($id){
        if($id>0 && is_numeric($id)){
            if($this->delete('id_usuario = '.$id)){
                return true;
            }
        }
    }
    public function obtener($id){
        if(is_numeric($id) && $id>0){
            $consulta = $this->select()->setIntegrityCheck(false)
                    ->from($this->_name,'*')
                    ->where('id_usuario = ?', $id);            
            $datos = $consulta->query()->fetch();    
            /* [DATOS] */
            $fila = new stdClass();
            $fila->id_usuario = $datos['id_usuario'];
            $fila->email_usuario = $datos['email_usuario'];
            $fila->clave_usuario  = $datos['clave_usuario'];
            return $fila;
        }
    } 
    public function actualizar($datos,$id){
        if(is_array($datos)){
            if($id>0 && is_numeric($id)){
                if($this->update($datos,'id_usuario = '.$id)){
                    return true;
                }
            }
        }
    }
    public function listartipo(){
        $consulta = $this->select()->setIntegrityCheck(false)
                ->from(array('u'=>'usuario_tipo'),
                        array('id_tipo'=>'u.id_tipo',
                            'nombre_tipo'=>'u.nombre_tipo'))  
                ->order('u.nombre_tipo ASC');
        return $this->fetchAll($consulta);
    }
    public function eliminartipo($id){
        if(is_numeric($id)){
            $base = $this->base();            
            if($base->delete('usuario_tipo','id_tipo = '.$id)){
                return true;
            }else{
                return false;
            }
        }
    }    
    public function agregartipo($datos){
        if(is_array($datos)){
            $base = $this->base();
            if($base->insert('usuario_tipo',$datos)){
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
}

