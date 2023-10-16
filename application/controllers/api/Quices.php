<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quices extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        $this->load->model('Quiz_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
//GESTIÓN DE ELEMENTOS
//---------------------------------------------------------------------------------------------------
    
    /**
     * AJAX
     * edita o crea registro en la tabla usuario_asignacion
     * 
     * El tipo detalle 'Quiz' corresponde al tipo_asignacion_id = 3,
     * tabla: item.categoria_id = 16
     */
    function guardar_resultado()
    {
        
        $usuarioAsignacionId = $this->Quiz_model->guardar_resultado();
        
        $this->load->model('Evento_model');
        $this->Evento_model->guardar_fin_quiz($usuarioAsignacionId);
        $data['saved_id'] = $usuarioAsignacionId;

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function guardar_elemento()
    {
        $aRow = $this->input->post();
        if ( $aRow['id'] == 0 ) unset($aRow['id']);

        $data['saved_id'] = $this->Db_model->save_id('quiz_elemento', $aRow);

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Eliminar registro de la tabla quiz_elemento
     * 2023-10-16
     */
    function delete_element($quiz_id, $elemento_id)
    {
        $data = $this->Quiz_model->delete_element($quiz_id, $elemento_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Listado de elementos incluidos en un quiz
     * 2023-10-16
     */
    function get_elementos($quiz_id)
    {
        $elementos = $this->Quiz_model->elementos($quiz_id);
        $data['list'] = $elementos->result();

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}
