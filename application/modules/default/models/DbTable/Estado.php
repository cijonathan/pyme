<?php

class Default_Model_DbTable_Estado extends Zend_Db_Table_Abstract
{
    protected $_name = 'estado_sistema';
    
    public function listar(){
        $consulta = $this->select()->setIntegrityCheck(false)
                ->from(array('e'=>$this->_name),array('id_estado'=>'e.id_estado','nombre_estado'=>'e.nombre_estado'))  
                ->order('e.nombre_estado ASC');
        return $this->fetchAll($consulta);
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
    public function eliminar($id){
        if(is_numeric($id)){
            if($this->delete('id_estado = '.$id)){
                return true;
            }else{
                return false;
            }
        }
    }
}