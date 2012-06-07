<?php

class Default_Model_DbTable_Idioma extends Zend_Db_Table_Abstract
{
    protected $_name = 'idioma';
    
    public function listar(){
        $consulta = $this->select()->setIntegrityCheck(false)
                ->from(array('i'=>$this->_name),array('id_idioma'=>'i.id_idioma','nombre_idioma'=>'i.nombre_idioma'))  
                ->order('i.nombre_idioma ASC');
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
            if($this->delete('id_idioma = '.$id)){
                return true;
            }else{
                return false;
            }
        }
    }
}