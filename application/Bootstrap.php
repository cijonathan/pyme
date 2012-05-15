<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
   protected function _initAutoLoad(){
        /* [REGISTRO DE MODULOS] */
        $modulos = new Zend_Application_Module_Autoloader(array(
                'namespace'=>'',
                'basePath'=>APPLICATION_PATH.'/modules/default'
        ));
        return $modulos;
   }
    protected function _initView()
    {
        $vista = new Zend_View();
        /* [META] */    
        $vista->headMeta()
                ->setHttpEquiv('Content-Language', 'es')
                ->setHttpEquiv('Content-Type', 'text/html; charset=UTF-8')                
                /*->appendName('title','Blog de Moda masculina - Revolution is my boyfriend')*/
                ->appendName('author', 'Creatividad e Inteligencia')
                ->appendName('description','')
                ->appendName('keywords','')
                ->appendName('robots','index,follow');
        /* [TITLE] */
        $vista->headTitle('CMS - Creatividad e inteligencia');
        /* FAVICON */
        $vista->headLink(array( 'rel' => 'shortcut icon','href' =>'/imagenes/favicon.ico'));        
        /* [JS] */
        $vista->headScript()
                ->appendFile('/js/jquery-1.6.1.min.js'); 
        return $vista;
    }   
}

