<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kits extends CI_Controller{
    
    function __construct() {
        parent::__construct();

        
        $this->load->model('Kit_model');
        
        //Para definir hora local
        date_default_timezone_set('America/Bogota');
    }
    
    function index($kit_id)
    {   
        $this->explorar($kit_id);
    }

//INFORMACIÓN DE KITS
//---------------------------------------------------------------------------------------------------
    
    function explorar()
    {
        $this->load->model('Busqueda_model');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Kit_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(4);
            $config['base_url'] = base_url("kits/explorar/?{$busqueda_str}");
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Kit_model->buscar($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
        
        //Solicitar vista
            $data['head_title'] = 'Kits';
            $data['head_subtitle'] = $config['total_rows'];
            $data['view_a'] = 'kits/explorar/explorar_v';
            $data['nav_2'] = 'kits/explorar_menu_v';
            $this->load->view(TPL_ADMIN_NEW, $data);
    }
    
    /**
     * Exporta el resultado de la búsqueda a un archivo de Excel
     */
    function exportar()
    {
        
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso
        //Cargando
            $this->load->model('Busqueda_model');
            $this->load->model('Pcrn_excel');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Kit_model->buscar($busqueda); //Para calcular el total de resultados
        
            if ( $resultados_total->num_rows() <= MAX_REG_EXPORT ) {
                //Preparar datos
                    $datos['nombre_hoja'] = 'Kits';
                    $datos['query'] = $resultados_total;

                //Preparar archivo
                    $objWriter = $this->Pcrn_excel->archivo_query($datos);

                $data['objWriter'] = $objWriter;
                $data['nombre_archivo'] = date('Ymd_His'). '_kits'; //save our workbook as this file name

                $this->load->view('app/descargar_phpexcel_v', $data);
            } else {
                $data['head_title'] = 'Plataforma Enlace';
                $data['mensaje'] = "El número de registros es de {$resultados_total->num_rows()}. El máximo permitido es de " . MAX_REG_EXPORT . " registros. Puede filtrar los datos por algún criterio para poder exportarlos.";
                $data['link_volver'] = "kits/explorar/?{$busqueda_str}";
                $data['view_a'] = 'app/mensaje_v';
                
                $this->load->view(TPL_ADMIN_NEW, $data);
            }
            
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
            $this->Kit_model->eliminar($elemento_id);
        }
        
        echo count($seleccionados);
    }    
    
    function nuevo()
    {
        
        //Render del grocery crud
            $output = $this->Kit_model->crud_basico();
        
        //Head includes específicos para la página
            //$head_includes[] = 'grocery_crud';
            //$data['head_includes'] = $head_includes;
        
        //Array data espefícicas
            $data['head_title'] = 'Kits';
            $data['head_subtitle'] = 'Nuevo';
            $data['nav_2'] = 'kits/explorar_menu_v';
            $data['view_a'] = 'app/gc_v';
        
        $output = array_merge($data,(array)$output);
        
        $this->load->view(TPL_ADMIN_NEW, $output);
    }
    
    function editar()
    {
        //Cargando datos básicos
            $tema_id = $this->uri->segment(4);
            $data = $this->Kit_model->basico($tema_id);
            
        //Render del grocery crud
            $output = $this->Kit_model->crud_basico();
        
        //Head includes específicos para la página
            $head_includes[] = 'grocery_crud';
            $data['head_includes'] = $head_includes;
            
        //Solicitar vista
            $data['view_a'] = 'comunes/bs4/gc_v';
            $output = array_merge($data,(array)$output);
            $this->load->view(TPL_ADMIN_NEW, $output);
    }
    
//GESTIÓN DE FLIPBOOKS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Listado de contenidos asignados al kit
     * 2024-03-18
     */
    function flipbooks($kit_id)
    {
        //Cargando datos básicos
            $data = $this->Kit_model->basico($kit_id);
            
        //Búsqueda
            $this->load->model('Busqueda_model');
            $this->load->model('Flipbook_model');
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda['condicion'] = "tipo_flipbook_id IN (0,3,4,6) AND id NOT IN (SELECT elemento_id FROM kit_elemento WHERE kit_id = {$kit_id} AND tipo_elemento_id = 1)";
            //$busqueda['tp'] = '0'; //Tipo flipbook estudiantes
            $data['resultados'] = $this->Flipbook_model->buscar($busqueda, 100, 0); //Se limita a 100 resultados
            
        //Cargando $data
            $data['flipbooks'] = $this->Kit_model->flipbooks($kit_id);
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $this->Busqueda_model->busqueda_str();
            $data['subseccion'] = 'listado';
            $data['destino_form'] = "kits/flipbooks/{$kit_id}";
            
        //Solicitar vista
            $data['head_subtitle'] = $data['flipbooks']->num_rows() . ' Contenidos';
            $data['view_a'] = 'kits/flipbooks_v';
            $this->load->view(TPL_ADMIN_NEW, $data);
    }
    
    function agregar_flipbook($kit_id, $flipbook_id)
    {
        //Guardar registro
            $registro['kit_id'] = $kit_id;
            $registro['elemento_id'] = $flipbook_id;
            $registro['tipo_elemento_id'] = 1;  //Flipbook

            $this->Kit_model->agregar_elemento($registro);
        
        //Redireccionando
            $this->load->model('Busqueda_model');
            $busqueda_str = $this->Busqueda_model->busqueda_str();

            $destino = "kits/flipbooks/{$kit_id}/{$flipbook_id}/?{$busqueda_str}";
            redirect($destino);
    }
    
    function quitar_flipbook($kit_id, $row_id)
    {
        //Eliminar registro
            $this->Kit_model->quitar_elemento($row_id);
        
        //Redireccionando
            $this->load->model('Busqueda_model');
            $busqueda_str = $this->Busqueda_model->busqueda_str();

            $destino = "kits/flipbooks/{$kit_id}/?{$busqueda_str}";
            redirect($destino);
    }
    
//GESTIÓN DE CUESTIONARIOS
//---------------------------------------------------------------------------------------------------
    
    function cuestionarios($kit_id)
    {
        //Cargando datos básicos
            $this->load->model('Cuestionario_model');
            $data = $this->Kit_model->basico($kit_id);
            
        //Búsqueda
            $this->load->model('Search_model');
            $filters = $this->Search_model->filters();
            $filters['condition'] = "cuestionario.id NOT IN (SELECT elemento_id FROM kit_elemento WHERE kit_id = {$kit_id} AND tipo_elemento_id = 2)";
            $data['resultados'] = $this->Cuestionario_model->search($filters, 100, 0); //Se limita a 100 resultados
            
        //Cargando $data
            $data['cuestionarios'] = $this->Kit_model->cuestionarios($kit_id);
            $data['busqueda'] = $filters;
            $data['busqueda_str'] = $this->Search_model->str_filters();
            
        //Solicitar vista
            $data['head_subtitle'] = $data['cuestionarios']->num_rows() . ' cuestionarios';
            $data['view_a'] = 'kits/cuestionarios_v';
            $this->load->view(TPL_ADMIN_NEW, $data);
    }
    
    function agregar_cuestionario($kit_id, $cuestionario_id)
    {
        //Guardar registro
            $registro['kit_id'] = $kit_id;
            $registro['elemento_id'] = $cuestionario_id;
            $registro['tipo_elemento_id'] = 2;  //Cuestionario

            $this->Kit_model->agregar_elemento($registro);
        
        //Redireccionando
            $this->load->model('Busqueda_model');
            $busqueda_str = $this->Busqueda_model->busqueda_str();

            $destino = "kits/cuestionarios/{$kit_id}/{$cuestionario_id}/?{$busqueda_str}";
            redirect($destino);
    }
    
    function quitar_cuestionario($kit_id, $row_id)
    {
        //Eliminar registro
            $this->Kit_model->quitar_elemento($row_id);
        
        //Redireccionando
            $this->load->model('Busqueda_model');
            $busqueda_str = $this->Busqueda_model->busqueda_str();

            $destino = "kits/cuestionarios/{$kit_id}/?{$busqueda_str}";
            redirect($destino);
    }
    
//GESTIÓN DE INSTITUCIONES
//---------------------------------------------------------------------------------------------------
    
    function instituciones($kit_id)
    {
        //Cargando datos básicos
            $data = $this->Kit_model->basico($kit_id);
            
        //Cargando $data
            $data['instituciones'] = $this->Kit_model->instituciones($kit_id);
            
        //Solicitar vista
            $data['view_a'] = 'kits/instituciones/instituciones_v';
            $data['head_subtitle'] = $data['instituciones']->num_rows() . ' instituciones';
            $this->load->view(TPL_ADMIN_NEW, $data);
    }

    /**
     * JSON
     * Listado de instituciones asignadas a un kit
     * 2023-03-02
     */
    function get_instituciones($kit_id)
    {
        $instituciones = $this->Kit_model->instituciones($kit_id);;
        $data['instituciones'] = $instituciones->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX JSON
     * Agrega a una institución a un kit y le asigna cuestionarios y flipboks
     */
    function agregar_institucion($kit_id, $institucion_id)
    {
        //Guardar registro
            $registro['kit_id'] = $kit_id;
            $registro['elemento_id'] = $institucion_id;
            $registro['tipo_elemento_id'] = 0;  //Institucón
            $registro['editado'] = date('Y-m-d H:i:s');

            $asignacion_id = $this->Kit_model->agregar_institucion($registro);
            
        //Aplicar, asignando flipbooks y cuestionarios
            $this->Kit_model->asignar_flipbooks($asignacion_id);
            $this->Kit_model->asignar_cuestionarios($asignacion_id);
        
        //Redireccionando
            $this->load->model('Busqueda_model');
            $busqueda_str = $this->Busqueda_model->busqueda_str();

            $data['status'] = 1;

            //Salida JSON
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX
     * Eliminar asignaciones del kit a la institución y luego eliminar institución del kit
     * 2023-03-02
     */
    function quitar_institucion($kit_id, $asignacion_id)
    {
        //Eliminar registro
            $data['qty_deleted'] = $this->Kit_model->quitar_institucion($asignacion_id);
        
            //Salida JSON
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * Realiza la asignación de lo elementos del kit a los estudiantes de una institución
     * $asignacion_id: corresponde a kit_elemento.id, que relaciona el kit con una
     * institución.
     * 
     * Si $depuracion == 1, se eliminan las asignaciones a los usuarios de elementos
     * que ya no están en el kit.
     * 
     * @param int $kit_id
     * @param int $asignacion_id
     * @param int $depurar
     */
    function asignar($kit_id, $asignacion_id, $depurar = FALSE)
    {
        set_time_limit(360);    //360 segundos, 6 minutos para ejecutar la asignación
        
        $data['qty_flipbooks_asignados'] = $this->Kit_model->asignar_flipbooks($asignacion_id);
        $data['qty_cuestionarios_asignados'] = $this->Kit_model->asignar_cuestionarios($asignacion_id);
        $this->Kit_model->actualizar_asignacion($asignacion_id);
        
        //Si está activada la opción, se eliminan las asignaciones de elementos inexistentes en el kit.
        if ( $depurar ) { 
            $data_depuracion = $this->Kit_model->depurar($asignacion_id);
            $data = array_merge($data,$data_depuracion);
        }

        $data['status'] = 1;
        
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
//CARGUE MASIVO DE ELEMENTOS
//---------------------------------------------------------------------------------------------------
    
    function importar_elementos($kit_id)
    {
        $data = $this->Kit_model->basico($kit_id);
        
        //Solicitar vista
            $data['destino_form'] = "kits/importar_elementos_e/{$kit_id}";
            //$data['ayuda_id'] = 158;
            $data['view_a'] = 'kits/importar_elementos_v';
            $this->load->view(TPL_ADMIN_NEW, $data);   
    }
    
    function importar_elementos_e($kit_id)
    {
        //Cargando datos básicos (basico)
            $data = $this->Kit_model->basico($kit_id);
        
        //Proceso
            $this->load->model('Pcrn_excel');
            $no_importados = array();
            $letra_columna = 'B';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] ) {
                $no_importados = $this->Kit_model->importar_elementos($kit_id, $resultado['array_hoja']);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $no_importados;
            $data['destino_volver'] = "kits/instituciones/{$kit_id}";
        
        //Cargar vista
            $data['view_a'] = 'comunes/resultado_importacion_v';
            $data['head_subtitle'] = 'Resultado cargue';
            $this->load->view(TPL_ADMIN_NEW, $data);
    }    
}