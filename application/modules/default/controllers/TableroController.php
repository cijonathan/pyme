<?php

class TableroController extends Zend_Controller_Action
{
    public function init() {
        /* [DATOS DEL ZEND AUTH] */
        $sesion = new Zend_Registry();       
        $this->view->email_usuario = $sesion->get('email_usuario');        
    }
    public function indexAction(){
        /* [TITLE] */       
        $this->view->headTitle()->prepend('Tablero - ');         
    }
    public function actualizacionesAction(){
        /* [TITLE] */       
        $this->view->headTitle()->prepend('Actualizaciones - ');          
    }
    public function cerrarAction(){
        /* [DESAHIBILITAR LAYOUT y VIEW] */
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if(Zend_Auth::getInstance()->hasIdentity()){
            /* [CERRAR SESSION] */
            Zend_auth::getInstance()->clearIdentity();
        }       
        /* [REDIRECCIONAR] */
        $this->_redirect('/');             
    }
    public function topAction(){}    
    
}