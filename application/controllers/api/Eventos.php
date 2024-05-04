<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eventos extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        $this->load->model('Evento_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
//GESTIÓN DE ELEMENTOS
//---------------------------------------------------------------------------------------------------
    
    /**
     * AJAX
     * crea o actualiza registro en la tabla evento
     * 2024-05-02
     */
    function save()
    {   
        $aRow = $this->input->post();

        //Verificar condición especial para identificación de evento
        $conditionAdd = NULL;
        if ( isset($aRow['condition_add']) ) {
            $conditionAdd = $aRow['condition_add'];
            unset($aRow['condition_add']);
        }
        
        $data['saved_id'] = $this->Evento_model->save($aRow);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}
