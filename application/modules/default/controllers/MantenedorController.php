<?php

class MantenedorController extends Zend_Controller_Action
{
    public function indexAction(){
        /* [TITLE] */
        $this->view->headTitle()->prepend('Mantenedores - ');               
    }
    public function estadoAction(){
        /* [EXITO DE ELIMINACION DE CAMPO] */
        $exito = new Zend_Session_Namespace("exito");        
        $this->view->exito = $exito->mensaje;       
        $exito->setExpirationSeconds(1);
        /* [ERROR DE ELIMINACION DE CAMPO] */
        $error_campo = new Zend_Session_Namespace("error_campo");        
        $this->view->error = $error_campo->error;       
        $error_campo->setExpirationSeconds(1);        
        /* [TITLE] */
        $this->view->headTitle()->prepend('Estado - Generales - ');  
        /* [LISTAR] */
        $estado = new Default_Model_DbTable_Estado();
        $this->view->datos = $estado->listar();
        /* [FORMULARIO] */
        $formulario = new Default_Form_Crear();
        $this->view->formulario = $formulario;
        /* [PROCESAR FORMULARIO] */
        $respuesta = $this->getRequest();
        if($respuesta->isPost()){
            if($formulario->isValid($this->_request->getPost())){ 
                /* [PROCESAR] */
                $estado = new Default_Model_DbTable_Estado();
                /* [DATOS] */
                if($estado->agregar(array('nombre_estado'=>$formulario->getValue('nombre_item')))){
                    /* [EXITO] */
                    $exito = new Zend_Session_Namespace("exito");
                    $exito->mensaje = true;   
                    /* [REDIRECCIONAR] */
                    $this->_redirect('/mantenedor/estado/');                 
                }else{
                    $this->view->error = true;                    
                }
            }   
        }
    }   
    public function eliminaestadoAction(){
        /* [DESAHIBILITAR LAYOUT y VIEW] */
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);  
        /* [PARAMETROS] */
        $id = $this->_getParam('id',0);     
        /* [PROCESAR] */
        $estado = new Default_Model_DbTable_Estado();
        if($estado->eliminar($id)){
            /* [EXITO] */
            $exito = new Zend_Session_Namespace("exito");
            $exito->mensaje = true;   
            /* [REDIRECCIONAR] */
            $this->_redirect('/mantenedor/estado/');              
        }else{
            $this->view->error = true;
        }
        
    }
    public function idiomaAction(){
        /* [EXITO DE ELIMINACION DE CAMPO] */
        $exito = new Zend_Session_Namespace("exito");        
        $this->view->exito = $exito->mensaje;       
        $exito->setExpirationSeconds(1);
        /* [ERROR DE ELIMINACION DE CAMPO] */
        $error_campo = new Zend_Session_Namespace("error_campo");        
        $this->view->error = $error_campo->error;       
        $error_campo->setExpirationSeconds(1);        
        /* [TITLE] */
        $this->view->headTitle()->prepend('Idioma - Generales - ');  
        /* [LISTAR] */
        $idioma = new Default_Model_DbTable_Idioma();
        $this->view->datos = $idioma->listar();
        /* [FORMULARIO] */
        $formulario = new Default_Form_Crear();
        $this->view->formulario = $formulario;
        /* [PROCESAR FORMULARIO] */
        $respuesta = $this->getRequest();
        if($respuesta->isPost()){
            if($formulario->isValid($this->_request->getPost())){ 
                /* [DATOS] */
                if($idioma->agregar(array('nombre_idioma'=>$formulario->getValue('nombre_item')))){
                    /* [EXITO] */
                    $exito = new Zend_Session_Namespace("exito");
                    $exito->mensaje = true;   
                    /* [REDIRECCIONAR] */
                    $this->_redirect('/mantenedor/idioma/');                 
                }else{
                    $this->view->error = true;                    
                }
            }   
        }        
    }
    public function eliminaidiomaAction(){
        /* [DESAHIBILITAR LAYOUT y VIEW] */
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);  
        /* [PARAMETROS] */
        $id = $this->_getParam('id',0);     
        /* [PROCESAR] */
        $idioma = new Default_Model_DbTable_Idioma();
        if($idioma->eliminar($id)){
            /* [EXITO] */
            $exito = new Zend_Session_Namespace("exito");
            $exito->mensaje = true;   
            /* [REDIRECCIONAR] */
            $this->_redirect('/mantenedor/idioma/');              
        }else{
            $this->view->error = true;
        }
        
    }   
    public function componenteAction(){
        /* [EXITO DE ELIMINACION DE CAMPO] */
        $exito = new Zend_Session_Namespace("exito");        
        $this->view->exito = $exito->mensaje;       
        $exito->setExpirationSeconds(1);
        /* [ERROR DE ELIMINACION DE CAMPO] */
        $error_campo = new Zend_Session_Namespace("error_campo");        
        $this->view->error = $error_campo->error;       
        $error_campo->setExpirationSeconds(1);        
        /* [TITLE] */
        $this->view->headTitle()->prepend('Componentes - Generales - ');  
        /* [LISTAR] */
        $componente = new Default_Model_DbTable_Componente();
        $this->view->datos = $componente->listar();
        /* [FORMULARIO] */
        $formulario = new Default_Form_Crear();
        $this->view->formulario = $formulario;
        /* [PROCESAR FORMULARIO] */
        $respuesta = $this->getRequest();
        if($respuesta->isPost()){
            if($formulario->isValid($this->_request->getPost())){ 
                /* [DATOS] */
                if($componente->agregarcomponente(array('nombre_componente'=>$formulario->getValue('nombre_item')))){
                    /* [EXITO] */
                    $exito = new Zend_Session_Namespace("exito");
                    $exito->mensaje = true;   
                    /* [REDIRECCIONAR] */
                    $this->_redirect('/mantenedor/componente/');                 
                }else{
                    $this->view->error = true;                    
                }
            }   
        }        
    }    
    public function eliminacomponenteAction(){
        /* [DESAHIBILITAR LAYOUT y VIEW] */
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);  
        /* [PARAMETROS] */
        $id = $this->_getParam('id',0);     
        /* [PROCESAR] */
        $componente = new Default_Model_DbTable_Componente();
        if($componente->eliminarcomponente($id)){
            /* [EXITO] */
            $exito = new Zend_Session_Namespace("exito");
            $exito->mensaje = true;   
            /* [REDIRECCIONAR] */
            $this->_redirect('/mantenedor/componente/');              
        }else{
            $this->view->error = true;
        }
        
    }
    public function cardinalidadAction(){
        /* [EXITO DE ELIMINACION DE CAMPO] */
        $exito = new Zend_Session_Namespace("exito");        
        $this->view->exito = $exito->mensaje;       
        $exito->setExpirationSeconds(1);
        /* [ERROR DE ELIMINACION DE CAMPO] */
        $error_campo = new Zend_Session_Namespace("error_campo");        
        $this->view->error = $error_campo->error;       
        $error_campo->setExpirationSeconds(1);        
        /* [TITLE] */
        $this->view->headTitle()->prepend('Cardinalidad - Generales - ');  
        /* [LISTAR] */
        $cardinalidad = new Default_Model_DbTable_Cardinalidad();
        $this->view->datos = $cardinalidad->listar();
        /* [FORMULARIO] */
        $formulario = new Default_Form_Crear();
        $this->view->formulario = $formulario;
        /* [PROCESAR FORMULARIO] */
        $respuesta = $this->getRequest();
        if($respuesta->isPost()){
            if($formulario->isValid($this->_request->getPost())){ 
                /* [DATOS] */
                if($cardinalidad->agregar(array('nombre_cardinalidad'=>$formulario->getValue('nombre_item')))){
                    /* [EXITO] */
                    $exito = new Zend_Session_Namespace("exito");
                    $exito->mensaje = true;   
                    /* [REDIRECCIONAR] */
                    $this->_redirect('/mantenedor/cardinalidad/');                 
                }else{
                    $this->view->error = true;                    
                }
            }   
        }        
    }  
    public function eliminacardinalidadAction(){
        /* [DESAHIBILITAR LAYOUT y VIEW] */
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);  
        /* [PARAMETROS] */
        $id = $this->_getParam('id',0);     
        /* [PROCESAR] */
        $cardinalidad = new Default_Model_DbTable_Cardinalidad();
        if($cardinalidad->eliminar($id)){
            /* [EXITO] */
            $exito = new Zend_Session_Namespace("exito");
            $exito->mensaje = true;   
            /* [REDIRECCIONAR] */
            $this->_redirect('/mantenedor/cardinalidad/');              
        }else{
            $this->view->error = true;
        }
        
    }
    public function relacionAction(){
        /* [EXITO DE ELIMINACION DE CAMPO] */
        $exito = new Zend_Session_Namespace("exito");        
        $this->view->exito = $exito->mensaje;       
        $exito->setExpirationSeconds(1);
        /* [ERROR DE ELIMINACION DE CAMPO] */
        $error_campo = new Zend_Session_Namespace("error_campo");        
        $this->view->error = $error_campo->error;       
        $error_campo->setExpirationSeconds(1);        
        /* [TITLE] */
        $this->view->headTitle()->prepend('Relación - Generales - ');  
        /* [LISTAR] */
        $relacion = new Default_Model_DbTable_Relacion();
        $this->view->datos = $relacion->listarelacion();
        /* [FORMULARIO] */
        $formulario = new Default_Form_Crear();
        $this->view->formulario = $formulario;
        /* [PROCESAR FORMULARIO] */
        $respuesta = $this->getRequest();
        if($respuesta->isPost()){
            if($formulario->isValid($this->_request->getPost())){ 
                /* [DATOS] */
                if($relacion->agregar(array('nombre_tipo'=>$formulario->getValue('nombre_item')))){
                    /* [EXITO] */
                    $exito = new Zend_Session_Namespace("exito");
                    $exito->mensaje = true;   
                    /* [REDIRECCIONAR] */
                    $this->_redirect('/mantenedor/relacion/');                 
                }else{
                    $this->view->error = true;                    
                }
            }   
        }        
    }   
    public function eliminarelacionAction(){
        /* [DESAHIBILITAR LAYOUT y VIEW] */
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);  
        /* [PARAMETROS] */
        $id = $this->_getParam('id',0);     
        /* [PROCESAR] */
        $relacion = new Default_Model_DbTable_Relacion();
        if($relacion->eliminarelacion($id)){
            /* [EXITO] */
            $exito = new Zend_Session_Namespace("exito");
            $exito->mensaje = true;   
            /* [REDIRECCIONAR] */
            $this->_redirect('/mantenedor/relacion/');              
        }else{
            $this->view->error = true;
        }
        
    } 
    public function tiposAction(){
        /* [EXITO DE ELIMINACION DE CAMPO] */
        $exito = new Zend_Session_Namespace("exito");        
        $this->view->exito = $exito->mensaje;       
        $exito->setExpirationSeconds(1);
        /* [ERROR DE ELIMINACION DE CAMPO] */
        $error_campo = new Zend_Session_Namespace("error_campo");        
        $this->view->error = $error_campo->error;       
        $error_campo->setExpirationSeconds(1);        
        /* [TITLE] */
        $this->view->headTitle()->prepend('Tipos - Campos - ');  
        /* [LISTAR] */
        $tipo = new Default_Model_DbTable_Tipo();
        $this->view->datos = $tipo->listar();
        /* [FORMULARIO] */
        $formulario = new Default_Form_Crear();
        $this->view->formulario = $formulario;
        /* [PROCESAR FORMULARIO] */
        $respuesta = $this->getRequest();
        if($respuesta->isPost()){
            if($formulario->isValid($this->_request->getPost())){ 
                /* [DATOS] */
                if($tipo->agregar(array('nombre_tipo'=>$formulario->getValue('nombre_item')))){
                    /* [EXITO] */
                    $exito = new Zend_Session_Namespace("exito");
                    $exito->mensaje = true;   
                    /* [REDIRECCIONAR] */
                    $this->_redirect('/mantenedor/tipos/');                 
                }else{
                    $this->view->error = true;                    
                }
            }   
        }        
    }    
    public function eliminatipoAction(){
        /* [DESAHIBILITAR LAYOUT y VIEW] */
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);  
        /* [PARAMETROS] */
        $id = $this->_getParam('id',0);     
        /* [PROCESAR] */
        $tipo = new Default_Model_DbTable_Tipo();
        if($tipo->eliminar($id)){
            /* [EXITO] */
            $exito = new Zend_Session_Namespace("exito");
            $exito->mensaje = true;   
            /* [REDIRECCIONAR] */
            $this->_redirect('/mantenedor/tipos/');              
        }else{
            $this->view->error = true;
        }
        
    }    
    public function validacionAction(){
        /* [EXITO DE ELIMINACION DE CAMPO] */
        $exito = new Zend_Session_Namespace("exito");        
        $this->view->exito = $exito->mensaje;       
        $exito->setExpirationSeconds(1);
        /* [ERROR DE ELIMINACION DE CAMPO] */
        $error_campo = new Zend_Session_Namespace("error_campo");        
        $this->view->error = $error_campo->error;       
        $error_campo->setExpirationSeconds(1);        
        /* [TITLE] */
        $this->view->headTitle()->prepend('Validaciones - Campos - ');  
        /* [LISTAR] */
        $validacion = new Default_Model_DbTable_Validacion();
        $this->view->datos = $validacion->listar();
        /* [FORMULARIO] */
        $formulario = new Default_Form_Crear();
        $this->view->formulario = $formulario;
        /* [PROCESAR FORMULARIO] */
        $respuesta = $this->getRequest();
        if($respuesta->isPost()){
            if($formulario->isValid($this->_request->getPost())){ 
                /* [DATOS] */
                if($validacion->agregar(array('nombre_validacion'=>$formulario->getValue('nombre_item')))){
                    /* [EXITO] */
                    $exito = new Zend_Session_Namespace("exito");
                    $exito->mensaje = true;   
                    /* [REDIRECCIONAR] */
                    $this->_redirect('/mantenedor/validacion/');                 
                }else{
                    $this->view->error = true;                    
                }
            }   
        }        
    }      
    public function eliminavalidacionAction(){
        /* [DESAHIBILITAR LAYOUT y VIEW] */
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);  
        /* [PARAMETROS] */
        $id = $this->_getParam('id',0);     
        /* [PROCESAR] */
        $validacion = new Default_Model_DbTable_Validacion();
        if($validacion->eliminar($id)){
            /* [EXITO] */
            $exito = new Zend_Session_Namespace("exito");
            $exito->mensaje = true;   
            /* [REDIRECCIONAR] */
            $this->_redirect('/mantenedor/validacion/');              
        }else{
            $this->view->error = true;
        }
        
    }      
    public function usuariotipoAction(){
        /* [EXITO DE ELIMINACION DE CAMPO] */
        $exito = new Zend_Session_Namespace("exito");        
        $this->view->exito = $exito->mensaje;       
        $exito->setExpirationSeconds(1);
        /* [ERROR DE ELIMINACION DE CAMPO] */
        $error_campo = new Zend_Session_Namespace("error_campo");        
        $this->view->error = $error_campo->error;       
        $error_campo->setExpirationSeconds(1);        
        /* [TITLE] */
        $this->view->headTitle()->prepend('Tipos - Usuarios - ');  
        /* [LISTAR] */
        $usuario = new Default_Model_DbTable_Usuario();
        $this->view->datos = $usuario->listartipo();
        /* [FORMULARIO] */
        $formulario = new Default_Form_Crear();
        $this->view->formulario = $formulario;
        /* [PROCESAR FORMULARIO] */
        $respuesta = $this->getRequest();
        if($respuesta->isPost()){
            if($formulario->isValid($this->_request->getPost())){ 
                /* [DATOS] */
                if($usuario->agregartipo(array('nombre_tipo'=>$formulario->getValue('nombre_item')))){
                    /* [EXITO] */
                    $exito = new Zend_Session_Namespace("exito");
                    $exito->mensaje = true;   
                    /* [REDIRECCIONAR] */
                    $this->_redirect('/mantenedor/usuariotipo/');                 
                }else{
                    $this->view->error = true;                    
                }
            }   
        }        
    } 
    public function eliminausuariotipoAction(){
        /* [DESAHIBILITAR LAYOUT y VIEW] */
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);  
        /* [PARAMETROS] */
        $id = $this->_getParam('id',0);     
        /* [PROCESAR] */
        $usuario = new Default_Model_DbTable_Usuario();
        if($usuario->eliminartipo($id)){
            /* [EXITO] */
            $exito = new Zend_Session_Namespace("exito");
            $exito->mensaje = true;   
            /* [REDIRECCIONAR] */
            $this->_redirect('/mantenedor/usuariotipo/');              
        }else{
            $this->view->error = true;
        }
        
    }      
    public function topAction(){}
}

