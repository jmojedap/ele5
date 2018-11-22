<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lugares extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('Lugar_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    
    function index($lugar_id = NULL) 
    {
        $destino = 'lugares/explorar';
        if ( ! is_null($lugar_id) ) { $destino = "lugares/sublugares/{$lugar_id}"; }
        
        redirect($destino);
    }
//LUGARES - TABLE PLACE
//---------------------------------------------------------------------------------------------------

    function explorar()
    {
        
        //Cargando
            $this->load->model('Busqueda_model');
            $this->load->helper('text');
        
        //Lugares de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Lugar_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(2);
            $config['base_url'] = base_url() . "lugares/explorar/?{$busqueda_str}";
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Lugar_model->buscar($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
            $data['listas'] = $this->db->get_where('item', 'categoria_id = 22');
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Lugares';
            $data['subtitulo_pagina'] = $config['total_rows'];
            $data['vista_a'] = 'sistema/lugares/explorar_v';
            $data['vista_menu'] = 'sistema/lugares/explorar_menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Exporta el resultado de la búsqueda a un archivo de Excel
     */
    function exportar()
    {
        
        //Cargando
            $this->load->model('Busqueda_model');
            $this->load->model('Pcrn_excel');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $resultados_total = $this->Lugar_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Preparar datos
            $datos['nombre_hoja'] = 'Lugares';
            $datos['query'] = $resultados_total;
            
        //Preparar archivo
            $objWriter = $this->Pcrn_excel->archivo_query($datos);
        
        $data['objWriter'] = $objWriter;
        $data['nombre_archivo'] = date('Ymd_His'). '_lugares'; //save our workbook as this file name
        
        $this->load->view('app/descargar_phpexcel_v', $data);
            
    }
    
    function editar()
    {
        $lugar_id = $this->uri->segment(4);
        $data = $this->Lugar_model->basico($lugar_id);
        
        $gc_output = $this->Lugar_model->crud_basico();

        //Array data espefícicas
            $data['subtitulo_pagina'] = 'Editar';
            $data['vista_b'] = 'comunes/gc_v';    
        
        $output = array_merge($data,(array)$gc_output);
        $this->load->view(PTL_ADMIN, $output);
    }
    
    /**
     * AJAX
     * Eliminar un grupo de registros seleccionados
     */
    function eliminar_seleccionados()
    {
        $str_seleccionados = $this->input->post('seleccionados');
        
        $seleccionados = explode('-', $str_seleccionados);
        
        foreach ( $seleccionados as $elemento_id ) {
            $this->Lugar_model->eliminar($elemento_id);
        }
        
        echo count($seleccionados);
    }
    
    function sublugares($lugar_id)
    {
        
        //Cargando
            $this->load->model('Busqueda_model');
            $this->load->helper('text');
            
        //Data básico
            $data = $this->Lugar_model->basico($lugar_id);
            $titulo_sublugares = $this->Lugar_model->titulo_sublugares($data['row']->tipo_id);
        
        //Variables para vista
            $data['sublugares'] = $this->Lugar_model->sublugares($lugar_id);
            $data['titulo_sublugares'] = $titulo_sublugares;
        
        //Solicitar vista
            $data['subtitulo_pagina'] = $titulo_sublugares;
            $data['vista_b'] = 'sistema/lugares/sublugares_v';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    function guardar($lugar_id)
    {
        $this->Lugar_model->guardar($lugar_id);
        redirect("lugares/sublugares/{$lugar_id}");
    }
    
}