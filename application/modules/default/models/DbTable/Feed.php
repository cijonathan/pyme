<?php

class Default_Model_DbTable_Feed extends Zend_Db_Table_Abstract
{
    protected $_name = 'modulo_feed';
    
    public function existe($id_modulo){
        if(is_numeric($id_modulo)){
            $consulta = $this->select()
                    ->setIntegrityCheck(false)
                    ->from($this->_name,'*')
                    ->where('id_modulo = ?',$id_modulo);
            return $consulta->query()->rowCount();            
        }else return false;
    }
    public function activar($id_modulo){
        if(is_numeric($id_modulo)){
            $datos = array('id_modulo'=>$id_modulo,'url_feed'=>'http://cms.hostprimario.com/feed/'.$id_modulo.'/');
            if($this->insert($datos)) return true; else return false;
        }else return false;        
    }
    public function obtener($id_modulo){
        if(is_numeric($id_modulo)){
            $consulta = $this->select()
                    ->setIntegrityCheck(false)
                    ->from($this->_name,'*')
                    ->where('id_modulo = ?',$id_modulo);
            return $consulta->query()->fetch(Zend_Db::FETCH_OBJ);
        }else return false;     
    }
    
}