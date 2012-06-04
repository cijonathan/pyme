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
    
}