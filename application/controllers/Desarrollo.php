<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Desarrollo extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Desarrollo_model');
        
        //Para formato de horas
        date_default_timezone_set("America/Bogota");
    }
        
    function index()
    {
        redirect('desarrollo/explorar');
    }
    
//CRUD
//---------------------------------------------------------------------------------------------------

    
    /**
     * Exploración y búsqueda de desarrollo
     */
    function explorar($num_pagina = 0)
    {
        //Datos básicos de la exploración
            $data = $this->Desarrollo_model->data_explorar(1);
        
        //Opciones de filtros de búsqueda
            $data['arr_filtros'] = array('rol', 'sexo');
            $data['opciones_rol'] = $this->Item_model->opciones('categoria_id = 58', 'Todos');
            $data['opciones_sexo'] = $this->Item_model->opciones('categoria_id = 59 and item_grupo = 1', 'Todos');
            
        //Arrays con valores para contenido en lista
            $data['arr_roles'] = $this->Item_model->arr_interno('categoria_id = 58');
            $data['arr_sexos'] = $this->Item_model->arr_interno('categoria_id = 59');
            
        //Cargar vista
            $this->App_model->vista(PTL_ADMIN_2, $data);
    }
    
    /**
     * AJAX
     * 
     * Devuelve JSON, que incluye string HTML de la tabla de exploración para la
     * página $num_pagina, y los filtros enviados por post
     * 
     * @param type $num_pagina
     */
    function tabla_explorar($num_pagina = 0)
    {
        //Datos básicos de la exploración
            $data = $this->Desarrollo_model->data_tabla_explorar($num_pagina);
        
        //Arrays con valores para contenido en lista
            $data['arr_tipos'] = $this->Item_model->arr_interno('categoria_id = 33');
        
        //Preparar respuesta
            $respuesta['html'] = $this->load->view('desarrollo/explorar/tabla_v', $data, TRUE);
            $respuesta['seleccionados_todos'] = $data['seleccionados_todos'];
            $respuesta['num_pagina'] = $num_pagina;
        
        //Salida
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($respuesta));
    }
    
    /**
     * AJAX
     * Eliminar un grupo de desarrollo seleccionados
     */
    function eliminar_seleccionados()
    {
        $str_seleccionados = $this->input->post('seleccionados');
        
        $seleccionados = explode('-', $str_seleccionados);
        
        foreach ( $seleccionados as $elemento_id ) 
        {
            $this->Desarrollo_model->eliminar($elemento_id);
        }
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($seleccionados));
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
            $resultados_total = $this->Desarrollo_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Preparar datos
            $datos['nombre_hoja'] = 'Posts';
            $datos['query'] = $resultados_total;
            
        //Preparar archivo
            $objWriter = $this->Pcrn_excel->archivo_query($datos);
        
        $data['objWriter'] = $objWriter;
        $data['nombre_archivo'] = date('Ymd_His'). '_post'; //save our workbook as this file name
        
        $this->load->view('app/descargar_phpexcel_v', $data);
            
    }
    
    /**
     * Formulario para la creación de un registro en la tabla post,
     * Después de crear el post, es redirigido al
     * formulario de edición.
     * 
     * @param type $institucion_id
     */
    function nuevo()
    {
        //Cargando datos básicos
            $data['destino_form'] = "desarrollo/guardar/nuevo";
            $data['valores_form'] = $this->Pcrn->valores_form(NULL, 'post');
            
        //Solicitar vista
            $data['titulo_pagina'] = 'Posts';
            $data['subtitulo_pagina'] = 'Nuevo';
            $data['vista_a'] = 'desarrollo/formulario_v';
            $data['vista_menu'] = 'desarrollo/explorar/menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * POST REDIRECT
     * Recibe los datos del formulario de post, nuevo o edición. Inserta o 
     * actualiza los datos de un post.
     * 
     * @param type $post_id
     */
    function guardar($post_id)
    {
        $resultado = array();
        
        if ( $post_id == 'nuevo' )
        {
            //Nuevo grupo
            $resultado = $this->Desarrollo_model->insertar();
            $post_id = $resultado['nuevo_id'];
        } else {
            //Actualizar post existente
            $resultado = $this->Desarrollo_model->actualizar($post_id);
        }
        
        $this->session->set_flashdata('resultado', $resultado);
        redirect("desarrollo/editar/{$post_id}");
    }
    
    /**
     * Editar la información básica de un post
     * Funciona con grocery crud
     * 
     * @param type $proceso
     * @param type $post_id
     */
    function editar($post_id)
    {   
        $this->load->model('Esp');
        
        //Datos básicos
            $data = $this->Desarrollo_model->basico($post_id);
        
        //Array data espefícicas
            $data['vista_b'] = $this->Desarrollo_model->vista_editar($data['row']);
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function ver($post_id)
    {
        
        $data = $this->Desarrollo_model->basico($post_id);    
        $data['detalle'] = $this->Desarrollo_model->detalle($post_id);
        $data['extras'] = $this->Desarrollo_model->extras($post_id);
        $data['row_ciudad'] = $this->Pcrn->registro_id('lugar', $data['row']->ciudad_id);
        
        //Estados
            $data['estados'] = $this->db->get_where('item', 'categoria_id = 7');
        
        //Variables
            $data['post_id'] = $post_id;
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Post';
            $data['vista_a'] = 'desarrollo/post_v';
            $data['vista_b'] = 'desarrollo/ver_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function leer($post_id)
    {
        $data = $this->Desarrollo_model->basico($post_id);    
        
        //Variables
            $data['post_id'] = $post_id;
            
            if ( $data['row']->imagen_id )
            {
                $data['row_archivo'] = $this->Pcrn->registro_id('archivo', $data['row']->imagen_id);
            }
        
        //Solicitar vista
            $data['vista_b'] = $this->Desarrollo_model->vista_leer($data['row']);
            $this->load->view(PTL_ADMIN, $data);
    }
}