<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recursos extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        $this->load->model('Recurso_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index()
    {        
        $this->explorar();
    }

//INFORMACIÓN DE RECURSOS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Controla y redirecciona las búsquedas de exploración
     * para cada elemento (explorador), evita el problema de reenvío del
     * formulario al presionar el botón "atrás" del browser
     * 
     * @param type $elemento
     */
    function explorar_redirect($elemento = 'links')
    {
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Busqueda_model');
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        redirect("recursos/{$elemento}/?{$busqueda_str}");
    }
    
//ARCHIVOS
//---------------------------------------------------------------------------------------------------
    
    function archivos()
    {
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Busqueda_model');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Recurso_model->buscar_archivos($busqueda); //Para calcular el total de resultados
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(4);
            $config['base_url'] = base_url("recursos/archivos/?{$busqueda_str}");
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Recurso_model->buscar_archivos($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
        
        //Solicitar vista
            $data['head_title'] = 'Archivos';
            $data['head_subtitle'] = $resultados_total->num_rows();
            $data['view_a'] = 'recursos/archivos_v';
            $data['nav_2'] = 'recursos/menu_archivos_v';
            $this->load->view(TPL_ADMIN_NEW, $data);
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
            $this->Recurso_model->eliminar($elemento_id);
        }
        
        echo count($seleccionados);
    }    
    
// Importación de asignación de archivos
//-----------------------------------------------------------------------------
    
    /**
     * Mostrar formulario de importación de asignación de archivos mediante archivo MS Excel.
     * El resultado del formulario se envía a 'recursos/asignar_e'
     * 
     * @param type $programa_id
     */
    function asignar()
    {
        
        //Iniciales
            $nombre_archivo = '05_formato_asignacion_archivos.xlsx';
            $parrafos_ayuda = array();
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo asignar archivos?';
            $data['nota_ayuda'] = 'Se asignarán archivos los temas de la Plataforma.';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = 'recursos/asignar_e';
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'archivos';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            $data['head_title'] = 'Archivos';
            $data['head_subtitle'] = 'Asignar';
            $data['view_a'] = 'comunes/bs4/importar_v';
            $data['nav_2'] = 'recursos/menu_archivos_v';
        
        $this->load->view(TPL_ADMIN_NEW, $data);
    }
    
    /**
     * Asignar archivos, (e) ejecutar.
     */
    function asignar_e()
    {
        //Proceso
            $this->load->library('excel_new');
            
            $imported_data = $this->excel_new->arr_sheet_default($this->input->post('nombre_hoja'));

            if ( $imported_data['status'] == 1 )
            {
                $data = $this->Recurso_model->asignar($imported_data['arr_sheet']);
            }
        
        //Cargue de variables
            $data['status'] = $imported_data['status'];
            $data['message'] = $imported_data['message'];
            $data['arr_sheet'] = $imported_data['arr_sheet'];
            $data['sheet_name'] = $this->input->post('sheet_name');
            $data['back_destination'] = "recursos/archivos";
        
        //Cargar vista
            $data['head_title'] = 'Archivos';
            $data['head_subtitle'] = 'Asignar';
            $data['view_a'] = 'common/import_result_v';
            $data['nav_2'] = 'recursos/menu_archivos_v';
            $this->load->view(TPL_ADMIN_NEW, $data);
            //Salida JSON
            //$this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    function procesos_archivos()
    {
        
        $this->db->where('categoria_id', 20);   //Recursos
        $this->db->where('item_grupo', 1);      //Archivos
        $carpetas = $this->db->get('item');
        
        $data['carpetas'] = $carpetas;
        
        //Solicitar vista
            $data['head_title'] = 'Archivos';
            $data['head_subtitle'] = 'Asociación automática';
            $data['view_a'] = 'recursos/procesos_archivos_v';
            $this->load->view(TPL_ADMIN_NEW, $data);
    }
    
    /**
     * 
     * 
     * @param type $tipo_archivo_id
     */
    function asociar_archivos_e($tipo_archivo_id = 619)
    {   
        $cant_asociados = $this->Recurso_model->asociar_archivos($tipo_archivo_id);
        $this->session->set_flashdata('mensaje', "Se asociaron {$cant_asociados} archivos");
        redirect('recursos/procesos_archivos');
    }
    
    /**
     * Función de transición V2 a V3, cambio de nombres de archivos
     * 
     * @param type $tipo_archivo_id
     */
    function cambiar_nombres_e($tipo_archivo_id = 619)
    {
        $cant_archivos = $this->Recurso_model->cambiar_nombres($tipo_archivo_id);
        $this->session->set_flashdata('mensaje', "Se cambió el nombre de {$cant_archivos} archivos");
        redirect('recursos/procesos_archivos');
    }
    
    /**
     * Actualizar el campo, recurso.disponible
     * 
     * @param type $tipo_archivo_id
     */
    function act_archivos_disponibles($tipo_archivo_id = 619)
    {   
        $cant_no_disponibles = $this->Recurso_model->act_archivos_disponibles($tipo_archivo_id);
        $this->session->set_flashdata('mensaje', "De los archivos asignados a los temas, {$cant_no_disponibles} no se encuentran disponibles en el servidor de la Plataforma");
        redirect('recursos/procesos_archivos');
    }
    
    function archivos_no_asignados($tipo_archivo_id = 619)
    {   
        //Variables
            $data['archivos'] = $this->Recurso_model->archivos_no_asignados($tipo_archivo_id, 25);
            $data['tipo_archivo_id'] = $tipo_archivo_id;
            $data['carpeta_uploads'] = base_url() . RUTA_UPLOADS . $this->Pcrn->campo_id('item', $tipo_archivo_id, 'slug') . '/';
        
        //Solicitar vista
            $data['head_title'] = 'Archivos';
            $data['head_subtitle'] = 'Disponibles sin asignar a un tema';
            $data['view_a'] = 'recursos/archivos_no_asignados_v';
            $data['nav_2'] = 'recursos/menu_archivos_v';
            $this->load->view(TPL_ADMIN_NEW, $data);
        
    }
    
    function ajax_cambiar_nombre()
    {
        
        $this->load->helper('string');
        
        $tipo_archivo_id = $this->input->post('tipo_archivo_id');
        $recurso_id = 0;
        $asignado = 0;
        $tema_id = 0;
        $nombre_tema = '';
        $mensaje = 'El archivo no se asignó a ningún tema';
        
        //Nombres de archivo
            $basename_actual = $this->input->post('nombre_actual') . '.' . $this->input->post('extension');
            $basename_nuevo = $this->input->post('nombre_nuevo') . '-' . random_string('numeric', 6) . '.' . $this->input->post('extension');
        
        //Carpeta y rutas
            $carpeta = $this->Recurso_model->carpeta($tipo_archivo_id);

            $ruta_actual = $carpeta . $basename_actual;
            $ruta_nuevo = $carpeta . $basename_nuevo;
        
        //Cambiar nombre
            rename($ruta_actual, $ruta_nuevo);
            
            if ( file_exists($ruta_nuevo) ) { $recurso_id = $this->Recurso_model->asociar_archivo($ruta_nuevo, $tipo_archivo_id); }
            if ( $recurso_id > 0 ) { $asignado = 1; }
            if ( $asignado ) { $tema_id = $this->Pcrn->campo_id('recurso', $recurso_id, 'tema_id'); }
            if ( $tema_id > 0 ) { $nombre_tema = $this->Pcrn->campo_id('tema', $tema_id, 'nombre_tema'); }
            if ( $asignado ) { $mensaje = "El archivo se asignó al tema: [{$nombre_tema}]"; }
            
        //Preparando respuesta
            $respuesta['basename_nuevo'] = $basename_nuevo;
            $respuesta['asignado'] = $asignado;
            $respuesta['recurso_id'] = $recurso_id;
            $respuesta['tema_id'] = $tema_id;
            $respuesta['nombre_tema'] = $nombre_tema;
            $respuesta['mensaje'] = $mensaje;
            
            
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($respuesta));
    }
    
//LINKS
//---------------------------------------------------------------------------------------------------

    /**
     * Exploración de links
     * 2020-03-21
     */
    function links()
    {
        //Datos básicos de la exploración
        $data = $this->Recurso_model->links_explore_data(1);
                
        //Opciones de filtros de búsqueda
            $data['options_area'] = $this->Item_model->opciones_id('categoria_id = 1', 'Todos');
            $data['options_nivel'] = $this->App_model->opciones_nivel('item_largo', 'Todos');
            $data['options_componente'] = $this->Item_model->opciones_id('categoria_id = 8', 'Todos');
            
        //Arrays con valores para contenido en la tabla
            $data['arr_areas'] = $this->Item_model->arr_item('1', 'id_nombre_corto');
            $data['arr_tipos'] = $this->Item_model->arr_interno('categoria_id = 156');
            $data['arr_componentes'] = $this->Item_model->arr_item('categoria_id = 8', 'id');

        //Especiales
            $str_grupos = ( count($this->session->userdata('arr_grupos')) > 0 ) ? implode(',', $this->session->userdata('arr_grupos')) : '0' ;
            //$data['str_grupos'] = $str_grupos;
            $data['options_grupo'] = $this->App_model->opciones_grupo("grupo.id IN ({$str_grupos})");
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

    /**
     * Listado de links, filtrados por búsqueda, JSON
     */
    function links_get($num_page = 1)
    {
        $data = $this->Recurso_model->links_get($num_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function links_programar()
    {
        $data = $this->Recurso_model->links_programar();
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Exportar resultados de búsqueda de links
     * 2021-09-27
     */
    function links_export()
    {
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso

        //Identificar filtros y búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $str_filters = $this->Search_model->str_filters();

        $data['query'] = $this->Recurso_model->links($filters);

        //if ( $data['query']->num_rows() <= MAX_REG_EXPORT ) {
        if ( $data['query']->num_rows() <= 50000 ) {
            //Preparar datos
                $data['sheet_name'] = 'links';

            //Objeto para generar archivo excel
                $this->load->library('Excel_new');
                $file_data['obj_writer'] = $this->excel_new->file_query($data);

            //Nombre de archivo
                $file_data['file_name'] = date('Ymd_His') . '_' . $data['sheet_name'];

            $this->load->view('common/download_excel_file_v', $file_data);
            //Salida JSON
            //$this->output->set_content_type('application/json')->set_output(json_encode($file_data['obj_writer']));
        } else {
            $data['head_title'] = APP_NAME;
            $data['mensaje'] = "El número de registros es de {$data['query']->num_rows()}. 
                El máximo permitido es de " . MAX_REG_EXPORT . " registros.
                Puede filtrar los datos por algún criterio para poder exportarlos.";
            $data['link_volver'] = "recursos/links/?{$str_filters}";
            $data['view_a'] = 'app/mensaje_v';
            
            $this->load->view(TPL_ADMIN, $data);
        }
    }

    /**
     * Vista gestión links programados por el usuario en sesión a sus grupos
     * 2020-03-27
     */
    function links_programados()
    {
        //Arrays con valores para contenido en la tabla
            $data['arr_areas'] = $this->Item_model->arr_item('1', 'id_nombre_corto');
            $data['arr_tipos'] = $this->Item_model->arr_interno('categoria_id = 156');
            $data['arr_componentes'] = $this->Item_model->arr_item('categoria_id = 8', 'id');

        //Variables vista
            $data['view_a'] = 'recursos/links/programados_v';
            $data['nav_2'] = 'recursos/links/explore/menu_v';
            $data['head_title'] = 'Links';
            $data['head_subtitle'] = 'Programados';
            
        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

    /**
     * JSON lista links programados por el usuario en sesión a sus grupos
     */
    function get_links_programados()
    {
        $links = $this->Recurso_model->links_programados();
        $data['list'] = $links->result();
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Actualiza el campo recurso.palabras_clave de forma automática
     */
    function links_update_palabras_clave_auto()
    {
        $data = $this->Recurso_model->links_update_palabras_clave_auto();
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * Mostrar formulario de importación de links mediante archivo MS Excel.
     * El resultado del formulario se envía a 'recursos/importar_links_e'
     * 2020-04-02
     */
    function links_importar()
    {
        //Iniciales
            $template_file_name = 'f33_cargue_links.xlsx';
            $help_tips = array();
        
        //Instructivo
            $data['help_note'] = 'Se importarán recursos tipo link a la Plataforma.';
            $data['help_tips'] = $help_tips;
        
        //Variables específicas
            $data['destination_form'] = 'recursos/links_importar_e';
            $data['template_file_name'] = $template_file_name;
            $data['sheet_name'] = 'links';
            $data['url_file'] = base_url("assets/formatos_cargue/{$template_file_name}");
            
        //Variables generales
            $data['head_title'] = 'Links';
            $data['head_subtitle'] = 'Importar';
            $data['view_a'] = 'common/import_v';
            $data['nav_2'] = 'recursos/links/explore/menu_v';
        
        $this->load->view(TPL_ADMIN_NEW, $data);
    }
    
    /**
     * Importar links, (e) ejecutar.
     * 2020-04-02
     */
    function links_importar_e()
    {
        //Proceso
        $this->load->library('excel_new');            
        $imported_data = $this->excel_new->arr_sheet_default($this->input->post('sheet_name'));
        
        if ( $imported_data['status'] == 1 )
        {
            $data = $this->Recurso_model->links_importar($imported_data['arr_sheet']);
        }

        //Cargue de variables
            $data['status'] = $imported_data['status'];
            $data['message'] = $imported_data['message'];
            $data['arr_sheet'] = $imported_data['arr_sheet'];
            $data['sheet_name'] = $this->input->post('sheet_name');
            $data['back_destination'] = "recursos/links/";
            $data['cf_open'] = 'users/info/';
        
        //Cargar vista
            $data['head_title'] = 'Links';
            $data['head_subtitle'] = 'Resultado importación';
            $data['view_a'] = 'common/import_result_v';
            $data['nav_2'] = 'recursos/links/explore/menu_v';

        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

    /**
     * Mostrar formulario de cargue de archivo excel con listado de temas
     * a los cuales se les eliminará los temas asociados
     * 2021-04-05
     */
    function links_eliminar()
    {
        //Configuración
            $data['help_note'] = '¿Cómo eliminar links de temas?';
            $data['help_tips'] = array(
                'La columna A no puede estar vacía.',
            );
            $data['template_file_name'] = 'f35_eliminacion_links_temas.xlsx';
            $data['url_file'] = base_url("assets/formatos_cargue/{$data['template_file_name']}");
            $data['sheet_name'] = 'temas_links';
            $data['destination_form'] = 'recursos/links_eliminar_e';

        //Vista
            $data['head_title'] = 'Links';
            $data['head_subtitle'] = 'Eliminar links de temas';
            $data['view_a'] = 'common/import_v';
            $data['nav_2'] = 'recursos/links/explore/menu_v';
            
        $this->load->view(TPL_ADMIN_NEW, $data);
    }

    /**
     * Ejecuta la eliminación masiva de links asociados a los temas en archivo excel.
     * 2021-04-05
     */
    function links_eliminar_e()
    {
        //Proceso
        $this->load->library('excel_new');            
        $imported_data = $this->excel_new->arr_sheet_default($this->input->post('sheet_name'));
        
        if ( $imported_data['status'] == 1 )
        {
            $data = $this->Recurso_model->links_eliminar($imported_data['arr_sheet']);
        }

        //Cargue de variables
            $data['status'] = $imported_data['status'];
            $data['message'] = $imported_data['message'];
            $data['arr_sheet'] = $imported_data['arr_sheet'];
            $data['sheet_name'] = $this->input->post('sheet_name');
            $data['back_destination'] = "recursos/links_eliminar/";
        
        //Cargar vista
            $data['head_title'] = 'Links';
            $data['head_subtitle'] = 'Resultado eliminar links';
            $data['view_a'] = 'common/import_result_v';
            $data['nav_2'] = 'recursos/links/explore/menu_v';

        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }
}