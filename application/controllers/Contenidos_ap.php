<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contenidos_ap extends CI_Controller{
    
    function __construct() {
        parent::__construct();

        $this->load->model('Contenido_ap_model');
        
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
    
// CONTENIDOS ACOMPAÑAMIENTO PEDAGÓGICO - TIPO 4311
//-----------------------------------------------------------------------------
    
    /**
     * Exploración y búsqueda de posts
     */
    function explorar($num_pagina = 1)
    {
        //Datos básicos de la exploración
            $data = $this->Contenido_ap_model->data_explorar($num_pagina);
        
        //Opciones de filtros de búsqueda
            $data['arr_filtros'] = array('f2', 'f3', 'n');
            $data['opciones_tipo_ap'] = $this->Item_model->opciones('categoria_id = 153', 'Todos');
            $data['opciones_area'] = $this->Item_model->opciones_id('categoria_id = 1', 'Todos');
            $data['opciones_nivel'] = $this->App_model->opciones_nivel('item');
            
        //Arrays con valores para contenido en la tabla
            $data['arr_tipos_ap'] = $this->Item_model->arr_interno('categoria_id = 153');
            $data['arr_categorias_ap'] = $this->Item_model->arr_interno('categoria_id = 152');
            $data['arr_areas'] = $this->Item_model->arr_interno('categoria_id = 1');
            
        //Si son usuarios externos
            if ( ! in_array($this->session->userdata('rol_id'), array(0,1,2)) )
            {
                $data['head_title'] = 'Acompañamiento pedagógico';
            }
        
        //Cargar vista
            $this->load->view(TPL_ADMIN_NEW, $data);
    }
    
    /**
     * AJAX
     * 
     * Devuelve JSON, que incluye string HTML de la tabla de exploración para la
     * página $num_pagina, y los filtros enviados por post
     * 
     * @param type $num_pagina
     */
    function tabla_explorar($num_pagina = 1)
    {
        //Datos básicos de la exploración
            $data = $this->Contenido_ap_model->data_tabla_explorar($num_pagina);
        
        //Arrays con valores para contenido en lista
            $data['arr_tipos_ap'] = $this->Item_model->arr_interno('categoria_id = 153');
            $data['arr_categorias_ap'] = $this->Item_model->arr_interno('categoria_id = 152');
        
        //Preparar respuesta
            $respuesta['html'] = $this->load->view('posts/contenidos_ap/explorar/tabla_v', $data, TRUE);
            $respuesta['seleccionados_todos'] = $data['seleccionados_todos'];
            $respuesta['num_pagina'] = $num_pagina;
        
        //Salida
            $this->output->set_content_type('application/json')->set_output(json_encode($respuesta));
    }
    
    /**
     * Formulario para la creación de un registro en la tabla post,
     * Después de crear el post, es redirigido al
     * formulario de edición.
     * 
     */
    function nuevo()
    {
        //Cargando datos básicos
            $data['destino_form'] = "posts/ap_crud/insertar";
            $data['valores_form'] = $this->Pcrn->valores_form(NULL, 'post');
            
        //Solicitar vista
            $data['head_title'] = 'Contenidos AP';
            $data['head_subtitle'] = 'Nuevo';
            $data['view_a'] = 'posts/contenidos_ap/formulario_v';
            $data['menu_view'] = 'posts/contenidos_ap/explorar/menu_v';
            $this->load->view(TPL_ADMIN_NEW, $data);
    }
    
    /**
     * Ejecuta un preso crud sobre un post del tipo AP
     * 
     * @param type $proceso
     * @param type $post_id
     */
    function crud($proceso, $post_id = NULL)
    {
        $resultado['ejecutado'] = 0;
        
        if ( $proceso == 'insertar' )
        {
            $resultado = $this->Contenido_ap_model->insertar();
        } elseif ( $proceso == 'actualizar' ) {
            $registro = $this->input->post();
            $resultado = $this->Contenido_ap_model->actualizar($post_id, $registro);
        } elseif ( $proceso == 'eliminar' ) {
            $resultado = $this->Contenido_ap_model->eliminar($post_id);
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
    function editar($post_id)
    {   
        $this->load->model('Esp');
        
        //Datos básicos
            $data = $this->Contenido_ap_model->basic($post_id);
        
        //Array data espefícicas
            $data['nav_2'] = 'posts/contenidos_ap/contenido_ap_v';
            $data['view_a'] = 'posts/contenidos_ap/editar_v';
        
        $this->load->view(TPL_ADMIN_NEW, $data);
    }
    
    function leer($post_id)
    {
        $data = $this->Contenido_ap_model->basic($post_id);    
        
        //Variables
            $data['post_id'] = $post_id;
            
        if ( $data['row']->imagen_id )
        {
            $data['row_archivo'] = $this->Pcrn->registro_id('archivo', $data['row']->imagen_id);
        }
        
        //Solicitar vista
            $data['nav_2'] = 'posts/contenidos_ap/contenido_ap_v';
            $data['view_a'] = 'posts/contenidos_ap/leer_v';
            $this->load->view(TPL_ADMIN_NEW, $data);
    }
    
    /**
     * Mostrar formulario de importación de datos de años de generación de grupos 
     * con archivo Excel. El resultado del formulario se envía a 
     * 'grupos/importar_editar_anios_e'
     * 
     */
    function importar_asignaciones()
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
            $data['head_title'] = 'Contenidos AP';
            $data['head_subtitle'] = 'Asignar con archivo Excel';
            $data['view_a'] = 'comunes/bs4/importar_v';
            $data['nav_2'] = 'posts/contenidos_ap/explorar/menu_v';
        
        $this->load->view(TPL_ADMIN_NEW, $data);
    }
    
    /**
     * Importar datos de años de generación de grupos, (e) ejecutar.
     */
    function importar_asignaciones_e()
    {
        //Proceso
            $this->load->model('Pcrn_excel');
            $this->load->model('Esp');
            $letra_columna = 'C';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $res_importacion = $this->Contenido_ap_model->importar_asignaciones($resultado['array_hoja']);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $res_importacion['no_importados'];
            $data['destino_volver'] = "posts/ap_explorar/";
        
        //Cargar vista
            $data['head_title'] = 'Contenidos AP';
            $data['head_subtitle'] = 'Asignar con archivo Excel';
            $data['view_a'] = 'comunes/resultado_importacion_v';
            $data['nav_2'] = 'posts/contenidos_ap/explorar/menu_v';
            $this->load->view(TPL_ADMIN_NEW, $data);
    }
    
    /**
     * Instituciones a las que se les asigna un contenido AP
     * @param type $post_id
     */
    function instituciones($post_id)
    {
        //Datos básicos
        $data = $this->Contenido_ap_model->basic($post_id);
        
        //Variables especificas
        $data['instituciones'] = $this->Contenido_ap_model->instituciones($post_id);

        //Variables generales
        $data['nav_2'] = 'posts/contenidos_ap/contenido_ap_v';
        $data['view_a'] = 'posts/contenidos_ap/instituciones_v';
        //$data['vista_menu'] = 'usuarios/explorar_menu_v';

        $this->load->view(TPL_ADMIN_NEW, $data);
    }
    
    /**
     * AJAX
     *
     */
    function guardar_asignacion() 
    {
        //Preparar registro
            $registro['elemento_id'] = $this->input->post('institucion_id');
            $registro['relacionado_id'] = $this->input->post('post_id');
            $registro['fecha_1'] = $this->input->post('fecha') . ' 23:59:59'; //Día completo
        
        //Resultado previo
            $resultado = $this->Contenido_ap_model->guardar_asignacion($registro);

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
    function eliminar_asignacion($post_id, $meta_id)
    {
        $resultado = $this->Contenido_ap_model->eliminar_asignacion($post_id, $meta_id);

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($resultado));
    }
}