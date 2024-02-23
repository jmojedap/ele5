<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Enunciados extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Datos_model');

        $this->load->library('grocery_CRUD');
        
        //Para formato de horas
        date_default_timezone_set("America/Bogota");

    }
    
//ENUNCIADOS
//------------------------------------------------------------------------------------------
    
    /**
     * Controla y redirecciona las búsquedas de exploración de enunciados
     * evita el problema de reenvío del formulario al presionar el botón
     * "atrás" del browser
     * 
     * @param type $controlador
     */
    function redirect()
    {
        $this->load->model('Busqueda_model');
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        redirect("enunciados/explorar/?{$busqueda_str}");
    }

    function explorar()
    {
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Busqueda_model');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
        
        //Paginación
            $resultados_total = $this->Datos_model->buscar_enunciados($busqueda); //Para calcular el total de resultados
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(4);
            $config['base_url'] = base_url("enunciados/explorar/?{$busqueda_str}");
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Datos_model->buscar_enunciados($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['resultados'] = $resultados;
            $data['destino_form'] = "enunciados/redirect/";
        
        //Solicitar vista
            $data['head_title'] = 'Lecturas';
            $data['head_subtitle'] = $config['total_rows'];
            $data['view_a'] = 'datos/enunciados/explorar_v';
            $this->load->view(TPL_ADMIN_NEW, $data);
    }
    
    function ver($enunciado_id)
    {
        $data = $this->Datos_model->enunciado_basico($enunciado_id);
        
        $data['view_b'] = 'datos/enunciados/enunciado_ver_v';
        $this->load->view(TPL_ADMIN_NEW, $data);
        
    }
    
    function editar()
    {
        //Cargando datos básicos
            $enunciado_id = $this->uri->segment(4);
            $data['enunciado_id'] = $enunciado_id;
            $data['row'] = $this->Pcrn->registro_id('post', $enunciado_id);
            $data['head_title'] = 'Editar enunciado';
            $data['view_a'] = 'datos/enunciados/enunciado_v';
            
        //Render del grocery crud
            $output = $this->Datos_model->crud_enunciados();

        //Solicitar vista
            $data['head_subtitle'] = 'Editar';
            $data['view_b'] = 'comunes/bs4/gc_v';
            $output = array_merge($data,(array)$output);
            $this->load->view(TPL_ADMIN_NEW, $output);
    }
    
    function nuevo()
    {
            
        //Render del grocery crud
            $output = $this->Datos_model->crud_enunciados();

        //Solicitar vista
            $data['head_title'] = 'Enunciados';
            $data['head_subtitle'] = 'Nuevo';
            $data['view_a'] = 'comunes/bs4/gc_v';
            $data['nav_2'] = 'datos/enunciados/explorar_menu_v';
            $output = array_merge($data,(array)$output);
            $this->load->view(TPL_ADMIN_NEW, $output);
    }
    
    function eliminar($enunciado_id)
    {
        $this->Datos_model->enunciado_eliminar($enunciado_id);
        
        $this->load->model('Busqueda_model');
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        
        redirect("datos/enunciados/?{$busqueda_str}");
    }    
}

/* Fin del archivo datos.php */