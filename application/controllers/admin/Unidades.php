<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unidades extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'admin/flipbooks/unidades/';
    public $url_controller = URL_ADMIN . 'unidades/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Post_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($post_id = NULL)
    {
        if ( is_null($post_id) ) {
            redirect("admin/unidades/explore/");
        } else {
            redirect("admin/unidades/info/{$post_id}");
        }
    }

    /**
     * CRUD Listado de cuestionarios de una unidad
     * 2024-09-26
     */
    function cuestionarios($post_id)
    {
        $data = $this->Post_model->basic($post_id);
        //$data['head_title'] = 'Cuestionarios';
        $data['view_a'] = $this->views_folder . 'cuestionarios/cuestionarios_v';
        $data['nav_2'] = 'admin/posts/types/60/menu_v';
        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }
}