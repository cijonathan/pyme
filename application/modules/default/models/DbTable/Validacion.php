<?php

class Default_Model_DbTable_Validacion extends Zend_Db_Table_Abstract
{
    protected $_name = 'campo_validacion';
    
    public function listar(){
        $consulta = $this->select()->setIntegrityCheck(false)
                ->from(array('v'=>$this->_name),
                        array('id_validacion'=>'v.id_validacion',
                            'nombre_validacion'=>'v.nombre_validacion'))  
                ->order('v.nombre_validacion ASC');
        return $this->fetchAll($consulta);
    }
    public function eliminar($id){
        if(is_numeric($id)){
            if($this->delete('id_validacion = '.$id)){
                return true;
            }else{
                return false;
            }
        }
    }    
    public function agregar($datos){
        if(is_array($datos)){
            if($this->insert($datos)){
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