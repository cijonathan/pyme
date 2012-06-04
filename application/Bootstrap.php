<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
   protected function _initAutoLoad(){
               /* [REGISTRO DE MODULOS] */
        $modulos = new Zend_Application_Module_Autoloader(array(
                'namespace'=>'',
                'basePath'=>APPLICATION_PATH.'/modules/default'
        ));
        /* [AUTH] */
        if(Zend_Auth::getInstance()->hasIdentity())
        {
            Zend_Registry::set('id_usuario', Zend_Auth::getInstance()->getStorage()->read()->id_usuario);            
            Zend_Registry::set('email_usuario', Zend_Auth::getInstance()->getStorage()->read()->email_usuario);            
            Zend_Registry::set('clave_usuario', Zend_Auth::getInstance()->getStorage()->read()->clave_usuario);               
            Zend_Registry::set('tipo_usuario', Zend_Auth::getInstance()->getStorage()->read()->tipo_usuario);                          
        }else{
            Zend_Registry::set ('tipo_usuario', 'visitante');            
        }      
        /* [ACL] */
        $this->acl = new Model_Acl();
        #$identidad = Zend_Auth::getInstance();        
        $controlador = Zend_Controller_Front::getInstance();
        $controlador->registerPlugin(new Plugin_Acceso($this->acl));
        /* [SLUG] */
        #$controlador->registerPlugin(new Plugin_Slug());
        /* [RETORNO] */
        return $modulos;  
   }
    protected function __initSession() {
        Zend_Session::start();
    }   
    protected function _initView()
    {
        $vista = new Zend_View();
        /* [META] */    
        $vista->headMeta()
                ->setHttpEquiv('Content-Language', 'es')
                ->setHttpEquiv('Content-Type', 'text/html; charset=UTF-8')                
                ->appendName('title','')
                ->appendName('author', 'Creatividad e Inteligencia')
                ->appendName('description','')
                ->appendName('keywords','')
                ->appendName('robots','index,follow');
        /* [TITLE] */
        $vista->headTitle('PYME');
        /* FAVICON */
        #$vista->headLink(array( 'rel' => 'shortcut icon','href' =>'/imagenes/favicon.ico'));       
        /* CSS */        
        $vista->headLink()
                ->appendStylesheet('/css/bootstrap-responsive.css')
                ->appendStylesheet('/css/bootstrap.css')
                ->appendStylesheet('/css/estilo.css');
        /* [JS] */
        $vista->headScript()
                ->appendFile('/js/jquery.js') 
                ->appendFile('/js/jquery.bootstrap.js') 
                ->appendFile('/js/jquery.validate.js') 
                ->appendFile('/js/jquery.ci.js'); 
        return $vista;
    }   
}

