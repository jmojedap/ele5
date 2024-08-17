<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuestionarios extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
public $url_controller = URL_API . 'temas/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() {
        parent::__construct();
        
        $this->load->model('Cuestionario_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($cuestionario_id = NULL)
    {
        $destino = URL_ADMIN . 'cuestionarios/explore/';
        if ( ! is_null($cuestionario_id) ) {
            $destino = URL_ADMIN . "cuestionarios/info/{$cuestionario_id}";
        }
        
        redirect($destino);
    }

//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------

    /**
     * Listado de Cuestionarios, filtrados por búsqueda, JSON
     * 2024-08-23
     */
    function get($numPage = 1, $perPage = 10)
    {
        if ( $perPage > 250 ) $perPage = 250;

        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Cuestionario_model->get($filters, $numPage, $perPage);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}