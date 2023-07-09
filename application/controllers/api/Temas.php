<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Temas extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
public $url_controller = URL_API . 'temas/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() {
        parent::__construct();
        
        $this->load->model('Tema_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($tema_id = NULL)
    {
        $destino = URL_ADMIN . 'temas/explore/';
        if ( ! is_null($tema_id) ) {
            $destino = URL_ADMIN . "temas/info/{$tema_id}";
        }
        
        redirect($destino);
    }

//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------

    /**
     * Listado de Posts, filtrados por búsqueda, JSON
     * 2022-08-23
     */
    function get($num_page = 1, $per_page = 10)
    {
        if ( $per_page > 250 ) $per_page = 250;

        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Tema_model->get($filters, $num_page, $per_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX JSON
     * Eliminar un conjunto de posts seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $data['qty_deleted'] += $this->Tema_model->delete($row_id);
        }

        //Establecer resultado
        if ( $data['qty_deleted'] > 0 ) { $data['status'] = 1; }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }    

// GESTIÓN DE PREGUNTAS DE UN TEMA
//-----------------------------------------------------------------------------

    function get_preguntas($tema_id)
    {
        $preguntas = $this->Tema_model->preguntas($tema_id);
        $data['list'] = $preguntas->result();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Cambia el valor del campo pregunta.orden
     * Se modifica también la posición de la página contigua, + o - 1
     * 2023-07-02
     * 
     * @param int $tema_id
     * @param int $pregunta_id
     * @param int $pos_final
     */
    function mover_pregunta($tema_id, $pregunta_id, $pos_final)
    {
        //Cambiar la posición de una página en un tema
        $data['qty_affected'] = $this->Tema_model->cambiar_pos_pregunta($tema_id, $pregunta_id, $pos_final);

        $preguntas = $this->Tema_model->preguntas($tema_id);
        $data['list'] = $preguntas->result();
        
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Quita una pregunta asignada a un tema, no elimina la pregunta
     * 2023-07-02
     */
    function quitar_pregunta($tema_id, $pregunta_id)
    {
        $data['qty_deleted'] = $this->Tema_model->quitar_pregunta($tema_id, $pregunta_id);

        //$data['qty_deleted'] = 1;

        $preguntas = $this->Tema_model->preguntas($tema_id);
        $data['list'] = $preguntas->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}