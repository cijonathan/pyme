<?php

class ProyectoController extends Zend_Controller_Action
{
    public function init() {
        /* [DATOS DEL ZEND AUTH] */
        $sesion = new Zend_Registry();       
        $this->view->email_usuario = $sesion->get('email_usuario');        
    }    
    public function indexAction(){
        /* [TITLE] */             
        $this->view->headTitle()->prepend('Proyectos - ');         
        /* [LISTAR] */
        $proyecto = new Default_Model_DbTable_Proyecto();
        $this->view->datos = $proyecto->listar();
        /* [FORMULARIO] */
        $formulario = new Default_Form_Estadoproyecto();
        $this->view->formulario = $formulario;
        /* [PROCESAR FORMULARIO] */
        $respuesta = $this->getRequest();
        if($respuesta->isPost()){
            if($formulario->isValid($this->_request->getPost())){   
                /* [DATOS] */
                $id_empresa = $formulario->getValue('id_empresa');
                $id_estado = $formulario->getValue('id_estado');
                /* [PROCESAR ESTADO] */
                $proyecto = new Default_Model_DbTable_Proyecto();
                if($proyecto->actualizarestado($id_empresa, $id_estado)){
                    $this->view->exito = true;
                }else{
                    $this->view->error = true;                    
                }
            }
        }
    }
    public function crearAction(){
        /* [TITLE] */             
        $this->view->headTitle()->prepend('Proyectos - ');         
        /* [FORMULARIO] */
        $formulario = new Default_Form_Proyecto();
        $this->view->formulario_proyecto = $formulario;
        /* [PROCESAR FORMULARIO] */
        $respuesta = $this->getRequest();
        if($respuesta->isPost()){
            if($formulario->isValid($this->_request->getPost())){
                /* [VALORES] */
                $empresa = $formulario->getValue('nombre_empresa');       
                $miscelaneos = new Default_Model_Miscelaneos(); 
                $slug = $miscelaneos->Amigable($empresa);
                $url = $formulario->getValue('url_empresa');
                $estado = $formulario->getValue('id_estado');
                $clave = $miscelaneos->ClaveAleatoria(16);
                /* [FORMATEAR 16 CARACTERES] */
                $usuario = trim(substr('ci_pyme_'.$slug,0,16));
                $basededatos = trim(substr('ci_pyme_'.$slug,0,16));                    
                /* [ARREGLO] */
                $datos = array(
                    'nombre_empresa'=>$empresa,
                    'nombre_empresa_slug'=>$slug,
                    'url_empresa'=>$url,
                    'servidor_empresa'=>'127.0.0.1',
                    'usuario_empresa'=>$usuario,
                    'clave_empresa'=>$clave,
                    'basededatos_empresa'=>$basededatos,
                    'id_estado'=>$estado
                );
                /* [CREAR BASE] */
                $base = new Default_Model_DbTable_Proyecto();
                if($base->crearbase($usuario,$basededatos,$clave)){
                    $ultimo = $base->crearempresa($datos);
                    if($ultimo>0){
                        $datosusuario = array(
                            'nombre_usuario'=>'Desarrollador',
                            'email_usuario'=>$slug.'@creatividadeinteligencia.cl',
                            'clave_usuario'=>'creativo',
                            'id_tipo'=>1
                        );
                        if($base->crearusuario($datosusuario, $ultimo)){
                            $this->_redirect('/proyecto/');
                        }
                    }
                }else{
                    $this->view->error = true;
                }
            }
        }
            
    }    
    public function moduloAction(){
        /* [TITLE] */             
        $this->view->headTitle()->prepend('Modulos del proyecto - ');         
        /* [PARAMETROS] */
        $id = $this->_getParam('id',0);   
        $this->view->id = $id;
        /* [OBTENER] */
        $proyecto = new Default_Model_DbTable_Proyecto();
        $this->view->datos = $proyecto->obtener($id);
        /* [LISTAR] */
        $modulos = new Default_Model_DbTable_Modulo();
        $this->view->modulos = $modulos->listar($id);
        /* [FORMULARIO ESTADO] */
        $formulario = new Default_Form_Estado();
        $this->view->formulario = $formulario;
        /* [PROCESAR FORMULARIO] */
        $respuesta = $this->getRequest();
        if($respuesta->isPost()){
            if($formulario->isValid($this->_request->getPost())){
                /* [CAPTURAR VALORES] */
                $id_estado = $formulario->getValue('id_estado');
                $id_modulo = $formulario->getValue('id_modulo');
                /* [ACTUALIZAR ESTADO] */
                $modulo = new Default_Model_DbTable_Modulo();
                if($modulo->actualizarestado($id_modulo, $id_estado)){
                    $this->view->exito = true;
                }
            }    
        }
    }
    public function crearmoduloAction(){
        /* [TITLE] */             
        $this->view->headTitle()->prepend('Crear modulo del proyecto - ');         
        /* [PARAMETROS] */
        $id = $this->_getParam('id',0);   
        $this->view->id = $id;        
        /* [OBTENER] */
        $proyecto = new Default_Model_DbTable_Proyecto();
        $this->view->datos = $proyecto->obtener($id);        
        /* [FORMULARIO] */
        $formulario = new Default_Form_Modulo();
        $this->view->formulario = $formulario;
        $formulario->populate(array('id_empresa'=>$id));
        /* [PROCESAR FORMULARIO] */
        $respuesta = $this->getRequest();
        if($respuesta->isPost()){
            if($formulario->isValid($this->_request->getPost())){
                /* [DATOS FORMULARIO] */
                $nombre = $formulario->getValue('nombre_modulo');
                $estado = $formulario->getValue('id_estado');
                $empresa = $formulario->getValue('id_empresa');
                /* [SLUG] */
                $slug = new Default_Model_Miscelaneos();
                $slug = $slug->Amigable($nombre);
                /* [CAPTURAR DATOS] */
                $datos = array(
                    'nombre_modulo'=>$nombre,
                    'nombre_modulo_slug'=>$slug,
                    'id_estado'=>$estado,
                    'id_empresa'=>$empresa
                );
                $modulo = new Default_Model_DbTable_Modulo();
                $ultimo = $modulo->agregar($datos);
                if($ultimo>0) {
                    $this->_redirect('/proyecto/crearcampo/id/'.$empresa.'/idmodulo/'.$ultimo);
                }else{
                    $this->view->error = true;
                }
            }
        }        
    }
    public function crearcampoAction(){
        /* [EXITO DE ELIMINACION DE CAMPO] */
        $exito = new Zend_Session_Namespace("exito");        
        $this->view->exito = $exito->mensaje;       
        $exito->setExpirationSeconds(1);
        /* [ERROR DE ELIMINACION DE CAMPO] */
        $error_campo = new Zend_Session_Namespace("error_campo");        
        $this->view->error_campo = $error_campo->error;       
        $error_campo->setExpirationSeconds(1);
        /* [TITLE] */             
        $this->view->headTitle()->prepend('Crear campo del modulo - ');        
        /* [PARAMETROS] */
        $id = $this->_getParam('id',0);          
        $id_modulo = $this->_getParam('idmodulo',0);        
        /* [OBTENER PROYECTO] */
        $proyecto = new Default_Model_DbTable_Proyecto();
        $this->view->datos = $proyecto->obtener($id);
        /* [OBTENER MODULO] */
        $modulo = new Default_Model_DbTable_Modulo();
        $datosmodulo = $modulo->obtener($id_modulo);
        $this->view->modulos = $datosmodulo;
        /* [FORMULARIO] */
        $formulario = new Default_Form_Campo();
        $this->view->formulario = $formulario;
        $formulario->populate(array('id_modulo'=>$id_modulo));
        /* [PROCESAR FORMULARIO] */
        $campo = new Default_Model_DbTable_Campo();           
        /* [BOTON CREAR o ACTUALIZAR] */
        $this->view->boton = $campo->tabla($id,$datosmodulo->nombre_modulo_slug);        
        $respuesta = $this->getRequest();
        if($respuesta->isPost()){
            if($formulario->isValid($this->_request->getPost())){
                /* [VALORES] */
                $modulo = $formulario->getValue('id_modulo');
                $nombre = $formulario->getValue('nombre_modulo');                
                $orden = $formulario->getValue('orden_campo');
                $listado = $formulario->getValue('listado_campo');
                $validacion = $formulario->getValue('id_validacion');
                $tipo = $formulario->getValue('id_tipo');
                $estado = $formulario->getValue('id_estado');
                /* [SLUG] */
                $nombre_slug = new Default_Model_Miscelaneos();
                $nombre_slug = $nombre_slug->Amigable($nombre);
                $datos = array(
                    'nombre_campo'=>$nombre,
                    'nombre_campo_slug'=>$nombre_slug,
                    'orden_campo'=>$orden,
                    'listado_campo'=>$listado,
                    'id_validacion'=>$validacion,
                    'id_tipo'=>$tipo,
                    'id_modulo'=>$modulo,
                    'id_estado'=>$estado
                );             
                if($campo->agregar($datos)){
                    $this->_redirect('proyecto/crearcampo/id/'.$id.'/idmodulo/'.$id_modulo);
                }else{
                    $this->view->error = true;                    
                }
            }   
        }
        /* [LISTAR CAMPO] */
        $this->view->campos = $campo->listar($id_modulo);
    }
    public function bdmoduloactualizaAction(){       
        /* [DESAHIBILITAR LAYOUT y VIEW] */
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true); 
        /* [PARAMETROS] */
        $id_empresa = $this->_getParam('id',0);          
        $id_modulo = $this->_getParam('idmodulo',0);   
        /* [MODULO] */
        $modulo = new Default_Model_DbTable_Modulo(); 
        if($modulo->actualizarmodulo($id_empresa, $id_modulo)){
            /* [EXITO] */
            $exito = new Zend_Session_Namespace("exito");
            $exito->mensaje = true;
            /* [REDIRECCIONAR] */
            $this->_helper->redirector('crearcampo', 'proyecto','default',array('id'=>$id_empresa,'idmodulo'=>$id_modulo));             
        }else{
            /* [ERROR] */            
            $error_campo = new Zend_Session_Namespace("error_campo");
            $error_campo->error = true;
            /* [REDIRECCIONAR] */            
            $this->_helper->redirector('crearcampo', 'proyecto','default',array('id'=>$id_empresa,'idmodulo'=>$id_modulo));             
        }
    }
    public function bdmodulocrearAction(){
        /* [DESAHIBILITAR LAYOUT y VIEW] */
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        /* [PARAMETROS] */
        $id_empresa = $this->_getParam('id',0);          
        $id_modulo = $this->_getParam('idmodulo',0);  
        /* [MODULO] */
        $modulo = new Default_Model_DbTable_Modulo();
        if($modulo->crearmodulo($id_empresa, $id_modulo)){
            /* [EXITO] */
            $exito = new Zend_Session_Namespace("exito");
            $exito->mensaje = true;
            /* [REDIRECCIONAR] */
            $this->_helper->redirector('crearcampo', 'proyecto','default',array('id'=>$id_empresa,'idmodulo'=>$id_modulo));              
        }
    }
    public function eliminacampoAction(){
        /* [DESAHIBILITAR LAYOUT y VIEW] */
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true); 
        /* [PARAMETROS] */
        $id_empresa = $this->_getParam('id',0);          
        $id_modulo = $this->_getParam('idmodulo',0);         
        $id_campo = $this->_getParam('idcampo',0);
        /* [CAMPO] */
        $campo = new Default_Model_DbTable_Campo();
        if($campo->eliminar($id_campo, $id_modulo, $id_empresa)){
            /* [EXITO] */
            $exito = new Zend_Session_Namespace("exito");
            $exito->mensaje = true;
            /* [REDIRECCIONAR] */
            $this->_redirect('proyecto/crearcampo/id/'.$id_empresa.'/idmodulo/'.$id_modulo);                  
        }else{    
            /* [ERROR] */            
            $error_campo = new Zend_Session_Namespace("error_campo");
            $error_campo->error = true;
            /* [REDIRECCIONAR] */            
            $this->_helper->redirector('crearcampo', 'proyecto','default',array('id'=>$id_empresa,'idmodulo'=>$id_modulo));            
        }
        
    }
    public function componentesAction(){
        /* [EXITO DESDE OTRO LADO] */
        $exito = new Zend_Session_Namespace("exito");        
        $this->view->exito = $exito->mensaje;       
        $exito->setExpirationSeconds(1);        
        /* [ERROR DESDE OTRO LADO] */
        $exito = new Zend_Session_Namespace("error");        
        $this->view->error = $exito->mensaje;       
        $exito->setExpirationSeconds(1);        
        /* [PARAMETROS] */
        $id_empresa = $this->_getParam('id',0);          
        $id_modulo = $this->_getParam('idmodulo',0);  
        /* [OBTENER PROYECTO] */
        $proyecto = new Default_Model_DbTable_Proyecto();
        $this->view->datos = $proyecto->obtener($id_empresa);     
        /* [OBTENER MODULO] */
        $modulo = new Default_Model_DbTable_Modulo();
        $datosmodulo = $modulo->obtener($id_modulo);
        $this->view->modulos = $datosmodulo;  
        /* [FORMULARIO] */
        $formulario = new Default_Form_Componente();
        $this->view->formulario = $formulario;
        /* [FORMULARIO TAMAÑO] */
        $formulario_tamano = new Default_Form_Tamano();        
        $this->view->formulario_tamano = $formulario_tamano;
        /* [OBTENER TAMAÑO] */
        $tamano = new Default_Model_DbTable_Tamano();
        $total = $tamano->obtener($id_modulo);
        if($total>0) $formulario_tamano->populate($total);          
        /* [PROCESAR FORMULARIO] */
        $respuesta = $this->getRequest();
        if($respuesta->isPost()){
            if($this->_request->getPost('submit_form_componente', false)){
                if($formulario->isValid($this->_request->getPost())){ 
                    $id_componente = $formulario->getValue('nombre_componente');
                    $datos = array(
                        'id_modulo'=>$id_modulo,
                        'id_componente'=>$id_componente
                    );
                    $componente = new Default_Model_DbTable_Componente();
                    if($componente->agregar($datos)){
                        $this->view->exito = true;
                    }else{
                        $this->view->error = true;
                    }                    
                }else return false;                                
            }elseif($this->_request->getPost('submit_form_tamano', false)){
                if($formulario_tamano->isValid($this->_request->getPost())){ 
                    $datos_tamano = $formulario_tamano->getValues();
                    if($tamano->actualizar($datos_tamano, $id_modulo)){
                        $this->view->exito = true;                        
                    }else{
                        $this->view->error = true;                        
                    }
                }else return false;               
            }            
            /*if($formulario->isValid($this->_request->getPost())){
                
            }              */             
        }
        /* [LISTAR COMPONENTE] */
        $componente = new Default_Model_DbTable_Componente();
        $this->view->componente = $componente->listarModulo($id_modulo);
    }
    public function eliminacomponenteAction(){
        /* [DESAHIBILITAR LAYOUT y VIEW] */
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);  
        /* [PARAMETROS] */
        $id_empresa = $this->_getParam('id',0);          
        $id_modulo = $this->_getParam('idmodulo',0);  
        $id_componente = $this->_getParam('idcomponente',0);
        /* [DATOS] */
        $datos = array(
            'id_empresa'=>$id_empresa,
            'id_modulo'=>$id_modulo,
            'id_componente'=>$id_componente
        );
        /* [PROCESAR] */
        $componente = new Default_Model_DbTable_Componente();
        if($componente->eliminar($datos)){   
            /* [EXITO] */
            $exito = new Zend_Session_Namespace("exito");
            $exito->mensaje = true;
            /* [REDIRECCIONAR] */
            $this->_redirect('proyecto/componentes/id/'.$id_empresa.'/idmodulo/'.$id_modulo);            
        }else{
            /* [ERROR] */
            $exito = new Zend_Session_Namespace("error");
            $exito->mensaje = true;
            /* [REDIRECCIONAR] */
            $this->_redirect('proyecto/componentes/id/'.$id_empresa.'/idmodulo/'.$id_modulo);              
        }
    }
    public function relacionAction(){
        /* [EXITO DESDE OTRO LADO] */
        $exito = new Zend_Session_Namespace("exito");        
        $this->view->exito = $exito->mensaje;       
        $exito->setExpirationSeconds(1);  
        /* [ERROR DESDE OTRO LADO] */
        $exito = new Zend_Session_Namespace("error");        
        $this->view->error = $exito->mensaje;       
        $exito->setExpirationSeconds(1);        
        /* [TITLE] */             
        $this->view->headTitle()->prepend('Crear relacion del modulo - ');   
        /* [PARAMETROS] */
        $id = $this->_getParam('id',0);                 
        /* [OBTENER PROYECTO] */
        $proyecto = new Default_Model_DbTable_Proyecto();
        $this->view->datos = $proyecto->obtener($id);
        /* [FORMULARIO] */
        $formulario = new Default_Form_Relacion(array('id_empresa'=>$id));
        $formulario->populate(array('id_empresa'=>$id));
        $this->view->formulario = $formulario;
        /* [INSTANCREAR] */
        $relacion = new Default_Model_DbTable_Relacion();
        /* [PROCESAR FORMULARIO] */
        $respuesta = $this->getRequest();
        if($respuesta->isPost()){
            if($formulario->isValid($this->_request->getPost())){
                /* [VALORES] */
                $id_padre = $formulario->getValue('id_padre');            
                $id_hijo = $formulario->getValue('id_hijo');            
                $id_tipo = $formulario->getValue('id_tipo'); 
                $id_cardinalidad = $formulario->getValue('id_cardinalidad');
                /* [DATOS] */
                $datos = array(
                    'id_padre'=>$id_padre,
                    'id_hijo'=>$id_hijo,
                    'id_tipo'=>$id_tipo,
                    'id_cardinalidad'=>$id_cardinalidad
                );
                /* [RELACION] */
                if($relacion->crear($datos)){
                    $this->view->exito = true;
                }else{
                    $this->view->error = true;                    
                }
            }   
        }
        /* [LISTAR RELACIONES] */
        $this->view->datosrelacion = $relacion->listar($id);
    }
    public function eliminarelacionAction(){
        /* [DESAHIBILITAR LAYOUT y VIEW] */
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true); 
        /* [PARAMETROS] */
        $id = $this->_getParam('id',0);
        /* [OBTENER DATOS] */
        $relacion = new Default_Model_DbTable_Relacion();
        $modulo = new Default_Model_DbTable_Modulo();
        $id_modulo = $relacion->obtener($id)->id_padre;
        $id_proyecto = $modulo->obtener($id_modulo)->id_empresa;
        /* [INSTANCEAR BASE] */
        $relacion = new Default_Model_DbTable_Relacion();
        if($relacion->eliminar($id)){   
            /* [EXITO] */
            $exito = new Zend_Session_Namespace("exito");
            $exito->mensaje = true;
            /* [REDIRECCIONAR] */
            $this->_redirect('proyecto/relacion/id/'.$id_proyecto);             
        }else{     
            /* [EXITO] */
            $error = new Zend_Session_Namespace("error");
            $error->mensaje = true;
            /* [REDIRECCIONAR] */
            $this->_redirect('proyecto/relacion/id/'.$id_proyecto);              
        }
        
    }        
    public function estadoAction(){
        /* [DESAHIBILITAR LAYOUT y VIEW] */
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);        
    }
    public function topAction(){}
}