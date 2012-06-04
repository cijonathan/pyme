<?php

class UsuarioController extends Zend_Controller_Action
{
    public function init() {
        /* [DATOS DEL ZEND AUTH] */
        $sesion = new Zend_Registry();       
        $this->view->email_usuario = $sesion->get('email_usuario');   
        /* [LIMPIEZA] */
        unset($sesion);        
    }       
    public function indexAction(){
        /* [TITLE] */
        $this->view->headTitle()->prepend('Usuario sistema - '); 
        /* [LISTAR] */
        $sesion = new Zend_Registry();
        $usuario = new Default_Model_DbTable_Usuario();
        $this->view->datos = $usuario->listar($sesion->get('id_usuario'));
        /* [LIMPIEZA] */
        unset($usuario,$sesion);
    }
    public function crearAction(){
        /* [TITLE] */
        $this->view->headTitle()->prepend('Crear - Usuario sistema - ');       
        /* [FORMULARIO] */
        $formulario = new Default_Form_Usuario();
        $this->view->formulario_usuario = $formulario;
        $respuesta = $this->getRequest();
        if($respuesta->isPost()){
            if($formulario->isValid($this->_request->getPost())){
                /* [VALORES] */
                $datos = array(
                    'email_usuario'=>$formulario->getValue('email_usuario'),
                    'clave_usuario'=>$formulario->getValue('clave_usuario'),
                    'tipo_usuario'=>'root'
                );
                $usuario = new Default_Model_DbTable_Usuario();
                if($usuario->agregar($datos)){
                    $this->_redirect('/usuario/');
                }
            }   
        }
    }
    public function editarAction(){  
        /* [TITLE] */
        $this->view->headTitle()->prepend('Editar - Usuario sistema - ');       
        /* [FORMULARIO] */
        $formulario = new Default_Form_Usuario();         
        $formulario->getElement('Enviar')->setLabel('Actualizar');
        $this->view->formulario_usuario = $formulario;
        /* [PARAMETROS] */
        $id = $this->_getParam('id',0);        
        /* [CAPTURAR DATOS] */
        $usuario = new Default_Model_DbTable_Usuario();
        $datos = $usuario->obtener($id);
        /* [PROCESAR DATOS] */
        $respuesta = $this->getRequest();        
        if($respuesta->isPost()){
            if($formulario->isValid($this->_request->getPost())){
                /* [VALORES] */
                $datos = array(
                    'email_usuario'=>$formulario->getValue('email_usuario'),
                    'clave_usuario'=>$formulario->getValue('clave_usuario')
                );
                $usuario = new Default_Model_DbTable_Usuario();
                if($usuario->actualizar($datos,$formulario->getValue('id_usuario'))){
                    $this->_redirect('/usuario/');                    
                }
            }   
        }else{
            /* [INGRESAR DATOS FORMULARIO] */
           $formulario->populate((array)$datos);            
        }
        /* [LIMPIEZA] */
        unset($usuario,$formulario);
    }
    public function eliminarAction(){  
        /* [DESAHIBILITAR LAYOUT y VIEW] */
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        /* [PARAMETROS] */
        $id = $this->_getParam('id',0);
        /* [PROCESAR] */
        $usuario = new Default_Model_DbTable_Usuario();        
        if($usuario->eliminar($id)){
            $this->_redirect('/usuario/');            
        }
    }
    public function topAction(){}
}