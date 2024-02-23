<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grupos extends CI_Controller{
    
    function __construct() {
        parent::__construct();

        $this->load->model('Grupo_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($grupo_id = NULL)
    {
        $destino = 'grupos/explorar';
        if ( ! is_null($grupo_id) ) 
        {
            $destino = "grupos/estudiantes/{$grupo_id}";
        }
        
        redirect($destino);
    }

// PREGUNTAS ABIERTAS ASIGNADAS A GRUPOS
//-----------------------------------------------------------------------------

    /**
     * Asignar pregunta abierta a grupo, desde contenidos de tipo clase dinámica
     * Se guarda en la tabla meta
     * 2023-09-15
     */
    function asignar_pregunta_abierta($grupo_id, $pregunta_id = 0)
    {
        if ( $pregunta_id == 0 )
        {
            //Crear nueva pregunta abierta, con datos del formulario
            $this->load->model('Tema_model');
            $data_pa = $this->Tema_model->save_pa($this->input->post('tema_id'), 0);
            
            //Si la creación de pregunta abierta fue exitosa
            if ( $data_pa['status'] ) { $pregunta_id = $data_pa['saved_id']; }
        }

        //Se realiza la asignación en la tabla meta
        $data = $this->Grupo_model->asignar_pa($grupo_id, $pregunta_id);

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Listado de preguntas abiertas asignadas para un grupo y área determinadas
     * Se solicita desde el flipbook del tipo Clase Dinámica
     * 2023-09-15
     */
    function preguntas_abiertas_asignadas($grupo_id, $area_id)
    {
        $pa_asignadas = $this->Grupo_model->pa_asignadas($grupo_id, $area_id);

        $data['pa_asignadas'] = $pa_asignadas->result();

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
}