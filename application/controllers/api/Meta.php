<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Meta extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        $this->load->model('Meta_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }

//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------

    /**
     * Listado de metadatos, filtrados por búsqueda, JSON
     * 2024-08-23
     */
    function get($numPage = 1, $perPage = 10)
    {
        if ( $perPage > 250 ) $perPage = 250;

        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Meta_model->get($filters, $numPage, $perPage);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
//GESTIÓN DE ELEMENTOS
//---------------------------------------------------------------------------------------------------
    
    /**
     * AJAX JSON
     * crea o actualiza registro en la tabla meta
     * 2024-08-10
     */
    function save()
    {   
        $aRow = $this->input->post();
        $data['saved_id'] = $this->Meta_model->save($aRow);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Eliminar un registro de la tabla meta
     * 2024-08-10
     */
    function delete($metaId, $relacionadoId)
    {
        $data['qtyDeleted'] = $this->Meta_model->delete($metaId, $relacionadoId);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// ESPECIALES
//-----------------------------------------------------------------------------

    function cuestionarios_unidad($postId)
    {
        $cuestionarios = $this->Meta_model->cuestionarios_unidad($postId);
        $data['list'] = $cuestionarios->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

}
