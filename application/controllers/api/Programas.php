<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Programas extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
public $url_controller = URL_API . 'programas/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() {
        parent::__construct();
        
        $this->load->model('Programa_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($programa_id = NULL)
    {
        $destino = URL_ADMIN . 'programas/explore/';
        if ( ! is_null($programa_id) ) {
            $destino = URL_ADMIN . "programas/info/{$programa_id}";
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

        $data = $this->Programa_model->get($filters, $num_page, $per_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX JSON
     * Eliminar un conjunto de programas seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $data['qty_deleted'] += $this->Programa_model->delete($row_id);
        }

        //Establecer resultado
        if ( $data['qty_deleted'] > 0 ) { $data['status'] = 1; }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }    

// GESTIÓN DE TEMAS DE UN TEMA
//-----------------------------------------------------------------------------

    function get_temas($tema_id)
    {
        $temas = $this->Programa_model->temas($tema_id);
        $data['list'] = $temas->result();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Agregar un tema a un programa
     * 2023-08-06
     */
    function save_programa_tema()
    {
        $aRow = $this->input->post();
        $data['saved_id'] = $this->Programa_model->save_programa_tema($aRow);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));

    }

    /**
     * Cambia el valor del campo tema.orden
     * Se modifica también la posición de la página contigua, + o - 1
     * 2023-07-02
     * 
     * @param int $programa_id
     * @param int $tema_id
     * @param int $pos_final
     */
    function mover_tema($programa_id, $tema_id, $pos_final)
    {
        //Cambiar la posición de una página en un tema
        $data['qty_affected'] = $this->Programa_model->cambiar_pos_tema($programa_id, $tema_id, $pos_final);

        $temas = $this->Programa_model->temas($programa_id);
        $data['list'] = $temas->result();
        
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Quita una tema asignada a un programa, no elimina la tema
     * 2023-07-02
     */
    function remove_tema($programa_id, $tema_id, $pt_id)
    {
        $data['qty_deleted'] = $this->Programa_model->remove_tema($programa_id, $tema_id, $pt_id);

        $temas = $this->Programa_model->temas($programa_id);
        $data['list'] = $temas->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}