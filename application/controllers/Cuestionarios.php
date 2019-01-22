<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuestionarios extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        $this->load->model('Cuestionario_model');
        
        //Para formato de horas
        date_default_timezone_set("America/Bogota");
        
    }
    
    function index($cuestionario_id)
    {   
        if ( $this->session->userdata('rol_id') == 7){
            redirect("cuestionarios/grupos/{$cuestionario_id}");
        } else {
            redirect("cuestionarios/vista_previa/{$cuestionario_id}");
        }
        
    }
    
//CRUD DE CUESTIONARIO
//------------------------------------------------------------------------------------------

    /**
     * Exploración y búsqueda de cuestionarios
     */
    function explorar($num_pagina = 1)
    {
        //Datos básicos de la exploración
            $data = $this->Cuestionario_model->data_explorar($num_pagina);
        
        //Opciones de filtros de búsqueda
            $data['arr_filtros'] = array('a', 'n', 'tp', 'i');
            $data['opciones_area'] = $this->Item_model->opciones_id('categoria_id = 1', 'Todos');
            $data['opciones_nivel'] = $this->App_model->opciones_nivel('item_largo', 'Nivel');
            $data['opciones_tipo'] = $this->Item_model->opciones('categoria_id = 15', 'Tipo');
            $data['opciones_institucion'] = $this->App_model->opciones_institucion('id > 0', 'Institución');
            
        //Arrays con valores para contenido en la tabla
            $data['arr_tipos'] = $this->Item_model->arr_interno('categoria_id = 15');
        
        //Cargar vista
            $this->load->view(PTL_ADMIN_2, $data);
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
            $data = $this->Cuestionario_model->data_tabla_explorar($num_pagina);
        
        //Arrays con valores para contenido en lista
            $data['arr_tipos'] = $this->Item_model->arr_interno('categoria_id = 15');
        
        //Preparar respuesta
            $respuesta['html'] = $this->load->view('cuestionarios/explorar/tabla_v', $data, TRUE);
            $respuesta['seleccionados_todos'] = $data['seleccionados_todos'];
            $respuesta['num_pagina'] = $num_pagina;
            $respuesta['busqueda_str'] = $data['busqueda_str'];
            $respuesta['cant_resultados'] = $data['cant_resultados'];
            $respuesta['max_pagina'] = $data['max_pagina'];
        
        //Salida
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($respuesta));
    }

    /**
     * POST REDIRECT
     * 2018-01-17
     * Toma los datos de POST, los establece en formato GET para url y redirecciona
     * a la función de explorar cuestionarios
     * 
     */
    function redirect_explorar($filtro_alcance)
    {
        $this->load->model('Busqueda_model');
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        redirect("cuestionarios/explorar/{$filtro_alcance}/?{$busqueda_str}");
    }
    
    /**
     * AJAX JSON
     * Eliminar un grupo de registros seleccionados
     */
    function eliminar_seleccionados()
    {
        $str_seleccionados = $this->input->post('seleccionados');
        $seleccionados = explode('-', $str_seleccionados);
        
        foreach ( $seleccionados as $elemento_id ) 
        {
            $this->Cuestionario_model->eliminar($elemento_id);
        }
        
        $resultado = array('ejecutado' => 1, 'mensaje' =>  count($seleccionados) . ' cuestionarios eliminados');

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($resultado));
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
            $resultados_total = $this->Cuestionario_model->buscar($busqueda); //Para calcular el total de resultados
        
            if ( $resultados_total->num_rows() <= MAX_REG_EXPORT ) {
                //Preparar datos
                    $datos['nombre_hoja'] = 'Cuestionarios';
                    $datos['query'] = $resultados_total;

                //Preparar archivo
                    $objWriter = $this->Pcrn_excel->archivo_query($datos);

                $data['objWriter'] = $objWriter;
                $data['nombre_archivo'] = date('Ymd_His'). '_cuestionarios'; //save our workbook as this file name

                $this->load->view('app/descargar_phpexcel_v', $data);
            } else {
                $data['titulo_pagina'] = 'Plataforma Enlace';
                $data['mensaje'] = "El número de registros es de {$resultados_total->num_rows()}. El máximo permitido es de " . MAX_REG_EXPORT . " registros. Puede filtrar los datos por algún criterio para poder exportarlos.";
                $data['link_volver'] = "cuestionarios/explorar/?{$busqueda_str}";
                $data['vista_a'] = 'app/mensaje_v';
                
                $this->load->view(PTL_ADMIN, $data);
            }
            
    }
    
    /**
     * Formulario Grocery Crud para la creación de un nuevo cuestionario.
     */
    function nuevo()
    {   
        //Render del grocery crud
            if ( $this->session->userdata('rol_id') <= 2 ) 
            {
                //Usuarios Enlace
                $gc_output = $this->Cuestionario_model->crud_editar();
            } else {
                //Usuarios Instituciones
                $gc_output = $this->Cuestionario_model->crud_editar_profesor();
            }
        
        //Array data espefícicas
            $data['titulo_pagina'] = 'Cuestionarios';
            $data['subtitulo_pagina'] = 'Nuevo';
            $data['vista_a'] = 'app/gc_v';
            $data['vista_menu'] = 'cuestionarios/explorar/menu_v';
        
        $output = array_merge($data,(array)$gc_output);
        
        $this->load->view(PTL_ADMIN_2, $output);
    }
    
    /**
     * Formulario de edición de los datos básicos de un cuestionario
     */
    function editar()
    {
        //Cargando datos básicos
            $cuestionario_id = $this->uri->segment(4);
            $data = $this->Cuestionario_model->basico($cuestionario_id);
            
        //Render del grocery crud
            if ( $this->session->userdata('rol_id') <= 2 )  //Usuarios Enlace
            {
                $gc_output = $this->Cuestionario_model->crud_editar($cuestionario_id);
            }
            else    //Usuarios Instituciones
            {
                $gc_output = $this->Cuestionario_model->crud_editar_profesor($cuestionario_id);
            }
            
        //Verificar permiso de edición
            if ( ! $this->Cuestionario_model->editable($cuestionario_id) ) { $data['vista_a'] = 'app/no_permitido_v'; }
            
        //Solicitar vista
            $data['vista_b'] = 'app/gc_v';
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(PTL_ADMIN, $output);
    }
    
    function eliminar($cuestionario_id)
    {
        $this->Cuestionario_model->eliminar($cuestionario_id);
        $destino = "cuestionarios/explorar";
        
        redirect($destino);
    }
    
// Copia de cuestionarios
//-----------------------------------------------------------------------------
    
    /**
     * Formulario para la creación de una copia de un cuestionario
     * 
     * 
     * @param type $cuestionario_id 
     */
    function copiar_cuestionario($cuestionario_id)
    {
        //Cargando datos básicos (_basico)
            $data = $this->Cuestionario_model->basico($cuestionario_id);
        
        //Variables data
        
        //Solicitar vista
            $data['titulo_pagina'] .= ' - Copiar';
            $data['vista_b'] = 'cuestionarios/copiar_cuestionario_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Ejecuta el proceso de crear la copia, proviene de cuestionarios/copiar_cuestionario
     * 
     * Se copian las características del cuestionario y las páginas que lo componen
     * definidas en la tabla cuestionario_contenido.
     *  
     */
    function generar_copia()
    {
        //Validación del formulario
        $this->load->library('form_validation');

        //Reglas
            $this->form_validation->set_rules('nombre_cuestionario', 'Nombre del cuestionario', 'max_length[200]|required');

        //Mensajes de validación
            $this->form_validation->set_message('max_length', "El campo [ %s ] puede tener hasta 200 caracteres");
            $this->form_validation->set_message('required', "El [ %s ] no puede estar vacío");

        //Comprobar validación
            if ( $this->form_validation->run() == FALSE ) 
            {
                //No se cumple la validación, se regresa al cuestionario
                $this->copiar_cuestionario($this->input->post('cuestionario_id'));
            } else {
                //Se cumple la validación, se genera la copia del cuestionario
                
                //Preparar datos para la copia
                    $datos['nombre_cuestionario'] = $this->input->post('nombre_cuestionario');
                    $datos['cuestionario_id'] = $this->input->post('cuestionario_id');
                    $datos['descripcion'] = $this->input->post('descripcion');
                
                $nuevo_cuestionario_id = $this->Cuestionario_model->generar_copia($datos);
                
                //Se redirige al nuevo flibbook creado
                $data['url'] = base_url() . "cuestionarios/preguntas/{$nuevo_cuestionario_id}";
                $this->load->view('app/redirect_v', $data);
                
                redirect("cuestionarios/preguntas/{$nuevo_cuestionario_id}");
            }   
    }
    
//DATOS DE CUESTIONARIO
//------------------------------------------------------------------------------------------
    
    /**
     * Grupos y estudiantes que tienen asignado el cuestionario
     * 
     * @param type $cuestionario_id
     * @param type $institucion_id
     * @param type $grupo_id
     */
    function grupos($cuestionario_id, $institucion_id = NULL, $grupo_id = NULL)
    {
        //Cargando datos básicos (Cuestionario_model->basico)
            $data = $this->Cuestionario_model->basico($cuestionario_id);
        
        //Instituciones
            $instituciones = $this->Cuestionario_model->instituciones($cuestionario_id);
            $opciones_institucion = $this->Pcrn->opciones_dropdown($instituciones, 'id', 'nombre_institucion', 'Institución');
            
            if ( $instituciones->num_rows() > 0 ) {
                $institucion_id = $this->Pcrn->si_nulo($institucion_id, $instituciones->row()->id);
            }
            
            if ( is_null($institucion_id) ) { $institucion_id = 0; }
            
        //Grupos
            $grupos = $this->Cuestionario_model->grupos($cuestionario_id, $institucion_id);
            
            if ( $instituciones->num_rows() > 0 )
            {
                if ( $grupos->num_rows() > 0 ) {
                    $grupo_id = $this->Pcrn->si_nulo($grupo_id, $grupos->row()->grupo_id);
                }
            }
            
            if ( is_null($grupo_id) ) { $grupo_id = 0; }
            
        //Estudiantes
            $data['estudiantes'] = $this->Cuestionario_model->estudiantes($cuestionario_id, $grupo_id);
            $data['estados_uc'] = $this->Item_model->arr_campo(151, 'item');
        
        //Variables
            $data['instituciones'] = $instituciones;
            $data['opciones_institucion'] = $opciones_institucion;
            $data['institucion_id'] = $institucion_id;
            $data['grupos'] = $grupos;
            $data['grupo_id'] = $grupo_id;
        
        //Solicitar vista
            $data['vista_b'] = 'cuestionarios/grupos_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Exporta en archivo MS-Excel el resultado de la respuesta de los estudiantes
     * de un grupo a un cuestionario
     * 
     * @param type $cuestionario_id
     * @param type $grupo_id
     */
    function grupos_exportar($cuestionario_id, $grupo_id)
    {
        $this->load->model('Pcrn_excel');
        
        $data['objWriter'] = $this->Cuestionario_model->archivo_grupos_exportar($cuestionario_id, $grupo_id);
        $data['nombre_archivo'] = date('Ymd_His'). '_resultado_cuestionario'; //save our workbook as this file name
        
        $this->load->view('app/descargar_phpexcel_v', $data);
    }
    
    
    /**
     * Editor de las preguntas que componen un cuestionario
     * 
     * Basado en la herramienta de relaciones n-n de grocery crud
     * 
     * @param type $proceso
     * @param type $cuestionario_id 
     */
    function preguntas($cuestionario_id)
    {
        
        //Variables data
            $data = $this->Cuestionario_model->basico($cuestionario_id);
            $data['preguntas'] = $this->Cuestionario_model->preguntas($cuestionario_id, 1000, 0);
        
        //Solicitar vista
            $data['cuestionario_id'] = $cuestionario_id;
            $data['vista_b'] = 'cuestionarios/preguntas_v';

        $this->load->view(PTL_ADMIN, $data);
    }
    
    function temas($cuestionario_id)
    {
        
        //Variables data
            $data = $this->Cuestionario_model->basico($cuestionario_id);
            $data['temas'] = $this->Cuestionario_model->temas($cuestionario_id);
        
        //Variables específicas
        

        //Variables generales
            $data['subtitulo_pagina'] = 'Temas';
            $data['vista_b'] = 'cuestionarios/temas_v';

        $this->load->view(PTL_ADMIN, $data);
    }

    
    function sugerencias($cuestionario_id, $area_id = 0, $competencia_id = 0)
    {
        //Cargando datos básicos (Cuestionario_model->basico)
            $data = $this->Cuestionario_model->basico($cuestionario_id);
        
        //Variables
            $busqueda['cuestionario_id'] = $cuestionario_id;
            $busqueda['area_id'] = $area_id;
            $busqueda['competencia_id'] = $competencia_id;
            
            $sugerencias = $this->Cuestionario_model->sugerencias($busqueda);
        
        //Variables
            $data['area_id'] = $area_id;
            $data['competencia_id'] = $competencia_id;
            $data['sugerencias'] = $sugerencias;
            $data['areas'] = $this->Cuestionario_model->areas($cuestionario_id);
            $data['competencias'] = $this->Cuestionario_model->competencias($cuestionario_id, $area_id);
        
        //Solicitar vista
            $data['vista_b'] = 'cuestionarios/sugerencias_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
//PROCESO DE ASIGNACIÓN
//------------------------------------------------------------------------------------------
    
    function asignaciones()
    {
        //Cargando
            $this->load->model('Busqueda_model');
            $this->load->helper('text');
        
        //Grupos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            
            $resultados_total = $this->Cuestionario_model->asignaciones($busqueda); //Para calcular el total de resultados
            
        //Generar resultados para mostrar
            $data['per_page'] = 15; //Cantidad de registros por página
            $data['offset'] = $this->input->get('per_page');
            $resultados = $this->Cuestionario_model->asignaciones($busqueda, $data['per_page'], $data['offset']);
        
        //Variables para vista
            $data['cant_resultados'] = $resultados_total->num_rows();
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
            $data['url_paginacion'] = base_url("cuestionarios/asignaciones/?{$busqueda_str}");
            $data['elemento_s'] = 'Cuestionario';
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Asignaciones';
            $data['subtitulo_pagina'] = $data['cant_resultados'];
            $data['vista_a'] = 'cuestionarios/asignaciones/explorar_v';
            $data['vista_menu'] = 'cuestionarios/explorar/menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function n_asignar($cuestionario_id)
    {
        //Cargando datos básicos (Cuestionario_model->basico)
            $data = $this->Cuestionario_model->basico($cuestionario_id);
            
        //Variables de filtro
            $this->load->model('Busqueda_model');
            $busqueda = $this->Busqueda_model->busqueda_array();
            
        //Institución
            if ( $this->session->userdata('rol_id') > 2 ) { $busqueda['i'] = $this->session->userdata('institucion_id'); }
            
        //Nivel
            if ( is_null($busqueda['n']) ) { $busqueda['n'] = $data['row']->nivel; } 
            
        //Cargando variables
            $data['busqueda'] = $busqueda;
            $data['opciones_institucion'] = $this->App_model->opciones_institucion();
            $data['opciones_nivel'] = $this->Item_model->opciones('categoria_id = 03');
        
        //Solicitar vista
            $data['vista_b'] = 'cuestionarios/asignar/asignar_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function lista_estudiantes($cuestionario_id, $grupo_id = NULL)
    {
        $resultado['lista'] = NULL;
        if ( ! is_null($grupo_id) )
        {
            $estudiantes = $this->Cuestionario_model->estudiantes($cuestionario_id, $grupo_id);
            //$resultado['lista'] = $estudiantes->result();
            
            foreach ( $estudiantes->result() as $row_estudiante )
            {
                $fila = $row_estudiante;
                $filtros['usuario_pregunta.usuario_id'] = $row_estudiante->usuario_id;
                $filtros['usuario_pregunta.cuestionario_id'] = $cuestionario_id;
                $fila->cant_correctas = $this->Cuestionario_model->cant_correctas_simple($filtros);

                $fila->pct_correctas = $this->Pcrn->int_percent($fila->cant_correctas, 6);
                
                $lista[] = $fila;
            }
            
            $resultado['lista'] = $lista;
        }
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($resultado));
    }
    
    function vista_estudiantes($cuestionario_id, $grupo_id)
    {
        $data = $this->Cuestionario_model->basico($cuestionario_id);
        $data['estudiantes'] = $this->Cuestionario_model->estudiantes($cuestionario_id, $grupo_id);
        
        $resultado['html'] = $this->load->view('cuestionarios/asignar/estudiantes_v', $data, true);
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($resultado));
    }
    
    function asignar($cuestionario_id, $grupo_id = 0, $institucion_id = 0)
    {
        $this->load->model('Grupo_model');
        
        //Cargando datos básicos (Cuestionario_model->basico)
            $data = $this->Cuestionario_model->basico($cuestionario_id);
            
        //Institución
            if ( $this->session->userdata('rol_id') > 2 ) 
            {
                $institucion_id = $this->session->userdata('institucion_id');
            }
            
        //Variables
            $data['institucion_id'] = $institucion_id;
            $data['grupo_id'] = $grupo_id;
            $data['estudiantes'] = $this->Grupo_model->estudiantes($grupo_id, 'usuario.pago = 1');
            $data['destino_form'] = "cuestionarios/crear_asignacion/{$cuestionario_id}";
        
        //Solicitar vista
            $data['vista_b'] = 'cuestionarios/asignar_v';
            $data['ayuda_id'] = 116;
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * AJAX JSON
     * Asigna un cuestionario a un grupo (tabla meta), crea el evento de asignación
     * en la tabla evento, asigna el cuestionario a los estudiantes del grupo 
     * (tabla usuario_cuestionario).
     * 
     * @param type $cuestionario_id
     */
    function asignar_e($cuestionario_id)
    {
        $resultado = array('ejecutado' => 0, 'mensaje' => 'Asignaciones NO creadas');

        $cg_id = $this->Cuestionario_model->asignar($cuestionario_id);
        $resultado = $this->Cuestionario_model->asignar_estudiantes($cg_id);
        
        if ( $cg_id > 0 ) 
        {
            $resultado['ejecutado'] = 1;
            $resultado['mensaje'] = 'El cuestionario fue asignado, evento: ' . $cg_id;
            $resultado['cg_id'] = $cg_id;
        }
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($resultado));
    }
    
    /**
     * JSON
     * Lista de grupos asignados a un cuestionario
     * 
     * @param type $cuestionario_id
     */
    function lista_grupos($cuestionario_id, $institucion_id, $nivel = null)
    {
        $grupos = $this->Cuestionario_model->n_grupos($cuestionario_id, $institucion_id, $nivel);
        
        $data['lista'] = $grupos->result();
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
    
    /**
     * AJAX JSON
     * Eliminar asignación Cuestionario-Grupo (CG), elimina registros de la tabla
     * evento (tipo_id 22) y los de la tabla usuario_cuestionario.
     */
    function eliminar_cg($cuestionario_id, $meta_id)
    {
        $resultado = $this->Cuestionario_model->eliminar_cg($cuestionario_id, $meta_id);
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($resultado));
    }
    
    function crear_asignacion($cuestionario_id)
    {
        
        $this->load->library('form_validation');
        
        //Reglas
            $this->form_validation->set_rules('grupo_id', 'Grupo', 'required');
            $this->form_validation->set_rules('fecha_inicio', 'Fecha inicio', 'required|min_length[10]');
            $this->form_validation->set_rules('fecha_fin', 'Fecha fin', 'required|min_length[10]|callback__validacion_fecha_fin');
            $this->form_validation->set_rules('tiempo_minutos', 'Tiempo minutos', 'required|integer|greater_than[9]');
        
        //Mensajes de validación
            $this->form_validation->set_message('required', "%s es obligatorio");
            $this->form_validation->set_message('min_length', "El valor en %s no tiene un formato válido");
            $this->form_validation->set_message('integer', "%s debe ser un número entero");
            $this->form_validation->set_message('greater_than', "El valor de %s es muy pequeño");
        
        //Comprobar validación
            if ( $this->form_validation->run() == FALSE )
            {
                //No se cumple la validación, se regresa al cuestionario
                $this->asignar($cuestionario_id);
            } else {
                //Se cumple la validación, 
                
                $this->Cuestionario_model->crear_asignacion($cuestionario_id);
                $grupo_id = $this->input->post('grupo_id');
                $institucion_id = $this->Pcrn->campo('grupo', "id = {$grupo_id}", 'institucion_id');
                $destino = "cuestionarios/grupos/{$cuestionario_id}/{$institucion_id}/{$grupo_id}";
                redirect($destino);
                //$this->output->enable_profiler(TRUE);
            }
    }
    
    function _validacion_fecha_fin()
    {
        $validacion = TRUE;
        
        if ( $this->input->post('fecha_fin') <= $this->input->post('fecha_inicio') )
        {
            $this->form_validation->set_message('_validacion_fecha_fin', 'La fecha fin debe ser posterior a la fecha de inicio');
            $validacion = FALSE;
        }
        
        return $validacion;
    }

    /**
     * AJAX
     * DESACTIVADA 2018-09-24
     * Eliminar un grupo de registros seleccionados
     */
    function eliminar_seleccionados_uc()
    {
        $cant_eliminados = 0;
        $str_seleccionados = $this->input->post('seleccionados');
        
        $seleccionados = explode('-', $str_seleccionados);
        
        foreach ( $seleccionados as $elemento_id ) 
        {
            $row_uc = $this->Pcrn->registro_id('usuario_cuestionario', $elemento_id);
            $condicion['usuario_id'] = $row_uc->usuario_id;
            $condicion['cuestionario_id'] = $row_uc->cuestionario_id;
            $cant_eliminados += $this->Cuestionario_model->eliminar_uc($condicion);
        }
        
        echo $cant_eliminados;
    }

// ASIGNACIÓN MASIVA DE CUESTIONARIOS A ESTUDIANTES DE GRUPOS
//-----------------------------------------------------------------------------

    /**
     * Mostrar formulario de asignación masiva de cuestionarios mediante archivos de excel.
     * El resultado del formulario se envía a 'cuestionarios/asignar_masivo_e'
     * 
     * @param type $grupo_id
     */
    function asignar_masivo()
    {
        //Iniciales
            $nombre_archivo = '17_formato_asignacion_cuestionarios.xlsx';
            $parrafos_ayuda = array(
                "Si el cuestionario relacionado en la casilla '<b class='text-danger'>ID cuestionario</b>' (columna A) no existe el grupo no será asignado.",
                "Si el grupo relacionado en la casilla '<b class='text-danger'>ID grupo</b>' (columna B) no existe el grupo no será asignado."
            );
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo asignar cuestionarios masivamente?';
            $data['nota_ayuda'] = 'Se asignarán cuestionarios a estudianes de forma masiva';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = 'cuestionarios/asignar_masivo_e';
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'cuestionarios';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            $data['titulo_pagina'] = 'Cuestionarios';
            $data['subtitulo_pagina'] = 'Asignar masivamente';
            $data['vista_a'] = 'comunes/bs4/importar_v';
            $data['vista_menu'] = 'cuestionarios/explorar/menu_v';
        
        $this->load->view(PTL_ADMIN_2, $data);
    }
    
    function asignar_masivo_e()
    {
        
        //Cargando datos básicos (basico)
        $letra_columna = 'E';
        
        //Variables
        $no_cargados = array();
        
        $archivo = $_FILES['file']['tmp_name'];    //Se crea un archivo temporal, no se sube al servidor, se toma el nombre temporal
        $nombre_hoja = $this->input->post('nombre_hoja');   //Nombre de hoja digitada por el usuario en el formulario
        
        $this->load->model('Pcrn_excel');
        $resultado = $this->Pcrn_excel->array_hoja($archivo, $nombre_hoja, $letra_columna);
        $array_hoja = $resultado['array_hoja'];
        
        if ( $resultado['valido'] ) {
            $this->load->model('Cuestionario_model');
            $no_cargados = $this->Cuestionario_model->asignar_masivo($array_hoja);
        }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $nombre_hoja;
            $data['no_cargados'] = $no_cargados;
        
        //Cargar vista
            $data['titulo_pagina'] = 'Cuestionarios';
            $data['subtitulo_pagina'] = 'Resultado asignación masiva';
            $data['vista_menu'] = 'cuestionarios/menu_explorar_v';
            $data['vista_a'] = 'app/resultado_cargue_v';
            $data['subtitulo_pagina'] = 'Resultado cargue';
            $this->load->view(PTL_ADMIN, $data);
    }
    
//RESOLVER CUESTIONARIO
//------------------------------------------------------------------------------------------
    
    /**
     * Vista inicial antes de empezar a responder un cuestionario. Informativa.
     * 
     * @param type $uc_id
     * @param type $origen
     */
    function preliminar($uc_id, $origen = 'bibloteca')
    {
        

        $permiso_uc = $this->Cuestionario_model->permiso_uc($uc_id);
        
        if ( $permiso_uc )
        {
            //Variable inicial
                $data = $this->Cuestionario_model->basico_uc($uc_id);

            //Identificar navegador para compatibilidad
                $this->load->model('Esp');
                $data['navegador'] = $this->Esp->navegador();

            //Variables específicas
                $data['origen'] = $origen;
                
                $this->session->set_userdata('uc_id', $uc_id);
                $data['destino'] = "cuestionarios/n_resolver/";

            //Variables generales
                $data['subtitulo_pagina'] = 'Iniciar un cuestionario';
                $data['vista_a'] = 'cuestionarios/cuestionario_v';
                $data['vista_b'] = 'cuestionarios/preliminar_v';

            $this->load->view(PTL_ADMIN, $data);
        }
        else
        {
            $row_uc = $this->Pcrn->registro_id('usuario_cuestionario', $uc_id);
            redirect("usuarios/resultados_detalle/{$row_uc->usuario_id}/{$uc_id}");
        }
    }
    
    /**
     * Proceso inicial para responder un cuestionario, asigna fechas y estado
     * de iniciado en las tablas usuario_cuestionario y evento.
     * 
     * @param type $uc_id
     */
    function iniciar($uc_id)
    {
        set_time_limit(180);    //180 segundos, 3 minutos para iniciar
        
        //Cargue
            $row_uc = $this->Pcrn->registro_id('usuario_cuestionario', $uc_id);
        
        //Actualizar inicio de respuesta del cuestionario
            $this->Cuestionario_model->iniciar($row_uc);
            $this->Cuestionario_model->actualizar_resumen($uc_id);
            
        //Modificar evento, asingación de cuestionarios, evento tipo 1
            $this->load->model('Evento_model');
            $reg_asignacion['tipo_id'] = 1;
            $reg_asignacion['referente_id'] = $uc_id;
            $reg_asignacion['estado'] = 1;  //Iniciado
            $this->Evento_model->guardar_evento($reg_asignacion);
            
        //Registrar el inicio respuesta del cuestionario en la tabla evento, tipo 11
            $this->Evento_model->guardar_inicia_ctn($row_uc);
        
        //Resultado
        $resultado['ejecutado'] = 1;
        
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($resultado));
        
        //$this->output->enable_profiler(TRUE);
    }
    
    /**
     * Proceso inicial para responder un cuestionario, asigna fechas y estado
     * de iniciado en las tablas usuario_cuestionario y evento.
     * 
     * @param type $uc_id
     */
    function iniciar_test($uc_id)
    {
        set_time_limit(180);    //180 segundos, 3 minutos para iniciar
        
        //Cargue
            $row_uc = $this->Pcrn->registro_id('usuario_cuestionario', $uc_id);
        
        //Actualizar inicio de respuesta del cuestionario
            $this->Cuestionario_model->iniciar($row_uc);
            $this->Cuestionario_model->actualizar_resumen($uc_id);
            
        //Modificar evento, asingación de cuestionarios, evento tipo 1
            $this->load->model('Evento_model');
            $reg_asignacion['tipo_id'] = 1;
            $reg_asignacion['referente_id'] = $uc_id;
            $reg_asignacion['estado'] = 1;  //Iniciado
            $this->Evento_model->guardar_evento($reg_asignacion);
            
        //Registrar el inicio respuesta del cuestionario en la tabla evento, tipo 11
            $this->Evento_model->guardar_inicia_ctn($row_uc);
        
        $this->output->enable_profiler(TRUE);
    }
    
    function resolver($uc_id, $num_pregunta = 1)
    {
        $permiso = $this->Cuestionario_model->permiso_uc($uc_id);
        
        if ( $permiso ) {
            $this->resolver_pregunta($uc_id, $num_pregunta);
        } else {
            $this->finalizar($uc_id);
        }
    }
    
    /**
     * Mostrar formulario de preguntas para resolver individualmente
     * $uc_id hace referencia a usuario_cuestionario.id
     * $num_pregunta, posición de la pregunta dentro del cuestionario
     * uc, hace referencia a lo relacionado con la tabla usuario_cuestionario
     * 
     * @param type $uc_id
     * @param type $num_pregunta 
     */
    function resolver_pregunta($uc_id, $num_pregunta = 1)
    {
        //Variable inicial
            $row_uc = $this->Pcrn->registro_id('usuario_cuestionario', $uc_id);

        //Cálculos de tiempo de ejecución del cuestionario
            $mkt1 = $this->Pcrn->texto_a_mktime($row_uc->inicio_respuesta); //Marca de tiempo, inicio de solución del cuestionario
            $mkt2 = $mkt1 + ( $row_uc->tiempo_minutos * 60 );   //Marca de tiempo, final programado de solución del cuestionario

            $hora_final = date('M-d h:i a', $mkt2);
            $segundos_restantes = $mkt2 - time();
            $tiempo_restante = $this->Pcrn->tiempo_formato($segundos_restantes);    

        //Variables
            $row_pregunta = $this->Cuestionario_model->pregunta_cuestionario($row_uc->cuestionario_id, $num_pregunta);

            $usuario_id = $row_uc->usuario_id;
            $cuestionario_id = $row_uc->cuestionario_id;
            $pregunta_id = $row_pregunta->id;
            $enunciado_id = $this->Pcrn->si_strlen($row_pregunta->enunciado_id, 0);

            $row_respuesta = $this->Cuestionario_model->row_respuesta($usuario_id, $pregunta_id, $cuestionario_id);
            $row_enunciado = $this->Pcrn->registro_id('post', $enunciado_id);

        //Cargando datos básicos (Cuestionario_model->basico)
            $data = $this->Cuestionario_model->basico($cuestionario_id);

        //Variables para cuenta regresiva
            $head_includes[] = 'countdown';                

        //Cargando array data
            $data['num_pregunta'] = $num_pregunta;
            $data['row_uc'] = $row_uc;
            $data['row_pregunta'] = $row_pregunta;
            $data['row_respuesta'] = $row_respuesta;
            $data['row_enunciado'] = $row_enunciado;
            $data['arr_respuestas'] = $this->Cuestionario_model->arr_respuestas($uc_id);

            //Tiempo
                $data['hora_final'] = $hora_final;
                $data['segundos_restantes'] = $segundos_restantes;
                $data['tiempo_restante'] = $tiempo_restante;

            //Cuenta regresiva
                $data['head_includes'] = $head_includes;

        //Valor para llenar las opciones radio del formulario
            $data['valor_opciones'] = array('','','','','');
            $data['vista_a'] = 'cuestionarios/resolver_v';
            
            $this->load->view(PTL_ADMIN, $data);
            
    }
    
// RESOLVER VUE
//-----------------------------------------------------------------------------
    
    function n_resolver()
    {
        $uc_id = $this->session->userdata('uc_id');
        
        if ( $uc_id > 0 )
        {
            $row_uc = $this->Pcrn->registro_id('usuario_cuestionario', $uc_id);
            $data = $this->Cuestionario_model->basico($row_uc->cuestionario_id);

            //Cálculos de tiempo de ejecución del cuestionario
                $mkt1 = $this->Pcrn->texto_a_mktime($row_uc->inicio_respuesta); //Marca de tiempo, inicio de solución del cuestionario
                $mkt2 = $mkt1 + ( $row_uc->tiempo_minutos * 60 );   //Marca de tiempo, final programado de solución del cuestionario

                $segundos_restantes = $mkt2 - time();

            $data['row_uc'] = $row_uc;
            $data['segundos_restantes'] = $segundos_restantes;
            $data['vista_a'] = 'cuestionarios/resolver/resolver_v';
        } else {
            $data['titulo_pagina'] = 'Cuestionario finalizado';
            $data['vista_a'] = 'app/no_permitido_v';
        }
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function lista_preguntas($cuestionario_id)
    {
        $preguntas = $this->Cuestionario_model->lista_preguntas($cuestionario_id);
        
        $data['lista'] = $preguntas->result();
        $data['cant_preguntas'] = $preguntas->num_rows();
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
    
    /**
     * 
     */
    function guardar_uc($uc_id) 
    {
        //Construir registro
            $registro['editado'] = date('Y-m-d H:i:s');
            $registro['respuestas'] = $this->input->post('respuestas');
            $registro['resultados'] = $this->input->post('resultados');
            $registro['num_con_respuesta'] = $this->input->post('cant_respondidas');

        //Actualizar
            $this->db->where('id', $uc_id);
            $this->db->update('usuario_cuestionario', $registro);
        
        //Cargar resultado
            $resultado['ejecutado'] = 1;
            $resultado['mensaje'] = 'Respuestas guardadas';
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($resultado));
    }
    
    function n_finalizar($uc_id)
    {
     
        set_time_limit(180);    //180 segundos, 3 minutos para finalizar
        
        $this->session->set_userdata('uc_id', 0);   //Se quita la asignación de cuestionarios, de la variable de sesión
        
        $cant_respuestas = $this->Cuestionario_model->generar_respuestas($uc_id);
        $this->Cuestionario_model->n_finalizar($uc_id);
        
        $resultado['ejecutado'] = 1;
        $resultado['mensaje'] = 'Respuestas guardadas: ' . $cant_respuestas;
        $resultado['cant_respuestas'] = $cant_respuestas;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($resultado));
    }
    
// 
//-----------------------------------------------------------------------------
    
    /**
     * AJAX
     * Guarda una respuesta individual de una pregunta por parte de un usuario
     * @param type $uc_id 
     */
    function guardar_respuesta_ajax($uc_id)
    {
        $resultado = 0;
        //Verificar permiso para guardar una respuesta
            $permiso = $this->Cuestionario_model->permiso_uc($uc_id);
            
        if ( $permiso )
        {   
            //Validado, se guarda respuesta
            $registro = $this->Cuestionario_model->registro_respuesta();
            $up_id = $this->Cuestionario_model->guardar_respuesta($registro);
            $this->Cuestionario_model->actualizar_respondidas($uc_id);
            //$this->Cuestionario_model->actualizar_uc($uc_id); //Desactivado 2018-05-11
            
            $resultado = $up_id;
        }
        
        $this->output
        ->set_content_type('application/json')
        ->set_output($resultado);
    }
    
    /**
     * Finalizar un cuestionario
     * @param type $uc_id
     */
    function finalizar($uc_id)
    {
        $data = $this->Cuestionario_model->basico_uc($uc_id);
        
        //Actualización en la base de datos
            $this->Cuestionario_model->actualizar_uc($uc_id); //Función agregada aquí en 2018-05-11
            $this->Cuestionario_model->finalizar($uc_id);
            $this->Cuestionario_model->actualizar_acumuladores($data['row_uc']->usuario_id);
        
        $data['nombre_usuario'] = $this->App_model->nombre_usuario($data['row_uc']->usuario_id, 2);
        $data['subtitulo_pagina'] = 'Cuestionario finalizado';
        $data['vista_a'] = 'cuestionarios/cuestionario_v';
        $data['vista_b'] = 'cuestionarios/finalizar_v';
        
        //$this->output->enable_profiler(TRUE);
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * REDIRECT
     * 
     * Se ejecutan los procesos de finalización de un cuestionario, cálculo de
     * totales, acumuladores
     * 
     * @param type $uc_id
     */
    function finalizar_externo($uc_id, $redirect = 'usuario')
    {
        $row_uc = $this->Pcrn->registro_id('usuario_cuestionario', $uc_id);
        
        $this->Cuestionario_model->generar_respuestas($uc_id); //Agregada 2018-08-27
        $this->Cuestionario_model->actualizar_uc($uc_id); //Función agregada aquí en 2018-05-11
        $this->Cuestionario_model->finalizar($uc_id);
        $this->Cuestionario_model->actualizar_acumuladores($row_uc->usuario_id);
        
        $destino = "usuarios/resultados/{$row_uc->usuario_id}/{$uc_id}";
        
        if ( $redirect == 'grupo' )
        {
            $destino = "cuestionarios/grupos/{$row_uc->cuestionario_id}/{$row_uc->institucion_id}/{$row_uc->grupo_id}";
        }
        
        redirect($destino);
    }
    
    function vista_previa($cuestionario_id, $num_pregunta = 1)
    {
        $data = $this->Cuestionario_model->basico($cuestionario_id);
        
        //Iniciales
            $cant_preguntas = $data['row']->num_preguntas;
            //$cant_preguntas = 50;
            
        if ( $cant_preguntas > 0 )
        {
            //Variables
            $row_pregunta = $this->Cuestionario_model->pregunta_cuestionario($cuestionario_id, $num_pregunta);

            $usuario_id = $this->session->userdata('usuario_id');
            $nombre_usuario = $this->App_model->nombre_usuario($usuario_id, 2);
            
            $enunciado_id = $this->Pcrn->si_strlen($row_pregunta->enunciado_id, 0);

            $row_enunciado = $this->Pcrn->registro_id('post', $enunciado_id);

            //Cargando array data
                $arr_respuestas = array();
                for ($i = 0; $i < $cant_preguntas; $i++) { $arr_respuestas[$i] = NULL; }            

                $data['cant_preguntas'] = $cant_preguntas;
                $data['cuestionario_id'] = $cuestionario_id;
                $data['nombre_usuario'] = $nombre_usuario;
                $data['num_pregunta'] = $num_pregunta;
                $data['row_pregunta'] = $row_pregunta;
                $data['row_enunciado'] = $row_enunciado;
                $data['arr_respuestas'] = $arr_respuestas;
                $data['valor_opciones'] = array("","","","","");
                $data['vista_b'] = 'cuestionarios/vista_previa_v';
                $data['convertible'] = $this->Cuestionario_model->convertible($cuestionario_id);
        }
        else    //No tiene preguntas asignadas
        {
            $data['vista_b'] = 'app/mensaje_v';
            $data['mensaje'] = '<i class="fa fa-info-circle"></i> El cuestionario no tiene preguntas';
        }
        
        //Cargar vista
        $this->load->view(PTL_ADMIN, $data);
    }
    
    
    /**
     * Vista impresión del cuestionario completo con imágenes y enunciados
     * agrupados, según el $tipo de impresión se puede generar las preguntas,
     * o las respuestas
     * 
     * @param type $cuestionario_id
     */
    function imprimir($cuestionario_id, $tipo = 'preguntas')
    {
        $data = $this->Cuestionario_model->basico($cuestionario_id);
        
        $arr_vistas = array(
            'preguntas' => 'cuestionarios/imprimir/preguntas_v',
            'respuestas' => 'cuestionarios/imprimir/respuestas_v',
        );
        
        $this->load->helper('text');
            
        //Variables
        $data['preguntas'] = $this->Cuestionario_model->preguntas($cuestionario_id);
        $data['enunciados'] = $this->Cuestionario_model->enunciados($cuestionario_id);
        $data['vista_a'] = 'cuestionarios/imprimir/imprimir_v';
        $data['vista_b'] = $arr_vistas[$tipo];
        
        //Cargar vista
        $this->load->view('p_print/plantilla_v', $data);
        
    }
    
    /**
     * REDIRECT
     * 
     * Convierte un cuestionario generado desde contenido (tipo 3) a cuestionario
     * simple (tipo 4), crea copia de las preguntas y las vuelve editables por
     * el usuario docente
     * 
     * @param type $cuestionario_id
     */
    function convertir($cuestionario_id) 
    {
        $resultado = $this->Cuestionario_model->convertir($cuestionario_id);
        
        $this->session->set_flashdata('resultado', $resultado);
        redirect("cuestionarios/vista_previa/{$cuestionario_id}");
    }
    
//RESOLVER EN LOTE
//------------------------------------------------------------------------------------------
    
    /**
     * Mostrar formulario de preguntas para resolver en lote
     * $uc_id hace referencia a usuario_cuestionario.id
     * $num_pregunta, posición de la pregunta dentro del formulario
     * uc, hace referencia a lo relacionado con la tabla usuario_cuestionario
     * El resultado del formulario se envía a cuestionarios/guardar_lote
     * 
     * @param type $uc_id 
     */
    function resolver_lote($uc_id)
    {
        
        //Head includes específicos para la página
            $head_includes[] = 'respuesta_lote';
        
        //Variables
            $row_uc = $this->Pcrn->registro('usuario_cuestionario', "id = {$uc_id}");
            $usuario_id = $row_uc->usuario_id;
            $cuestionario_id = $row_uc->cuestionario_id;
            
        //Revisiones, acción temporal
            if ( is_null($row_uc->respuestas) or strlen($row_uc->respuestas) == 0 ) { $this->Cuestionario_model->actualizar_uc($uc_id); }
        
        //Cargando datos básicos (Cuestionario_model->basico)
            $data = $this->Cuestionario_model->basico($cuestionario_id);
        
        //Cargando array data
            $data['row_uc'] = $row_uc;  //Registro en la tabla usuario_cuestionario
            $data['row_usuario'] = $this->Pcrn->registro('usuario', "id = {$usuario_id}");
            $data['nombre_usuario'] = $this->App_model->nombre_usuario($row_uc->usuario_id, 2);
            $data['head_includes'] = $head_includes;
            $data['preguntas'] = $this->Cuestionario_model->preguntas($cuestionario_id);
            $data['respuestas'] = $this->Cuestionario_model->array_respuestas($uc_id);
            //$data['estado_cuestionario'] = $this->Cuestionario_model->estado_cuestionario($cuestionario_id, $usuario_id);
        
        //Solicitar vista
            $data['vista_a'] = 'cuestionarios/resolver_lote_v';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    /**
     * Recibe los datos del formulario cuestionarios/resolver_lote y los guarda en la tabla 
     * usuario_pregunta.
     * @param type $uc_id 
     */
    function guardar_lote($uc_id)
    {
        
        $row_uc = $this->Pcrn->registro('usuario_cuestionario', "id = {$uc_id}");
        $row = $this->Cuestionario_model->datos_cuestionario($row_uc->cuestionario_id); //Registro cuestionario
        
        $respuestas = array();
        
        //Se cargan las respuestas del formulario en un array para enviarlo a la función del modelo
        for($i = 1; $i <= $row->num_preguntas; $i++)
        {
            $respuestas[] = $this->input->post('pregunta_'.$i);
        }
        
        $this->Cuestionario_model->guardar_lote($row_uc->usuario_id, $row_uc->cuestionario_id, $respuestas);
        $this->Cuestionario_model->actualizar_uc($uc_id);
        $this->Cuestionario_model->actualizar_acumuladores($row_uc->usuario_id);
        $this->Cuestionario_model->finalizar($uc_id);
            
        $this->session->set_flashdata('resultado', 1);
        
        redirect("cuestionarios/resolver_lote/{$uc_id}");
        
    }
    
    /**
     * Eliminar las respuestas de un estudiante para un cuestionario
     * 
     * No elimina la asignación del usuario al cuestionario
     * @param type $uc_id 
     */
    function n_reiniciar($uc_id)
    {
        //Reiniciar cuestionario
            $this->Cuestionario_model->reiniciar($uc_id);
            
        $resultado['ejecutado'] = 1;
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($resultado));
    }
    
    /**
     * Eliminar las respuestas de un estudiante para un cuestionario
     * 
     * No elimina la asignación del usuario al cuestionario
     * @param type $uc_id 
     */
    function reiniciar($uc_id, $destino = 1)
    {
        //Reiniciar cuestionario
            $row_uc = $this->Cuestionario_model->reiniciar($uc_id);
        
        //Reiniciar, en tabla evento
            $this->load->model('Evento_model');
            $this->Evento_model->reiniciar_ctn($uc_id);
        
        //Variables para proceso y redireccionamiento
            $row_grupo = $this->Pcrn->registro_id('grupo', $row_uc->grupo_id);
        
        //Se redirige a la página inicial desde la que se ejecutó el proceso
        if ( $destino == 1 )
        {
            redirect("cuestionarios/grupos/{$row_uc->cuestionario_id}/{$row_grupo->institucion_id}/{$row_grupo->id}");
        } elseif ( $destino == 2 ){
            redirect("cuestionarios/cargue_respuestas/{$row_uc->cuestionario_id}/{$row_grupo->id}");
        }   
    }
    
//CARGAR RESPUESTAS CON MS-EXCEL
//------------------------------------------------------------------------------------------
    
    /**
     * Mostrar formulario de cargue de respuestas mediante archivos de excel.
     * El resultado del formulario se envía a 'cuestionarios/responder_masivo_e'
     * 
     * @param type $grupo_id
     */
    function responder_masivo()
    {
        //Iniciales
            $nombre_archivo = '23_formato_cargue_respuestas.xlsx';
            $parrafos_ayuda = array();
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo cargar respuestas?';
            $data['nota_ayuda'] = 'Se importarán respuestas de cuestionarios a la Plataforma.';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = 'cuestionarios/responder_masivo_e';
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'respuestas';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            $data['titulo_pagina'] = 'Cuestionarios';
            $data['subtitulo_pagina'] = 'Importar respuestas';
            $data['vista_a'] = 'comunes/bs4/importar_v';
            $data['vista_menu'] = 'cuestionarios/explorar/menu_v';
            $data['ayuda_id'] = 143;
        
        $this->load->view(PTL_ADMIN_2, $data);
    }
    
    function responder_masivo_e()
    {
        //Cargando datos básicos (basico)
        $letra_columna = 'D';
        
        //Variables
        $no_cargados = array();
        
        $archivo = $_FILES['file']['tmp_name'];    //Se crea un archivo temporal, no se sube al servidor, se toma el nombre temporal
        $nombre_hoja = $this->input->post('nombre_hoja');   //Nombre de hoja digitada por el usuario en el formulario
        
        $this->load->model('Pcrn_excel');
        $resultado = $this->Pcrn_excel->array_hoja($archivo, $nombre_hoja, $letra_columna);
        $array_hoja = $resultado['array_hoja'];
        
        if ( $resultado['valido'] ) {
            $no_cargados = $this->Cuestionario_model->responder_masivo($array_hoja);
        }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $nombre_hoja;
            $data['no_cargados'] = $no_cargados;
        
        //Cargar vista
            $data['titulo_pagina'] = 'Cuestionarios';
            $data['subtitulo_pagina'] = 'Resultado respuestas masivas';
            $data['vista_menu'] = 'cuestionarios/menu_explorar_v';
            $data['vista_a'] = 'app/resultado_cargue_v';
            $data['subtitulo_pagina'] = 'Resultado cargue';
            $data['ayuda_id'] = 143;
            $this->load->view(PTL_ADMIN, $data);
    }
    
    
//EDICIÓN DE CONTENIDO DE CUESTIONARIOS
//------------------------------------------------------------------------------------------

    
    /**
     * Eliminar un registro de la tabla 'cuestionario_pregunta'
     * No se elimina el registro de la pregunta, solo se la quita del cuestionario
     */
    function quitar_pregunta($cuestionario_id, $pregunta_id)
    {
        $this->Cuestionario_model->quitar_pregunta($cuestionario_id, $pregunta_id);
        
        $data['url'] = base_url("cuestionarios/preguntas/$cuestionario_id");
        $data['msg_redirect'] = '';
        $this->load->view('app/redirect_v', $data);
    }
    
    /**
     * Reenumera los cuestionarios del sistema
     * Actualiza el campo cuestionario_contenido.orden
     */
    function reenumerar_cuestionarios($cuestionario_id = NULL){
        
        //Seleccionar cuestionarios
            //Si el $cuestionario_id se le agrega la condición, en caso contrario se eligen todos los cuestionarios
            if ( ! is_null($cuestionario_id) ){
                $this->db->where('id', $cuestionario_id);
            }

            $cuestionarios = $this->db->get('cuestionario');
        
        //Procesar datos
            $registros_modificados = 0;

            foreach ($cuestionarios->result() as $row_cuestionario) {
                $registros_modificados += $this->Cuestionario_model->reenumerar_cuestionario($row_cuestionario->id);
            }
        
        //Cargando vista
            $data['titulo_pagina'] = 'Reenumerar orden de preguntas';
            $data['mensaje'] = "Se actualizaron {$registros_modificados} registros";
            $data['vista_a'] = "app/mensaje_v";

            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    /**
     * Cambia el valor del campo pregunta_cuestionario.orden
     * 
     * Se modifica también la posición de la pregunta contigua, + o - 1
     * 
     * @param type $cuestionario_id
     * @param type $pregunta_id
     * @param type $pos_final
     */
    function mover_pregunta($cuestionario_id, $pregunta_id, $pos_final)
    {
        //$this->output->enable_profiler(TRUE);
        
        //Cambiar la posición de una pregunta en un cuestionario
        $this->Cuestionario_model->cambiar_pos_pregunta($cuestionario_id, $pregunta_id, $pos_final);
        
        $data['url'] = base_url("cuestionarios/preguntas/{$cuestionario_id}");
        $data['msg_redirect'] = '';
        $this->load->view('app/redirect_v', $data);
        
    }
    
    function actualizar_areas($cuestionario_id = NULL)
    {
        if ( ! is_null($cuestionario_id) ){
            $this->db->where('id', $cuestionario_id);
        }
        
        $cuestionarios = $this->db->get('cuestionario');
        
        foreach( $cuestionarios->result() as $row_cuestionario ){
            $this->Cuestionario_model->actualizar_areas($row_cuestionario->id);
        }
        
        //
        $data['mensaje'] = 'Cuestionarios actualizados';
        $data['titulo_pagina'] = 'Áreas de cuestionarios';
        $data['link_volver'] = 'develop/procesos';
        $data['vista_a'] = 'app/mensaje_v';
        
        $this->load->view(PTL_ADMIN, $data);
        
    }
    
    //DESACTIVADA 2018-11-14
    function z_pregunta_existente($cuestionario_id, $orden)
    {
        
        //Cargando datos básicos (_basico)
            $this->load->model('Busqueda_model');
            $this->load->model('Pregunta_model');
            $data = $this->Cuestionario_model->basico($cuestionario_id);    
            
            $busqueda = $this->Busqueda_model->busqueda_array();    //Construye array de búsqueda desde input->post()
            
            //Ejecutar búsqueda
            $resultados = $this->Pregunta_model->buscar($busqueda, 100, 0);
            
        //Variables
            $data['busqueda'] = $busqueda;
            $data['seccion'] = 'existente';
            $data['orden'] = $orden;
            $data['preguntas'] = $resultados;
            $data['destino_form'] = "cuestionarios/pregunta_existente/{$cuestionario_id}/{$orden}";
            $data['vista_b'] = 'cuestionarios/pregunta_existente_v';
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Agregar pregunta';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    function pregunta_nueva($cuestionario_id, $orden)
    {
        $this->load->model('Pregunta_model');
        $data = $this->Cuestionario_model->basico($cuestionario_id);
            
            $this->session->set_userdata('cuestionario_id', $cuestionario_id);
            $this->session->set_userdata('orden', $orden);
            
            $registro['nivel'] = $data['row']->nivel;
            $registro['area_id'] = $data['row']->area_id;
            $this->session->set_userdata('area_id', $data['row']->area_id);
            
            if ( in_array($this->session->userdata('rol_id'), array(3,4,5)) ) {
                $gc_output = $this->Pregunta_model->crud_add_institucional($cuestionario_id, $registro);
            } else {
                $gc_output = $this->Pregunta_model->crud_add($cuestionario_id, $registro);
            }
            
        //Variables
            $data['vista_b'] = 'comunes/gc_v';
            $data['vista_menu'] = 'cuestionarios/menu_agregar_pregunta_v';
            $data['orden'] = $orden;
            $data['orden_mostrar'] = $orden + 1;
            $data['seccion'] = 'nueva';
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Nueva pregunta';
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(PTL_ADMIN, $output);
    }   
}