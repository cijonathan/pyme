<?php

class Default_Form_Estadoproyecto extends Zend_Form
{

    public function init()
    {
        $this->setName('usuario');
        $this->setAttrib('class','form-inline');  
        
        $id = new Zend_Form_Element_Hidden('id_empresa');
        $id->addFilter('Int')
                ->removeDecorator('Label');                                      
        
        $estado = new Zend_Form_Element_Select('id_estado');
        $estado ->setRequired(true)
                ->setLabel('Estado:');
        
        $estadodb = new Default_Model_DbTable_Estado();
        foreach($estadodb->listar() as $retorno){
            $estado->addMultiOption($retorno['id_estado'],$retorno['nombre_estado']);
        }        
        
        $boton = new Zend_Form_Element_Submit('Enviar');
        $boton->setAttrib('class','btn btn-primary')
              ->setLabel('Modificar');
        
        $this->addElements(array($id,$estado,$boton));         
        
    }
}