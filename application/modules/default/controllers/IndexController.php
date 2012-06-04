<?php

class IndexController extends Zend_Controller_Action
{

    public function init(){
        /* [SET LAYOUT] */
        $this->_helper->layout->setLayout('login');
    }

    public function indexAction(){
        /* [TITLE] */
        $this->view->headTitle()->prepend('Acceso a la plataforma - ');         
        /* [LOGIN] */
        $login = new Default_Form_Login();
        $this->view->login = $login;
        $respuesta = $this->getRequest();
        if($respuesta->isPost()){
            if($login->isValid($this->_request->getPost())){
                /* [IDENTIFICACION BASE DE DATOS] */
                $identidad = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
                $identidad
                    ->setTableName('usuario_sistema')
                    ->setIdentityColumn('email_usuario')
                    ->setCredentialColumn('clave_usuario'); 
                /* [VALORES] */
                $email = $login->getValue('email_usuario');
                $clave = $login->getValue('clave_usuario');
                /* [SETEAR DATOS] */
                $identidad->setIdentity($email)->setCredential($clave);
                /* [INSTANCIA AUTH] */
                $validacion = Zend_Auth::getInstance();
                $respuesta = $validacion->authenticate($identidad);
                if($respuesta->isValid()){
                    $correcto = $identidad->getResultRowObject();
                    $guardado = $validacion->getStorage();
                    $guardado->write($correcto);
                    /* [REDIRECCION] */
                    $this->_redirect('/tablero/');
                }else{     
                    /* [DISTINTOS TIPOS DE ERRORES] */
                    switch ($respuesta->getCode()) {
                    case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND;
                        $this->view->error = 1;
                    break;
                    case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
                        $this->view->error = 2;
                    break;
                    case Zend_Auth_Result::FAILURE_UNCATEGORIZED;
                        $this->view->error = 3;
                    break;                    
                    default:
                        $this->view->error = 3;                            
                    break;
                    }                    
                }
            }
        /* [EN CASO QUE EXISTA UN ZEND AUTH */
        }elseif(Zend_Auth::getInstance()->hasIdentity()){            
            $this->_redirect('/tablero/');            
        }                   
    }
}

