<?php

class Default_Form_Campo extends Zend_Form
{

    public function init()
    {
        $this->setName('usuario');
        $this->setAttrib('id','formulario-login');
        
        $id = new Zend_Form_Element_Hidden('id_modulo');
        $id->addFilter('Int')
                ->removeDecorator('Label'); 
        
        $nombre = new Zend_Form_Element_Text('nombre_modulo');
        $nombre
                ->setRequired(true)
                ->setLabel('Nombre:')
                ->setAttrib('size','27')
                ->setAttrib('placeholder','Ingrese el nombre del campo')
                ->setAttrib('class','required'); 
        
        $orden = new Zend_Form_Element_Text('orden_campo');
        $orden
                ->setRequired(true)
                ->setLabel('Orden:')
                ->setAttrib('size','27')
                ->setAttrib('placeholder','Ingrese el orden del campo')
                ->setAttrib('class','required');
        
        $visualizacion = new Zend_Form_Element_Select('listado_campo');
        $visualizacion ->setRequired(true)
                ->setLabel('¿Se muestra en el listado?:');;
        $visualizacion->addMultiOption(1,'Si');
        $visualizacion->addMultiOption(2,'No');
        
        $validacion = new Zend_Form_Element_Select('id_validacion');
        $validacion ->setRequired(true)
                ->setLabel('Validación jQuery validate:');
        $validaciondb = new Default_Model_DbTable_Validacion();
        foreach($validaciondb->listar() as $retorno){
            $validacion->addMultiOption($retorno->id_validacion,$retorno->nombre_validacion);
        }
        
        $tipo = new Zend_Form_Element_Select('id_tipo');
        $tipo ->setRequired(true)
                ->setLabel('Tipo campo:');
        $tipodb = new Default_Model_DbTable_Tipo();
        foreach($tipodb->listar() as $retorno){
            $tipo->addMultiOption($retorno->id_tipo,$retorno->nombre_tipo);
        }  
        
        $estado = new Zend_Form_Element_Select('id_estado');
        $estado ->setRequired(true)
                ->setLabel('Estado:');
        
        $estadodb = new Default_Model_DbTable_Estado();
        foreach($estadodb->listar() as $retorno){
            $estado->addMultiOption($retorno['id_estado'],$retorno['nombre_estado']);
        }        
        
        $boton = new Zend_Form_Element_Submit('Enviar');
        $boton->setAttrib('class','btn btn-primary')
              ->setLabel('Agregar');

        $this->addElements(array($id,$nombre,$orden,$visualizacion,$validacion,$tipo,$estado,$boton));         
        
    }


}

