<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Temas extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        $this->load->model('Tema_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($tema_id = NULL)
    {
        $destino = 'temas/explorar/';
        if ( ! is_null($tema_id) ) {
            $destino = "temas/paginas/{$tema_id}";
        }
        
        redirect($destino);
    }

// EXPLORACIÓN DE TEMAS
//-----------------------------------------------------------------------------
    
    /**
     * Exploración y búsqueda de usuarios
     */
    function explorar($num_pagina = 1)
    {
        //Datos básicos de la exploración
            $this->load->helper('text');
            $data = $this->Tema_model->data_explorar($num_pagina);
        
        //Opciones de filtros de búsqueda
            $data['opciones_area'] = $this->Item_model->opciones_id('categoria_id = 1', 'Todos');
            $data['opciones_nivel'] = $this->Item_model->opciones('categoria_id = 3', 'Todos');
            $data['opciones_tipo'] = $this->Item_model->opciones('categoria_id = 17', 'Todos');
            
            
        //Arrays con valores para contenido en la tabla
            $data['arr_tipos'] = $this->Item_model->arr_interno('categoria_id = 17');
            $data['arr_nivel'] = $this->App_model->arr_nivel();
        
        //Cargar vista
            $this->load->view(TPL_ADMIN, $data);
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
            $this->load->helper('text');
            $data = $this->Tema_model->data_tabla_explorar($num_pagina);
        
        //Arrays con valores para contenido en lista
            $data['arr_tipos'] = $this->Item_model->arr_interno('categoria_id = 17');
            $data['arr_nivel'] = $this->App_model->arr_nivel();
        
        //Preparar respuesta
            $respuesta['html'] = $this->load->view('temas/explorar/tabla_v', $data, TRUE);
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
     * AJAX
     * Eliminar un grupo de registros seleccionados
     * 2019-08-05
     */
    function eliminar_seleccionados()
    {
        $str_seleccionados = $this->input->post('seleccionados');
        $seleccionados = explode('-', $str_seleccionados);
        
        foreach ( $seleccionados as $elemento_id ) 
        {
            $this->Tema_model->eliminar($elemento_id);
        }
        
        $data = array('status' => 1, 'message' =>  count($seleccionados) . ' usuarios eliminados');

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
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
            $resultados_total = $this->Tema_model->buscar($busqueda); //Para calcular el total de resultados
        
            if ( $resultados_total->num_rows() <= MAX_REG_EXPORT ) {
                //Preparar datos
                    $datos['nombre_hoja'] = 'Temas';
                    $datos['query'] = $resultados_total;

                //Preparar archivo
                    $objWriter = $this->Pcrn_excel->archivo_query($datos);

                $data['objWriter'] = $objWriter;
                $data['nombre_archivo'] = date('Ymd_His'). '_temas'; //save our workbook as this file name

                $this->load->view('app/descargar_phpexcel_v', $data);
            } else {
                $data['head_title'] = 'Plataforma Enlace';
                $data['mensaje'] = "El número de registros es de {$resultados_total->num_rows()}. El máximo permitido es de " . MAX_REG_EXPORT . " registros. Puede filtrar los datos por algún criterio para poder exportarlos.";
                $data['link_volver'] = "temas/explorar/?{$busqueda_str}";
                $data['view_a'] = 'app/mensaje_v';
                
                $this->load->view(TPL_ADMIN, $data);
            }
    }    
    
    function nuevo()
    {
        //Cargando datos básicos
            
        //Render del grocery crud
            $gc_output = $this->Tema_model->crud_basico();
            
        //Solicitar vista
            $data['head_title'] = 'Temas';
            $data['head_subtitle'] = 'Nuevo';
            $data['view_a'] = 'comunes/gc_v';
            $data['nav_2'] = 'temas/explorar/menu_v';
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(TPL_ADMIN, $output);
    }
    
    function editar()
    {
        //Cargando datos básicos
            $tema_id = $this->uri->segment(4);
            $data = $this->Tema_model->basico($tema_id);
            
        //Render del grocery crud
            $gc_output = $this->Tema_model->crud_basico();
            
        //Solicitar vista
            $data['head_subtitle'] = 'Editar';
            $data['view_a'] = 'comunes/gc_v';
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(TPL_ADMIN, $output);
    }
    
// SECCIONES DATOS
//-----------------------------------------------------------------------------
    
    /**
     * Mostrar las páginas de un tema en formato flipbook para leer
     * 
     * @param type $tema_id
     * @param type $num_pagina
     */
    function leer($tema_id, $num_pagina = NULL)
    {
        //$this->output->enable_profiler(TRUE);
        //Datos básicos
            $data = $this->Tema_model->basico($tema_id);

        //Variables 
            $data['num_pagina'] = $this->Pcrn->si_nulo(0, $num_pagina);
            $data['bookmark'] = 0;
            $data['paginas'] = $this->Tema_model->paginas($tema_id);
            $data['archivos'] = $this->Tema_model->archivos_leer($tema_id);
            $data['quices'] = $this->Tema_model->quices($tema_id);
            $data['links'] = $this->Tema_model->links($tema_id);
            $data['anotaciones'] = $this->Tema_model->anotaciones($tema_id);
            $data['carpeta_uploads'] = URL_UPLOADS;
            $data['carpeta_iconos'] = URL_IMG . 'flipbook/';
        
        //Cargar vista
            $vista = 'app/no_permitido_v';
            $visible = TRUE;
            if ( $visible ) { $vista = 'temas/leer/leer_v'; }
            
            $this->load->view($vista, $data);
            
    }
    
    function info($tema_id = NULL)
    {
        
        //Cargando datos básicos
            $tema_id = $this->Pcrn->si_nulo($tema_id, $this->Tema_model->tema_id());
            $data = $this->Tema_model->basico($tema_id);
            
        //Información
            $row = $data['row'];
            $pagina_web = $this->Pcrn->si_vacia($row->pagina_web, 'No disponible', anchor($row->pagina_web, $this->Pcrn->cortar_izq($row->pagina_web, 7), 'target="_blank"'));
            $facebook = $this->Pcrn->si_vacia($row->facebook, 'No disponible', anchor($row->facebook, substr($row->nombre_tema, 0, 50), 'target="_blank"'));
            $twitter = $this->Pcrn->si_vacia($row->twitter, 'No disponible', anchor('https://twitter.com/' . $row->twitter, $row->twitter, 'target="_blank"'));
            $email = $this->Pcrn->si_vacia($row->email, 'No disponible', $row->email);
        
        //$data, variables específicas
            $data['seccion'] = 'info';
            $data['pagina_web'] = $pagina_web;
            $data['facebook'] = $facebook;
            $data['twitter'] = $twitter;
            $data['email'] = $email;
        
        //Solicitar vista
            $data['head_title'] .= ' - Información';
            //$data['view_a'] = 'temas/info_v';
            $this->load->view(TPL_ADMIN, $data);
        
    }
    
    /**
     * Temas relacionados
     */
    function relacionados($tema_id)
    {
        //Cargando datos básicos
            $data = $this->Tema_model->basico($tema_id);
            
        //Render del grocery crud
            $gc_output = $this->Tema_model->crud_relacionados($data['row']);
            
        //Solicitar vista
            $data['head_subtitle'] = 'Temas relacionados';
            $data['view_a'] = 'comunes/bs4/gc_v';
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(TPL_ADMIN, $output);
    }
    
    function quices($tema_id)
    {
        $this->load->model('Esp');

        //Cargando datos básicos
        $data = $this->Tema_model->basico($tema_id);
            
        $data['quices'] = $this->Tema_model->quices($tema_id);
            
        //Tipos de quices
            $data['arr_tipo_quiz'] = $this->Item_model->arr_item(9);
            
        //Solicitar vista
            $data['head_subtitle'] = 'Evidencias de aprendizaje';
            $data['view_a'] = 'temas/quices_v';
            $this->load->view(TPL_ADMIN, $data);
    }
    
    /**
     * AJAX
     * Asigna un quiz a un tema, lo relaciona en la tabla recurso
     */
    function asignar_quiz()
    {
        $tema_id = $this->input->post('tema_id');
        $quiz_id = $this->input->post('quiz_id');
        
        $recurso_id = $this->Tema_model->asignar_quiz($tema_id, $quiz_id);
                
        $this->output->set_content_type('application/json')->set_output($recurso_id);
    }
    
    /**
     * REDIRECT
     * Quita la asignación de un quiz a un tema. No elimina el quiz, solo elimina
     * la asignación en la tabla recurso.
     * 
     * @param type $quiz_id
     */
    function quitar_quiz($tema_id, $quiz_id)
    {
        $cant_eliminados = $this->Tema_model->quitar_quiz($tema_id, $quiz_id);
        
        $resultado['ejecutado'] = 1;
        $resultado['mensaje'] = "Se quitaron {$cant_eliminados} asignaciones de evidencias.";
        $resultado['clase'] = 'alert-success';
        $resultado['icono'] = 'fa-info-circle';
        
        $this->session->set_flashdata('resultado', $resultado);
        
        redirect("temas/quices/{$tema_id}");
    }
    
    function preguntas($tema_id)
    {   
        //Cargando datos básicos
            $data = $this->Tema_model->basico($tema_id);
            
        //Actualizar pregunta.orden
            $this->Tema_model->numerar_preguntas($tema_id);
            
        //Cargando $data
            $data['preguntas'] = $this->Tema_model->preguntas($tema_id);
            
        //Solicitar vista
            $data['head_subtitle'] = 'Preguntas';
            $data['view_a'] = 'temas/preguntas_v';
            $this->load->view(TPL_ADMIN, $data);
    }
    
    function programas($tema_id)
    {
        //Cargando datos básicos
            $data = $this->Tema_model->basico($tema_id);
            
        //programas
            $this->db->join('programa', 'programa_tema.programa_id = programa.id');
            $this->db->where('tema_id', $tema_id);
            $programas = $this->db->get('programa_tema');
            
        //Cargando $data
            $data['programas'] = $programas;
            
        //Solicitar vista
            $data['head_subtitle'] = 'Programas';
            $data['view_a'] = 'temas/programas_v';
            $this->load->view(TPL_ADMIN, $data);
    }
    
    function paginas($tema_id)
    {
        
        //Cargando datos básicos
            $this->load->model('Pagina_model');
            $data = $this->Tema_model->basico($tema_id);
            
        //paginas
            $this->db->where('tema_id', $tema_id);
            $this->db->where('pagina_origen_id IS NULL');
            $this->db->order_by('orden', 'ASC');
            $paginas = $this->db->get('pagina_flipbook');
            
        //Cargando $data
            $data['paginas'] = $paginas;
            
            
        //Solicitar vista
            $data['head_subtitle'] = 'Páginas';
            $data['view_a'] = 'temas/paginas_v';
            $this->load->view(TPL_ADMIN, $data);
    }

// GESTIÓN DE ARCHIVOS ASOCIADOS A TEMAS
//-----------------------------------------------------------------------------
    
    function archivos($tema_id)
    {       
        //Cargando datos básicos
            $data = $this->Tema_model->basico($tema_id);
        
        //Archivos
            $data['archivos'] = $this->Tema_model->archivos($tema_id);
            $data['options_type'] = $this->Item_model->opciones_id('categoria_id = 20', 'Elija el tipo de archivo');
            $data['options_yn'] = $this->Item_model->opciones('categoria_id = 55 AND id_interno < 2');

            $data['arr_types'] = $this->Item_model->arr_item(20, 'id');
            $data['arr_yn'] = $this->Item_model->arr_item(55, 'id_interno_num');
            
        //Solicitar vista
            $data['head_subtitle'] = 'Archivos';
            $data['view_a'] = 'temas/archivos_v';
            $this->load->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Listado de archivos asociados a un tema
     * 2020-01-23
     */
    function get_archivos($tema_id)
    {
        $archivos = $this->Tema_model->archivos($tema_id);
        $data['archivos'] = $archivos->result();

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Guarda un archivo asociado a un tema
     * 2020-01-23
     */
    function save_archivo($tema_id, $archivo_id = 0)
    {
        $data = $this->Tema_model->save_archivo($tema_id, $archivo_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Elimina un archivo asociado a un tema
     * 2020-01-23
     */
    function delete_archivo($tema_id, $archivo_id)
    {
        $data = $this->Tema_model->delete_archivo($tema_id, $archivo_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// GESTIÓN DE LINKS ASOCIADOS A TEMAS
//-----------------------------------------------------------------------------
    
    function links($tema_id)
    {       
        //Cargando datos básicos
            $data = $this->Tema_model->basico($tema_id);
        
        //Variables
            $data['links'] = $this->Tema_model->links($tema_id);
            $data['options_componente'] = $this->Item_model->opciones_id('categoria_id = 8', 'Seleccione el componente');
            $data['arr_componentes'] = $this->Item_model->arr_item(8, 'id');
            
        //Solicitar vista
            $data['head_subtitle'] = 'Links';
            $data['view_a'] = 'temas/links_v';
            $this->load->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Listado de links asociados a un tema
     * 2019-09-02
     */
    function get_links($tema_id)
    {
        $links = $this->Tema_model->links($tema_id);

        $data['links'] = $links->result();

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Guarda un link asociado a un tema
     * 2019-09-02
     */
    function save_link($tema_id, $link_id = 0)
    {
        $data = $this->Tema_model->save_link($tema_id, $link_id);

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Elimina el un link asociado a un tema
     * 2019-09-02
     */
    function delete_link($tema_id, $link_id)
    {
        $data = $this->Tema_model->delete_link($tema_id, $link_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Abrir link, recurso de un tema, registra evento y redirige a url
     * 2020-10-21
     */
    function open_link($tema_id, $link_id, $area_id, $nivel)
    {
        //Identificar URL destino
        $url_link = trim($this->input->get('url_link'));

        //Construir registro para tabla evento
        $arr_row['fecha_inicio'] = date('Y-m-d');
        $arr_row['hora_inicio'] = date('H:i:s');
        $arr_row['tipo_id'] = 73;   //Evento tipo abrir link
        $arr_row['url'] = $url_link;
        $arr_row['referente_id'] = $tema_id;
        $arr_row['referente_2_id'] = $link_id;
        $arr_row['entero_1'] = $this->input->get('flipbook_id');
        $arr_row['usuario_id'] = $this->session->userdata('user_id');
        $arr_row['institucion_id'] = $this->session->userdata('institucion_id');
        $arr_row['grupo_id'] = $this->session->userdata('grupo_id');
        $arr_row['area_id'] = $area_id;
        $arr_row['nivel'] = $nivel;

        //Guardar evento
        $this->load->model('Evento_model');
        $this->Evento_model->guardar_evento($arr_row, "usuario_id = {$arr_row['usuario_id']} AND referente_2_id = {$arr_row['referente_2_id']}");

        //Redirigir
        redirect($url_link, 'refresh');
    }
    
// CARGA MASIVA DE TEMAS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Mostrar formulario de importación de temas mediante archivo MS Excel.
     * El resultado del formulario se envía a 'temas/importar_e'
     * 
     */
    function importar()
    {
        
        //Iniciales
            $nombre_archivo = '08_formato_cargue_temas.xlsx';
            $parrafos_ayuda = array();
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo importar temas?';
            $data['nota_ayuda'] = 'Se importarán temas a la Plataforma.';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = 'temas/importar_e';
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'temas';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            $data['head_title'] = 'Temas';
            $data['head_subtitle'] = 'Importar temas';
            $data['view_a'] = 'comunes/bs4/importar_v';
            $data['nav_2'] = 'temas/explorar/menu_v';
            $data['nav_3'] = 'temas/menu_importar_v';
        
        $this->load->view(TPL_ADMIN, $data);
    }
    
    /**
     * Importar programas, (e) ejecutar.
     */
    function importar_e()
    {
        
        //Proceso
            $this->load->model('Pcrn_excel');
            $no_importados = array();
            $letra_columna = 'H';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $this->load->model('Tema_model');
                $no_importados = $this->Tema_model->importar($resultado['array_hoja']);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $no_importados;
            $data['destino_volver'] = 'temas/explorar/';
        
        //Cargar vista
            $data['head_title'] = 'Temas';
            $data['head_subtitle'] = 'Resultado cargue';
            $data['view_a'] = 'comunes/resultado_importacion_v';
            $data['nav_2'] = 'temas/menu_explorar_v';
            $data['ayuda_id'] = 104;
            $this->load->view(TPL_ADMIN, $data);
    }
    
// COPIAR MASIVAMENTE PREGUNTAS DE UN TEMA A OTRO
//-----------------------------------------------------------------------------
    
    /**
     * Mostrar formulario de cargue de archivo excel con listado de temas
     * origen y destino, para copia y asignación de preguntas
     * 
     */
    function copiar_preguntas()
    {
        //Iniciales
            $nombre_archivo = '26_formato_copiar_preguntas.xlsx';
            $parrafos_ayuda = array(
                'La columna A y B no pueden estar vacías.'
            );
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo copiar preguntas de un tema a otro?';
            $data['nota_ayuda'] = 'Se crearán copias de preguntas del tema en la columna [A] y se asignan a los temas de la columna [B]';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = "temas/copiar_preguntas_e";
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'temas_preguntas';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            //$data['ayuda_id'] = 97;
            $data['head_title'] = 'Temas';
            $data['head_subtitle'] = 'Copiar preguntas de temas';
            $data['view_a'] = 'comunes/bs4/importar_v';
            $data['nav_2'] = 'temas/explorar/menu_v';
            $data['nav_3'] = 'temas/menu_importar_v';
        
        $this->load->view(TPL_ADMIN, $data);
    }
    
    /**
     * Copiar preguntas de un tema a otro, desde excel. Proviene de temas/copiar_preguntas
     */
    function copiar_preguntas_e()
    {
        //Proceso
            $this->load->model('Pcrn_excel');
            $this->load->model('Esp');
            $letra_columna = 'B';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $res_importacion = $this->Tema_model->copiar_preguntas_masivo($resultado['array_hoja']);
                $data['no_importados'] = $res_importacion['no_importados'];
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['destino_volver'] = "temas/explorar/";
        
        //Cargar vista
            $data['head_title'] = 'Temas';
            $data['head_subtitle'] = 'Resultado copia de preguntas';
            $data['view_a'] = 'comunes/resultado_importacion_v';
            $data['nav_2'] = 'temas/explorar/menu_v';
            //$data['vista_submenu'] = 'usuarios/importar_menu_v';
            $this->load->view(TPL_ADMIN, $data);
    }
    
// ASIGNAR MASIVAMENTE QUICES DE UN TEMA A OTRO
//-----------------------------------------------------------------------------
    
    /**
     * Mostrar formulario de cargue de archivo excel con listado de temas
     * origen y destino, para asignación de quices
     * 
     */
    function asignar_quices()
    {
        //Iniciales
            $nombre_archivo = '27_formato_asignar_quices.xlsx';
            $parrafos_ayuda = array(
                'La columna A y B no pueden estar vacías.',
                'Las evidencias quedarán asignados a los dos temas al mismo tiempo.'
            );
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo asignar evidencias de un tema a otro?';
            $data['nota_ayuda'] = 'Se asignarán las evidencias del tema de la columna [A] a los temas de la columna [B]';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = "temas/asignar_quices_e";
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'temas_quices';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            //$data['ayuda_id'] = 97;
            $data['head_title'] = 'Temas';
            $data['head_subtitle'] = 'Asignar evidencias';
            $data['view_a'] = 'comunes/bs4/importar_v';
            $data['nav_2'] = 'temas/explorar/menu_v';
            $data['nav_3'] = 'temas/menu_importar_v';
        
        $this->load->view(TPL_ADMIN, $data);
    }
    
    /**
     * Asignar evidencias de un tema a otro, desde excel. Proviene de temas/asignar_quices
     */
    function asignar_quices_e()
    {
        //Proceso
            $this->load->model('Pcrn_excel');
            $this->load->model('Esp');
            $letra_columna = 'B';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $res_importacion = $this->Tema_model->asignar_quices_masivo($resultado['array_hoja']);
                $data['no_importados'] = $res_importacion['no_importados'];
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['destino_volver'] = "temas/asignar_quices/";
        
        //Cargar vista
            $data['head_title'] = 'Temas';
            $data['head_subtitle'] = 'Resultado asignar quices';
            $data['view_a'] = 'comunes/resultado_importacion_v';
            $data['nav_2'] = 'temas/explorar/menu_v';
            $data['nav_3'] = 'usuarios/importar_menu_v';
            $this->load->view(TPL_ADMIN, $data);
    }
    
    /**
     * Formulario para la creación de una copia de un tema
     * 
     * 
     * @param type $tema_id 
     */
    function copiar($tema_id)
    {
        //Cargando datos básicos (_basico)
            $data = $this->Tema_model->basico($tema_id);
        
        //Variables data
            $data['destino_form'] = 'temas/generar_copia';
        
        //Solicitar vista
            $data['head_subtitle'] = 'Crear copia';
            $data['view_a'] = 'temas/copiar_tema_v';
            $this->load->view(TPL_ADMIN, $data);
    }
    
    /**
     * Ejecuta el proceso de crear la copia, proviene de temas/copiar
     * 
     * Se copian las características del tema, y los elementos realacionados
     *  
     */
    function generar_copia()
    {
        //Validación del formulario
        $this->load->library('form_validation');

        //Reglas
            $this->form_validation->set_rules('nombre_tema', 'Nombre del tema', 'max_length[200]|required|is_unique[tema.nombre_tema]');
            $this->form_validation->set_rules('cod_tema', 'Código tema', 'max_length[20]|required|is_unique[tema.cod_tema]');

        //Mensajes de validación
            $this->form_validation->set_message('max_length', "El campo [ %s ] puede tener hasta 200 caracteres");
            $this->form_validation->set_message('required', "El [ %s ] no puede estar vacío");
            $this->form_validation->set_message('is_unique', "El valor de [ %s ] ya está en uso, por favor elija otro");

        //Comprobar validación
            if ( $this->form_validation->run() == FALSE ) {
                //No se cumple la validación, se regresa al tema
                $this->copiar($this->input->post('tema_id'));
            } else {
                //Se cumple la validación, se genera la copia del tema
                
                //Preparar datos para la copia
                    $datos['cod_tema'] = $this->input->post('cod_tema');
                    $datos['nombre_tema'] = $this->input->post('nombre_tema');
                    $datos['tema_id'] = $this->input->post('tema_id');
                    $datos['descripcion'] = $this->input->post('descripcion');
                
                $nuevo_tema_id = $this->Tema_model->generar_copia($datos);
                
                //Se redirige al nuevo tema creado
                redirect("temas/archivos/{$nuevo_tema_id}");
            }
            
    }
    
    /**
     * Quitar una página flipbook de un tema
     * 
     * @param type $tema_id
     * @param type $pagina_id
     */
    function quitar_pf($tema_id, $pagina_id)
    {
        //Actualizar pf
            $registro['tema_id'] = NULL;
            $this->db->where('id', $pagina_id);
            $this->db->update('pagina_flipbook', $registro);
            
        //Actualizar números de orden, campo pagina_flipbook.orden
            $this->Tema_model->enumerar_pf($tema_id);
            
        //Volver
            $resultado['mensaje'] = 'La página fue quitada del tema, pero todavía se encuentra en la plataforma sin tema asignado';
            $this->session->set_flashdata('resultado', $resultado);
            
            redirect("temas/paginas/{$tema_id}");
    }
    
    /**
     * Cambia el valor del campo pagina_flipbook.orden
     * 
     * Se modifica también la posición de la página contigua, + o - 1
     * 
     * @param type $tema_id
     * @param type $pf_id
     * @param type $subir 
     */
    function mover_pagina($tema_id, $pf_id, $pos_final)
    {
        //Cambiar la posición de una página en un tema
        $this->Tema_model->cambiar_pos_pag($tema_id, $pf_id, $pos_final);
        
        $data['url'] = base_url() . "temas/paginas/$tema_id";
        $data['msg_redirect'] = '';
        $this->load->view('app/redirect_v', $data);
        
    }
    
    function quitar_pregunta($tema_id, $pregunta_id)
    {
        $this->Tema_model->quitar_pregunta($tema_id, $pregunta_id);
        $this->preguntas($tema_id);
    }
    
    /**
     * Cambia el valor del campo pregunta.orden
     * 
     * Se modifica también la posición de la página contigua, + o - 1
     * 
     * @param type $tema_id
     * @param type $pregunta_id
     * @param type $subir 
     */
    function mover_pregunta($tema_id, $pregunta_id, $pos_final)
    {
        //Cambiar la posición de una página en un tema
        $this->Tema_model->cambiar_pos_pregunta($tema_id, $pregunta_id, $pos_final);
        
        $data['url'] = base_url() . "temas/preguntas/$tema_id";
        $data['msg_redirect'] = '';
        $this->load->view('app/redirect_v', $data);
        
    }
    
    function agregar_pregunta($tema_id, $orden, $proceso = '')
    {
        
        //Cargando datos básicos (_basico)
            $data = $this->Tema_model->basico($tema_id, $orden);

        //Ejecutar búsqueda
            $this->load->model('Search_model');
            $filters = $this->Search_model->filters();
            $this->load->model('Pregunta_model');
            $resultados = $this->Pregunta_model->search($filters, 100, 0);
            
        //Grocery crud para agregar nueva pregunta
            $this->session->set_userdata('tema_id', $tema_id);
            $this->session->set_userdata('orden', $orden);
            $this->load->model('Pregunta_model');
            $registro_tema['nivel'] = $data['row']->nivel;
            $registro_tema['area_id'] = $data['row']->area_id;
            $gc_output = $this->Pregunta_model->crud_add_tema($tema_id, $registro_tema, $orden);
            
        //Establecer vista
            $data['view_a'] = 'temas/agregar_pregunta_v';
            if ( $proceso == 'add' ){ $data['view_a'] = 'temas/agregar_pregunta_add_v'; }
            
        //Variables
            $data['filters'] = $filters;
            $data['proceso'] = $proceso;
            $data['orden'] = $orden;
            $data['orden_mostrar'] = $orden + 1;
            $data['preguntas'] = $resultados;
        
        //Solicitar vista
            $data['head_subtitle'] = 'Agregar pregunta';
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(TPL_ADMIN, $output);
        
    }
    
    /**
     * Respuesta ajax
     * 
     */
    function actualizar_paginas()
    {
        $this->load->model('Busqueda_model');
        $this->load->model('Pagina_model');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $temas = $this->Busqueda_model->temas($busqueda); //Para calcular el total de resultados
            
            $cant_paginas = 0;
            foreach ( $temas->result() as $row_tema ) {
                $this->Tema_model->eliminar_paginas($row_tema->id);
                $cant_paginas += $this->Tema_model->actualizar_paginas($row_tema);
            }
            
        echo $cant_paginas;
    }

// RECURSOS DE TEMAS
//---------------------------------------------------------------------------------------------------
 
    function recursos_archivos()
    {
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('recurso');
        $crud->set_subject('archivo');
        //$crud->unset_export();
        $crud->unset_print();
        $crud->unset_read();
        $crud->columns('nombre_archivo', 'tema_id', 'tipo_archivo_id', 'editado');

        //Títulos de campo
            $crud->display_as('tema_id', 'cod_tema');
            $crud->display_as('tipo_archivo_id', 'Tipo archivo');
        
        //Filtro
            $crud->where('tipo_recurso_id', 1); //Archivos

        //Relaciones
            $crud->set_relation('tipo_archivo_id', 'item', 'item', 'categoria_id = 20');
            $crud->set_relation('tema_id', 'tema', 'cod_tema');

        //Reglas de validación
            $crud->set_rules('nombre_archivo', 'Nombre archivo', 'required');

        //Valores por defecto
            $crud->field_type('usuario_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->field_type('editado', 'hidden', date('Y-m-d H:i:s'));

        $gc_output = $crud->render();

        //Solicitar vista
            $data['head_title'] = 'Recursos';
            $data['head_subtitle'] = 'Archivos';
            $data['nav_2'] = 'temas/explorar/menu_v';
            $data['view_a'] = 'temas/recursos_v';

        $output = array_merge($data,(array)$gc_output);
        $this->load->view(TPL_ADMIN, $output);
    }
    
    function recursos_links()
    {

        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('recurso');
        $crud->set_subject('link');
        //$crud->unset_export();
        $crud->unset_print();
        $crud->unset_read();
        $crud->columns('url', 'tema_id', 'editado');
        

        //Títulos de campo
            $crud->display_as('tema_id', 'cod_tema');
        
        //Filtro
            $crud->where('tipo_recurso_id', 2); //Links

        //Relaciones
            $crud->set_relation('tipo_archivo_id', 'item', 'item', 'categoria_id = 20');
            $crud->set_relation('tema_id', 'tema', 'cod_tema');

        //Reglas de validación
            $crud->set_rules('nombre_archivo', 'Nombre archivo', 'required');

        //Valores por defecto
            $crud->field_type('usuario_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->field_type('editado', 'hidden', date('Y-m-d H:i:s'));

        $output = $crud->render();

        //Head includes específicos para la página
            $head_includes[] = 'grocery_crud';
        
            $data['head_includes'] = $head_includes;

        //Solicitar vista
            $data['head_title'] = 'Recursos';
            $data['head_subtitle'] = 'Archivos';
            $data['nav_2'] = 'temas/explorar/menu_v';
            $data['view_a'] = 'temas/recursos_v';

        $output = array_merge($data,(array)$output);
        $this->load->view(TPL_ADMIN, $output);
    }
    
    function recursos_quices()
    {

        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('recurso');
        $crud->set_subject('quiz');
        //$crud->unset_export();
        $crud->unset_print();
        $crud->unset_read();
        $crud->columns('referente_id', 'tipo_quiz', 'tema_id', 'editado');
                        
        //Títulos de campo
            $crud->display_as('tema_id', 'cod_tema');
            $crud->display_as('referente_id', 'Nombre quiz');
        
        //Filtro
            $crud->where('tipo_recurso_id', 3); //Quices

        //Relaciones
            $crud->set_relation('tipo_archivo_id', 'item', 'item', 'categoria_id = 20');
            $crud->set_relation('tema_id', 'tema', 'cod_tema');
            $crud->set_relation('referente_id', 'quiz', 'nombre_quiz');

        //Reglas de validación
            $crud->set_rules('nombre_archivo', 'Nombre archivo', 'required');

        //Valores por defecto
            $crud->field_type('usuario_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->field_type('editado', 'hidden', date('Y-m-d H:i:s'));

        $output = $crud->render();

        //Head includes específicos para la página
            $head_includes[] = 'grocery_crud';
        
            $data['head_includes'] = $head_includes;

        //Solicitar vista
        $data['head_title'] = 'Recursos';
        $data['head_subtitle'] = 'Archivos';
        $data['view_a'] = 'temas/recursos_v';
        $data['nav_2'] = 'temas/explorar/menu_v';

        $output = array_merge($data,(array)$output);
        $this->load->view(TPL_ADMIN, $output);
    }

// UNIDADES TEMÁTICAS
//---------------------------------------------------------------------------------------------------
    
    function importar_ut()
    {
        //Iniciales
            $nombre_archivo = '18_formato_temas_relacionados_ut.xlsx';
            $parrafos_ayuda = array();
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo importar temas a unidades temáticas?';
            $data['nota_ayuda'] = 'Se asignarán temas a las Unidades temáticas.';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = 'temas/importar_ut_e';
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'temas_ut';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            $data['head_title'] = 'Temas';
            $data['head_subtitle'] = 'Importar elementos UT';
            $data['view_a'] = 'comunes/bs4/importar_v';
            $data['nav_2'] = 'temas/explorar/menu_v';
            $data['nav_3'] = 'temas/menu_importar_v';
            $data['ayuda_id'] = 100;
        
        $this->load->view(TPL_ADMIN, $data);
    }
    
    /**
     * Importar asignación de temas a unidades temáticas, (e) ejecutar.
     * Recibe archivo y datos de "temas/importar_ut"
     */
    function importar_ut_e()
    {
        
        //Proceso
            $this->load->model('Pcrn_excel');
            $no_importados = array();
            $letra_columna = 'C';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $this->load->model('Tema_model');
                $no_importados = $this->Tema_model->importar_ut($resultado['array_hoja']);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $no_importados;
            $data['destino_volver'] = 'temas/explorar/';
        
        //Cargar vista
            $data['head_title'] = 'Temas';
            $data['head_subtitle'] = 'Resultado asignación UT';
            $data['view_a'] = 'comunes/resultado_importacion_v';
            $data['nav_2'] = 'temas/explorar/menu_v';
            $data['ayuda_id'] = 100;
            $this->load->view(TPL_ADMIN, $data);
    }
    
// PREGUNTAS ABIERTAS (pa)
//-----------------------------------------------------------------------------

    /**
     * Vista para gestión de preguntas abiertas (pa) asociadas a tema
     * 2019-09-06
     */
    function preguntas_abiertas($tema_id)
    {
        $data = $this->Tema_model->basico($tema_id);
        $data['view_a'] = 'temas/preguntas_abiertas_v';
        $data['subtitle_head'] = 'Preguntas abiertas';
        $this->load->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Listado de preguntas abiertas (pa) asociadass a un tema
     * 2019-09-06
     */
    function get_pa($tema_id)
    {
        $preguntas_abiertas = $this->Tema_model->preguntas_abiertas($tema_id);

        $data['preguntas_abiertas'] = $preguntas_abiertas->result();

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    /**
     * Guarda una pregunta abierta (pa) asociada a un tema
     * 2019-09-06
     */
    function save_pa($tema_id, $pa_id = 0)
    {
        $data = $this->Tema_model->save_pa($tema_id, $pa_id);

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    /**
     * Elimina el una pregunta abierta (pa) asociado a un tema
     * 2019-09-06
     */
    function delete_pa($tema_id, $pa_id)
    {
        $data = $this->Tema_model->delete_pa($tema_id, $pa_id);
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    /**
     * Vista formulario importación de preguntas abiertas para temas
     * 2019-09-06
     */
    function importar_pa()
    {
        //Iniciales
            $nombre_archivo = '31_formato_cargue_preguntas_abiertas.xlsx';
            $parrafos_ayuda = array();
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo importar preguntas abiertas?';
            $data['nota_ayuda'] = 'Se importarán preguntas abiertas asociadas a cada tema';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = 'temas/importar_pa_e';
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'preguntas_abiertas';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            $data['head_title'] = 'Temas';
            $data['head_subtitle'] = 'Importar preguntas abiertas';
            $data['view_a'] = 'comunes/bs4/importar_v';
            $data['nav_2'] = 'temas/explorar/menu_v';
            $data['nav_3'] = 'temas/menu_importar_v';
        
        $this->load->view(TPL_ADMIN, $data);
    }
    
    /**
     * Importar preguntas abiertas a los temas, (e) ejecutar.
     * Recibe archivo y datos de "temas/importar_pa"
     * 2019-09-06
     */
    function importar_pa_e()
    {
        
        //Proceso
            $this->load->model('Pcrn_excel');
            $no_importados = array();
            $letra_columna = 'B';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $this->load->model('Tema_model');
                $no_importados = $this->Tema_model->importar_pa($resultado['array_hoja']);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $no_importados;
            $data['destino_volver'] = 'temas/explorar/';
        
        //Cargar vista
            $data['head_title'] = 'Temas';
            $data['head_subtitle'] = 'Resultado importación PA';
            $data['view_a'] = 'comunes/resultado_importacion_v';
            $data['nav_2'] = 'temas/explorar/menu_v';
            $data['nav_3'] = 'temas/menu_importar_v';
            $this->load->view(TPL_ADMIN, $data);
    }

// LECTURAS DINÁMICAS (ledin)
//-----------------------------------------------------------------------------

    /**
     * Lecturas dinámicas, asociadas a un tema
     * 2019-10-17
     */
    function lecturas_dinamicas($tema_id, $ledin_id = NULL)
    {
        $data = $this->Tema_model->basico($tema_id);

        $data['ledins'] = $this->Tema_model->ledins($tema_id);
        $data['ledin_id'] = $ledin_id;

        if ( is_null($ledin_id) && $data['ledins']->num_rows() > 0 ) {
            $data['ledin_id'] = $data['ledins']->row()->id;
        }

        $data['ledin'] = $this->Tema_model->ledin($data['ledin_id']);

        $data['view_a'] = 'temas/ledins/ledins_v';
        $data['subtitle_head'] = 'Lecturas dinámicas';
        $this->load->view(TPL_ADMIN, $data);
    }

    function lectura_dinamica($ledin_id, $json = FALSE)
    {
        $data['ledin_id'] = $ledin_id;
        $data['ledin'] = $this->Tema_model->ledin($ledin_id);
        
        if ( $json )
        {
            //Salida JSON
            $data_json['html'] = $this->load->view('temas/ledins/ledin_v', $data, true);
            $this->output->set_content_type('application/json')->set_output(json_encode($data_json));
        } else {
            
            $data['view_a'] = 'temas/ledins/ledin_v';
            $data['head_title'] = $data['ledin']->nombre_post;
            $data['subtitle_head'] = 'Lecturas dinámicas';
            $this->load->view(TPL_ADMIN, $data);
        }
    }

    function lectura_dinamica_tema($tema_id)
    {
        $ledins = $this->Tema_model->ledins($tema_id);
        
        $data_json['status'] = 0;
        $data_json['message'] = 'Este tema no tiene lectura dinámica asignada';
        $data_json['html'] = 'Este tema no tiene lectura dinámica asignada';

        if ( $ledins->num_rows() > 0 )
        {
            $data['ledin_id'] = $ledins->row()->id;
            $data['ledin'] = $this->Tema_model->ledin($ledins->row()->id);
            $data_json['status'] = 1;
            $data_json['message'] = 'Sí tiene ledin: ' . $data['ledin_id'];
            $data_json['html'] = $this->load->view('temas/ledins/ledin_v', $data, true);
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data_json));
    }

    /**
     * Vista formulario importación de lecturas ledins para temas
     * 2019-10-17
     */
    function importar_lecturas_dinamicas()
    {
        //Iniciales
            $nombre_archivo = '32_formato_cargue_lecturas.xlsx';
            $parrafos_ayuda = array();
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo importar lecturas dinámicas?';
            $data['nota_ayuda'] = 'Se importarán lecturas dinámicas asociadas a cada tema';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = 'temas/importar_lecturas_dinamicas_e';
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'lecturas';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            $data['head_title'] = 'Temas';
            $data['head_subtitle'] = 'Importar lecturas dinámicas';
            $data['view_a'] = 'comunes/bs4/importar_v';
            $data['nav_2'] = 'temas/explorar/menu_v';
            $data['nav_3'] = 'temas/menu_importar_v';
        
        $this->load->view(TPL_ADMIN, $data);
    }
    
    /**
     * Importar preguntas abiertas a los temas, (e) ejecutar.
     * Recibe archivo y datos de "temas/importar_pa"
     * 2019-09-06
     */
    function importar_lecturas_dinamicas_e()
    {
        //Proceso
            $this->load->model('Pcrn_excel');
            $no_importados = array();
            $letra_columna = 'W';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $this->load->model('Tema_model');
                $no_importados = $this->Tema_model->importar_ledins($resultado['array_hoja']);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $no_importados;
            $data['destino_volver'] = 'temas/importar_lecturas_dinamicas/';
        
        //Cargar vista
            $data['head_title'] = 'Temas';
            $data['head_subtitle'] = 'Resultado importación Lecturas dinámicas';
            $data['view_a'] = 'comunes/resultado_importacion_v';
            $data['nav_2'] = 'temas/explorar/menu_v';
            $data['nav_3'] = 'temas/menu_importar_v';
            $this->load->view(TPL_ADMIN, $data);
    }
}