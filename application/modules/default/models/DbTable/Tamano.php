<?php

class Default_Model_DbTable_Tamano extends Zend_Db_Table_Abstract
{    
    protected $_name = 'modulo_imagen_configuracion';
    
    public function obtener($id_modulo){        
        if(is_numeric($id_modulo)){
            $consulta = $this->select()
                    ->setIntegrityCheck(false)
                    ->from($this->_name,'*')                    
                    ->where('id_modulo = ?', $id_modulo);
            
            return $consulta->query()->fetch();
        }
    }    
    public function actualizar($datos,$id_modulo){
        if(is_array($datos) && is_numeric($id_modulo)){
            if($this->update($datos,'id_modulo = '.$id_modulo)){
                return true;
            } return true;
        }
    }
    public function inicial($id_modulo){
        if(is_numeric($id_modulo)){
            $datos = array(
                'id_modulo'=>(int)$id_modulo,
                'ancho_grande'=>800,
                'alto_grande'=>600,
                'ancho_mediana'=>320,
                'alto_mediana'=>240,
                'ancho_chica'=>100,
                'alto_chica'=>75
            );
            if($this->insert($datos)) {
                return true;
            }else return false;
        }else return false;
    }
    public function eliminar($id_modulo){
        if(is_numeric($id_modulo)){
            if($this->delete('id_modulo = '.$id_modulo)){
                return true;
            }else return false;
        }
    }
}