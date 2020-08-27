<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instituciones extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('Institucion_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($institucion_id)
    {
        redirect("instituciones/grupos/{$institucion_id}");
    }

//EXPLORACIÓN
//---------------------------------------------------------------------------------------------------

    /** Exploración de Instituciones */
    function explorar($num_page = 1)
    {
        //Identificar filtros de búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Institucion_model->explore_data($filters, $num_page);
        
        //Opciones de filtros de búsqueda
            $data['options_city'] = $this->App_model->options_place('tipo_id = 4', 'full_name', 'Todas');
            
        //Arrays con valores para contenido en lista
            //$data['arr_types'] = $this->Item_model->arr_cod('category_id = 33');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN_NEW, $data);
    }

    /**
     * Listado de Instituciones, filtrados por búsqueda, JSON
     */
    function get($num_page = 1)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Institucion_model->get($filters, $num_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    
    function explorar_ant()
    {
        $this->load->model('Busqueda_model');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Institucion_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(2);
            $config['per_page'] = 10;
            $config['base_url'] = base_url() . "instituciones/explorar/?{$busqueda_str}";
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Institucion_model->buscar($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Instituciones';
            $data['subtitulo_pagina'] = $data['cant_resultados'];
            $data['vista_a'] = 'instituciones/explorar_v';
            $data['vista_menu'] = 'instituciones/explorar_menu_v';
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
            $resultados_total = $this->Institucion_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Preparar datos
            $datos['nombre_hoja'] = 'Instituciones';
            $datos['query'] = $resultados_total;
            
        //Preparar archivo
            $objWriter = $this->Pcrn_excel->archivo_query($datos);
        
        $data['objWriter'] = $objWriter;
        $data['nombre_archivo'] = date('Ymd_His'). '_instituciones'; //save our workbook as this file name
        
        $this->load->view('comunes/descargar_phpexcel_v', $data);
            
    }
    
    /**
     * Formulario GroceryCrud para creación de instituciones
     */
    function nuevo()
    {
        
        //Render del grocery crud
            $gc_output = $this->Institucion_model->crud_editar();
        
        //Array data espefícicas
            $data['head_title'] = 'Instituciones';
            $data['head_subtitle'] = 'Nueva';
            $data['view_a'] = 'comunes/gc_v';
            $data['nav_2'] = 'instituciones/explore/menu_v';
        
        $output = array_merge($data,(array)$gc_output);
        
        $this->load->view(TPL_ADMIN_NEW, $output);
    }
    
    function editar()
    {
        //Cargando datos básicos
            $institucion_id = $this->uri->segment(4);
            $data = $this->Institucion_model->basico($institucion_id);
            
        //Render del grocery crud
            $gc_output = $this->Institucion_model->crud_editar();

        //Solicitar vista
            $data['subtitulo_pagina'] = 'Editar';
            $data['vista_b'] = 'comunes/gc_v';
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(PTL_ADMIN, $output);
    }
    
    function eliminar_pre($institucion_id)
    {
        $data = $this->Institucion_model->basico($institucion_id);
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Eliminar';
            $data['vista_b'] = 'instituciones/eliminar_pre_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function eliminar($institucion_id)
    {
        $this->Institucion_model->eliminar($institucion_id);
        $destino = "instituciones/explorar/";
        redirect($destino);
    }
    
//GESTIÓN DE USUARIOS Y ESTUDIANTES
//---------------------------------------------------------------------------------------------------
    
    /**
     * Listado de usuarios (no estudiantes) de la Institución
     * 
     * @param type $institucion_id
     */
    function usuarios($institucion_id)
    {
        $this->load->model('Esp');
        $this->load->model('Evento_model');
        
        //Cargando datos básicos ($basico)
        if ( in_array($this->session->userdata('rol_id'), array(3,4,5)) ) { $institucion_id = $this->session->userdata('institucion_id'); }
        $data = $this->Institucion_model->basico($institucion_id);
        
        $data['usuarios'] = $this->Institucion_model->usuarios($institucion_id);
        
        $this->load->model('Grupo_model');
        
        //Solicitar vista
            $data['subseccion'] = 'usuarios';
            $data['vista_b'] = 'instituciones/usuarios_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function procesar_usuarios($institucion_id)
    {
     
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Usuario_model');
        $resultado['num_procesados'] = 0;
        $usuarios_arr = array();
        
        $procesos = array(
            1 => 'Activar',
            2 => 'Desactivar',
            3 => 'Restaurar contraseña',
            4 => 'Eliminar'
        );
        
        $cod_proceso = str_replace('p', '', $this->input->post('proceso') );
        $resultado['proceso'] = $procesos[$cod_proceso];
        
        //Se carga la lista de usuarios que pertenecen una institucion
        $usuarios = $this->Institucion_model->usuarios($institucion_id);
        
        foreach ($usuarios->result() as $row_usuario){
            if ( $this->input->post($row_usuario->usuario_id) ){
                $usuarios_arr[] = $row_usuario->usuario_id;
            }
        }
        
        $resultado['mensaje'] = 'Usuarios procesados: ' . count($usuarios_arr);
        $this->Usuario_model->procesar_usuarios($usuarios_arr, $cod_proceso);
        
        $this->session->set_flashdata('resultado', $resultado);
        
        redirect("instituciones/usuarios/{$institucion_id}");
        
    }
    
// IMPORTACIÓN DE USUARIOS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Mostrar formulario de importación de usuarios (no estudiantes) con archivo MS Excel.
     * El resultado del formulario se envía a 'instituciones/importar_usuarios_e'
     * 
     */
    function importar_usuarios($institucion_id)
    {
        $data = $this->Institucion_model->basico($institucion_id);
        
        //Iniciales
            $nombre_archivo = '02_formato_cargue_usuarios.xlsx';
            $parrafos_ayuda = array(
                'Si la casilla "apellidos" (columna B) se encuentra vacía el usuario no será creado.'
            );
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo importar usuarios?';
            $data['nota_ayuda'] = 'Se importarán usuarios a la institución ' . $data['row']->nombre_institucion;
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = "instituciones/importar_usuarios_e/{$institucion_id}";
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'usuarios';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            $data['subtitulo_pagina'] = 'Importar usuarios';
            $data['vista_b'] = 'comunes/importar_v';
            $data['vista_submenu'] = 'instituciones/submenu_usuarios_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Importar estudiantes, (e) ejecutar.
     */
    function importar_usuarios_e($institucion_id)
    {
        $data = $this->Institucion_model->basico($institucion_id);
        
        //Proceso
            $this->load->model('Pcrn_excel');
            //$no_importados = array();
            $letra_columna = 'G';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $this->load->model('Institucion_model');
                $res_importacion = $this->Institucion_model->importar_usuarios($resultado['array_hoja'], $institucion_id);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $res_importacion['no_importados'];
            $data['importados'] = $res_importacion['importados'];
            $data['vista_importados'] = 'instituciones/importar_usuarios_r_v';
            $data['destino_volver'] = "instituciones/usuarios/{$institucion_id}";
        
        //Cargar vista
            $data['subtitulo_pagina'] = 'Resultado importación';
            $data['vista_b'] = 'comunes/resultado_importacion_v';
            $data['vista_submenu'] = 'instituciones/submenu_usuarios_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
// CARGA MASIVA DE USUARIOS
//---------------------------------------------------------------------------------------------------
    /**
     * Mostrar formulario de cargue de usuarios mediante archivos de excel.
     * El resultado del formulario se envía a 'institucions/resultado_cargue'    
     * 
     * @param type $institucion_id
     */
    function cargar_usuarios($institucion_id)
    {
        //Cargando datos básicos (_basico)
        $data = $this->Institucion_model->basico($institucion_id);
        
        //Calcular usuarios
        $this->db->where('rol_id IN (3, 4, 5)');
        $this->db->where('institucion_id', $institucion_id);
        $data['usuarios'] = $this->db->get('usuario');
        
        $data['ayuda_id'] = 32;
        $data['subseccion'] = 'cargue';
        $data['titulo_pagina'] = 'Cargar usuarios al institucion';
        $data['vista_b'] = "instituciones/cargar_usuarios_v";
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function procesar_cargue($institucion_id)
    {
        $this->load->library('form_validation');
        
        //Reglas
            $this->form_validation->set_rules('nombre_hoja', 'Nombre hoja', 'required');
            //$this->form_validation->set_rules('file', 'Archivo', 'required');
        
        //Mensajes de validación
            $this->form_validation->set_message('required', "%s no puede quedar vacío");
        
        //Comprobar validación
            if ( $this->form_validation->run() == FALSE ){
                //No se cumple la validación, se regresa al cuestionario
                $this->cargar_usuarios($institucion_id);
            } else {
                //Se cumple la validación, 
                $this->resultado_cargue($institucion_id);
            }    
    }
    
    function resultado_cargue($institucion_id)
    {
        
        //Cargando datos básicos (_basico)
        $data = $this->Institucion_model->basico($institucion_id);
        
        //Variables
        $usuarios_insertados = array();
        
        $archivo = $_FILES['file']['tmp_name'];    //Se crea un archivo temporal, no se sube al servidor, se toma el nombre temporal
        $nombre_hoja = $this->input->post('nombre_hoja');   //Nombre de hoja digitada por el usuario en el formulario
        
        $this->load->model('Pcrn_excel');
        $resultado = $this->Pcrn_excel->array_hoja($archivo, $nombre_hoja, 'F');
        $usuarios = $resultado['array_hoja'];
        
        if ( $resultado['cargado'] ) {
            $this->load->model('Usuario_model');
            $usuarios_insertados = $this->Usuario_model->insert_usuarios_inst($institucion_id, $usuarios);
        }
        
        //Cargue de variabls
            $data['cargado'] = $resultado['cargado'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['usuarios'] = $usuarios;
            $data['nombre_hoja'] = $nombre_hoja;
            $data['usuarios_insertados'] = $usuarios_insertados;
        
        //Cargar vista
            $data['subseccion'] = 'cargue';
            $data['vista_b'] = 'instituciones/resultado_cargue_v';
            $data['subtitulo_pagina'] = 'Cargue de usuarios';
            $this->load->view(PTL_ADMIN, $data);
    }
    
// IMPORTACIÓN MASIVA DE ESTUDIANTES
//---------------------------------------------------------------------------------------------------
    
    /**
     * DESACTIVADA DEL MENÚ 2018-10-19
     * Mostrar formulario de importación de estudiantes con archivo MS Excel.
     * El resultado del formulario se envía a 'instituciones/importar_estudiantes_e'
     * 
     */
    function importar_estudiantes($institucion_id)
    {
        $data = $this->Institucion_model->basico($institucion_id);
        
        //Iniciales
            $nombre_archivo = '13_formato_cargue_estudiantes.xlsx';
            $parrafos_ayuda = array(
                'Si la casilla "apellidos" (columna B) se encuentra vacía el estudiante no será creado.'
            );
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo importar estudiantes?';
            $data['nota_ayuda'] = 'Se importarán estudiantes a la institución ' . $data['row']->nombre_institucion;
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = "instituciones/importar_estudiantes_e/{$institucion_id}";
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'usuarios';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            $data['ayuda_id'] = 97;
            $data['subtitulo_pagina'] = 'Importar estudiantes';
            $data['vista_b'] = 'comunes/importar_v';
            $data['vista_submenu'] = 'instituciones/grupos/submenu_grupos_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * DESACTIVADA DEL MENÚ 2018-10-19
     * Importar estudiantes, (e) ejecutar.
     */
    function importar_estudiantes_e($institucion_id)
    {
        $data = $this->Institucion_model->basico($institucion_id);
        
        //Proceso
            $this->load->model('Pcrn_excel');
            //$no_importados = array();
            $letra_columna = 'G';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $this->load->model('Institucion_model');
                $res_importacion = $this->Institucion_model->importar_estudiantes($resultado['array_hoja'], $institucion_id);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $res_importacion['no_importados'];
            $data['importados'] = $res_importacion['importados'];
            $data['destino_volver'] = "instituciones/grupos/{$institucion_id}";
            $data['vista_importados'] = 'instituciones/importar_estudiantes_r_v';
        
        //Cargar vista
            $data['ayuda_id'] = 97;
            $data['subtitulo_pagina'] = 'Resultado importación';
            $data['vista_b'] = 'comunes/resultado_importacion_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
//GESTIÓN DE GRUPOS
//---------------------------------------------------------------------------------------------------
    
    function grupos($institucion_id = NULL, $anio_generacion = NULL)
    {
        //$this->output->enable_profiler(TRUE);
        //Cargando datos básicos ($basico)
            if ( in_array($this->session->userdata('rol_id'), array(3,4,5) ) ) { $institucion_id = $this->session->userdata('institucion_id'); }
            $data = $this->Institucion_model->basico($institucion_id);
        
        //Grupos
            //if ( is_null($anio_generacion) ) { $anio_generacion = $this->App_model->anio_institucion($institucion_id); }
            $data['grupos'] = $this->Institucion_model->grupos($institucion_id, $anio_generacion);
            
        //Años
            $this->db->select('anio_generacion');
            $this->db->where('institucion_id', $institucion_id);
            $this->db->group_by('anio_generacion');
            $this->db->order_by('anio_generacion', 'DESC');
            $data['anios'] = $this->db->get('grupo');
            
        //Data
            $data['anio_generacion'] = $anio_generacion;
        
        //Solicitar vista
            $data['subseccion'] = 'listado';
            $data['vista_b'] = 'instituciones/grupos/grupos_v';
            $data['vista_menu'] = 'instituciones/institucion_v';
            $this->load->view(PTL_ADMIN, $data);
    }    

    //Ajustada en 2015-07-16, error de Grocery Crud
    function nuevo_grupo()
    {
        $this->load->model('Grupo_model');
        
        //Cargando datos básicos
            $institucion_id = $this->uri->segment(4);
            $row = $this->Pcrn->registro_id('institucion', $institucion_id);
            
            $data['row'] = $row;
            $data['titulo_pagina'] = 'Institución';
            $data['vista_a'] = 'instituciones/institucion_v';
            
        //Render del grocery crud
            $gc_output = $this->Grupo_model->crud_basico($institucion_id);
            
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Nuevo grupo';
            $data['vista_b'] = 'comunes/gc_v';
            $data['vista_menu'] = 'instituciones/grupos/submenu_grupos_v';
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(PTL_ADMIN, $output);
    }
    
// IMPORTAR GRUPOS A LA INSTITUCIÓN
//-----------------------------------------------------------------------------
    
    /**
     * Mostrar formulario de importación de grupos a la institución con archivo 
     * Excel. El resultado del formulario se envía a 
     * instituciones/importar_grupos_e
     * 
     */
    function importar_grupos($institucion_id)
    {
        $data = $this->Institucion_model->basico($institucion_id);
        
        //Iniciales
            $nombre_archivo = '12_formato_cargue_grupos.xlsx';
            $parrafos_ayuda = array(
                'Las columnas [nivel], [grupo] no pueden estar vacías.',
            );
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo importar grupos a la institución?';
            $data['nota_ayuda'] = 'Se importarán los grupos a la institución.';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = "instituciones/importar_grupos_e/{$institucion_id}";
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'grupos';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            //$data['ayuda_id'] = 97;
            $data['subtitulo_pagina'] = 'Importar grupos';
            $data['vista_b'] = 'comunes/importar_v';
            $data['vista_submenu'] = 'instituciones/grupos/submenu_grupos_v';
        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Importar grupos a institución, (e) ejecutar.
     */
    function importar_grupos_e($institucion_id)
    {
        $data = $this->Institucion_model->basico($institucion_id);
        
        //Proceso
            $this->load->model('Pcrn_excel');
            $this->load->model('Esp');
            $letra_columna = 'B';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $res_importacion = $this->Institucion_model->importar_grupos($resultado['array_hoja'], $institucion_id);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $res_importacion['no_importados'];
            $data['destino_volver'] = "instituciones/grupos/{$institucion_id}";
        
        //Cargar vista
            //$data['titulo_pagina'] = 'Grupos';
            $data['subtitulo_pagina'] = 'Resultado importación de grupos';
            $data['vista_b'] = 'comunes/resultado_importacion_v';
            //$data['vista_menu'] = 'grupos/explorar/menu_v';
            $data['vista_submenu'] = 'instituciones/grupos/submenu_grupos_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function z_cargar_grupos($institucion_id)
    {
        $data = $this->Institucion_model->basico($institucion_id);
        
        //Solicitar vista
            $data['ayuda_id'] = 150;
            $data['vista_b'] = 'instituciones/grupos/cargar_grupos_v';
            $data['vista_menu'] = 'instituciones/grupos/submenu_grupos_v';
            $data['subseccion'] = 'cargar_grupos';
            $this->load->view(PTL_ADMIN, $data);   
    }
    
    function z_cargar_grupos_e($institucion_id)
    {
        $data = $this->Institucion_model->basico($institucion_id);
        
        //Variables
        $cargados = array();
        $no_cargados = array();
        $mensaje = '';
        
        $archivo = $_FILES['file']['tmp_name'];    //Se crea un archivo temporal, no se sube al servidor, se toma el nombre temporal
        $nombre_hoja = $this->input->post('nombre_hoja');   //Nombre de hoja digitada por el tema en el formulario
        
        $this->load->model('Pcrn_excel');
        $resultado = $this->Pcrn_excel->array_hoja($archivo, $nombre_hoja, 'B');    //Hasta la columna B de la hoja de cálculo
        $grupos = $resultado['array_hoja'];
        
        if ( $resultado['cargado'] ) {
            $this->load->model('Tema_model');
            $resultado_cargue = $this->Institucion_model->cargar_grupos($institucion_id, $grupos);
            $cargados = $resultado_cargue['cargados'];
            $no_cargados = $resultado_cargue['no_cargados'];
            $mensaje = 'Se cargaron ' .  count($cargados) . ' grupos';
        } else {
            $mensaje = $resultado['mensaje'];
        }
        
        //Cargue de variabls
            $data['cargado'] = $resultado['cargado'];
            $data['mensaje'] = $mensaje;
            $data['temas'] = $grupos;
            $data['nombre_hoja'] = $nombre_hoja;
            $data['cargados'] = $cargados;
            $data['no_cargados'] = $no_cargados;
            $data['num_cargados'] = count($cargados);
            $data['num_no_cargados'] = count($no_cargados);
        
        //Cargar vista
            $data['subseccion'] = 'cargar_grupos';
            $data['vista_b'] = 'instituciones/grupos/cargar_grupos_r_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
// VACIAR GRUPOS
//-----------------------------------------------------------------------------
    
    /**
     * Mostrar formulario de importación de lista de grupos mediante archivo MS Excel.
     * Se eliminarán los estudiantes de los grupos en la lista del archivo de excel.
     * El resultado del formulario se envía a 'instituciones/vaciar_grupos_e'
     * 
     * @param type $institucion_id
     */
    function vaciar_grupos($institucion_id)
    {
        
        $data = $this->Institucion_model->basico($institucion_id);
        
        //Iniciales
            $nombre_archivo = '22_formato_vaciar_grupos.xlsx';
            $parrafos_ayuda = array();
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo vaciar grupos?';
            $data['nota_ayuda'] = 'Se eliminarán los estudiantes de los grupos en lista. También se eliminan los usuarios y sus datos en la plataforma.';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = "instituciones/vaciar_grupos_e/{$institucion_id}";
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'grupos_vaciar';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            $data['subtitulo_pagina'] = 'Vaciar grupos';
            $data['vista_b'] = 'comunes/importar_v';
            $data['vista_menu'] = 'instituciones/grupos/submenu_grupos_v';
            $data['ayuda_id'] = 138;
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Leer grupos en lista y eliminar los usuarios estudiantes asignados, (e) ejecutar.
     */
    function vaciar_grupos_e($institucion_id)
    {
        $data = $this->Institucion_model->basico($institucion_id);
        
        //Proceso
            $this->load->model('Pcrn_excel');
            $this->load->model('Grupo_model');
            $this->load->model('Usuario_model');
            $no_importados = array();
            $letra_columna = 'A';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $no_importados = $this->Grupo_model->vaciar_grupos($resultado['array_hoja']);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $no_importados;
            $data['destino_volver'] = "instituciones/grupos/{$institucion_id}";
        
        //Cargar vista
            $data['subtitulo_pagina'] = 'Resultado vaciado';
            $data['vista_b'] = 'comunes/resultado_importacion_v';
            $data['vista_menu'] = 'instituciones/grupos/submenu_grupos_v';
            $data['ayuda_id'] = 138;
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Mostrar formulario de asignación de profesores a grupos con archivo MS Excel.
     * El resultado del formulario se envía a 'instituciones/asignar_profesores_e'
     * 
     */
    function asignar_profesores($institucion_id)
    {
        $data = $this->Institucion_model->basico($institucion_id);
        
        //Iniciales
            $nombre_archivo = '14_formato_asignacion_profesores.xlsx';
            $parrafos_ayuda = array(
                'Si la casilla "ID grupo" (columna A) se encuentra vacía el profesor no será asignado.'
            );
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo asignar profesores a grupos?';
            $data['nota_ayuda'] = 'Se importará la asignación de profesores a grupos de la institución ' . $data['row']->nombre_institucion;
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = "instituciones/asignar_profesores_e/{$institucion_id}";
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'profesores';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            $data['subtitulo_pagina'] = 'Asignar profesores';
            $data['vista_b'] = 'comunes/importar_v';
            $data['vista_submenu'] = 'instituciones/grupos/submenu_grupos_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Asignar profesores, (e) ejecutar.
     */
    function asignar_profesores_e($institucion_id)
    {
        $data = $this->Institucion_model->basico($institucion_id);
        
        //Proceso
            $this->load->model('Pcrn_excel');
            $no_importados = array();
            $letra_columna = 'C';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $this->load->model('Institucion_model');
                $no_importados = $this->Institucion_model->asignar_profesores($resultado['array_hoja'], $institucion_id);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $no_importados;
            $data['destino_volver'] = "instituciones/grupos/{$institucion_id}";
        
        //Cargar vista
            $data['subtitulo_pagina'] = 'Resultado asignación';
            $data['vista_b'] = 'comunes/resultado_importacion_v';
            $data['vista_submenu'] = 'instituciones/grupos/submenu_grupos_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
//CUESTIONARIOS Y RESULTADOS
//---------------------------------------------------------------------------------------------------
    
    function cuestionarios($institucion_id)
    {
        //$this->output->enable_profiler(TRUE);
        $data = $this->Institucion_model->basico($institucion_id);
        $this->load->model('Busqueda_model');
        $this->load->model('Cuestionario_model');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            
        //Filtro especial
            $condicion = "id IN (SELECT cuestionario_id FROM usuario_cuestionario WHERE institucion_id = {$institucion_id})";
            
            $busqueda['condicion'] = $condicion;
        
        //Paginación
            $resultados_total = $this->Cuestionario_model->buscar($busqueda); //Para calcular el total de resultados
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(2);
            $config['base_url'] = base_url("instituciones/cuestionarios/{$institucion_id}/?{$busqueda_str}");
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Cuestionario_model->buscar($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['cuestionarios'] = $resultados;
        
        //Solicitar vista
            //$data['seccion'] = 'explorar';
            $data['subtitulo_pagina'] = "({$config['total_rows']})";
            $data['vista_b'] = 'instituciones/cuestionarios/cuestionarios_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function cuestionarios_resumen01($institucion_id, $area_id = 50, $nivel = 1)
    {
        
        $this->load->model('Cuestionario_model');
        
        //Cargando datos básicos (_basico)
            $data = $this->Institucion_model->basico($institucion_id);
        
        //Head includes específicos para esta función
            $head_includes[] = 'highcharts';
            
        //Cuestionarios
            $this->db->select('cuestionario_id');
            $this->db->join('cuestionario', 'dw_usuario_pregunta.cuestionario_id = cuestionario.id');
            $this->db->where('dw_usuario_pregunta.institucion_id', $institucion_id);
            $this->db->where('dw_usuario_pregunta.area_id', $area_id);
            $this->db->where('cuestionario.nivel', $nivel);
            $this->db->where('tipo_id IN (1, 2, 3)'); //Solo cuestionarios internos de Enlace
            $this->db->where('anio_generacion', $this->session->userdata('anio_usuario'));
            $this->db->group_by('cuestionario_id');
            $cuestionarios = $this->db->get('dw_usuario_pregunta');
            
        
        //$data Específico
            $data['areas'] = $this->db->get_where('item', "id IN (50, 51, 52, 53)");
            $data['niveles'] = $this->Item_model->arr_interno('categoria_id = 3 AND id_interno >= 1');
            $data['institucion_id'] = $institucion_id;
            $data['area_id'] = $area_id;
            $data['nivel'] = $nivel;
            $data['subseccion'] = 'resumen01';
            $data['head_includes'] = $head_includes;
            $data['cuestionarios'] = $cuestionarios;
            $data['competencias'] = $this->Cuestionario_model->competencias_area($area_id); //Query competencias
            $data['vista_b'] = 'instituciones/res01_v';
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Desempeño histórico en cuestionarios';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    /**
     * Gráfico de desempeño del grupo por competencias
     * agrupado por acumulador (usuario_pregunta.acumulador)
     */
    function cuestionarios_resumen02($institucion_id, $area_id = 50, $nivel = NULL)
    {
        //$this->output->enable_profiler(TRUE);
        
        $this->load->model('Cuestionario_model');
        
        //Cargando datos básicos (_basico)
            $data = $this->Institucion_model->basico($institucion_id);
        
        //Head includes específicos para esta función
            $head_includes[] = 'highcharts';
            
        //Array competencias
            $this->db->select('id AS competencia_id, item AS nombre_competencia');
            $this->db->where('item_grupo', $area_id);
            $this->db->where('abreviatura IS NOT NULL');
            $competencias = $this->db->get('item');
            
            $nombres_competencias = array();
            foreach ($competencias->result() AS $row_competencia) {
                $nombres_competencias[$row_competencia->competencia_id] = $row_competencia->nombre_competencia;
            }
            
        //Calcular cantidad de acumuladores
            $filtros['usuario_cuestionario.institucion_id'] = $institucion_id;
            $filtros['area_id'] = $area_id;
            $cant_acumuladores = $this->Cuestionario_model->cant_acumuladores($filtros);
            
        
        //$data Específico
            $data['areas'] = $this->db->get_where('item', "categoria_id = 1 AND item_grupo = 1");
            $data['niveles'] = $this->Item_model->arr_interno('categoria_id = 3 AND id_interno >= 1');
            $data['area_id'] = $area_id;
            $data['nivel'] = $nivel;
            $data['institucion_id'] = $institucion_id;
            $data['cant_acumuladores'] = $cant_acumuladores;
            $data['subseccion'] = 'resumen02';
            $data['head_includes'] = $head_includes;
            $data['nombres_competencias'] = $nombres_competencias;
            $data['vista_b'] = 'instituciones/res02_v';
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Desempeño por competencias';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    /**
     * Gráfico de desempeño del grupo por competencias
     * agrupado por acumulador mixto (usuario_pregunta.acumulador_2)
     */
    function cuestionarios_resumen03($institucion_id, $area_id = 50, $nivel = NULL)
    {
        //$this->output->enable_profiler(TRUE);
        
        $this->load->model('Cuestionario_model');
        
        //Cargando datos básicos (_basico)
            $data = $this->Institucion_model->basico($institucion_id);
        
        //Head includes específicos para esta función
            $head_includes[] = 'highcharts';
            
        //Identificar acumuladores de la gráfica
            $filtros['usuario_cuestionario.institucion_id'] = $institucion_id;
            $filtros['area_id'] = $area_id;
            if ( ! is_null($nivel) ) { $filtros['nivel'] = $nivel; }
            $acumuladores = $this->Cuestionario_model->acumuladores_2($filtros);
        
        //$data Específico
            $data['areas'] = $this->db->get_where('item', "categoria_id = 1 AND item_grupo = 1");
            $data['niveles'] = $this->Item_model->arr_interno('categoria_id = 3 AND id_interno >= 1');
            $data['area_id'] = $area_id;
            $data['nivel'] = $nivel;
            $data['acumuladores'] = $acumuladores;
            $data['institucion_id'] = $institucion_id;
            $data['subseccion'] = 'resumen03';
            $data['head_includes'] = $head_includes;
            $data['competencias'] = $this->Cuestionario_model->competencias_area($area_id); //Query competencias
            $data['vista_b'] = 'instituciones/res03_v';
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Desempeño por competencias';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    /**
     * Muestra el resultado obtenido por los grupos de una institución en la ejecución de un cuestionario,
     * los resultados se muestran en un gráfico.
     */
    function resultados_grupo($institucion_id, $cuestionario_id = NULL)
    {
        //Cargando datos básicos ($basico)
        $data = $this->Institucion_model->basico($institucion_id);
        $data['cuestionarios'] = $this->Institucion_model->cuestionarios($institucion_id);
        
        //Verificar si la institución tiene cuestionarios asociados
        if ( $data['cuestionarios']->num_rows() > 0 )
        {
            //Sí tiene, cuestionario asosciados
            $cuestionario_id = $this->Pcrn->si_nulo($cuestionario_id, $data['cuestionarios']->row()->id);
            $vista_b = 'instituciones/cuestionarios/resultados_grupo_v';
            
            //Head includes específicos para la página, para gráficos
                $head_includes[] = 'highcharts';
                $head_includes[] = 'grafico_grupo';
                $data['head_includes'] = $head_includes;

            //Variables para el gráfico

                //Obtener información del cuestionario
                $this->load->model('Cuestionario_model');
                $data['row_cuestionario'] = $this->Cuestionario_model->datos_cuestionario($cuestionario_id);

                $data['titulo_grafico'] = $data['row_cuestionario']->nombre_cuestionario;
                $data['num_preguntas'] = $data['row_cuestionario']->num_preguntas;
                $grupos = $this->Institucion_model->grupos_cuestionario($institucion_id, $cuestionario_id);

                //Se carga para cada grupo, un array de resultados
                foreach ($grupos->result() as $row) {
                    $resultados[$row->grupo_id] = $this->App_model->res_cuestionario($cuestionario_id, "grupo_id = {$row->grupo_id}");
                }

                //Se carga un array con el valor de las preguntas correctas
                foreach ( $resultados as $value )
                {
                    $correctas[] = $value['correctas'];
                }

            //Cargando array $data
                $data['institucion_id'] = $institucion_id;
                $data['grupos'] = $grupos;
                $data['correctas'] = $correctas;
                $data['resultados'] = $resultados;
                $data['menu_sub'] = 'instituciones/cuestionarios/submenu_v';
                $data['subseccion'] = 'resultados_grupo';
                $data['cuestionario_id'] = $cuestionario_id;
        }
        else
        {
            
            //La institución no tiene cuestionarios asignados
            $data['mensaje'] = 'Los estudiantes de esta institución no tienen cuestionarios asignados';
            $vista_b = 'app/mensaje_v';
        }
        
        //Solicitar vista
            $data['titulo_pagina'] = $data['titulo_pagina'];
            $data['subtitulo_pagina'] = 'Resultados por grupo';
            $data['vista_b'] = $vista_b;
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    
    /**
     * Muestra el resultado obtenido por los grupos de una institución en la ejecución de un cuestionario,
     * los resultados se muestran en un gráfico.
     *
     * @param type $institucion_id
     * @param type $cuestionario_id
     */
    function resultados_area($institucion_id, $cuestionario_id = NULL)
    {
        
        //$this->output->enable_profiler(TRUE);
        
        //Cargando datos básicos ($basico)
        $data = $this->Institucion_model->basico($institucion_id);
        
        //Head includes específicos para la página, para gráficos
            $head_includes[] = 'highcharts';
            $head_includes[] = 'grafico_area';
            $data['head_includes'] = $head_includes;
        
        //Variables para el gráfico
            
            //Obtener información del cuestionario
            $this->load->model('Cuestionario_model');
            $data['row_cuestionario'] = $this->Cuestionario_model->datos_cuestionario($cuestionario_id);
            
            $data['titulo_grafico'] = $data['row_cuestionario']->nombre_cuestionario;
            $data['num_preguntas'] = $data['row_cuestionario']->num_preguntas;
            $areas = $this->Cuestionario_model->areas($cuestionario_id);

            //Se carga para cada área, un array de resultados
            foreach ($areas->result() as $row) {
                $resultados[$row->area_id] = $this->Cuestionario_model->resultado($cuestionario_id, "institucion_id = {$institucion_id}", "area_id = {$row->area_id}");
            }
            
            //Se carga un array con el valor de las preguntas correctas
            foreach ( $resultados as $key => $value ) {
                $correctas[] = $value['correctas'];
            }
            
            foreach ( $resultados as $key => $value ){
                $num_preguntas_area[] = $value['num_preguntas'];
            }
            
        //Cargando array $data
            $data['institucion_id'] = $institucion_id;
            $data['cuestionarios'] = $this->Institucion_model->cuestionarios($institucion_id);
            $data['areas'] = $areas;
            $data['correctas'] = $correctas;
            $data['num_preguntas_area'] = $num_preguntas_area;
            $data['resultados'] = $resultados;
            $data['menu_sub'] = 'instituciones/cuestionarios/submenu_v';
            $data['subseccion'] = 'resultados_grupo';
            $data['cuestionario_id'] = $cuestionario_id;
            
        
        //Solicitar vista
        $data['subtitulo_pagina'] = 'Resultados por área';
        $data['vista_b'] = 'instituciones/cuestionarios/resultados_area_v';
        $this->load->view(PTL_ADMIN, $data);
        
    }
    
    /* Se muestran las listas de estudiantes y sus resultados en los diferentes cuestionarios
    * Se filtran por cuestionario, grupo.
    */    
    function resultados_lista($institucion_id, $cuestionario_id = NULL, $grupo_id = NULL)
    {    
        
        $this->output->enable_profiler(TRUE);
        
        //Cargando datos básicos
            $data = $this->Institucion_model->basico($institucion_id);
            $cuestionario_id = 0;
            $grupo_id = 0;
        
        //Cuestionarios con los que están asociados estudiantes de la institucion (usuario_cuestionraio)
            $anio_generacion = $this->session->userdata('anio_usuario');
            $data['cuestionarios_grupos'] = $this->Institucion_model->cuestionarios_grupos($institucion_id, $anio_generacion);
        
        //Definiendo segundo y tercer argumento de la función
            if ( $data['cuestionarios_grupos']->num_rows() > 0 ){
                //La institución está asociada a al menos un cuestionario
                $grupo_id = $this->Pcrn->si_nulo($grupo_id, $data['cuestionarios_grupos']->row()->grupo_id);
                $cuestionario_id = $this->Pcrn->si_nulo($cuestionario_id, $data['cuestionarios_grupos']->row()->cuestionario_id);
            }
        
        //Obtener información del cuestionario
            $this->load->model('Cuestionario_model');
            $data['row_cuestionario'] = $this->Cuestionario_model->datos_cuestionario($cuestionario_id);
        
        $data['lista'] = $this->Institucion_model->resultados_lista($grupo_id, $cuestionario_id);
        
        //Cargando array $data
            $data['grupo_id'] = $grupo_id;
            $data['cuestionario_id'] = $cuestionario_id;
            $data['menu_sub'] = 'instituciones/menu_sub_lista_v';
        
        //Solicitar vista
            $data['subseccion'] = 'resultados_lista';
            $data['vista_b'] = 'instituciones/resultados_lista_v';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    /**
     * Muestra el resultado obtenido por los grupos de una institución en la ejecución de un cuestionario,
     * los resultados se muestran en un gráfico.
     * 
     * @param type $institucion_id
     * @param type $cuestionario_id
     * @param type $area_id
     */
    function resultados_componente($institucion_id, $cuestionario_id, $area_id = NULL)
    {
                
        //Cargando datos básicos ($basico)
            $data = $this->Institucion_model->basico($institucion_id);
        
        //Definiendo segundo argumento de la función
            $cuestionarios = $this->Institucion_model->cuestionarios($institucion_id);
            $cuestionario_id = $this->Pcrn->si_nulo($cuestionario_id, $cuestionarios->row()->id, $cuestionario_id);
        
        //Definiendo tercer argumento de la función
            $this->load->model('Cuestionario_model');
            $areas = $this->Cuestionario_model->areas($cuestionario_id);
            $area_id = $this->Pcrn->si_nulo($area_id, $areas->row()->area_id);
        
        //Head includes específicos para la página, para gráficos
            $head_includes[] = 'highcharts';
            $head_includes[] = 'grafico_componentes';
            $data['head_includes'] = $head_includes;
        
        //Variables para el gráfico
            
            //Obtener información del cuestionario
            $data['row_cuestionario'] = $this->Cuestionario_model->datos_cuestionario($cuestionario_id);
            $data['titulo_grafico'] = $data['row_cuestionario']->nombre_cuestionario .  " - " . $this->App_model->nombre_item($area_id, 1);
            $data['num_preguntas'] = $data['row_cuestionario']->num_preguntas;

            $componentes = $this->Cuestionario_model->componentes($cuestionario_id, $area_id);
            
            //Se carga para cada componente, un array de resultados
            $resultados = array();
            foreach ($componentes->result() as $row_componente) {
                $resultados[$row_componente->componente_id] = $this->App_model->res_cuestionario($cuestionario_id, "usuario.institucion_id = {$institucion_id}", "componente_id = {$row_componente->componente_id}");
                //$resultados[$row_componente->componente_id] = $this->Cuestionario_model->resultado($cuestionario_id, "institucion_id = {$institucion_id}", "componente_id = {$row_componente->componente_id}");
            }

            //Se carga un array con el valor de las preguntas correctas
            $correctas = array();
            $num_preguntas_componente = array();
            foreach ( $resultados as $value ){
                $correctas[] = $value['correctas'];
                $num_preguntas_componente[] = $value['num_preguntas'];
            }
            
            /*foreach ( $resultados as $value ){
                $num_preguntas_componente[] = $value['num_preguntas'];
            }*/
            
        //Cargando array $data
            $data['institucion_id'] = $institucion_id;
            $data['cuestionarios'] = $cuestionarios;
            $data['area_id'] = $area_id;
            $data['areas'] = $areas;
            $data['componentes'] = $componentes;
            $data['correctas'] = $correctas;
            $data['num_preguntas_componente'] = $num_preguntas_componente;
            $data['resultados'] = $resultados;
            $data['menu_sub'] = 'instituciones/cuestionarios/submenu_v';
            $data['subseccion'] = 'resultados_grupo';
            $data['cuestionario_id'] = $cuestionario_id;
            
        
        //Solicitar vista
        $data['titulo_pagina'] = "Resultados por componente | " . $data['titulo_pagina'];
        $data['vista_b'] = 'instituciones/cuestionarios/resultados_componentes_v';
        if ( count($resultados) == 0 ){
            //Si las preguntas del cuestionario no tienen definidos los componentes
            $data = array();
            $data['titulo_pagina'] = 'Sin componentes';
            $data['mensaje'] = 'Las preguntas de este cuestionario no tienen componentes definidos';    //Si no tienen componente
            $data['link_volver'] = "instituciones/resultados_grupo/{$institucion_id}/{$cuestionario_id}";
            $data['vista_a'] = 'app/mensaje_v';
        }
        
        $this->load->view(PTL_ADMIN, $data);
        
    }
    
    /**
     * Muestra el resultado obtenido por la institucionen la ejecución de un cuestionario,
     * los resultados se muestran en un gráfico. Clasificando los resultados por competencias
     * 
     * @param type $institucion_id
     * @param type $cuestionario_id
     * @param type $area_id
     */
    function resultados_competencia($institucion_id, $cuestionario_id, $area_id = NULL)
    {
        
        //Cargando datos básicos ($basico)
            $data = $this->Institucion_model->basico($institucion_id);
        
        //Definiendo segundo argumento de la función
            $cuestionarios = $this->Institucion_model->cuestionarios($institucion_id);
            $cuestionario_id = $this->Pcrn->si_nulo($cuestionario_id, $cuestionarios->row()->id, $cuestionario_id);
        
        //Definiendo tercer argumento de la función
            $this->load->model('Cuestionario_model');
            $areas = $this->Cuestionario_model->areas($cuestionario_id);
            $area_id = $this->Pcrn->si_nulo($area_id, $areas->row()->area_id, $area_id);
        
        $data['cuestionarios_grupos'] = $this->Institucion_model->cuestionarios_grupos($institucion_id);
        
        //Head includes específicos para la página, para gráficos
            $head_includes[] = 'highcharts';
            $head_includes[] = 'grafico_competencias';
            $data['head_includes'] = $head_includes;
        
        //Variables para el gráfico
            
            //Valores iniciales
                $resultados = array();
            
            //Obtener información del cuestionario
            $data['row_cuestionario'] = $this->Cuestionario_model->datos_cuestionario($cuestionario_id);
            $data['titulo_grafico'] = $data['row_cuestionario']->nombre_cuestionario .  " - " . $this->App_model->nombre_item($area_id, 1);;
            $data['num_preguntas'] = $data['row_cuestionario']->num_preguntas;

            $competencias = $this->Cuestionario_model->competencias($cuestionario_id, $area_id);
            
            //Se carga para cada competencia, un array de resultados
            foreach ($competencias->result() as $row_competencia) {
                //$resultados[$row_competencia->competencia_id] = $this->App_model->res_cuestionario($cuestionario_id, "usuario.institucion_id = {$institucion_id}", "competencia_id = {$row_competencia->competencia_id}");
                $resultados[$row_competencia->competencia_id] = $this->Cuestionario_model->resultado($cuestionario_id, "institucion_id = {$institucion_id}", "competencia_id = {$row_competencia->competencia_id}");
            }

            //Se carga un array con el valor de las preguntas correctas
            foreach ( $resultados as $value ){
                $correctas[] = $value['correctas'];
            }

            foreach ( $resultados as $value ){
                $num_preguntas_competencia[] = $value['num_preguntas'];
            }
            
        //Cargando array $data
            $data['institucion_id'] = $institucion_id;
            $data['cuestionarios'] = $cuestionarios;
            $data['area_id'] = $area_id;
            $data['areas'] = $areas;
            $data['competencias'] = $competencias;
            $data['correctas'] = $correctas;
            $data['num_preguntas_competencia'] = $num_preguntas_competencia;
            $data['resultados'] = $resultados;
            $data['menu_sub'] = 'instituciones/cuestionarios/submenu_v';
            $data['subseccion'] = 'resultados_grupo';
            $data['cuestionario_id'] = $cuestionario_id;
        
        //Solicitar vista
        $data['titulo_pagina'] = $data['titulo_pagina'] . " - Resultados por competencia";
        $data['vista_b'] = 'instituciones/cuestionarios/resultados_competencias_v';
        $this->load->view(PTL_ADMIN, $data);
        
    }
    
    function resctn_grupo($institucion_id)
    {
        $this->output->enable_profiler(TRUE);
        //Load
            $this->load->model('Busqueda_model');
            $this->load->model('Cuestionario_model');
            $this->load->model('Grupo_model');
        
        //Cargando datos básicos ($basico)
            $data = $this->Institucion_model->basico($institucion_id);
            
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            //$resultados_total = $this->Usuario_model->buscar($busqueda); //Para calcular el total de resultados
            
        //Variables específicas
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['grupos'] = $this->Grupo_model->buscar($busqueda);

        //Variables generales
            $data['subtitulo_pagina'] = 'Resultados Cuestionarios x Grupos';
            $data['vista_b'] = 'instituciones/cuestionarios/resctn_grupo_v';
            //$data['vista_menu'] = 'usuarios/explorar_menu_v';

        $this->load->view(PTL_ADMIN, $data);
    }
    
//FLIPBOOKS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Mostrar los flipbooks que han sido asignados a los estudiantes de una institución
     * @param type $institucion_id 
     */
    function flipbooks($institucion_id)
    {
        
        //Cargando datos básicos ($basico)
        if ( in_array($this->session->userdata('rol_id'), array(3,4,5)) ) { $institucion_id = $this->session->userdata('institucion_id'); }
        $data = $this->Institucion_model->basico($institucion_id);
        
        //Cargando array $data
            $data['subseccion'] = 'listado';
            $data['flipbooks'] = $this->Institucion_model->flipbooks($institucion_id);
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Contenidos';
            $data['vista_b'] = 'instituciones/flipbooks_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * AJAX
     * Elimina las asignaciones de flipbook a todos los usuarios de una institución
     * 
     */
    function quitar_flipbook()
    {
        $institucion_id = $this->input->post('institucion_id');
        $flipbook_id = $this->input->post('flipbook_id');
        
        $cant_registros = $this->Institucion_model->quitar_flipbook($institucion_id, $flipbook_id);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output($cant_registros);
    }

// PROCESOS
//---------------------------------------------------------------------------------------------------
    
    function procesos($institucion_id)
    {
        
        //Cargando datos básicos ($basico)
            if ( $this->session->userdata('rol_id') > 2 ) { $institucion_id = $this->session->userdata('institucion_id'); }
            $data = $this->Institucion_model->basico($institucion_id);
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Procesos';
            $data['vista_b'] = 'instituciones/procesos_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function actualizar_acumulador($institucion_id)
    {
        //2015-09-16 ampliar memory limit y tiempo de ejecución
            ini_set('memory_limit', '2048M');   
            set_time_limit(180);
        
        $cant_reg = $this->Institucion_model->actualizar_consecutivo($institucion_id);
        $this->Institucion_model->actualizar_acumulador($institucion_id);
        $this->Institucion_model->actualizar_acumulador_2($institucion_id);
        
        $mensaje = "{$cant_reg} registros procesados en la acumulación de compentencias";
        
        $this->session->set_flashdata('clase_alert', 'alert_success');
        $this->session->set_flashdata('mensaje', $mensaje);
        
        redirect("instituciones/procesos/{$institucion_id}");
    }
    
    function desactivar_morosos($institucion_id)
    {
        $cant_reg = $this->Institucion_model->desactivar_morosos($institucion_id);   
        
        $resultado['mensaje'] = "{$cant_reg} estudiantes morosos fueron desactivados";
        $resultado['clase'] = 'alert-success';
        
        $this->session->set_flashdata('resultado', $resultado);
        
        redirect("instituciones/procesos/{$institucion_id}");
    }
    
    /**
     * Activar a todos los usuarios de la institución
     * @param type $institucion_id
     */
    function activar_todos($institucion_id)
    {
        $registro['estado'] = 1;
        $this->db->where('institucion_id', $institucion_id);
        $this->db->update('usuario', $registro);
        
        $cant_reg = $this->db->affected_rows();
        
        $resultado['mensaje'] = "Se activaron {$cant_reg} usuarios";
        
        $this->session->set_flashdata('resultado', $resultado);
        
        redirect("instituciones/procesos/{$institucion_id}");
    }
    
    /**
     * Elimina la actividad de usuarios de una institución, según el rol
     * 
     * @param type $institucion_id
     * @param type $tipo
     */
    function eliminar_actividad($institucion_id, $tipo = 'usuarios')
    {
        $roles = '3,4,5';
        
        if ( $tipo == 'estudiantes' ) { $roles = '6'; }
        
        $condicion = "usuario_id IN (SELECT id FROM usuario WHERE institucion_id = {$institucion_id} AND rol_id IN ({$roles}))";
        
        //Tabla evento
        $this->db->where($condicion);
        $this->db->where('tipo_id IN (11,12,13,15,21,50,101)');    //Ver item.categoria_id = 13, eventos de la aplicación
        $this->db->delete('evento');
        
        $cant_reg = $this->db->affected_rows();
        
        $resultado['mensaje'] = "Se eliminaron {$cant_reg} registros de actividad";
        
        $this->session->set_flashdata('resultado', $resultado);
        
        redirect("instituciones/procesos/{$institucion_id}");
    }
    
    /**
     * Vista para elegir opción de mensajes masivos
     * 
     * @param type $institucion_id
     */
    function mensajes_masivos($institucion_id)
    {
        //Cargando datos básicos ($basico)
            if ( $this->session->userdata('rol_id') > 2 ) { $institucion_id = $this->session->userdata('institucion_id'); }
            $data = $this->Institucion_model->basico($institucion_id);

        //Variables generales
            $data['subtitulo_pagina'] = 'Mensajes';
            $data['vista_b'] = 'instituciones/mensajes_masivos_v';
            $data['ayuda_id'] = 119;

        $this->load->view(PTL_ADMIN, $data);
    }

// Pagos y compras
//-----------------------------------------------------------------------------

    /**
     * Listado de instituciones a través del código
     */
    function get_by_cod($cod)
    {
        $institutions = $this->Institucion_model->get_by_cod($cod);
        $data['list'] = $institutions->result();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
}