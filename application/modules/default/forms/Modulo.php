<?php

class Default_Form_Modulo extends Zend_Form
{

    public function init()
    {
        $this->setName('usuario');
        $this->setAttrib('id','formulario-login');
        
        $id = new Zend_Form_Element_Hidden('id_empresa');
        $id->addFilter('Int')
                ->removeDecorator('Label'); 
        
        $nombre = new Zend_Form_Element_Text('nombre_modulo');
        $nombre
                ->setRequired(true)
                ->setLabel('Nombre:')
                ->setAttrib('size','27')
                ->setAttrib('placeholder','Ingrese el nombre del modulo')
                ->setAttrib('class','required email'); 
        
        $estado = new Zend_Form_Element_Select('id_estado');
        $estado ->setRequired(true)
                ->setLabel('Estado:');
        
        $estadodb = new Default_Model_DbTable_Estado();
        foreach($estadodb->listar() as $retorno){
            $estado->addMultiOption($retorno['id_estado'],$retorno['nombre_estado']);
        }        
        
        $boton = new Zend_Form_Element_Submit('Enviar');
        $boton->setAttrib('class','btn btn-primary btn-large')
              ->setLabel('Agregar');

        $this->addElements(array($id,$nombre,$estado,$boton));         
        
    }
}
