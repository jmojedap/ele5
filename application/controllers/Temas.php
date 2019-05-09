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

//INFORMACIÓN DE TEMAS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Para evitar error de reenvío de formulario en búsquedas
     */
    function explorar_post()
    {
        
        $this->load->model('Busqueda_model');
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        redirect("temas/explorar/?{$busqueda_str}");
    }
    
    function explorar()
    {
        //$this->output->enable_profiler(TRUE);
        
        $this->load->model('Esp');
        $this->load->model('Busqueda_model');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Tema_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(2);
            $config['base_url'] = base_url("temas/explorar/?{$busqueda_str}");
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Tema_model->buscar($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
            $data['arr_nivel'] = $this->App_model->arr_nivel();
            $data['arr_tipo_quiz'] = $this->Item_model->arr_item(9);   //Tipos de quices
        
        //Solicitar vista
            $data['ayuda_id'] = 141;
            $data['titulo_pagina'] = 'Temas';
            $data['subtitulo_pagina'] = number_format($config['total_rows'], 0, ',', '.');
            $data['vista_a'] = 'temas/explorar_v';
            $data['vista_menu'] = 'temas/menu_explorar_v';
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
                $data['titulo_pagina'] = 'Plataforma Enlace';
                $data['mensaje'] = "El número de registros es de {$resultados_total->num_rows()}. El máximo permitido es de " . MAX_REG_EXPORT . " registros. Puede filtrar los datos por algún criterio para poder exportarlos.";
                $data['link_volver'] = "temas/explorar/?{$busqueda_str}";
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
            $this->Tema_model->eliminar($elemento_id);
        }
        
        echo count($seleccionados);
    }    
    
    function nuevo()
    {
        //Cargando datos básicos
            
        //Render del grocery crud
            $gc_output = $this->Tema_model->crud_basico();
            
        //Solicitar vista
            $data['titulo_pagina'] = 'Temas';
            $data['subtitulo_pagina'] = 'Nuevo';
            $data['vista_a'] = 'comunes/gc_v';
            $data['vista_menu'] = 'temas/menu_explorar_v';
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(PTL_ADMIN, $output);
    }
    
    function editar()
    {
        //Cargando datos básicos
            $tema_id = $this->uri->segment(4);
            $data = $this->Tema_model->basico($tema_id);
            
        //Render del grocery crud
            $gc_output = $this->Tema_model->crud_basico();
            
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Editar';
            $data['vista_b'] = 'comunes/gc_v';
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(PTL_ADMIN, $output);
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
            $data['titulo_pagina'] .= ' - Información';
            //$data['vista_b'] = 'temas/info_v';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    function relacionados($tema_id)
    {
        //Cargando datos básicos
            $data = $this->Tema_model->basico($tema_id);
            
        //Render del grocery crud
            $gc_output = $this->Tema_model->crud_relacionados($data['row']);
            
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Temas relacionados';
            $data['vista_b'] = 'comunes/gc_v';
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(PTL_ADMIN, $output);
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
            $data['subtitulo_pagina'] = 'Evidencias de aprendizaje';
            $data['vista_b'] = 'temas/quices_v';
            $this->load->view(PTL_ADMIN, $data);
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
                
        $this->output
        ->set_content_type('application/json')
        ->set_output($recurso_id);
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
            $data['subtitulo_pagina'] = 'Preguntas';
            $data['vista_b'] = 'temas/preguntas_v';
            $this->load->view(PTL_ADMIN, $data);
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
            $data['subtitulo_pagina'] = 'Programas';
            $data['vista_b'] = 'temas/programas_v';
            $this->load->view(PTL_ADMIN, $data);
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
            $data['subtitulo_pagina'] = 'Páginas';
            $data['vista_b'] = 'temas/paginas_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function archivos($tema_id)
    {       
        //Cargando datos básicos
            $data = $this->Tema_model->basico($tema_id);
            
        //Render del grocery crud
            $gc_output = $this->Tema_model->crud_archivos($tema_id);
        
        //Archivos
            $data['archivos'] = $this->Tema_model->archivos($tema_id);
            
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Archivos';
            $data['vista_b'] = 'comunes/gc_v';
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(PTL_ADMIN, $output);
    }
    
    function links($tema_id)
    {       
        //Cargando datos básicos
            $data = $this->Tema_model->basico($tema_id);
            
        //Render del grocery crud
            $gc_output = $this->Tema_model->crud_links($tema_id);
        
        //Archivos
            $data['archivos'] = $this->Tema_model->archivos($tema_id);
            
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Links';
            $data['vista_b'] = 'comunes/gc_v';
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(PTL_ADMIN, $output);
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
            $data['titulo_pagina'] = 'Temas';
            $data['subtitulo_pagina'] = 'Importar temas';
            $data['vista_a'] = 'comunes/importar_v';
            $data['vista_menu'] = 'temas/menu_importar_v';
            $data['ayuda_id'] = 104;
        
        $this->load->view(PTL_ADMIN, $data);
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
            $data['titulo_pagina'] = 'Temas';
            $data['subtitulo_pagina'] = 'Resultado cargue';
            $data['vista_a'] = 'comunes/resultado_importacion_v';
            $data['vista_menu'] = 'temas/menu_explorar_v';
            $data['ayuda_id'] = 104;
            $this->load->view(PTL_ADMIN, $data);
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
            $data['titulo_pagina'] = 'Temas';
            $data['subtitulo_pagina'] = 'Copiar preguntas de temas';
            $data['vista_a'] = 'comunes/importar_v';
            $data['vista_menu'] = 'temas/menu_importar_v';
            //$data['vista_submenu'] = '';
        
        $this->load->view(PTL_ADMIN, $data);
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
            $data['titulo_pagina'] = 'Temas';
            $data['subtitulo_pagina'] = 'Resultado copia de preguntas';
            $data['vista_a'] = 'comunes/resultado_importacion_v';
            $data['vista_menu'] = 'temas/menu_importar_v';
            //$data['vista_submenu'] = 'usuarios/importar_menu_v';
            $this->load->view(PTL_ADMIN, $data);
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
            $data['titulo_pagina'] = 'Temas';
            $data['subtitulo_pagina'] = 'Asignar evidencias';
            $data['vista_a'] = 'comunes/importar_v';
            $data['vista_menu'] = 'temas/menu_importar_v';
            //$data['vista_submenu'] = '';
        
        $this->load->view(PTL_ADMIN, $data);
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
            $data['titulo_pagina'] = 'Temas';
            $data['subtitulo_pagina'] = 'Resultado asignar quices';
            $data['vista_a'] = 'comunes/resultado_importacion_v';
            $data['vista_menu'] = 'temas/menu_importar_v';
            //$data['vista_submenu'] = 'usuarios/importar_menu_v';
            $this->load->view(PTL_ADMIN, $data);
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
            $data['subtitulo_pagina'] = 'Crear copia';
            $data['vista_b'] = 'temas/copiar_tema_v';
            $this->load->view(PTL_ADMIN, $data);
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
            $this->load->model('Busqueda_model');
            $busqueda = $this->Busqueda_model->busqueda_array();
            $this->load->model('Pregunta_model');
            $resultados = $this->Pregunta_model->buscar($busqueda, 100, 0);
            
        //Grocery crud para agregar nueva pregunta
            $this->session->set_userdata('tema_id', $tema_id);
            $this->session->set_userdata('orden', $orden);
            $this->load->model('Pregunta_model');
            $registro_tema['nivel'] = $data['row']->nivel;
            $registro_tema['area_id'] = $data['row']->area_id;
            $gc_output = $this->Pregunta_model->crud_add_tema($tema_id, $registro_tema, $orden);
            
        //Establecer vista
            $data['vista_b'] = 'temas/agregar_pregunta_v';
            if ( $proceso == 'add' ){ $data['vista_b'] = 'temas/agregar_pregunta_add_v'; }
            
        //Variables
            $data['busqueda'] = $busqueda;
            $data['proceso'] = $proceso;
            $data['orden'] = $orden;
            $data['orden_mostrar'] = $orden + 1;
            $data['preguntas'] = $resultados;
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Agregar pregunta';
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(PTL_ADMIN, $output);
        
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
            $data['titulo_pagina'] = 'Recursos';
            $data['subtitulo_pagina'] = 'Archivos';
            $data['vista_a'] = 'temas/recursos_v';

        $output = array_merge($data,(array)$gc_output);
        $this->load->view(PTL_ADMIN, $output);
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
        $data['titulo_pagina'] = 'Recursos';
        $data['subtitulo_pagina'] = 'Archivos';
        $data['vista_a'] = 'temas/recursos_v';

        $output = array_merge($data,(array)$output);
        $this->load->view(PTL_ADMIN, $output);
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
        $data['titulo_pagina'] = 'Recursos';
        $data['subtitulo_pagina'] = 'Archivos';
        $data['vista_a'] = 'temas/recursos_v';

        $output = array_merge($data,(array)$output);
        $this->load->view(PTL_ADMIN, $output);
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
            $data['titulo_pagina'] = 'Temas';
            $data['subtitulo_pagina'] = 'Importar elementos UT';
            $data['vista_a'] = 'comunes/importar_v';
            $data['vista_menu'] = 'temas/menu_importar_v';
            $data['ayuda_id'] = 100;
        
        $this->load->view(PTL_ADMIN, $data);
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
            $data['titulo_pagina'] = 'Temas';
            $data['subtitulo_pagina'] = 'Resultado asignación UT';
            $data['vista_a'] = 'comunes/resultado_importacion_v';
            $data['vista_menu'] = 'temas/menu_importar_v';
            $data['ayuda_id'] = 100;
            $this->load->view(PTL_ADMIN, $data);
    }
    
}
