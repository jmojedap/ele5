<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends CI_Controller{
    
    function __construct() {
        parent::__construct();

        $this->load->model('Post_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($post_id)
    {
        $row = $this->Pcrn->registro_id('post', $post_id);
        $destino = 'posts/editar/' . $post_id;
        if ( $row->tipo_id == 3 ) { $destino = "posts/leer/{$post_id}"; }
        if ( $row->tipo_id == 22 ) { $destino = "posts/lista/{$post_id}"; }
        if ( $row->tipo_id == 4311 ) { $destino = "posts/ap_leer/{$post_id}"; }
        
        redirect($destino);
    }

//CRUD
//---------------------------------------------------------------------------------------------------
    
    /**
     * Exploración y búsqueda de posts
     */
    function explorar($num_page = 1)
    {
        //Identificar filtros de búsqueda
            $this->load->model('Search_model');
            $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Post_model->explore_data($filters, $num_page);
        
        //Opciones de filtros de búsqueda
            $data['options_type'] = $this->Item_model->opciones('categoria_id = 33', 'Todos');
            
        //Arrays con valores para contenido en la tabla
            $data['arr_types'] = $this->Item_model->arr_interno('categoria_id = 33');
        
        //Cargar vista
            $this->App_model->view(TPL_ADMIN_NEW, $data);
    }
    
    /**
     * Listado de Projects, filtrados por búsqueda, JSON
     */
    function get($num_page = 1)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Post_model->get($filters, $num_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX JSON
     * Eliminar un conjunto de projects seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $data['qty_deleted'] += $this->Post_model->delete($row_id);
        }

        //Establecer resultado
        if ( $data['qty_deleted'] > 0 ) { $data['status'] = 1; }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Eliminar POST
     * 2019-12-05
     */
    function delete($post_id)
    {
        $data = array('status' => 0, 'qty_deleted' => 0);
        $qty_deleted = $this->Post_model->eliminar($post_id);

        if ( $qty_deleted > 0 ) {
            $data = array('status' => 1, 'qty_deleted' => $qty_deleted);
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
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
            $resultados_total = $this->Post_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Preparar datos
            $datos['nombre_hoja'] = 'Posts';
            $datos['query'] = $resultados_total;
            
        //Preparar archivo
            $objWriter = $this->Pcrn_excel->archivo_query($datos);
        
        $data['objWriter'] = $objWriter;
        $data['nombre_archivo'] = date('Ymd_His'). '_post'; //save our workbook as this file name
        
        $this->load->view('app/descargar_phpexcel_v', $data);
            
    }

// INFORMACÍON LECTURA Y APERTURA
//-----------------------------------------------------------------------------

    /**
     * Abrir o redireccionar a la vista pública de un post
     */
    function open($post_id)
    {
        $row = $this->Db_model->row_id('post', $post_id);
        $destination = "posts/read/{$post_id}";
        //if ( $row->type_id == 8 ) { $destination = "books/read/{$row->code}/0"; }

        redirect($destination);
    }

    /**
     * Mostrar post en vista lectura
     */
    function read($post_id)
    {
        //Datos básicos
        $data = $this->Post_model->basic($post_id);
        unset($data['nav_2']);
        $data['view_a'] = $this->Post_model->type_folder($data['row']->tipo_id) . 'read_v';

        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

    /**
     * Información general del post
     */
    function info($post_id)
    {        
        //Datos básicos
        $data = $this->Post_model->basic($post_id);
        $data['view_a'] = 'posts/info_v';

        //if ( $data['row']->type_id == 8 ) { $data['view_a'] = 'posts/types/book/info_v'; }

        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

    /**
     * Información detallada del post desde la perspectiva de base de datos
     * 2020-08-18
     */
    function details($post_id)
    {        
        //Datos básicos
        $data = $this->Post_model->basic($post_id);
        $data['view_a'] = 'posts/details_v';
        $data['fields'] = $this->db->list_fields('post');

        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }
    
// CREACIÓN DE UN POST
//-----------------------------------------------------------------------------

    /**
     * Vista Formulario para la creación de un nuevo post
     */
    function add($tipo_id = '')
    {
        //Variables generales
            $data['tipo_id'] = $tipo_id;
            $data['head_title'] = 'Post';
            $data['head_subtitle'] = 'Nuevo';
            $data['nav_2'] = 'posts/explore/menu_v';
            $data['view_a'] = 'posts/add/add_v';

        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

    /**
     * Crea un nuevo registro en la tabla post
     * 2019-11-29
     */
    function insert()
    {
        $data = $this->Post_model->insert();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// EDICIÓN Y ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Formulario para la edición de los datos de un user. Los datos que se
     * editan dependen de la $section elegida.
     */
    function edit($post_id)
    {
        //Datos básicos
        $data = $this->Post_model->basic($post_id);

        $data['options_type'] = $this->Item_model->options('categoria_id = 33', 'Todos');
        
        //Array data espefícicas
            $data['nav_2'] = 'posts/menu_v';
            $data['head_subtitle'] = 'Editar';
            $data['view_a'] = $this->Post_model->type_folder($data['row']) . 'edit_v';
        
        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

    /**
     * Guardar un registro en la tabla post, si post_id = 0, se crea nuevo registro
     * 2019-11-29
     */
    function update($post_id)
    {
        $data = $this->Post_model->update($post_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// FUNCIONES ANTERIORES
//-----------------------------------------------------------------------------
    
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
            $resultado = $this->Post_model->insertar();
            $post_id = $resultado['nuevo_id'];
        } else {
            //Actualizar post existente
            $resultado = $this->Post_model->actualizar($post_id);
        }
        
        $this->session->set_flashdata('resultado', $resultado);
        redirect("posts/editar/{$post_id}");
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
            $data = $this->Post_model->basic($post_id);
        
        //Array data espefícicas
            $data['view_description'] = 'posts/post_v';
            $data['nav_2'] = 'posts/menu_v';
            $data['view_a'] = $this->Post_model->vista_editar($data['row']);
        
        $this->load->view(TPL_ADMIN_NEW, $data);
    }
    
    /**
     * Actualiza un registro en la tabla post
     */
    function actualizar($post_id)
    {
        $data = $this->Post_model->actualizar($post_id);

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
    
    function cargar_archivo($post_id, $funcion = 'editar')
    {
        $resultado = $this->Pcrn->res_inicial();
        $resultado['type'] = 'error';
        
        //Si hay archivo se carga
        if ( $_FILES['archivo'] ) 
        {
            $this->load->model('Archivo_model');
            $res_archivo = $this->Archivo_model->cargar();
            $registro['imagen_id'] = $res_archivo['row_archivo']->id;
            
            $resultado = $this->Post_model->actualizar($post_id, $registro);
        }
        
        redirect("posts/{$funcion}/{$post_id}/{$resultado['type']}");
    }
    
    function ver($post_id)
    {
        
        $data = $this->Post_model->basic($post_id);    
        $data['detalle'] = $this->Post_model->detalle($post_id);
        $data['extras'] = $this->Post_model->extras($post_id);
        $data['row_ciudad'] = $this->Pcrn->registro_id('lugar', $data['row']->ciudad_id);
        
        //Estados
            $data['estados'] = $this->db->get_where('item', 'categoria_id = 7');
        
        //Variables
            $data['post_id'] = $post_id;
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Post';
            $data['vista_a'] = 'posts/post_v';
            $data['vista_b'] = 'posts/ver_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function leer($post_id)
    {
        $data = $this->Post_model->basic($post_id);    
        
        //Variables
            $data['post_id'] = $post_id;
            
            if ( $data['row']->imagen_id )
            {
                $data['row_archivo'] = $this->Pcrn->registro_id('archivo', $data['row']->imagen_id);
            }
        
        //Solicitar vista
            $data['view_description'] = 'posts/post_v';
            $data['nav_2'] = 'posts/menu_v';
            $data['view_a'] = $this->Post_model->vista_leer($data['row']);
            $this->load->view(TPL_ADMIN_NEW, $data);
    }
    
//LISTAS - TIPO 22
//---------------------------------------------------------------------------------------------------
    
    /**
     * Muestra los elementos de un post tipo lista, CRUD de los elementos de la lista
     * 
     * @param type $post_id
     */
    function lista($post_id)
    {
        //$this->output->enable_profiler(TRUE);
            $this->load->model('Esp');
            
        //Datos básicos
            $data = $this->Post_model->basic($post_id);
        
        //Variables
            $tabla_id = $data['row']->referente_1_id;
            $elementos_lista = $this->Post_model->metadatos($post_id, 22);  //22, tipo de dato, elementos de lista
            
        //Cargando variables
            $data['tabla_id'] = $tabla_id;
            $data['tabla'] = $this->Pcrn->campo('sis_tabla', "cod_tabla = {$tabla_id}", 'nombre_tabla');;
            $data['elementos_lista'] = $elementos_lista;
            
        //Array data espefícicas
            $data['vista_a'] = 'posts/listas/lista_v';
            $data['vista_b'] = 'posts/listas/elementos_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function reordenar_lista($post_id)
    {
        $str_orden = $this->input->post('str_orden');
        
        parse_str($str_orden);
        $arr_elementos = $elemento;
        
        $cant_elementos = $this->Post_model->reordenar_lista($post_id, $arr_elementos);
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($cant_elementos));
    }

//BITÁCORA DE ACTIVIDAD - TIPO 30
//-----------------------------------------------------------------------------

    /**
     * Bitácora de actividad
     */
    function bitacora($pago_id = 14272, $actividad_id = NULL)
    {
        $this->db->select('id, nombre_post, fecha, contenido, texto_1 AS modulo, texto_2 AS elemento, referente_1_id AS prioridad, decimal_2 AS costo');
        $this->db->order_by('texto_1', 'DESC');
        $this->db->order_by('fecha', 'ASC');
        $this->db->where('tipo_id', 30);
        $this->db->where('referente_3_id', $pago_id);
        if ( ! is_null($actividad_id) ) { $this->db->where('id', $actividad_id);}
        $data['bitacora'] = $this->db->get('post');

        $data['pago_id'] = $pago_id;

        //Pagos
        $this->db->order_by('id', 'DESC');
        $this->db->where('tipo_id', 91);
        $data['pagos'] = $this->db->get('post');

        $data['row'] = $this->Pcrn->registro_id('post', $pago_id);

        //$data['view_a'] = 'posts/bitacora/print_v';
        $data['view_a'] = 'posts/bitacora/bitacora_v';
        if ( $this->input->get('print') == 1 ) { $data['view_a'] = 'posts/bitacora/print_v'; }
        $data['head_title'] = 'Bitacora';
        $this->load->view(TPL_ADMIN_NEW, $data);
    }

    
// ENUNCIADOS - TIPO 4401
//-----------------------------------------------------------------------------
    
    function enunciado($post_id)
    {
        //Variables específicas

        //Variables generales
            $data['titulo_pagina'] = '';
            $data['subtitulo_pagina'] = '';
            $data['vista_a'] = 'app/gc_v';
            $data['vista_menu'] = 'instituciones/explorar_menu_v';

        $this->load->view(PTL_ADMIN, $data);
    }
    
    function eliminar_imagen($post_id, $archivo_id, $funcion = 'editar')
    {
        $this->load->model('Archivo_model');
        $this->Archivo_model->eliminar($archivo_id);
        
        $resultado['ejecutado'] = 1;
        $resultado['mensaje'] = 'El archivo fue eliminado';
        $resultado['clase'] = 'alert-info';
        $resultado['icono'] = 'fa-check';
        
        $this->session->set_flashdata('resultado', $resultado);
        redirect("posts/{$funcion}/{$post_id}");
    }

// POST TIPO 129 ARTÍCULOS TEMA
//-----------------------------------------------------------------------------

    function leer_articulo_tema($articulo_id)
    {
        $data = $this->Post_model->basic($articulo_id);
        /*$data['head_title'] = '';
        $data['view_a'] = '';*/
        $this->App_model->view('flipbooks/lectura/articulo/leer_v', $data);
    }
}