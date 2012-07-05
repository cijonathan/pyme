<?php

class Default_Form_Tamano extends Zend_Form
{

    public function init()
    {
        $this->setName('usuario');
        $this->setAttrib('id','formulario-login'); 
        
        $id = new Zend_Form_Element_Hidden('id_modulo');
        $id->addFilter('Int')
                ->removeDecorator('Label');   
        
        $ancho_grande = new Zend_Form_Element_Text('ancho_grande');
        $ancho_grande
                ->setRequired(true)
                ->setLabel('Ancho Grande:')
                ->setAttrib('size','27')
                ->setAttrib('placeholder','Ingrese el ancho grande del campo')
                ->setAttrib('class','required numeric'); 
        $alto_grande = new Zend_Form_Element_Text('alto_grande');
        $alto_grande
                ->setRequired(true)
                ->setLabel('Alto Grande:')
                ->setAttrib('size','27')
                ->setAttrib('placeholder','Ingrese el alto grande del campo')
                ->setAttrib('class','required numeric');         
        $ancho_mediana = new Zend_Form_Element_Text('ancho_mediana');
        $ancho_mediana
                ->setRequired(true)
                ->setLabel('Ancho Mediana:')
                ->setAttrib('size','27')
                ->setAttrib('placeholder','Ingrese el ancho mediana del campo')
                ->setAttrib('class','required numeric'); 
        $alto_mediana = new Zend_Form_Element_Text('alto_mediana');
        $alto_mediana
                ->setRequired(true)
                ->setLabel('Alto Mediana:')
                ->setAttrib('size','27')
                ->setAttrib('placeholder','Ingrese el alto mediana del campo')
                ->setAttrib('class','required numeric');         
        $ancho_chica = new Zend_Form_Element_Text('ancho_chica');
        $ancho_chica
                ->setRequired(true)
                ->setLabel('Ancho Chica:')
                ->setAttrib('size','27')
                ->setAttrib('placeholder','Ingrese el ancho chica del campo')
                ->setAttrib('class','required numeric'); 
        $alto_chica = new Zend_Form_Element_Text('alto_chica');
        $alto_chica
                ->setRequired(true)
                ->setLabel('Alto Chica:')
                ->setAttrib('size','27')
                ->setAttrib('placeholder','Ingrese el alto chica del campo')
                ->setAttrib('class','required numeric');         

        
        
        
        $boton = new Zend_Form_Element_Submit('submit_form_tamano');
        $boton->setAttrib('class','btn btn-primary')
              ->setLabel('Actualizar');

        $this->addElements(array($id,$ancho_grande,$alto_grande,$ancho_mediana,$alto_mediana,$ancho_chica,$alto_chica,$boton));         
        
    }


}

