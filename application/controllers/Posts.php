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
    function explorar($num_pagina = 0)
    {
        //Datos básicos de la exploración
            $data = $this->Post_model->data_explorar($num_pagina);
        
        //Opciones de filtros de búsqueda
            $data['arr_filtros'] = array('tp', 'o');
            $data['opciones_tipo'] = $this->Item_model->opciones('categoria_id = 33', 'Todos');
            
        //Arrays con valores para contenido en la tabla
            $data['arr_tipos'] = $this->Item_model->arr_interno('categoria_id = 33');
        
        //Cargar vista
            $this->load->view(PTL_ADMIN, $data);
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
            $data = $this->Post_model->data_tabla_explorar($num_pagina);
        
        //Arrays con valores para contenido en lista
            $data['arr_tipos'] = $this->Item_model->arr_interno('categoria_id = 33');
        
        //Preparar respuesta
            $respuesta['html'] = $this->load->view('posts/explorar/tabla_v', $data, TRUE);
            $respuesta['seleccionados_todos'] = $data['seleccionados_todos'];
            $respuesta['num_pagina'] = $num_pagina;
        
        //Salida
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($respuesta));
    }
    
    /**
     * AJAX
     * Eliminar un grupo de posts seleccionados
     */
    function eliminar_seleccionados()
    {
        $str_seleccionados = $this->input->post('seleccionados');
        
        $seleccionados = explode('-', $str_seleccionados);
        
        foreach ( $seleccionados as $elemento_id ) 
        {
            $this->Post_model->eliminar($elemento_id);
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
            $data['destino_form'] = "posts/guardar/nuevo";
            $data['valores_form'] = $this->Pcrn->valores_form(NULL, 'post');
            
        //Solicitar vista
            $data['titulo_pagina'] = 'Posts';
            $data['subtitulo_pagina'] = 'Nuevo';
            $data['vista_a'] = 'posts/formulario_v';
            $data['vista_menu'] = 'posts/explorar/menu_v';
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
            $data = $this->Post_model->basico($post_id);
        
        //Array data espefícicas
            $data['vista_b'] = $this->Post_model->vista_editar($data['row']);
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function actualizar($post_id)
    {
        $this->Post_model->actualizar($post_id);
        redirect("posts/editar/{$post_id}/actualizado");
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
        
        $data = $this->Post_model->basico($post_id);    
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
        $data = $this->Post_model->basico($post_id);    
        
        //Variables
            $data['post_id'] = $post_id;
            
            if ( $data['row']->imagen_id )
            {
                $data['row_archivo'] = $this->Pcrn->registro_id('archivo', $data['row']->imagen_id);
            }
        
        //Solicitar vista
            $data['vista_b'] = $this->Post_model->vista_leer($data['row']);
            $this->load->view(PTL_ADMIN, $data);
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
            $data = $this->Post_model->basico($post_id);
        
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
    
// CONTENIDOS ACOMPAÑAMIENTO PEDAGÓGICO - TIPO 4311
//-----------------------------------------------------------------------------
    
    /**
     * Exploración y búsqueda de posts
     */
    function ap_explorar($num_pagina = 0)
    {
        //Datos básicos de la exploración
            $data = $this->Post_model->ap_data_explorar($num_pagina);
        
        //Opciones de filtros de búsqueda
            $data['arr_filtros'] = array('f2', 'f3');
            $data['opciones_tipo_ap'] = $this->Item_model->opciones('categoria_id = 153', 'Todos');
            $data['opciones_area'] = $this->Item_model->opciones_id('categoria_id = 1', 'Todos');
            
        //Arrays con valores para contenido en la tabla
            $data['arr_tipos_ap'] = $this->Item_model->arr_interno('categoria_id = 153');
            $data['arr_categorias_ap'] = $this->Item_model->arr_interno('categoria_id = 152');
            $data['arr_areas'] = $this->Item_model->arr_interno('categoria_id = 1');
            
        //Si son usuarios externos
            if ( ! in_array($this->session->userdata('rol_id'), array(0,1,2)) )
            {
                $data['titulo_pagina'] = 'Acompañamiento pedagógico';
            }
        
        //Cargar vista
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * AJAX
     * 
     * Devuelve JSON, que incluye string HTML de la tabla de exploración para la
     * página $num_pagina, y los filtros enviados por post
     * 
     * @param type $num_pagina
     */
    function ap_tabla_explorar($num_pagina = 0)
    {
        //Datos básicos de la exploración
            $data = $this->Post_model->ap_data_tabla_explorar($num_pagina);
        
        //Arrays con valores para contenido en lista
            $data['arr_tipos_ap'] = $this->Item_model->arr_interno('categoria_id = 153');
            $data['arr_categorias_ap'] = $this->Item_model->arr_interno('categoria_id = 152');
        
        //Preparar respuesta
            $respuesta['html'] = $this->load->view('posts/contenidos_ap/explorar/tabla_v', $data, TRUE);
            $respuesta['seleccionados_todos'] = $data['seleccionados_todos'];
            $respuesta['num_pagina'] = $num_pagina;
        
        //Salida
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($respuesta));
    }
    
    /**
     * Formulario para la creación de un registro en la tabla post,
     * Después de crear el post, es redirigido al
     * formulario de edición.
     * 
     * @param type $institucion_id
     */
    function ap_nuevo()
    {
        //Cargando datos básicos
            $data['destino_form'] = "posts/ap_crud/insertar";
            $data['valores_form'] = $this->Pcrn->valores_form(NULL, 'post');
            
        //Solicitar vista
            $data['titulo_pagina'] = 'Contenidos AP';
            $data['subtitulo_pagina'] = 'Nuevo';
            $data['vista_a'] = 'posts/contenidos_ap/formulario_v';
            $data['vista_menu'] = 'posts/contenidos_ap/explorar/menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Ejecuta un preso crud sobre un post del tipo AP
     * 
     * @param type $proceso
     * @param type $post_id
     */
    function ap_crud($proceso, $post_id = NULL)
    {
        $resultado['ejecutado'] = 0;
        
        if ( $proceso == 'insertar' )
        {
            $resultado = $this->Post_model->insertar();
        } elseif ( $proceso == 'actualizar' ) {
            $registro = $this->input->post();
            $resultado = $this->Post_model->actualizar($post_id, $registro);
        } elseif ( $proceso == 'eliminar' ) {
            $resultado = $this->Post_model->eliminar($post_id);
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($resultado));
    }
    
    /**
     * Editar la información básica de un post
     * Funciona con grocery crud
     * 
     * @param type $proceso
     * @param type $post_id
     */
    function ap_editar($post_id)
    {   
        $this->load->model('Esp');
        
        //Datos básicos
            $data = $this->Post_model->basico($post_id);
        
        //Array data espefícicas
            $data['vista_a'] = 'posts/contenidos_ap/contenido_ap_v';
            $data['vista_b'] = 'posts/contenidos_ap/editar_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function ap_leer($post_id)
    {
        $data = $this->Post_model->basico($post_id);    
        
        //Variables
            $data['post_id'] = $post_id;
            
        if ( $data['row']->imagen_id )
        {
            $data['row_archivo'] = $this->Pcrn->registro_id('archivo', $data['row']->imagen_id);
        }
        
        //Solicitar vista
            $data['vista_a'] = 'posts/contenidos_ap/contenido_ap_v';
            $data['vista_b'] = 'posts/contenidos_ap/leer_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Mostrar formulario de importación de datos de años de generación de grupos 
     * con archivo Excel. El resultado del formulario se envía a 
     * 'grupos/importar_editar_anios_e'
     * 
     */
    function ap_importar_asignaciones()
    {
        //Iniciales
            $nombre_archivo = '29_formato_asignar_ap.xlsx';
            $parrafos_ayuda = array(
                'Las columnas [ID AP], [ID Institución] y [Fecha máxima] no pueden estar vacías.',
                'Verifique que el <span class="resaltar">ID institución</span> existe en la plataforma.'
            );
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo asignar contenidos AP a las instituciones?';
            $data['nota_ayuda'] = 'Se asignarán contenidos AP a instituciones';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = "posts/ap_importar_asignaciones_e";
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'ap_asignar';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            //$data['ayuda_id'] = 97;
            $data['titulo_pagina'] = 'Contenidos AP';
            $data['subtitulo_pagina'] = 'Asignar con archivo Excel';
            $data['vista_a'] = 'comunes/importar_v';
            $data['vista_menu'] = 'posts/contenidos_ap/explorar/menu_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Importar datos de años de generación de grupos, (e) ejecutar.
     */
    function ap_importar_asignaciones_e()
    {
        //Proceso
            $this->load->model('Pcrn_excel');
            $this->load->model('Esp');
            $letra_columna = 'C';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $res_importacion = $this->Post_model->ap_importar_asignaciones($resultado['array_hoja']);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $res_importacion['no_importados'];
            $data['destino_volver'] = "posts/ap_explorar/";
        
        //Cargar vista
            $data['titulo_pagina'] = 'Contenidos AP';
            $data['subtitulo_pagina'] = 'Asignar con archivo Excel';
            $data['vista_a'] = 'comunes/resultado_importacion_v';
            $data['vista_menu'] = 'posts/contenidos_ap/explorar/menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Instituciones a las que se les asigna un contenido AP
     * @param type $post_id
     */
    function ap_instituciones($post_id)
    {
        //$this->output->enable_profiler(TRUE);
        
        //Datos básicos
        $data = $this->Post_model->basico($post_id);
        
        //Variables especificas
        $data['instituciones'] = $this->Post_model->instituciones($post_id);

        //Variables generales
        $data['vista_a'] = 'posts/contenidos_ap/contenido_ap_v';
        $data['vista_b'] = 'posts/contenidos_ap/instituciones_v';
        //$data['vista_menu'] = 'usuarios/explorar_menu_v';

        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * AJAX
     *
     */
    function ap_guardar_asignacion() 
    {
        //Preparar registro
            $registro['elemento_id'] = $this->input->post('institucion_id');
            $registro['relacionado_id'] = $this->input->post('post_id');
            $registro['fecha_1'] = $this->input->post('fecha') . ' 23:59:59'; //Día completo
        
        //Resultado previo
            $resultado = $this->Post_model->ap_guardar_asignacion($registro);

        //Respuesta
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($resultado));
    }
    
    /**
     * AJAX
     * Elimina la asignación de un post a una institución en la tabla meta
     * dato_id = 400010
     */
    function ap_eliminar_asignacion($post_id, $meta_id)
    {
        $resultado = $this->Post_model->ap_eliminar_asignacion($post_id, $meta_id);

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($resultado));
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
}