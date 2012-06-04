<?php

class Default_Form_Usuario extends Zend_Form
{

    public function init()
    {
        $this->setName('usuario');
        $this->setAttrib('id','formulario-login');
        
        $id = new Zend_Form_Element_Hidden('id_usuario');
        $id->addFilter('Int');        
        
        $email = new Zend_Form_Element_Text('email_usuario');
        $email
                ->setRequired(true)
                ->setLabel('Email:')
                ->setAttrib('size','27')
                ->setAttrib('placeholder','Ingrese el email de acceso')
                ->setAttrib('class','required email');        
        
        $clave = new Zend_Form_Element_Text('clave_usuario');
        $clave
                ->setRequired(true)
                ->setLabel('Contraseña:')
                ->setAttrib('size','27')     
                ->setAttrib('placeholder','Ingrese la clave de acceso')                
                ->setAttrib('class','required login')
                ->addFilter('StringtoLower')
                ->addFilter('StripTags');
        
        $boton = new Zend_Form_Element_Submit('Enviar');
        $boton->setAttrib('class','btn btn-primary btn-large')
              ->setLabel('Agregar');

        $this->addElements(array($id,$email,$clave,$boton));         
        
    }


}

