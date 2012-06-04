<?php

class Plugin_Acceso extends Zend_Controller_Plugin_Abstract
{
    private $_acl = null;
    
    public function __construct(Zend_Acl $acl)
    {        
        $this->_acl = $acl;
    }
    
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $module = $request->getModuleName();
        $resource = $request->getControllerName();
        $action = $request->getActionName();
        /* [CUANDO NO TENGA PERMISO] */
        if(!$this->_acl->isAllowed(Zend_Registry::get('tipo_usuario'), $module.':'.$resource, $action)){            
            $request->setModuleName('default')->setControllerName('permiso')->setActionName('index');                     
        }      
    }
}