<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Programas extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        $this->load->model('Programa_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($programa_id = NULL)
    {   
        if ( is_null($programa_id) ) {
            $this->explorar();
        } else {
            redirect("programas/temas/{$programa_id}");
        }
    }
    
    function reciente()
    {
        $programa_id = $this->Programa_model->reciente();
        redirect("programas/editar_temas/edit/{$programa_id}");
    }

//INFORMACIÓN DE PROGRAMAS
//---------------------------------------------------------------------------------------------------
    
    function explorar()
    {
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Busqueda_model');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Programa_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(2);
            $config['base_url'] = base_url() . "programas/explorar/?{$busqueda_str}";
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Programa_model->buscar($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Programas';
            $data['subtitulo_pagina'] = $data['cant_resultados'];
            $data['vista_a'] = 'programas/explorar_v';
            $data['vista_menu'] = 'programas/explorar_menu_v';
            $data['ayuda_id'] = 91;
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
            $resultados_total = $this->Programa_model->buscar($busqueda); //Para calcular el total de resultados
        
            if ( $resultados_total->num_rows() <= MAX_REG_EXPORT ) {
                //Preparar datos
                    $datos['nombre_hoja'] = 'Programas';
                    $datos['query'] = $resultados_total;

                //Preparar archivo
                    $objWriter = $this->Pcrn_excel->archivo_query($datos);

                $data['objWriter'] = $objWriter;
                $data['nombre_archivo'] = date('Ymd_His'). '_programas'; //save our workbook as this file name

                $this->load->view('app/descargar_phpexcel_v', $data);
            } else {
                $data['titulo_pagina'] = 'Plataforma Enlace';
                $data['mensaje'] = "El número de registros es de {$resultados_total->num_rows()}. El máximo permitido es de " . MAX_REG_EXPORT . " registros. Puede filtrar los datos por algún criterio para poder exportarlos.";
                $data['link_volver'] = "programas/explorar/?{$busqueda_str}";
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
            $this->Programa_model->eliminar($elemento_id);
        }
        
        echo count($seleccionados);
    }
    
    function editar()
    {
        //Cargando datos básicos
            $tema_id = $this->uri->segment(4);
            $data = $this->Programa_model->basico($tema_id);
            $row = $data['row'];
            
        //Render del grocery crud
            $output = $this->Programa_model->crud_basico($tema_id);
        
        //Head includes específicos para la página
            $head_includes[] = 'grocery_crud';
            $data['head_includes'] = $head_includes;
            
        //Solicitar vista
            $data['vista_b'] = 'comunes/gc_v';
            $data['vista_menu'] = 'programas/editar_submenu_v';
            $output = array_merge($data,(array)$output);
            $this->load->view(PTL_ADMIN, $output);
    }
    
    function editar_temas()
    {
        //Cargando datos básicos
            $tema_id = $this->uri->segment(4);
            $data = $this->Programa_model->basico($tema_id);
            $row = $data['row'];
            
        //Valores previos de filtro
            $filtros['nivel'] = $row->nivel;
            $filtros['tipo_id'] = 0;
            
        //Identificar filtros de opciones de temas para asignar
            if ( strlen($this->input->get('n')) ) { $filtros['nivel'] = $this->input->get('n'); }
            if ( strlen($this->input->get('tp')) ) { $filtros['tipo_id'] = $this->input->get('tp'); }
            
        //Render del grocery crud
            $output_gc = $this->Programa_model->crud_editar_temas($tema_id, $filtros);
            
        //Niveles
            $this->db->select('id_interno, item_largo as nivel');
            $this->db->where('categoria_id', 3);
            $niveles = $this->db->get('item');
            
        //Tipos de temas
            $this->db->select('id_interno, item as tipo_tema');
            $this->db->where('categoria_id', 17);
            $tipos_tema = $this->db->get('item');
            
        //Cargando $data
            $data['nivel'] = $filtros['nivel'];
            $data['niveles'] = $niveles;
            $data['tipo_id'] = $filtros['tipo_id'];
            $data['tipos_tema'] = $tipos_tema;
            
        //Solicitar vista
            $data['vista_b'] = 'programas/editar_temas_v';
            $data['ayuda_id'] = 91;
            $output = array_merge($data,(array)$output_gc);
            $this->load->view(PTL_ADMIN, $output);
    }
    
    function nuevo()
    {
        
        //Render del grocery crud
            $output = $this->Programa_model->crud_basico();
        
        //Head includes específicos para la página
            $head_includes[] = 'grocery_crud';
            $data['head_includes'] = $head_includes;
        
        //Array data espefícicas
            $data['titulo_pagina'] = 'Programas';
            $data['subtitulo_pagina'] = 'Nuevo';
            $data['vista_a'] = 'comunes/gc_v';
            $data['vista_menu'] = 'programas/explorar_menu_v';
        
        $output = array_merge($data,(array)$output);
        
        $this->load->view(PTL_ADMIN, $output);
    }
    
    function eliminar($programa_id)
    {
        $this->Programa_model->eliminar($programa_id);
        
        $this->load->model('Busqueda_model');
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        
        redirect("programas/explorar/?{$busqueda_str}");
    }
    
    /**
     * Mostrar formulario de importación de programas mediante archivo MS Excel.
     * El resultado del formulario se envía a 'programas/importar_e'
     * 
     * @param type $programa_id
     */
    function importar()
    {
        
        //Iniciales
            $nombre_archivo = '09_formato_cargue_programas.xlsx';
            $parrafos_ayuda = array();
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo importar programas?';
            $data['nota_ayuda'] = 'Se importarán programas a la Plataforma.';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = 'programas/importar_e';
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'programas';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            $data['titulo_pagina'] = 'Programas';
            $data['subtitulo_pagina'] = 'Importar programas';
            $data['vista_a'] = 'comunes/importar_v';
            $data['vista_menu'] = 'programas/explorar_menu_v';
        
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
            $letra_columna = 'E';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $this->load->model('Tema_model');
                $no_importados = $this->Programa_model->importar($resultado['array_hoja']);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $no_importados;
            $data['destino_volver'] = "programas/explorar/";
        
        //Cargar vista
            $data['titulo_pagina'] = 'Programas';
            $data['subtitulo_pagina'] = 'Resultado cargue';
            $data['vista_a'] = 'comunes/resultado_importacion_v';
            $data['vista_menu'] = 'programas/explorar_menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    
    function info($tema_id = NULL)
    {
        
        //Cargando datos básicos
            $tema_id = $this->Pcrn->si_nulo($tema_id, $this->Programa_model->tema_id());
            $data = $this->Programa_model->basico($tema_id);
            
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
            //$data['vista_b'] = 'programas/info_v';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    function temas($programa_id)
    {
        //Cargando datos básicos
            $data = $this->Programa_model->basico($programa_id);
            
        //Cargando el tema_model
            $this->load->model('Tema_model');
            
        //temas
            $this->db->join('tema', 'programa_tema.tema_id = tema.id');
            $this->db->where('programa_id', $programa_id);
            $this->db->order_by('orden', 'ASC');
            $temas = $this->db->get('programa_tema');
            
        //Cargando $data
            $data['temas'] = $temas;
            $data['preguntas'] = $this->Programa_model->preguntas($programa_id);
            $data['paginas'] = $this->Programa_model->paginas($programa_id);
            
        //Solicitar vista
            $data['subseccion'] = 'listado';
            $data['vista_b'] = 'programas/temas_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
//CARGUE MASIVO DE TEMAS MULTIPLE PROGRAMA
//---------------------------------------------------------------------------------------------------
    
    /**
     * Mostrar formulario de cargue de temas mediante archivos de excel.
     * El resultado del formulario se envía a 'programas/'
     * 
     */
    function asignar_temas_multi()
    {   
        
        //Iniciales
            $nombre_archivo = '10_formato_asignacion_temas_multiple.xlsx';
            $parrafos_ayuda = array(
                'Si la casilla Id Programa (columna A) se encuentra vacía el tema no será asignado.'
            );
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo asignar temas a los programas?';
            $data['nota_ayuda'] = 'Aguí puede asignar temas (ya existentes en la plataforma) a los programas ya creados.';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = 'programas/asignar_temas_multi_e';
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'temas';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            $data['titulo_pagina'] = 'Programas';
            $data['subtitulo_pagina'] = 'Asignación de temas multiprograma';
            $data['vista_a'] = 'comunes/importar_v';
            $data['vista_menu'] = 'programas/explorar_menu_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function asignar_temas_multi_e()
    {
        
        //Proceso
            $this->load->model('Pcrn_excel');
            $no_importados = array();
            $letra_columna = 'B';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $no_importados = $this->Programa_model->asignar_temas_multi($resultado['array_hoja']);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $no_importados;
            $data['destino_volver'] = "programas/explorar/";
        
        //Cargar vista
            $data['titulo_pagina'] = 'Programas';
            $data['subtitulo_pagina'] = 'Resultado asignación';
            $data['vista_a'] = 'comunes/resultado_importacion_v';
            $data['vista_menu'] = 'programas/explorar_menu_v';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    /**
     * Formulario para la creación de una copia de un programa
     * 
     * 
     * @param type $programa_id 
     */
    function copiar($programa_id)
    {
        //Cargando datos básicos (_basico)
            $data = $this->Programa_model->basico($programa_id);
            $data['destino_form'] = 'programas/copiar_e';
        
        //Variables data
        
        //Solicitar vista
            $data['vista_b'] = 'programas/copiar_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Ejecuta el proceso de crear la copia, proviene de programas/copiar
     * 
     * Se copian las características del programa y las páginas que lo componen
     * definidas en la tabla programa_contenido.
     *  
     */
    function copiar_e()
    {
        //Validación del formulario
            $this->load->library('form_validation');

        //Reglas
            $this->form_validation->set_rules('nombre_programa', 'Nombre del programa', 'max_length[200]|required');

        //Mensajes de validación
            $this->form_validation->set_message('max_length', "El campo [ %s ] puede tener hasta 200 caracteres");
            $this->form_validation->set_message('required', "El [ %s ] no puede estar vacío");

        //Comprobar validación
            if ( $this->form_validation->run() )
            {
                //Se cumple la validación, se genera la copia del programa
                
                //Preparar datos para la copia
                    $datos['nombre_programa_nuevo'] = $this->input->post('nombre_programa');
                    $datos['programa_id'] = $this->input->post('programa_id');
                    $datos['descripcion'] = $this->input->post('descripcion');
                
                $nuevo_programa_id = $this->Programa_model->copiar($datos);
                
                //Se redirige al nuevo flibbook creado
                redirect("programas/temas/{$nuevo_programa_id}");
            } else {
                //No se cumple la validación, se regresa al cuestionario
                $this->copiar($this->input->post('programa_id'));
            }
            
    }
    
//GENERACIÓN DE FLIPBOOK
//---------------------------------------------------------------------------------------------------
    
    /**
     * Función formulario previo para la creación de un nuevo flipbook a partir de un programa
     * Formulario, se envía a programas/generar_flipbook
     * 
     * @param type $programa_id
     */
    function nuevo_flipbook($programa_id, $tipo = 'nuevo')
    {
        $this->load->model('Flipbook_model');
        
        //Cargando datos básicos
            $data = $this->Programa_model->basico($programa_id);
            
        //Filtros de flipbooks opciones
            $filtros['area_id'] = $data['row']->area_id;
            //$filtros['anio_generacion'] = $data['row']->anio_generacion;
            
        //Definiendo tipo
            if ( $tipo == 'nuevo' ){
                $data['vista_b'] = 'programas/nuevo_flipbook_v';
                $destino_form = "programas/generar_flipbook/{$programa_id}";
            } elseif ( $tipo == 'existente' ) {
                $data['vista_b'] = 'programas/nuevo_flipbook_existente_v';
                $destino_form = "programas/sobreescribir_fb/{$programa_id}";
            }
            
        //Cargando $data
            $data['destino_form'] = $destino_form;
            $data['paginas'] = $this->Programa_model->paginas($programa_id);
            $data['opciones_flipbook'] = $this->Flipbook_model->opciones_flipbook($filtros);
            $data['flipbooks'] = $this->Programa_model->flipbooks($programa_id);
            
            
        //Solicitar vista
            $data['subseccion'] = $tipo;
            $data['subtitulo_pagina'] = 'Nuevo contenido';
            

            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Viene del formulario en programas/nuevo_flipbook
     * Crea un nuevo flipbook a partir de las páginas asociadas a los temas del programa
     * 
     * @param type $programa_id
     */
    function generar_flipbook($programa_id)
    {
        
        $row_programa = $this->Pcrn->registro_id('programa', $programa_id);
        $this->load->library('form_validation');
        
        //Reglas
            $this->form_validation->set_rules('nombre_flipbook', 'Nombre del contenido', 'trim|required|is_unique[flipbook.nombre_flipbook]');
        
        //Mensajes de validación
            $this->form_validation->set_message('required', "El campo %s es requerido");
            $this->form_validation->set_message('is_unique', "El valor escrito en %s ya fue utilizado, debe elegir otro");
        
        if ( $this->form_validation->run() )
        {
            //Validación exitosa, crear registro
                $registro['nombre_flipbook'] = $this->input->post('nombre_flipbook');
                $registro['tipo_flipbook_id'] = $this->input->post('tipo_flipbook_id');
                $registro['nivel'] = $row_programa->nivel;
                $registro['area_id'] = $row_programa->area_id;
                $registro['anio_generacion'] = $row_programa->anio_generacion;
                $registro['descripcion'] = $this->input->post('descripcion');
                $registro['programa_id'] = $programa_id;
                $registro['creado'] = date('Y-m-d H:i:s');
                $registro['editado'] = date('Y-m-d H:i:s');
                $registro['creado_usuario_id'] = $this->session->userdata('usuario_id');
                $registro['editado_usuario_id'] = $this->session->userdata('usuario_id');
            
            //Crear flipbook, registro y páginas
                $flipbook_id = $this->Programa_model->generar_flipbook($programa_id, $registro);
            
            //Resultado
                $resultado['clase'] = 'alert-success';
                $resultado['mensaje'] = "El contenido {$registro['nombre_flipbook']} fue generado exitosamente";
                $resultado['html'] = 'Abrir ' . anchor("flipbooks/paginas/{$flipbook_id}", $registro['nombre_flipbook']);
                $this->session->set_flashdata('resultado', $resultado);
            
            //Redireccionar
                $destino = "programas/nuevo_flipbook/{$programa_id}";
                redirect($destino);
        } else {
            //La validación falla, volver al formulario de login
            $this->nuevo_flipbook($programa_id);
        }
    }
    
    /**
     * Viene del formulario en programas/nuevo_flipbook
     * Recrea un nuevo flipbook a partir de las páginas asociadas a los temas del programa
     * Elimina las páginas que están inicialmente en el flipbook
     * 
     * @param type $programa_id
     */
    function sobreescribir_fb($programa_id)
    {
        $this->load->library('form_validation');
        
        //Reglas
            $this->form_validation->set_rules('flipbook_id', 'Flipbook', 'required');
        
        //Mensajes de validación
            $this->form_validation->set_message('required', "El campo %s no puede quedar vacío");
        
        if ( $this->form_validation->run() == FALSE ){
            //La validación falla, volver al formulario de login
            $this->nuevo_flipbook($programa_id, 'existente');
        } else {
            //Validación exitosa, crear registro
                $registro['descripcion'] = $this->input->post('descripcion');
                $flipbook_id = $this->input->post('flipbook_id');
            
            //Crear flipbook, registro y páginas
                $this->Programa_model->sobreescribir_fb($programa_id, $flipbook_id, $registro);
            
            //Resultado
                $row_flipbook = $this->Pcrn->registro_id('flipbook', $flipbook_id);
                $resultado['nombre_flipbook'] = $row_flipbook->nombre_flipbook;
                $resultado['mensaje'] = "El flipbook {$row_flipbook->nombre_flipbook} fue sobreescrito exitosamente";
                $resultado['clase'] = 'alert-success';
                $resultado['html'] = 'Abrir ' . anchor("flipbooks/paginas/{$flipbook_id}", $this->App_model->nombre_flipbook($flipbook_id));
                $this->session->set_flashdata('resultado', $resultado);
            
            //Redireccionar
                $destino = "programas/nuevo_flipbook/{$programa_id}/existente";
                redirect($destino);
        }
    }
    
//GENERACIÓN MULTIPLE DE FLIPBOOKS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Formulario, para cargar archivo Excel, y generar flipbooks con una lista de programas
     */
    function generar_flipbooks_multi()
    {
        //Iniciales
            $nombre_archivo = '11_formato_generar_flipbooks.xlsx';
            $parrafos_ayuda = array(
                'Si la casilla Id Programa (columna A) se encuentra vacía el programa no será procesado.',
                'Si la casilla Id Contenido (columna B) se encuentra vacía el programa no será procesado.'
            );
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo generar Contenidos masivamente desde Programas?';
            $data['nota_ayuda'] = 'Aquí puede generar Contenidos desde los Programas existentes.';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = 'programas/generar_flipbooks_multi_e';
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'programas_contenido';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            $data['titulo_pagina'] = 'Programas';
            $data['subtitulo_pagina'] = 'Generar contenidos';
            $data['vista_a'] = 'comunes/importar_v';
            $data['vista_menu'] = 'programas/explorar_menu_v';
            $data['ayuda_id'] = 111;
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function generar_flipbooks_multi_e()
    {
        //Proceso
            $this->load->model('Pcrn_excel');
            $no_importados = array();
            $letra_columna = 'C';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $no_importados = $this->Programa_model->generar_flipbooks_multi($resultado['array_hoja']);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $no_importados;
            $data['destino_volver'] = "programas/explorar/";
        
        //Cargar vista
            $data['titulo_pagina'] = 'Programas';
            $data['subtitulo_pagina'] = 'Resultado generación';
            $data['vista_a'] = 'comunes/resultado_importacion_v';
            $data['vista_menu'] = 'programas/explorar_menu_v';
            $this->load->view(PTL_ADMIN, $data);
        
    }

//GENERACIÓN DE CUESTIONARIOS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Función formulario previo para la creación de un nuevo cuestionario a partir de un programa
     * Formulario, se envía a programas/generar_cuestionario
     * 
     * @param type $programa_id
     */
    function nuevo_cuestionario($programa_id)
    {
        //Cargando datos básicos
            $data = $this->Programa_model->basico($programa_id);
            
        //Cargando $data
            $data['preguntas'] = $this->Programa_model->preguntas($programa_id);
            $data['destino_form'] = "programas/generar_cuestionario/{$data['row']->id}";
            
        //Solicitar vista
            $data['titulo_pagina'] .= ' - Nuevo cuestionario';
            $data['vista_b'] = 'programas/nuevo_cuestionario_v';

            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Viene del formulario en programas/nuevo_cuestionario
     * Crea un nuevo cuestionario a partir de las páginas asociadas a los temas del programa
     * 
     * @param type $programa_id
     */
    function generar_cuestionario($programa_id)
    {
        
        $this->load->library('form_validation');
        
        //Reglas
            $this->form_validation->set_rules('nombre_cuestionario', 'Nombre del cuestionario', 'trim|required|is_unique[cuestionario.nombre_cuestionario]');
            $this->form_validation->set_rules('unidad', 'Unidad','greater_than[0]|less_than[13]');
        
        //Mensajes de validación
            $this->form_validation->set_message('required', "El campo %s es requerido");
            $this->form_validation->set_message('is_unique', "El valor escrito en %s ya fue utilizado, debe elegir otro");
            $this->form_validation->set_message('greater_than', 'El valor escrito en %s no es válido');
            $this->form_validation->set_message('less_than', 'El valor escrito en %s no es válido');
            
        
        if ( $this->form_validation->run() ){
            //Validación exitosa, crear registro
                $registro['nombre_cuestionario'] = $this->input->post('nombre_cuestionario');
                $registro['nivel'] = $this->input->post('nivel');
                $registro['area_id'] = $this->input->post('area_id');
                $registro['unidad'] = $this->input->post('unidad');
                $registro['privado'] = $this->input->post('privado');
                $registro['prueba_periodica'] = $this->input->post('prueba_periodica');
                $registro['descripcion'] = $this->input->post('descripcion');
                $registro['creado'] = date('Y-m-d H:i:s');
                $registro['editado'] = date('Y-m-d H:i:s');
                $registro['creado_usuario_id'] = $this->session->userdata('usuario_id');
                $registro['editado_usuario_id'] = $this->session->userdata('usuario_id');
            
            //Crear cuestionario, registro y páginas
                $cuestionario_id = $this->Programa_model->generar_cuestionario($programa_id, $registro);
            
            //Resultado
                $resultado['cuestionario_id'] = $cuestionario_id;
                $resultado['nombre_cuestionario'] = $registro['nombre_cuestionario'];
                $resultado['mensaje'] = "El cuestionario {$registro['nombre_cuestionario']} fue generado exitosamente";
                $resultado['html'] = 'Abrir ' . anchor("cuestionarios/vista_previa/{$cuestionario_id}", $this->App_model->nombre_cuestionario($cuestionario_id));
            $this->session->set_flashdata('resultado', $resultado);
            
            //Redireccionar
                $destino = "programas/nuevo_cuestionario/{$programa_id}";
                redirect($destino);
        } else {
            //La validación falla, volver al formulario de login
            $this->nuevo_cuestionario($programa_id);
        }
    }
    
// VACIAR TEMAS
//-----------------------------------------------------------------------------
    
    /**
     * Mostrar formulario de importación de lista de programas mediante archivo MS Excel.
     * A los programas en la lista del archivo de excel se les eliminarán los temas asignados.
     * El resultado del formulario se envía a 'programas/vaciar_e'
     * 
     * @param type $programa_id
     */
    function vaciar()
    {
        
        //Iniciales
            $nombre_archivo = '20_formato_vaciar_programas.xlsx';
            $parrafos_ayuda = array();
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo vaciar programas?';
            $data['nota_ayuda'] = 'Se quitarán de los programas en lista los temas asignados. No se eliminan los temas de la plataforma.';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = 'programas/vaciar_e';
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'programas_vaciar';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            $data['titulo_pagina'] = 'Programas';
            $data['subtitulo_pagina'] = 'Vaciar temas';
            $data['vista_a'] = 'comunes/importar_v';
            $data['vista_menu'] = 'programas/explorar_menu_v';
            $data['ayuda_id'] = 122;
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Leer programas en lista y vaciar sus temas asignados, (e) ejecutar.
     */
    function vaciar_e()
    {
        
        //Proceso
            $this->load->model('Pcrn_excel');
            $no_importados = array();
            $letra_columna = 'A';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $no_importados = $this->Programa_model->vaciar($resultado['array_hoja']);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $no_importados;
            $data['destino_volver'] = "programas/explorar/";
        
        //Cargar vista
            $data['titulo_pagina'] = 'Programas';
            $data['subtitulo_pagina'] = 'Resultado vaciado';
            $data['vista_a'] = 'comunes/resultado_importacion_v';
            $data['vista_menu'] = 'programas/explorar_menu_v';
            $data['ayuda_id'] = 122;
            $this->load->view(PTL_ADMIN, $data);
    }
    
//PROCESOS
//---------------------------------------------------------------------------------------------------
    
    /*
     * Actualiza el campo: programa.temas
     */
    function act_campo_temas()
    {
        $this->load->model('Busqueda_model');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            //$busqueda_str = $this->Busqueda_model->busqueda_str();
            $programas = $this->Programa_model->buscar($busqueda); //Para calcular el total de resultados
            
        foreach ( $programas->result() as $row_programa ) {
            $this->Programa_model->act_campo_temas($row_programa->id);
        }
        
        $mensaje = "{$programas->num_rows()} programas fueron actualizados";
        
        //Resultado
            $resultado['clase'] = 'alert-success';
            $resultado['mensaje'] = $mensaje;
            
            $this->session->set_flashdata('resultado', $resultado);
            
            redirect('develop/procesos');
    }
    
}
