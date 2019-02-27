<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Flipbooks extends CI_Controller{
    
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Flipbook_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($flipbook_id)
    {
        $this->ver_flipbook($flipbook_id);
    }
    
    
//---------------------------------------------------------------------------------------------------
//
    
    function explorar()
    {
        $this->load->helper('text');
        $this->load->model('Busqueda_model');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Flipbook_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(2);
            $config['base_url'] = base_url("flipbooks/explorar/?{$busqueda_str}");
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Flipbook_model->buscar($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['vista_menu'] = 'flipbooks/explorar_menu_v';
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Contenidos';
            $data['subtitulo_pagina'] = $config['total_rows'];
            $data['vista_a'] = 'flipbooks/explorar_v';
            $this->load->view(PTL_ADMIN, $data);
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
            $resultados_total = $this->Flipbook_model->buscar($busqueda); //Para calcular el total de resultados
        
            if ( $resultados_total->num_rows() <= MAX_REG_EXPORT ) {
                //Preparar datos
                    $datos['nombre_hoja'] = 'Contenidos';
                    $datos['query'] = $resultados_total;

                //Preparar archivo
                    $objWriter = $this->Pcrn_excel->archivo_query($datos);

                $data['objWriter'] = $objWriter;
                $data['nombre_archivo'] = date('Ymd_His'). '_contenidos'; //save our workbook as this file name

                $this->load->view('app/descargar_phpexcel_v', $data);
            } else {
                $data['titulo_pagina'] = 'Plataforma Enlace';
                $data['mensaje'] = "El número de registros es de {$resultados_total->num_rows()}. El máximo permitido es de " . MAX_REG_EXPORT . " registros. Puede filtrar los datos por algún criterio para poder exportarlos.";
                $data['link_volver'] = "flipbooks/explorar/?{$busqueda_str}";
                $data['vista_a'] = 'app/mensaje_v';
                
                $this->load->view(PTL_ADMIN, $data);
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
            $this->Flipbook_model->eliminar($elemento_id);
        }
        
        echo count($seleccionados);
    }  
    
    function nuevo()
    {
        
        //Render del grocery crud
            $gc_output = $this->Flipbook_model->crud_nuevo();
        
        //Head includes específicos para la página
        
        //Array data espefícicas
            $data['titulo_pagina'] = 'Contenidos';
            $data['subtitulo_pagina'] = 'Nuevo';
            $data['vista_a'] = 'comunes/gc_v';
            $data['vista_menu'] = 'flipbooks/explorar_menu_v';
        
        $output = array_merge($data,(array)$gc_output);
        
        $this->load->view(PTL_ADMIN, $output);
    }
    
    function editar()
    {
        //Cargando datos básicos
            $tema_id = $this->uri->segment(4);
            $data = $this->Flipbook_model->basico($tema_id);
            
        //Render del grocery crud
            $gc_output = $this->Flipbook_model->crud_editar($tema_id);
            
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Editar';
            $data['vista_b'] = 'comunes/gc_v';
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(PTL_ADMIN, $output);
    }
    
//IMPORTAR
    
    /**
     * Mostrar formulario de importación de relación de contenidos y talleres mediante archivo MS Excel.
     * El resultado del formulario se envía a 'programas/asignar_taller_e'
     * 
     * @param type $programa_id
     */
    function asignar_taller()
    {
        
        //Iniciales
            $nombre_archivo = '21_formato_asignacion_talleres.xlsx';
            $parrafos_ayuda = array();
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo asignar talleres?';
            $data['nota_ayuda'] = 'Se asignarán talleres a los contenidos de la Plataforma.';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = 'flipbooks/asignar_taller_e';
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'contenidos_talleres';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            $data['titulo_pagina'] = 'Contenidos';
            $data['subtitulo_pagina'] = 'Asignar talleres';
            $data['vista_a'] = 'comunes/importar_v';
            $data['vista_menu'] = 'flipbooks/explorar_menu_v';
            $data['ayuda_id'] = 127;
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Importar asignación de talleres a contenidos, (e) ejecutar.
     */
    function asignar_taller_e()
    {
        
        //Proceso
            $this->load->model('Pcrn_excel');
            $no_importados = array();
            $letra_columna = 'B';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $this->load->model('Tema_model');
                $no_importados = $this->Flipbook_model->asignar_taller($resultado['array_hoja']);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $no_importados;
            $data['destino_volver'] = "flipbooks/explorar/";
        
        //Cargar vista
            $data['titulo_pagina'] = 'Contenidos';
            $data['subtitulo_pagina'] = 'Resultado asignación';
            $data['vista_a'] = 'comunes/resultado_importacion_v';
            $data['vista_menu'] = 'flipbooks/explorar_menu_v';
            $data['ayuda_id'] = 127;
            $this->load->view(PTL_ADMIN, $data);
    }
    
// SECCIONES
//-----------------------------------------------------------------------------
    
    function temas($flipbook_id)
    {
        //Cargando datos básicos (_basico)
            $this->load->model('Tema_model');
            $data = $this->Flipbook_model->basico($flipbook_id);
        
        //Variables data
            $data['seccion'] = 'Temas';
            $data['temas'] = $this->Flipbook_model->temas($flipbook_id);
            $data['subtitulo_pagina'] = "{$data['temas']->num_rows()} temas";
        
        //Solicitar vista
            $data['vista_b'] = 'flipbooks/temas_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
// CREACIÓN DE CUESTIONARIOS DESDE FLIPBOOK
//-----------------------------------------------------------------------------

    /**
     * Formulario para la crear un cuestionario a partir de la selección de los
     * temas de un flipbook. Se crea con las preguntas asociadas a los temas.
     * 
     * @param type $flipbook_id
     */
    function crear_cuestionario($flipbook_id)
    {
        
        //Cargando datos básicos
            $this->load->model('Tema_model');
            $this->load->model('Cuestionario_model');
            $data = $this->Flipbook_model->basico($flipbook_id);
        
        //Variables data
            $data['destino_form'] = "flipbooks/crear_cuestionario_e/{$flipbook_id}";
            $data['temas'] = $this->Flipbook_model->temas($flipbook_id);
            $data['subtitulo_pagina'] = "{$data['temas']->num_rows()} temas";
            
        //Vistas
            $arr_vistas = array(
                0 => 'flipbooks/crear_cuestionario_v',
                1 => 'flipbooks/crear_cuestionario_v',
                3 => 'flipbooks/crear_cuestionario_ut_v',
            );
        
        //Solicitar vista
            $data['vista_b'] = $arr_vistas[$data['row']->tipo_flipbook_id];
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Ejecución del proceso de creación de cuestionario a partir de la 
     * selección de los temas de un flipbook. Se crea con las preguntas 
     * asociadas a los temas.
     * 
     * @param type $flipbook_id
     */
    function crear_cuestionario_e($flipbook_id)
    {
        $this->load->model('Cuestionario_model');
        
        $cuestionario_id = $this->Cuestionario_model->nuevo_de_fb($flipbook_id);
        
        //Se carga la lista de temas que pertenecen al flipbook
        $temas = $this->Flipbook_model->temas($flipbook_id);
        
        foreach ($temas->result() as $row_tema)
        {
            //Tema principal
            if ( $this->input->post($row_tema->id) ){
                $this->Cuestionario_model->agregar_preguntas($cuestionario_id, $row_tema->id);
            }
            
            //Agregar preguntas de los temas relacionados
            $this->Cuestionario_model->agregar_prg_rel($cuestionario_id, $row_tema->id);
         
        }

        //Actualizar la clave de respuestas correctas.
            $this->Cuestionario_model->act_clave($cuestionario_id);
        
        //Registrar creación de cuestionario en la tabla evento
            $this->load->model('Evento_model');
            $this->Evento_model->guardar_ev_crea_ctn($cuestionario_id);
        
        redirect("cuestionarios/vista_previa/{$cuestionario_id}");
    }
    
    /**
     * Ver el listado de páginas que componen un flipbook
     * 
     * @param type $flipbook_id 
     */
    function paginas($flipbook_id)
    {
        //Cargando datos básicos (_basico)
            $this->load->model('Pagina_model');
            $data = $this->Flipbook_model->basico($flipbook_id);
        
        //Variables data
            $data['seccion'] = 'Páginas';
            $data['paginas'] = $this->Flipbook_model->paginas($flipbook_id);
            $data['cargar_en'] = 'flipbook';
        
        //Solicitar vista
            //$data['cargado'] = FALSE;
            $data['vista_b'] = 'flipbooks/paginas_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * 
     * @param type $flipbook_id
     * @param type $usuario_id
     */
    function anotaciones($flipbook_id, $tema_id = NULL, $usuario_id = NULL)
    {
        
        //Cargando datos básicos (_basico)
            $data = $this->Flipbook_model->basico($flipbook_id);
            
        //
            if ( is_null($usuario_id) ){
                $usuario_id = $this->session->userdata('usuario_id');
            }
        
        //Variables data
            $data['seccion'] = 'Anotaciones';
            $data['temas'] = $this->Flipbook_model->temas($flipbook_id);
            $data['anotaciones'] = $this->Flipbook_model->anotaciones_profesor($flipbook_id, $tema_id, $usuario_id);
            $data['usuario_id'] = $usuario_id;
            $data['tema_id'] = $tema_id;
        
        //Solicitar vista
            $data['vista_b'] = 'flipbooks/anotaciones_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    
    function aperturas($flipbook_id)
    {
        
        //$this->output->enable_profiler(TRUE);
        
        //Cargando datos básicos (_basico)
            $data = $this->Flipbook_model->basico($flipbook_id);
        
        //Variables data
            $data['aperturas'] = $this->Flipbook_model->aperturas($flipbook_id);
        
        //Solicitar vista
            $data['vista_b'] = 'flipbooks/aperturas_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function asignados($flipbook_id, $institucion_id = NULL)
    {   
        //Cargando datos básicos (_basico)
            $data = $this->Flipbook_model->basico($flipbook_id);
            
            $instituciones = $this->Flipbook_model->instituciones($flipbook_id);
            $opciones_instituciones = $this->Pcrn->opciones_dropdown($instituciones, 'institucion_id', 'nombre_institucion');
            
        //Identificar institucion
            if ( $instituciones->num_rows() > 0 ) {
                $institucion_id = $this->Pcrn->si_nulo($institucion_id, $instituciones->row()->institucion_id);
            } else {
                $institucion_id = 0;
            }
        
        //Variables data
            $data['instituciones'] = $this->Flipbook_model->instituciones($flipbook_id);
            $data['opciones_instituciones'] = $opciones_instituciones;
            $data['institucion_id'] = $institucion_id;
            $data['asignados'] = $this->Flipbook_model->asignados($flipbook_id, $institucion_id);
        
        //Solicitar vista
            $data['vista_b'] = 'flipbooks/asignados_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Formulario para la creación de una copia de un flipbook
     * 
     * 
     * @param type $flipbook_id 
     */
    function copiar($flipbook_id)
    {
        //Cargando datos básicos (_basico)
            $data = $this->Flipbook_model->basico($flipbook_id);
        
        //Variables data
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Crecar copia';
            $data['vista_b'] = 'flipbooks/copiar_flipbook_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Ejecuta el proceso de crear la copia, proviene de flipbooks/copiar_flipbook
     * 
     * Se copian las características del flipbook y las páginas que lo componen
     * definidas en la tabla flipbook_contenido.
     *  
     */
    function generar_copia()
    {
        //Validación del formulario
        $this->load->library('form_validation');

        //Reglas
            $this->form_validation->set_rules('nombre_flipbook', 'Nombre del flipbook', 'max_length[200]|required');

        //Mensajes de validación
            $this->form_validation->set_message('max_length', "El campo [ %s ] puede tener hasta 200 caracteres");
            $this->form_validation->set_message('required', "El [ %s ] no puede estar vacío");

        //Comprobar validación
            if ( $this->form_validation->run() == FALSE ) {
                //No se cumple la validación, se regresa al cuestionario
                $this->copiar_flipbook($this->input->post('flipbook_id'));
            } else {
                //Se cumple la validación, se genera la copia del flipbook
                
                //Preparar datos para la copia
                    $datos['nombre_flipbook_nuevo'] = $this->input->post('nombre_flipbook');
                    $datos['flipbook_id'] = $this->input->post('flipbook_id');
                    $datos['descripcion'] = $this->input->post('descripcion');
                
                $nuevo_flipbook_id = $this->Flipbook_model->generar_copia($datos);
                
                //Se redirige al nuevo flibbook creado
                $data['url'] = base_url() . "flipbooks/paginas/{$nuevo_flipbook_id}";
                $this->load->view('app/redirect_v', $data);
                
                //redirect("flipbooks/paginas/{$nuevo_flipbook_id}");
            }
            
    }
    
// PROGRAMACIÓN DE TEMAS POR FECHA
//-----------------------------------------------------------------------------
    
    function programar_temas($flipbook_id)
    {
        //Cargando datos básicos (_basico)
            $this->load->model('Tema_model');
            $this->load->model('Evento_model');
            $this->load->model('Usuario_model');
            $data = $this->Flipbook_model->basico($flipbook_id);
            
        //Institución
            $instituciones = $this->Flipbook_model->instituciones($flipbook_id);
            $institucion_id = $this->session->userdata('institucion_id');
            if ( $this->session->userdata('srol') == 'interno' ) {
                $institucion_id = $this->input->get('i');
                if ( $institucion_id == NULL && $instituciones->num_rows() > 0 ) { $institucion_id = $instituciones->row()->institucion_id; }
            }
            
        //Grupos
            $grupos = $this->Usuario_model->grupos_usuario($this->session->userdata('usuario_id'), $institucion_id, $data['row']->nivel);
            $grupo_id = $this->input->get('g');
            if ( $grupo_id == NULL && $grupos->num_rows() > 0 ) { $grupo_id = $grupos->row()->id; }
        
        //Cargando variables data
            $data['institucion_id'] = $institucion_id;
            $data['instituciones'] = $this->Flipbook_model->instituciones($flipbook_id);
            $data['grupo_id'] = $grupo_id;
            $data['grupos'] = $grupos;
            $data['temas'] = $this->Flipbook_model->temas($flipbook_id);
        
        //Solicitar vista
            $data['subtitulo_pagina'] = "{$data['temas']->num_rows()} temas";
            $data['vista_b'] = 'flipbooks/programar/programar_temas_v';
            $data['vista_submenu'] = 'flipbooks/programar/submenu_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function importar_programacion($flipbook_id)
    {
        $this->load->model('Usuario_model');
        
        $data = $this->Flipbook_model->basico($flipbook_id);
        
        //Institución
            $instituciones = $this->Flipbook_model->instituciones($flipbook_id);
            $institucion_id = $this->session->userdata('institucion_id');
            if ( $this->session->userdata('srol') == 'interno' ) {
                $institucion_id = $this->input->get('i');
                if ( $institucion_id == NULL && $instituciones->num_rows() > 0 ) { $institucion_id = $instituciones->row()->institucion_id; }
            }
            
        //Grupos
            $grupos = $this->Usuario_model->grupos_usuario($this->session->userdata('usuario_id'), $institucion_id, $data['row']->nivel);
            $grupo_id = $this->input->get('g');
            if ( $grupo_id == NULL && $grupos->num_rows() > 0 ) { $grupo_id = $grupos->row()->id; }
            
            $data['institucion_id'] = $institucion_id;
            $data['instituciones'] = $this->Flipbook_model->instituciones($flipbook_id);
            $data['grupo_id'] = $grupo_id;
            $data['grupos'] = $grupos;
        
        //Iniciales
            $nombre_archivo = '19_formato_programacion_temas.xlsx';
            $parrafos_ayuda = array();
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo programar temas?';
            $data['nota_ayuda'] = 'Se programarán temas del Contenido a los estudiantes del grupo';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = "flipbooks/importar_programacion_e/{$flipbook_id}/{$grupo_id}";
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'temas_fecha';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            //$data['titulo_pagina'] = 'Temas';
            $data['subtitulo_pagina'] = 'Importar programación de temas';
            $data['vista_b'] = 'comunes/importar_v';
            $data['vista_submenu'] = 'flipbooks/programar/submenu_v';
            $data['ayuda_id'] = 108;
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Importar programación de temas, (e) ejecutar.
     */
    function importar_programacion_e($flipbook_id, $grupo_id)
    {
        
        
        $data = $this->Flipbook_model->basico($flipbook_id);
            
        //Grupos
            $row_grupo = $this->Pcrn->registro_id('grupo', $grupo_id);
            $institucion_id = $row_grupo->institucion_id;
            $this->load->model('Usuario_model');
            $grupos = $this->Usuario_model->grupos_usuario($this->session->userdata('usuario_id'), $institucion_id, $data['row']->nivel);
            
            $data['institucion_id'] = $institucion_id;
            $data['instituciones'] = $this->Flipbook_model->instituciones($flipbook_id);
            $data['grupo_id'] = $grupo_id;
            $data['grupos'] = $grupos;
        
        //Proceso
            $this->load->model('Pcrn_excel');
            $no_importados = array();
            $letra_columna = 'B';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $no_importados = $this->Flipbook_model->importar_programacion($flipbook_id, $grupo_id, $resultado['array_hoja']);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $no_importados;
            $data['destino_volver'] = "flipbooks/programar_temas/{$flipbook_id}/?i={$institucion_id}&g={$grupo_id}";
        
        //Cargar vista
            $data['subtitulo_pagina'] = 'Resultado importación de programación';
            $data['vista_b'] = 'comunes/resultado_importacion_v';
            $data['vista_submenu'] = 'flipbooks/programar/submenu_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
// LECTURA
//-----------------------------------------------------------------------------
    
    function abrir($flipbook_id, $num_pagina = NULL)
    {
        //Redirigir
        $this->load->model('Esp');
        $navegador = $this->Esp->navegador();

        $destino = "flipbooks/leer/{$flipbook_id}/{$num_pagina}";
        $navegadores_ant = array('Internet Explorer', 'Safari');

        if ( in_array($navegador, $navegadores_ant) )
        {
            $destino = "flipbooks/leer_v3/{$flipbook_id}/{$num_pagina}";
        }

        redirect($destino);
        
    }

    /**
     * Registra el evento de abrir el flipbook y redirige a la lectura
     * 
     * @param type $flipbook_id
     */
    function abrir_flipbook($flipbook_id, $num_pagina = NULL, $tema_id = NULL)
    {
        //Registrar evento de apertura de flipbook
            $this->load->model('Evento_model');
            $this->Evento_model->guardar_apertura_flipbook($flipbook_id);
            
        //Registrar ingreso a tema en la tabla evento
            if ( ! is_null($tema_id) ) {
                $this->Evento_model->guardar_lectura_tema($flipbook_id, $tema_id);
            }
            
        //Redirigir
            $this->load->model('Esp');
            $navegador = $this->Esp->navegador();
            $navegadores_ant = array('Internet Explorer', 'Safari');

            $destino = "flipbooks/leer/{$flipbook_id}/{$num_pagina}";
            if ( in_array($navegador, $navegadores_ant) )
            {
                $destino = "flipbooks/leer_v3/{$flipbook_id}/{$num_pagina}";
            }

            redirect($destino);
    }
    
    /**
     * Mostrar el flipbook para leer, vista completa para estudiantes
     * 
     * @param type $flipbook_id
     * @param type $num_pagina
     */
    function leer_v3($flipbook_id, $num_pagina = NULL)
    {
        if ( $this->input->get('profiler') == 1 ) { $this->output->enable_profiler(TRUE); }
        
        //Datos básicos
            $data = $this->Flipbook_model->basico($flipbook_id);
            
        //Temas relacionados
            $relacionados = $this->Flipbook_model->arr_relacionados($flipbook_id);

        //Variables 
            $data['bookmark'] = $this->Flipbook_model->bookmark($flipbook_id);
            $data['num_pagina'] = $this->Pcrn->si_nulo($num_pagina, $data['bookmark']);
            $data['paginas'] = $this->Flipbook_model->paginas($flipbook_id);
            $data['archivos'] = $this->Flipbook_model->archivos($flipbook_id);
            $data['planes_aula'] = $this->Flipbook_model->planes_aula($flipbook_id);
            $data['quices'] = $this->Flipbook_model->quices($flipbook_id);
            $data['links'] = $this->Flipbook_model->links($flipbook_id);
            $data['anotaciones'] = $this->Flipbook_model->anotaciones($flipbook_id);
            $data['relacionados'] = $relacionados;
            //$data['subquices'] = $this->Flipbook_model->subquices($relacionados);
            $data['elementos_fb'] = $this->Flipbook_model->elementos_fb($data['row']);
            $data['carpeta_uploads'] = URL_UPLOADS;
            $data['carpeta_iconos'] = URL_IMG . 'flipbook/';
        
        //Cargar vista
            $vista = 'app/no_permitido_v';
            $visible = $this->Flipbook_model->visible($flipbook_id);
            if ( $visible ) { $vista = 'flipbooks/leer_v3/leer_v'; }
            
            $this->load->view($vista, $data);       
    }
    
    /**
     * Mostrar el flipbook para leer, vista completa para estudiantes
     * 
     * @param type $flipbook_id
     */
    function subquices($flipbook_id)
    {   
        $this->output->enable_profiler(TRUE);
        //Datos básicos
            $data = $this->Flipbook_model->basico($flipbook_id);
            
            $data['quices'] = $this->Flipbook_model->quices_total($flipbook_id);
        
        //Cargar vista
            $data['vista_b'] = 'flipbooks/subquices_v';
            
            $this->load->view(PTL_ADMIN, $data);       
    }
    
    function animacion($recurso_id)
    {
        $data['row'] = $this->Pcrn->registro_id('recurso', $recurso_id);
        $data['row_tema'] = $this->Pcrn->registro_id('tema', $data['row']->tema_id);
        $data['titulo_pagina'] = $data['row_tema']->nombre_tema;
        
        $this->load->view('flipbooks/leer_v3/ver_animacion_v', $data);
    }
    
    function ajax_pagina($flipbook_id, $num_pagina)
    {
        $data = $this->Flipbook_model->datos_pagina_full($flipbook_id, $num_pagina);
        $respuesta = json_encode($data);
        echo $respuesta;
    }
    
// NUEVAS HERRAMIENTAS PARA LEER
//-----------------------------------------------------------------------------
    
    /**
     * Mostrar el flipbook para leer, vista completa para estudiantes
     * 2018-10-23
     * 
     * @param type $flipbook_id
     * @param type $num_pagina
     */
    function leer($flipbook_id, $num_pagina = 0)
    {
        if ( $this->input->get('profiler') == 1 ) { $this->output->enable_profiler(TRUE); }
        
        //Datos básicos
            $data = $this->Flipbook_model->basico($flipbook_id);
            
        //Datos referencia
            $data['bookmark'] = $this->Flipbook_model->bookmark($flipbook_id);
            $data['num_pagina'] = $this->Pcrn->si_nulo($num_pagina, $data['bookmark']);
            $data['carpeta_uploads'] = URL_UPLOADS;
            $data['carpeta_iconos'] = URL_IMG . 'flipbook/';
            $data['colores'] = $this->App_model->arr_color_area();
            $data['elementos_fb'] = $this->Flipbook_model->elementos_fb($data['row']);
            
        //Cargar vista
        $this->load->view('flipbooks/leer/leer_v', $data);
    }

    /**
     * String JSON para construir el flipbook para leer, vista completa para 
     * estudiantes y profesores. 1) Verifica si el archivo JSON del flipbook
     * existe, si no existe se crea.
     * 
     * @param type $flipbook_id
     */
    function data($flipbook_id)
    {
        $ruta_archivo = $this->Flipbook_model->ruta_json($flipbook_id);

        if ( file_exists($ruta_archivo) )
        {
            //El archivo JSON ya existe, se lee
            $data_str = file_get_contents($ruta_archivo);
        } else {
            //El archivo JSON del flipbook no existe, se crea.
            $data_str = $this->Flipbook_model->crear_json($flipbook_id);
        }
            
        $this->output
        ->set_content_type('application/json')
        ->set_output($data_str);
    }
    
    /**
     * JSON
     * Devuelve las anotaciones del usuario en sesión relizadas en un flipbook
     * específico.
     * 
     * @param type $flipbook_id
     */
    function json_anotaciones($flipbook_id)
    {
        $data['anotaciones'] = $this->Flipbook_model->anotaciones($flipbook_id)->result();
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));   
    }
    
    /**
     * JSON
     * Crea el archivo JSON con el contenido de un flipbook, utilizado para
     * construir la vista de lectura.
     * 
     * @param type $flipbook_id
     */
    function crear_json($flipbook_id)
    {
        $resultado['ejecutado'] = 0;
        
        $data_str = $this->Flipbook_model->crear_json($flipbook_id);
        if ( strlen($data_str) > 0 ) { $resultado['ejecutado'] = 1; }
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($resultado));
    }
    
    
//GESTIÓN DE PÁGINAS DE FLIPBOOK
//---------------------------------------------------------------------------------------------------
    
    /**
     * Cambia el valor del campo flipbook_contenido.num_pagina
     * 
     * Se aumenta en uno de posición una página en un flipbook,
     * Se modifica también la posición de la página siguiente, disminuye en 1
     * 
     * @param type $flipbook_id
     * @param type $pf_id
     */
    function mover_pagina($flipbook_id, $pf_id, $movimiento = 1)
    {
        //$this->output->enable_profiler(TRUE);
        
        if ( $movimiento == 1 ){
            //Proviene del link Bajar, se aumenta el número de página para que aparezca más abajo
            $this->Flipbook_model->aumentar_num_pagina($flipbook_id, $pf_id);
        } elseif ( $movimiento == 2 ){
            //Proviene del link Subir, se disminuye el número de página para qua aparezca más arriba
            $this->Flipbook_model->disminuir_num_pagina($flipbook_id, $pf_id);
        }
        
        $data['url'] = base_url() . "flipbooks/paginas/$flipbook_id";
        $this->load->view('app/redirect_v', $data);
        
    } 
    
    /** 
     * Subir archivo de imagen y crear sus miniaturas
     * Crear registro del archivo subido, tabla 'pagina_flipbook'
     * Crear registro de relación entre página y flipbook
     */
    function guardar_pagina($flipbook_id)
    {
        
        //Validación del formulario
        
            $this->load->library('form_validation');
            $this->load->model('Pagina_model');
            
            //Reglas
                $this->form_validation->set_rules('titulo_pagina', 'Título de la página', 'max_length[100]');
                $this->form_validation->set_rules('num_pagina', 'Número de la página', 'is_natural');

            //Mensajes de validación
                $this->form_validation->set_message('less_than', "El campo [ %s ] puede tener hasta 100 caracteres");
                $this->form_validation->set_message('is_natural', "El campo [ %s ] debe ser un número entero mayor a cero");

            //Comprobar validación
                if ( $this->form_validation->run() == FALSE ) {
                    //No se cumple la validación, se regresa al cuestionario
                    $this->cargar_pagina($flipbook_id);
                } else {
                    
                    //Se cumple la validación, se procede a subir la imagen
                    $resultados = $this->Pagina_model->subir_imagen();
                    
                    $this->session->set_flashdata('mensaje', $resultados['mensaje']);
                    $this->session->set_flashdata('cargado', $resultados['cargado']);

                    if ( $resultados['cargado'] == TRUE ){
                        
                        //Crear registro en la tabla 'pagina_flipbook'
                            $registro['titulo_pagina'] = $this->Pcrn->si_vacia($this->input->post('titulo_pagina'), "Sin título");
                            $registro['archivo_imagen'] = $resultados['upload_data']['file_name'];
                            $this->db->insert('pagina_flipbook', $registro);
                            $pagina_id = $this->db->insert_id();
                            
                        //Crear registro en la tabla 'flipbook_contenido'
                            //$num_pagina = $this->Pcrn->si_vacia($this->input->post('num_pagina'), 0,);
                            $registro = array();
                            $registro['flipbook_id'] = $flipbook_id;
                            $registro['pagina_id'] = $pagina_id;
                            $registro['num_pagina'] = $this->input->post('num_pagina');
                            
                            $this->Flipbook_model->insertar_flipbook_contenido($registro);
                        
                    }
                    
                    $data['url'] = base_url() . "flipbooks/cargar_pagina/{$flipbook_id}/{$this->input->post('num_pagina')}";
                    $this->load->view('app/redirect_v', $data);
                    //Proceso modificado por problemas de sesión, 2013/04/09
                    //redirect("flipbooks/cargar_pagina/{$flipbook_id}/{$this->input->post('num_pagina')}");
                }
                
                
    }
    
    /**
     * Inserta una página existente en un flipbook
     * 2013-04-10
     * 
     * @param type $flipbook_id 
     */
    function insertar_pagina($flipbook_id, $pagina_id, $num_pagina)
    {
        
        $registro['flipbook_id'] = $flipbook_id;
        $registro['pagina_id'] = $pagina_id;
        $registro['num_pagina'] = $num_pagina;

        $this->Flipbook_model->insertar_flipbook_contenido($registro);

        //Regresar a la página
        $this->paginas($flipbook_id);
        
    }
    
    /**
     * Mostrar el resultado de cargar una página
     * 
     * Se muestra el resultado de cargar una página
     * 
     * @param type $flipbook_id
     * @param type $resultados 
     */
    function resultado_carga($flipbook_id, $resultados = NULL)
    {
        
        //Cargando datos básicos (_basico)
        $flipbook_id = $this->Pcrn->si_nulo($flipbook_id, $this->Flipbook_model->flipbook_id(), $flipbook_id);
        $data = $this->Flipbook_model->basico($flipbook_id);
        
        if ( !is_null($resultados) ){
            $data['mensaje'] = $resultados['mensaje'];
        }
        
        $upload_data = $resultados['upload_data'];
        $data['archivo_imagen'] = $upload_data['file_name'];
        $data['resultados'] = $resultados;
        
        //Solicitar vista
        $data['cargado'] = FALSE;
        $data['titulo_pagina'] = "Estudiantes | " . $data['titulo_pagina'];
        $data['vista_b'] = 'flipbooks/resultado_carga_v';
        $this->load->view(PTL_ADMIN, $data);

    }
    
    
    /**
     * Eliminar un registro de la tabla 'flipbook_contenido'
     * 
     * No se elimina el registro de la página, solo se la quita del flipbook
     * 
     */
    function quitar_pf($flipbook_id, $pf_id)
    {
        $this->db->where('flipbook_id', $flipbook_id);
        $this->db->where('pagina_id', $pf_id);
        $this->db->delete('flipbook_contenido');
        
        $this->reenumerar_flipbooks($flipbook_id);
        
        $data['url'] = base_url() . "flipbooks/paginas/$flipbook_id";
        $this->load->view('app/redirect_v', $data);
        
    }
    
    function _miniatura_pagina($uploader_response)
    {
        //Crea las imágenes miniatura de la página que se sube.
        $this->Flipbook_model->img_pf_mini($uploader_response[0]->name);
        return true;
    }
        
    
//---------------------------------------------------------------------------------------------------
//GESTIÓN DETALLE DE PÁGINAS, OPERACIONES POR AJAX
    
    /**
     * AJAX
     * 
     * Guardar en el campo usuario_flipbook.bookmark el número de página
     * en la que el usuario continuará su lectura.
     * 
     * El proceso se hace vía ajax.
     * 
     * @param type $flipbook_id 
     */
    function guardar_bookmark($flipbook_id, $num_pagina)
    {
        if ( $num_pagina >= 0 )
        {
            //Construir el registro que se va a guardar
            $registro = array(
                'flipbook_id' => $flipbook_id,
                'usuario_id' => $this->session->userdata('usuario_id'),
                'bookmark' => $num_pagina,
            );

            //Guardar bookmark
            $condicion = "flipbook_id = {$flipbook_id} AND usuario_id = {$this->session->userdata('usuario_id')}";
            $uf_id = $this->Pcrn->guardar('usuario_flipbook', $condicion, $registro);
        }

        //Respuesta
            $this->output
            ->set_content_type('application/json')
            ->set_output($uf_id . ': ' . $num_pagina);
    }
    
    /**
     * AJAX
     * 
     * Crea un registro de anotación en la tabla 'pagina_flipbook_detalle'
     * 
     * El tipo detalle 'Anotación' corresponde al tipo_detalle_id = 3
     * Ver id_interno en la tabla item, categoría 13.
     */
    function guardar_anotacion($flipbook_id)
    {
        $detalle_id = 0;    //Valor por defecto
        
        //Identificar registro de la página, a la que se hace referencia según el flipbook_id y el num_pagina definidos
            $datos_pagina = $this->Flipbook_model->pagina_num($flipbook_id, $this->input->post('num_pagina'));
        
        //Si es una página existente
            if ( $datos_pagina['id'] > 0 )
            {
                
                //Construir el registro que se va a insertar
                $registro = array(
                    'pagina_id' => $datos_pagina['id'],
                    'anotacion' => $this->input->post('anotacion'),
                    'tipo_detalle_id' => 3,
                    'publico' => 0,
                    'usuario_id' => $this->session->userdata('usuario_id'),
                    'editado' => date('Y-m-d H:i:s')
                );

                $detalle_id = $this->Flipbook_model->guardar_anotacion($registro);
                
                //Quitar caracteres no permitidos para JSON
                $this->Flipbook_model->limpiar_anotacion($detalle_id);
            }

        //Respuesta
            $this->output
            ->set_content_type('application/json')
            ->set_output($detalle_id);
    }

//---------------------------------------------------------------------------------------------------
//PROCESAMIENTO MASIVO DE DATOS, PROCESOS DE ADMINISTRADOR
    
    /**
     * Actualiza el campo flipbook_contenido.tema_id según la página relacionada
     * en el campo flipbook_contenido.pagina_id
     */
    function act_fc_tema()
    {
        $sql = 'UPDATE flipbook_contenido 
                JOIN pagina_flipbook ON flipbook_contenido.pagina_id = pagina_flipbook.id
                SET flipbook_contenido.tema_id = pagina_flipbook.tema_id';
        
        $this->db->query($sql);
        $filas_modificadas = $this->db->affected_rows();
        
        $resultado['mensaje'] = 'Proceso ejecutado. Filas actualizadas: ' . $filas_modificadas;
        $resultado['clase'] = 'alert-info';
        
        $this->session->set_flashdata('resultado', $resultado);
        redirect('develop/procesos');
    }
    
    /**
     * Actualiza el campo usuario_flipbook.grupo_id según el usuario asignado
     */
    function act_uf_grupo()
    {
        $sql = 'UPDATE usuario_flipbook 
                JOIN usuario ON usuario_flipbook.usuario_id = usuario.id
                SET usuario_flipbook.grupo_id = usuario.grupo_id';
        
        $this->db->query($sql);
        $filas_modificadas = $this->db->affected_rows();
        
        $resultado['mensaje'] = 'Proceso ejecutado. Filas actualizadas: ' . $filas_modificadas;
        $resultado['clase'] = 'alert-info';
        
        $this->session->set_flashdata('resultado', $resultado);
        redirect('develop/procesos');
    }
    
    /**
     * Reenumera los flipbooks del sistema
     * 
     * Actualiza el campo flipbook_contenido.num_pagina.
     */
    function reenumerar_flipbooks($flipbook_id = NULL){
        
        //Seleccionar flipbooks
            //Si el $flipbook_id se le agrega la condición, en caso contrario se eligen todos los flipbooks
            if ( ! is_null($flipbook_id) ){
                $this->db->where('id', $flipbook_id);
            }

            $flipbooks = $this->db->get('flipbook');
        
        //Procesar datos
            $registros_modificados = 0;

            foreach ($flipbooks->result() as $row_flipbook) {
                $registros_modificados += $this->Flipbook_model->reenumerar_flipbook($row_flipbook->id);
            }
        
        //Cargando vista
            $data['titulo_pagina'] = 'Reenumerar números de páginas';
            $data['mensaje'] = "Se actualizaron {$registros_modificados} registros";
            $data['vista_a'] = "app/mensaje_v";

            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    /**
     * Actualiza el campo flipbook.primera_pagina_id
     * 
     * @param type $flipbook_id 
     */
    function primera_pagina($flipbook_id = NULL){
        //Seleccionar flipbooks
            //Si el $flipbook_id se le agrega la condición, en caso contrario se eligen todos los flipbooks
            if ( ! is_null($flipbook_id) ){
                $this->db->where('id', $flipbook_id);
            }

            $flipbooks = $this->db->get('flipbook');
        
        //Procesar datos
            $registros_modificados = 0;

            foreach ($flipbooks->result() as $row_flipbook) {
                $registros_modificados += $this->Flipbook_model->primera_pagina($row_flipbook->id);
            }
        
        //Cargando vista
            $data['titulo_pagina'] = 'Primeras páginas';
            $data['mensaje'] = "Se actualizaron {$registros_modificados} registros para el campo primera_pagina_id";
            $data['link_volver'] = 'develop/procesos';
            $data['vista_a'] = "app/mensaje_v";

            $this->load->view(PTL_ADMIN, $data);
    }   
}