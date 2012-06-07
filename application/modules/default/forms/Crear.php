<?php

class Default_Form_Crear extends Zend_Form
{

    public function init()
    {
        $this->setName('usuario');        
        
        $nombre = new Zend_Form_Element_Text('nombre_item');
        $nombre
                ->setRequired(true)
                ->setLabel('Nombre:')
                ->setAttrib('size','27')
                ->setAttrib('placeholder','Ingrese el nombre del estado')
                ->setAttrib('class','required');           
        
        $boton = new Zend_Form_Element_Submit('Enviar');
        $boton->setAttrib('class','btn btn-primary')
              ->setLabel('Crear');
        
        $this->addElements(array($nombre,$boton));         
        
    }
}