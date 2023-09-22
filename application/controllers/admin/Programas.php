<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Programas extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
public $views_folder = 'admin/programas/';
public $url_controller = URL_ADMIN . 'programas/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() {
        parent::__construct();
        
        $this->load->model('Programa_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($tema_id = NULL)
    {
        $destino = URL_ADMIN . 'programas/explore/';
        if ( ! is_null($tema_id) ) {
            $destino = URL_ADMIN . "programas/info/{$tema_id}";
        }
        
        redirect($destino);
    }

    function reciente()
    {
        $programa_id = $this->Programa_model->reciente();
        redirect("admin/programas/info/{$programa_id}");
    }

//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------

    /** 
    * Exploración de Posts
    * 2022-08-23
    * */
    function explore($num_page = 1)
    {
        //Identificar filtros de búsqueda
            $this->load->model('Search_model');
            $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Programa_model->explore_data($filters, $num_page);
        
        //Opciones de filtros de búsqueda
            $data['arrArea'] = $this->Item_model->arr_options('categoria_id = 1');
            $data['arrNivel'] = $this->Item_model->arr_options('categoria_id = 3');
            $data['optionsInstitucion'] = $this->App_model->opciones_institucion('id > 0', 'Todas');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

    /**
     * Exportar resultados de búsqueda
     * 2022-08-17
     */
    function export($element_name = 'programas')
    {
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso

        //Identificar filtros y búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data['query'] = $this->Programa_model->query_export($filters);

        if ( $data['query']->num_rows() > 0 ) {
            //Preparar datos
                $data['sheet_name'] = $element_name;

            //Objeto para generar archivo excel
                $this->load->library('Excel');
                $file_data['obj_writer'] = $this->excel->file_query($data);

            //Nombre de archivo
                $file_data['file_name'] = date('Ymd_His') . '_' . $data['sheet_name'];
            $this->load->view('common/download_excel_file_v', $file_data);
        } else {
            $data = array('message' => 'No se encontraron registros para exportar');
            //Salida JSON
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

// CRUD
//-----------------------------------------------------------------------------

    /**
     * Información general de un programa
     * 2023-07-02
     */
    function info($tema_id)
    {
        $data = $this->Programa_model->basic($tema_id);

        $data['temas'] = $this->Programa_model->temas($tema_id);
        $data['nav_2'] = $this->views_folder . 'menus/row_v';

        $data['view_a'] = $this->views_folder . 'info_v';
        $this->load->view(TPL_ADMIN_NEW, $data);
    }
    
    /**
     * Formulario adición nuevo tema
     * 2023-07-01
     */
    function nuevo()
    {    
        //Render del grocery crud
            $gc_output = $this->Programa_model->crud_basico();
            
        //Solicitar vista
            $data['head_title'] = 'Crear programa';
            $data['head_subtitle'] = 'Nuevo';
            $data['view_a'] = 'comunes/gc_v';
            $data['nav_2'] = $this->views_folder  . 'menus/explore_v';
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(TPL_ADMIN_NEW, $output);
    }
    
    /**
     * Formulario de edición de un tema
     * 2023-07-01
     */
    function editar()
    {
        //Cargando datos básicos
            $tema_id = $this->uri->segment(5);
            $data = $this->Programa_model->basic($tema_id);
            
        //Render del grocery crud
            $gc_output = $this->Programa_model->crud_basico();
            
        //Solicitar vista
            $data['head_subtitle'] = 'Editar';
            $data['view_a'] = 'common/bs4/gc_v';
            $data['nav_2'] = $this->views_folder . 'menus/row_v';
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(TPL_ADMIN_NEW, $output);
    }

// GESTIÓN DE TEMAS
//-----------------------------------------------------------------------------

/**
     * Gestión de los temas de un programa
     * 2023-08-06
     */
    function temas($programa_id)
    {   
        //Cargando datos básicos
            $data = $this->Programa_model->basic($programa_id);
            
        //Actualizar tema.orden
            //$this->Programa_model->numerar_temas($programa_id);
            
        //Cargando $data
            $data['temas'] = $this->Programa_model->temas($programa_id);
            $data['arrArea'] = $this->Item_model->arr_options('categoria_id = 1');
            
        //Solicitar vista
            $data['head_subtitle'] = 'Temas';
            $data['nav_2'] = $this->views_folder . 'menus/row_v';
            $data['view_a'] = $this->views_folder . 'temas/temas_v';
            $this->load->view(TPL_ADMIN_NEW, $data);
    }

// IMPORTACIÓN DE DATOS
//-----------------------------------------------------------------------------

    /**
     * Mostrar formulario de cargue de archivo excel con listado de temas
     * a los cuales se les eliminará las preguntas abiertas (pa) asignadas
     * 2021-03-30
     */
    function import()
    {
        //Configuración
            $data['help_note'] = '¿Cómo importar programas?';
            $data['help_tips'] = array(
                'La columna A no puede estar vacía.',
            );
            $data['template_file_name'] = '09_formato_cargue_programas.xlsx';
            $data['url_file'] = base_url("assets/formatos_cargue/{$data['template_file_name']}");
            $data['sheet_name'] = 'programas';
            $data['destination_form'] = 'admin/programas/run_import';

        //Vista
            $data['head_title'] = 'Programas';
            $data['head_subtitle'] = 'Importar programas';
            $data['view_a'] = 'common/import_v';
            $data['nav_2'] = $this->views_folder . 'menus/explore_v';
            $data['nav_3'] = $this->views_folder  . 'menus/importar_v';
            
        $this->load->view(TPL_ADMIN_NEW, $data);
    }

    /**
     * Ejecuta la importación de datos de programas masivamente desde archivo excel
     * 2021-03-30
     */
    function run_import()
    {
        //Proceso
        $this->load->library('excel_new');
        $imported_data = $this->excel_new->arr_sheet_default($this->input->post('sheet_name'));
        
        if ( $imported_data['status'] == 1 )
        {
            $data = $this->Programa_model->import($imported_data['arr_sheet']);
        }

        //Cargue de variables
            $data['status'] = $imported_data['status'];
            $data['message'] = $imported_data['message'];
            $data['arr_sheet'] = $imported_data['arr_sheet'];
            $data['sheet_name'] = $this->input->post('sheet_name');
            $data['back_destination'] = "admin/programas/import/";
        
        //Cargar vista
            $data['head_title'] = 'Programas';
            $data['head_subtitle'] = 'Resultado importación';
            $data['view_a'] = 'common/import_result_v';
            $data['nav_2'] = $this->views_folder . 'menus/explore_v';
            $data['nav_3'] = $this->views_folder  . 'menus/importar_v';

        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

// IMPORTACIÓN DE TEMAS A LOS PROGRAMAS
//-----------------------------------------------------------------------------

    /**
     * Mostrar formulario de cargue de archivo excel con listado de temas
     * a los cuales se les eliminará las preguntas abiertas (pa) asignadas
     * 2021-03-30
     */
    function asignar_temas_multi()
    {
        //Configuración
            $data['help_note'] = '¿Cómo asignar temas a los programas?';
            $data['help_tips'] = array(
                'Si la casilla Id Programa (columna A) se encuentra vacía el tema no será asignado.',
            );
            $data['template_file_name'] = '10_formato_asignacion_temas_multiple_v2.xlsx';
            $data['url_file'] = base_url("assets/formatos_cargue/{$data['template_file_name']}");
            $data['sheet_name'] = 'temas';
            $data['destination_form'] = 'admin/programas/asignar_temas_multi_run';

        //Vista
            $data['head_title'] = 'Importar';
            $data['head_subtitle'] = 'Asignar temas a programas';
            $data['view_a'] = 'common/import_v';
            $data['nav_2'] = $this->views_folder . 'menus/explore_v';
            $data['nav_3'] = $this->views_folder  . 'menus/importar_v';
            
        $this->load->view(TPL_ADMIN_NEW, $data);
    }

    /**
     * Ejecuta la importación de datos de programas masivamente desde archivo excel
     * 2021-03-30
     */
    function asignar_temas_multi_run()
    {
        //Proceso
        $this->load->library('excel_new');
        $imported_data = $this->excel_new->arr_sheet_default($this->input->post('sheet_name'));
        
        if ( $imported_data['status'] == 1 )
        {
            $data = $this->Programa_model->asignar_temas_multi($imported_data['arr_sheet']);
        }

        //Cargue de variables
            $data['status'] = $imported_data['status'];
            $data['message'] = $imported_data['message'];
            $data['arr_sheet'] = $imported_data['arr_sheet'];
            $data['sheet_name'] = $this->input->post('sheet_name');
            $data['back_destination'] = "admin/programas/import/";
        
        //Cargar vista
            $data['head_title'] = 'Programas';
            $data['head_subtitle'] = 'Resultado importación';
            $data['view_a'] = 'common/import_result_v';
            $data['nav_2'] = $this->views_folder . 'menus/explore_v';
            $data['nav_3'] = $this->views_folder  . 'menus/importar_v';

        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

// GENERAR FLIPBOOKS MULTI
//-----------------------------------------------------------------------------

    /**
     * Generar los contenidos a parir de los programas relacionados en un archivo
     * Excel
     * 2021-03-30
     */
    function generar_flipbooks_multi()
    {
        //Configuración
            $data['help_note'] = 'Se generarán masivamente los contenidos (Flipbooks)
                a partir de los programas';
            $data['help_tips'] = array(
                'Las columnas A y B deben estar diligenciadas',
            );
            $data['template_file_name'] = '11_formato_generar_flipbooks.xlsx';
            $data['url_file'] = base_url("assets/formatos_cargue/{$data['template_file_name']}");
            $data['sheet_name'] = 'programas_contenido';
            $data['destination_form'] = 'admin/programas/generar_flipbooks_multi_run';

        //Vista
            $data['head_title'] = 'Generar contenidos';
            $data['head_subtitle'] = 'desde programas';
            $data['view_a'] = 'common/import_v';
            $data['nav_2'] = $this->views_folder . 'menus/explore_v';
            $data['nav_3'] = $this->views_folder  . 'menus/importar_v';
            
        $this->load->view(TPL_ADMIN_NEW, $data);
    }

    /**
     * Ejecuta la importación de datos de programas masivamente desde archivo excel
     * 2021-03-30
     */
    function generar_flipbooks_multi_run()
    {
        //Proceso
        $this->load->library('excel_new');
        $imported_data = $this->excel_new->arr_sheet_default($this->input->post('sheet_name'));
        
        if ( $imported_data['status'] == 1 )
        {
            $data = $this->Programa_model->generar_flipbooks_multi($imported_data['arr_sheet']);
        }

        //Cargue de variables
            $data['status'] = $imported_data['status'];
            $data['message'] = $imported_data['message'];
            $data['arr_sheet'] = $imported_data['arr_sheet'];
            $data['sheet_name'] = $this->input->post('sheet_name');
            $data['back_destination'] = "admin/programas/generar_flipbooks_multi/";
        
        //Cargar vista
            $data['head_title'] = 'Generar contenidos';
            $data['head_subtitle'] = 'Resultado';
            $data['view_a'] = 'common/import_result_v';
            $data['nav_2'] = $this->views_folder . 'menus/explore_v';
            $data['nav_3'] = $this->views_folder  . 'menus/importar_v';

        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

}