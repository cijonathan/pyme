<?php

class Model_Acl  extends Zend_Acl
{
    public function __construct() 
    {
        # [ROLES]
        $this->addRole(new Zend_Acl_Role('visitante'));
        $this->addRole(new Zend_Acl_Role('root'),'visitante');             
        
        # [RECURSOS]          
        $this->add(new Zend_Acl_Resource('default'));
        $this->add(new Zend_Acl_Resource('default:index'));
        $this->add(new Zend_Acl_Resource('default:error'));        
        $this->add(new Zend_Acl_Resource('default:tablero'));        
        $this->add(new Zend_Acl_Resource('default:permiso'));  
        $this->add(new Zend_Acl_Resource('default:proyecto'));  
        $this->add(new Zend_Acl_Resource('default:mantenedor'));  
        $this->add(new Zend_Acl_Resource('default:usuario'));  
      
        # [PERMISOS]
        $this->allow('visitante',array('default:index','default:permiso'));        
        $this->deny('visitante');
        $this->allow('root');
    }
}