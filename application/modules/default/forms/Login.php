<?php

class Default_Form_Login extends Zend_Form
{

    public function init()
    {
        $this->setName('usuario');
        $this->setAttrib('id','formulario-login');
        
        $email = new Zend_Form_Element_Text('email_usuario');
        $email
                ->setRequired(true)
                ->setLabel('Email:')
                ->setAttrib('size','27')
                ->setAttrib('placeholder','Ingrese su email de acceso')
                ->setAttrib('class','required email');        
        
        $clave = new Zend_Form_Element_Password('clave_usuario');
        $clave
                ->setRequired(true)
                ->setLabel('Contraseña:')
                ->setAttrib('size','27')     
                ->setAttrib('placeholder','Ingrese su clave de acceso')                
                ->setAttrib('class','required login')
                ->addFilter('StringtoLower')
                ->addFilter('StripTags');
        
        $boton = new Zend_Form_Element_Submit('Enviar');
        $boton->setAttrib('class','btn btn-primary btn-large')
              ->setLabel('Acceder');

        $this->addElements(array($email,$clave,$boton));         
        
    }


}

