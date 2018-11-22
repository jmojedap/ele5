<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Busquedas extends CI_Controller{
    
    function __construct()
    {
        parent::__construct();
        
        //Específicos
        $this->load->model('Busqueda_model');
    }
    
    /**
     * Controla y redirecciona las búsquedas de exploración
     * para cada elemento (explorador), evita el problema de reenvío del
     * formulario al presionar el botón "atrás" del browser
     * 
     * @param type $controlador
     */
    function explorar_redirect($controlador)
    {
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Busqueda_model');
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        redirect("{$controlador}/explorar/?{$busqueda_str}");
    }
    
    /**
     * POST REDIRECT
     * 2017-07-07
     * Toma los datos de POST, los establece en formato GET para url y redirecciona
     * a una controlador y función definidos.
     * 
     * @param type $controlador
     * @param type $funcion
     */
    function redirect($controlador, $funcion)
    {
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        redirect("{$controlador}/{$funcion}/?{$busqueda_str}");
    }
    
    function usuarios()
    {
        $data = $this->Busqueda_model->basico();    //Datos básicos
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Busqueda_model->usuarios($busqueda); //Para calcular el total de resultados
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(3);
            $config['base_url'] = base_url() . "busquedas/usuarios/?{$busqueda_str}";
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Busqueda_model->usuarios($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Usuarios';
            $data['vista_b'] = 'busquedas/usuarios_v';
            $this->load->view('plantilla_apanel/plantilla', $data);
        
    }
    
    function instituciones()
    {
        
        //Variables
            $offset = 0;
        
        //Cargando datos básicos (basico)
        $data = $this->Busqueda_model->basico();
            
        $busqueda = $this->Busqueda_model->busqueda_array();
        $busqueda_str = $this->Busqueda_model->busqueda_str();    
        $resultados_total = $this->Busqueda_model->instituciones($busqueda); //Para calcular el total de resultados
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(3);
            $config['base_url'] = base_url() . "busquedas/instituciones/?{$busqueda_str}";
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            if ( ! is_null($this->input->get('per_page')) ) { $offset = $this->input->get('per_page'); }
            $resultados = $this->Busqueda_model->instituciones($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['resultados'] = $resultados;
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Instituciones';
            $data['vista_b'] = 'busquedas/instituciones_v';
            $this->load->view('plantilla_apanel/plantilla', $data);
    }
    
}