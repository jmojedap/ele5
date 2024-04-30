<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Enfoque_lector extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
public $views_folder = 'enfoque_lector/';
public $url_controller = URL_APP . 'enfoque_lector/';
    
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Post_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($flipbook_id)
    {
        //$this->ver_flipbook($flipbook_id);
    }
    
//---------------------------------------------------------------------------------------------------
//
    function inicio()
    {
        $data['head_title'] = 'Enfoque Lector';
        $data['view_a'] = $this->views_folder . 'inicio/inicio_v';
        $this->App_model->view('templates/easypml/full', $data);
    }

    /**
     * Vista panel principal de lectura de contenidos módulo enfoque lector
     * 2024-02-19
     */
    function panel($post_id, $flipbook_id = 4004)
    {
        $data = $this->Post_model->basic($post_id);
        $data['flipbook'] = $this->Db_model->row_id('flipbook', $flipbook_id);

        //Lecturas dinámicas
            $idsCondition = 'id = 0';
            if ( strlen($data['row']->texto_1) > 0 ) {
                $idsCondition = "id IN ({$data['row']->texto_1})";
            } 
            
            $this->db->select('id, nombre_post');
            $this->db->where($idsCondition);
            $data['lecturas'] = $this->db->get('post');

        //Archivos de enfoque lector tipo 10
        $data['files'] = $this->Post_model->files($post_id, 10);

        $data['head_title'] = 'Enfoque Lector';
        $data['view_a'] = $this->views_folder . 'panel/panel_v';
        $this->App_model->view('templates/easypml/full', $data);
    }

    /**
     * Vista pantalla completa para ejecución de aplicación de lectura dinámica
     * 2023-12-03
     */
    function fluidez_lectora($ledin_id, $json = FALSE)
    {
        $this->load->model('Tema_model');
        $data['ledin_id'] = $ledin_id;
        $data['ledin'] = $this->Tema_model->ledin($ledin_id);
        $data['segundosLectura'] = 60;
        
        $data['view_a'] = 'enfoque_lector/fluidez_lectora/fluidez_lectora_v';
        $data['head_title'] = $data['ledin']->nombre_post;
        $data['subtitle_head'] = 'Lecturas dinámicas';
        $this->load->view('templates/easypml/empty', $data);
    }
}