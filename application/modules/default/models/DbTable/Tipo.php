<?php

class Default_Model_DbTable_Tipo extends Zend_Db_Table_Abstract
{
    protected $_name = 'campo_tipo';
    
    public function listar(){
        $consulta = $this->select()->setIntegrityCheck(false)
                ->from(array('t'=>$this->_name),
                        array('id_tipo'=>'t.id_tipo',
                            'nombre_tipo'=>'t.nombre_tipo'))  
                ->order('t.nombre_tipo ASC');
        return $this->fetchAll($consulta);
    }
    
}