<?php

class Default_Model_DbTable_Proyecto extends Zend_Db_Table_Abstract
{
    protected $_name = 'empresa';
    
    public function listar(){
        /* [GENERAR CONSULTA] */
        $consulta = $this->select()->setIntegrityCheck(false)
                ->from(array('e'=>$this->_name),array(
                    'id_empresa'=>'e.id_empresa',
                    'nombre_empresa'=>'e.nombre_empresa',
                    'usuario_empresa'=>'e.usuario_empresa',
                    'clave_empresa'=>'e.clave_empresa',
                    'basededatos_empresa'=>'e.basededatos_empresa',
                    'url_empresa'=>'e.url_empresa',
                    'servidor_empresa'=>'e.servidor_empresa'))
                ->order('e.nombre_empresa ASC');
        $datos = array();
        foreach($this->fetchAll($consulta) as $retorno){
            $fila = new stdClass();
            $fila->id_empresa = $retorno->id_empresa;
            $fila->nombre_empresa = $retorno->nombre_empresa;
            $fila->usuario_empresa = $retorno->usuario_empresa;
            $fila->clave_empresa = $retorno->clave_empresa;
            $fila->basededatos_empresa = $retorno->basededatos_empresa;
            $fila->url_empresa = $retorno->url_empresa;
            $fila->servidor_empresa = $retorno->servidor_empresa;
            $datos[] = $fila;
        }
        return $datos;
    }    
    public function crearbase($usuario,$base,$clave){    
        /* [INSTANCIA DE BASE PERSONALIZADA] */
        $conexion = $this->base();
        /* [VALIDAR SI EXISTE EL PROYECTO] */
        if($this->existe($usuario,$base)){
            /* [CREAR BASE Y USUARIO POR PERMISO SOLO A ESTA BASE DE DATOS] */
            $resultado = "
                CREATE USER '$usuario'@'%' IDENTIFIED BY '$clave';
                GRANT USAGE ON * . * TO '$usuario'@'%' IDENTIFIED BY '$clave' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;
                CREATE DATABASE IF NOT EXISTS `$base` ;
                GRANT ALL PRIVILEGES ON `$usuario` . * TO '$base'@'%';";
            if($conexion->query($resultado)){
                return true;
            }
        }else{
            return false;
        }
    }
    public function crearempresa($datos){
        if(is_array($datos)){
            if($this->insert($datos)){
                return $this->getAdapter()->lastInsertId();                
            }
        }
    }
    public function crearusuario($datos,$id_proyecto){
        if(is_array($datos)){
            if($id_proyecto>0 && is_numeric($id_proyecto)){
                /* [INSTANCIA DE BASE PERSONALIZADA] */                
                $conexion = $this->base();
                /* [CREAR USUARIO] */
                $datos = $conexion->insert('usuario',$datos);             
                $ultimo =  $conexion->lastInsertId();                      
                if($datos){                    
                    if($ultimo>0){
                        $datos = array(
                            'id_empresa'=>$id_proyecto,
                            'id_usuario'=>$ultimo
                        );
                        if($conexion->insert('empresa_has_usuario',$datos)){
                            return true;
                        }
                    }
                }
            }
        }        
    }
    public function obtener($id){
        if(is_numeric($id) && $id>0){
            $consulta = $this->select()->setIntegrityCheck(false)
                    ->from(array('e'=>$this->_name),array(
                    'id_empresa'=>'e.id_empresa',
                    'nombre_empresa'=>'e.nombre_empresa',
                    'usuario_empresa'=>'e.usuario_empresa',
                    'clave_empresa'=>'e.clave_empresa',
                    'basededatos_empresa'=>'e.basededatos_empresa',
                    'url_empresa'=>'e.url_empresa',
                    'servidor_empresa'=>'e.servidor_empresa'))
                    ->where('id_empresa = ?',$id);
            $retorno = $consulta->query()->fetch();
            $fila = new stdClass();
            $fila->id_empresa = $retorno['id_empresa'];
            $fila->nombre_empresa = $retorno['nombre_empresa'];
            $fila->usuario_empresa = $retorno['usuario_empresa'];
            $fila->clave_empresa = $retorno['clave_empresa'];
            $fila->basededatos_empresa = $retorno['basededatos_empresa'];
            $fila->url_empresa = $retorno['url_empresa'];
            $fila->servidor_empresa = $retorno['servidor_empresa'];      
            return $fila;
        }
    }
    private function existe($usuario,$base){
        if(is_string($base) && is_string($usuario)){
            /* [CONSULTA EXISTENCIA] */
            $existe = $this->select()->setIntegrityCheck(false)
                    ->from($this->_name,'COUNT(*) as total')
                    ->where('usuario_empresa = "'.$usuario.'" AND basededatos_empresa = "'.$base.'"');
            $retorno = $existe->query()->fetch();
            if((int)$retorno['total'] == 1){
                return false;
            }else{
                return true;
            }
        }  
    }
    private function base(){
        /* [BASE DE DATOS PERSONALIZADA] */
        $config = new Zend_Config_Ini('../application/configs/application.ini', 'production');
        $db = Zend_Db::factory('Pdo_Mysql', $config->resources->db->params);
        $db->setFetchMode(Zend_Db::FETCH_OBJ);        
        return $db;        
    }    
    
}