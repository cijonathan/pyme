<?php

class Default_Form_Componente extends Zend_Form
{

    public function init()
    {
        $this->setName('usuario');
        $this->setAttrib('id','formulario-login');        
        
        $componente = new Zend_Form_Element_Select('nombre_componente');
        $componente ->setRequired(true)
                ->setLabel('¿Qué componente?:');  
        
        $componentebase = new Default_Model_DbTable_Componente();
        foreach($componentebase->listar() as $retorno){
            $componente->addMultiOption($retorno->id_componente,$retorno->nombre_componente);            
        }
        
        $boton = new Zend_Form_Element_Submit('Enviar');
        $boton->setAttrib('class','btn btn-primary')
              ->setLabel('Agregar');

        $this->addElements(array($componente,$boton));         
        
    }


}

