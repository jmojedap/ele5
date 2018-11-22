<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cgr extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Pcrn');
        $this->load->model('App_model');
        $this->load->model('Esp');

        $this->load->database();
        $this->load->helper('url');

        $this->load->library('grocery_CRUD');
        
        //Para formato de horas
        date_default_timezone_set("America/Bogota");
        
        //Para permitir acceso remoto a funciones ajax en el CGR
        header('Access-Control-Allow-Origin: *'); 
        header('Access-Control-Allow-Methods: GET');
        //header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        

    }
    
    function programa($usuario_id, $username)
    {
        
        $this->db->order_by('editado', 'DESC');
        $query = $this->db->get('programa', 10000);
        $respuesta = array();
        
        foreach( $query->result() as $row ){
            $registro[0] =  $row->id;
            $registro[1] =  $row->nombre_programa;
            $registro[2] =  $row->anio_generacion;
            $registro[3] =  $row->institucion_id;
            $registro[4] =  $row->area_id;
            $registro[5] =  $row->nivel;
            $registro[6] =  $row->temas;
            $registro[7] =  $row->descripcion;
            $registro[8] =  $row->usuario_id;
            $registro[9] =  $row->creado;
            $registro[10] =  $row->editado;
            
            $respuesta[] = $registro;
        }
        
        if ( $this->Esp->permiso_cgr($usuario_id, $username) ){
            echo json_encode($respuesta);
        }
    }
    
    function programa_tema()
    {
        
        //2015-05-15 solucionar problema memory limit y tiempo de ejecución
            ini_set('memory_limit', '2048M');   
            set_time_limit(60);
        
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('programa_tema', 200000);
        $respuesta = array();
        
        foreach( $query->result() as $row ){
            $registro[0] =  $row->id;
            $registro[1] =  $row->programa_id;
            $registro[2] =  $row->tema_id;
            $registro[3] =  $row->orden;
            
            $respuesta[] = $registro;
        }
        
        echo json_encode($respuesta);
    }
    
    function recurso()
    {
        $this->db->order_by('id', 'DESC');
        //$this->db->order_by('tipo_recurso_id', 1);
        $query = $this->db->get('recurso', 200000);
        $respuesta = array();
        
        foreach( $query->result() as $row ){
            $registro[0] =  $row->id;
            $registro[1] =  $row->nombre_archivo;
            $registro[2] =  ''; //Pendiente
            $registro[3] =  $row->referente_id;
            $registro[4] =  $row->tema_id;
            $registro[5] =  $row->tipo_recurso_id;
            $registro[6] =  $row->tipo_archivo_id;
            $registro[7] =  $row->disponible;
            $registro[8] =  $row->fecha_subida;
            $registro[9] =  $row->editado;
            $registro[10] =  $row->usuario_id;

            
            $respuesta[] = $registro;
        }
        
        echo json_encode($respuesta);
    }
    
    function item()
    {
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('item', 200000);
        $respuesta = array();
        
        foreach( $query->result() as $row ){
            $registro[0] =  $row->id;
            $registro[1] =  $row->item;
            $registro[2] =  $row->categoria_id;
            $registro[3] =  $row->id_interno;
            $registro[4] =  $row->item_grupo;
            $registro[5] =  $row->slug;
            $registro[6] =  $row->abreviatura;
            $registro[7] =  $row->item_largo;
            $registro[8] =  $row->item_corto;
            $registro[9] =  $row->descripcion;
            $registro[10] =  $row->orden;
            
            $respuesta[] = $registro;
        }
        
        echo json_encode($respuesta);
    }
    
    function registros_json()
    {
        /*$this->db->order_by('id', 'DESC');
        $query = $this->db->get('item', 20);
        $respuesta = array();
        
        foreach( $query->result() as $row ){
            $registro[0] =  $row->id;
            $registro[1] =  $row->item;
            $registro[2] =  $row->categoria_id;
            $registro[3] =  $row->id_interno;
            $registro[4] =  $row->item_grupo;
            $registro[5] =  $row->slug;
            $registro[6] =  $row->abreviatura;
            $registro[7] =  $row->item_largo;
            $registro[8] =  $row->item_corto;
            $registro[9] =  $row->descripcion;
            $registro[10] =  $row->orden;
            
            $respuesta[] = $registro;
        }
        
        echo json_encode($respuesta);*/
        $tabla = 'item';
        $id_inicial = 0;
        
        $this->load->model('Develop_model');
        
        $this->db->where('id>', $id_inicial);
        $this->db->order_by('', 'ASC');
        $query = $this->db->get($tabla, 50);
        
        $resultado['campos'] = $query->list_fields();
        $resultado['registros'] = $this->Develop_model->query_liviano($query);
        
        echo json_encode($resultado);
        
        /*$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($resultado));*/
    }
    
    function tema()
    {
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('tema', 200000);
        $respuesta = array();
        
        foreach( $query->result() as $row ){
            $registro[0] =  $row->id;
            $registro[1] =  $row->cod_tema;
            $registro[2] =  $row->nombre_tema;
            $registro[3] =  $row->area_id;
            $registro[4] =  $row->nivel;
            $registro[5] =  $row->componente_id;
            $registro[6] =  $row->componente;
            $registro[7] =  $row->editado;
            $registro[8] =  $row->usuario_id;
            $registro[9] =  $row->descripcion;
            $registro[10] =  $row->tipo_id;
            
            $respuesta[] = $registro;
        }
        
        echo json_encode($respuesta);
    }
    
    function usuario($usuario_id, $username)
    {
        //$this->output->enable_profiler(TRUE);
        $this->db->order_by('id', 'DESC');
        $this->db->where('rol_id <= 2');
        $query = $this->db->get('usuario', 200000);
        $respuesta = array();
        
        foreach( $query->result() as $row ){
            $registro[0] =  $row->id;
            $registro[1] =  $row->username;
            $registro[2] =  $row->email;
            $registro[3] =  $row->password;
            $registro[4] =  $row->nombre;
            $registro[5] =  $row->apellidos;
            $registro[6] =  $row->sexo;
            $registro[7] =  $row->rol_id;
            $registro[8] =  $row->no_documento;
            $registro[9] =  $row->tipo_documento_id;
            $registro[10] =  $row->institucion_id;
            $registro[11] =  $row->grupo_id;
            $registro[12] =  $row->notas;
            $registro[13] =  $row->en_linea;
            $registro[14] =  $row->inactivo;
            $registro[15] =  $row->creado;
            $registro[16] =  $row->editado;
            $registro[17] =  $row->creado_usuario_id;
            $registro[18] =  $row->editado_usuario_id;
            
            $respuesta[] = $registro;
        }
        
        if ( $this->Esp->permiso_cgr($usuario_id, $username) ){
            echo json_encode($respuesta);
        }
        
    }
    
}

/* Fin del archivo cgr.php */