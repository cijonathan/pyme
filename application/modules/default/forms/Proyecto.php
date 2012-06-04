<?php

class Default_Form_Proyecto extends Zend_Form
{

    public function init()
    {
        $this->setName('usuario');
        $this->setAttrib('id','formulario-proyecto');  
        
        $id = new Zend_Form_Element_Hidden('id_empresa',array('disableLoadDefaultDecorators' => true));
        $id->addFilter('Int');            
        
        $nombre_slug = new Zend_Form_Element_Hidden('nombre_empresa_slug',array('disableLoadDefaultDecorators' => true));
        $nombre_slug->addFilter('Int');            
        
        $nombre = new Zend_Form_Element_Text('nombre_empresa');
        $nombre
                ->setRequired(true)
                ->setLabel('Nombre proyecto:')
                ->setAttrib('size','27')
                ->setAttrib('placeholder','Ingrese el nombre de empresa')
                ->setAttrib('class','required');        
        
        $url = new Zend_Form_Element_Text('url_empresa');
        $url
                ->setRequired(true)
                ->setLabel('Url proyecto:')
                ->setAttrib('size','27')     
                ->setAttrib('placeholder','Ingrese la url del proyecto')                
                ->setAttrib('class','required')
                ->addFilter('StringtoLower')
                ->addFilter('StripTags');              
        
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

        $this->addElements(array($id,$nombre_slug,$nombre,$url,$estado,$boton));         
        
    }
}