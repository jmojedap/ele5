<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();
        
        $this->load->model('Item_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
//CRUD
//---------------------------------------------------------------------------------------------------
    
    function explorar()
    {
        $this->load->model('Esp');
        $this->load->model('Busqueda_model');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Item_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(2);
            $config['base_url'] = base_url("items/explorar/?{$busqueda_str}");
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Item_model->buscar($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
            $data['categorias_item'] = $this->Item_model->categorias('num');
            $data['destino_form'] = 'app/redirect/items/explorar';
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Items';
            $data['subtitulo_pagina'] = $resultados_total->num_rows();
            $data['vista_a'] = 'sistema/items/explorar_v';
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
            $resultados_total = $this->Item_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Preparar datos
            $datos['nombre_hoja'] = 'Meta';
            $datos['query'] = $resultados_total;
            
        //Preparar archivo
            $objWriter = $this->Pcrn_excel->archivo_query($datos);
        
        $data['objWriter'] = $objWriter;
        $data['nombre_archivo'] = date('Ymd_His'). '_items.xls'; //save our workbook as this file name
        
        $this->load->view('app/descargar_phpexcel_v', $data);
            
    }
    
    /**
     * AJAX Eliminar un grupo de items seleccionados
     */
    function eliminar_seleccionados()
    {
        $str_seleccionados = $this->input->post('seleccionados');
        
        $seleccionados = explode('-', $str_seleccionados);
        
        foreach ( $seleccionados as $elemento_id ) {
            $condiciones['id'] = $elemento_id;
            $this->Item_model->eliminar($condiciones);
        }
        
        echo count($seleccionados);
    }
    
    /**
     * AJAX
     * Eliminar un registro, devuelve la cantidad de registros eliminados
     */
    function eliminar()
    {
        $condiciones['id'] = $this->input->post('item_id');
        $condiciones['categoria_id'] = $this->input->post('categoria_id');
        $this->Item_model->eliminar($condiciones);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output($this->db->affected_rows());
    }
    
    /**
     * Vista filtra items por categoría, CRUD de items.
     * 
     * @param type $categoria_id
     * @param type $item_id
     */
    function listado($categoria_id = '058', $item_id = 0)
    {    
        $data['row'] = $this->Pcrn->registro_id('item', $item_id);
        
        //Variables
            $data['categoria_id'] = $categoria_id;
            $data['row'] = $this->Pcrn->registro_id('item', $item_id);
            $data['valores_form'] = $this->Item_model->valores_form($data['row']);
            $data['arr_categorias'] = $this->Item_model->categorias();
            $data['items'] = $this->Item_model->items($categoria_id);
            $data['item_id'] = $item_id;
            $data['id_interno'] = $this->Item_model->siguiente_id_interno($categoria_id);
            $data['destino_form'] = "items/guardar/{$item_id}";
        
        //Array data generales
            $data['titulo_pagina'] = $data['arr_categorias'][$categoria_id];
            $data['subtitulo_pagina'] = 'Nuevo';
            $data['vista_menu'] = 'sistema/items/explorar_menu_v';
            $data['vista_a'] = 'sistema/items/listado_v';
            
        //Si es edición del ítem
            if ( ! is_null($data['row']) ) 
            {
                $data['id_interno'] = $data['row']->id_interno;
                $data['subtitulo_pagina'] = $data['row']->item;
            }
            
        //Cargar vista
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * POST REDIRECT
     * Guarda los datos enviados por post, registro en la tabla item, insertar
     * o actualizar.
     * 
     */
    function guardar($item_id)
    {
        $this->output->enable_profiler(TRUE);
        
        $registro = $this->input->post();
        
        $item_id_guardado = $this->Item_model->guardar($registro, $item_id);
        
        redirect("items/listado/{$registro['categoria_id']}/{$item_id_guardado}");
    }
    
}
