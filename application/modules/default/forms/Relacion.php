<?php

class Default_Form_Relacion extends Zend_Form
{ 
    
    public function __construct($options = null)
    {
        parent::__construct($options);
    }


    public function init()
    {
        $this->setName('relacion');
        $this->setAttrib('id','formulario-login');
        
        $id = new Zend_Form_Element_Hidden('id_empresa');
        $id->addFilter('Int')
                ->removeDecorator('Label'); 
        
        $modulo = new Default_Model_DbTable_Modulo();                
        
        $id_padre = new Zend_Form_Element_Select('id_padre');
        $id_padre ->setRequired(true)
                ->setLabel('Modulo padre:');        
        
        foreach($modulo->listar($this->getAttrib('id_empresa')) as $retorno){
            $id_padre->addMultiOption($retorno->id_modulo,$retorno->nombre_modulo);
        }
        
        $id_hijo = new Zend_Form_Element_Select('id_hijo');
        $id_hijo ->setRequired(true)
                ->setLabel('Modulo hijo:');        
        
        foreach($modulo->listar($this->getAttrib('id_empresa')) as $retorno){
            $id_hijo->addMultiOption($retorno->id_modulo,$retorno->nombre_modulo);
        }        
        
        $relacion = new Default_Model_DbTable_Relacion();
        
        $tiporelacion = new Zend_Form_Element_Select('id_tipo');
        $tiporelacion ->setRequired(true)
                ->setLabel('Tipo relacion:');
        foreach($relacion->listarTipo() as $retorno){
            $tiporelacion->addMultiOption($retorno->id_tipo,$retorno->nombre_tipo);
        }     
        
        $cardinalidad = new Zend_Form_Element_Select('id_cardinalidad');
        $cardinalidad ->setRequired(true)
                ->setLabel('Cardinalidad:');
        foreach($relacion->listarCardinalidad() as $retorno){
            $cardinalidad->addMultiOption($retorno->id_cardinalidad,$retorno->nombre_cardinalidad);
        }    
        
        $boton = new Zend_Form_Element_Submit('Enviar');
        $boton->setAttrib('class','btn btn-primary')
              ->setLabel('Crear');

        $this->addElements(array($id,$id_padre,$id_hijo,$tiporelacion,$cardinalidad,$boton));         
        
    }


}

