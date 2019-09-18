<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grupos extends CI_Controller{
    
    function __construct() {
        parent::__construct();

        $this->load->model('Grupo_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($grupo_id = NULL)
    {
        $destino = 'grupos/explorar';
        if ( ! is_null($grupo_id) ) 
        {
            $destino = "grupos/estudiantes/{$grupo_id}";
        }
        
        redirect($destino);
    }
    
    function explorar()
    {
        //Cargando
            $this->load->model('Busqueda_model');
            $this->load->helper('text');
        
        //Grupos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Grupo_model->buscar($busqueda); //Para calcular el total de resultados
            
        //Generar resultados para mostrar
            $data['per_page'] = 20; //Cantidad de registros por página
            $data['offset'] = $this->input->get('per_page');
            $resultados = $this->Grupo_model->buscar($busqueda, $data['per_page'], $data['offset']);
        
        //Variables para vista
            $data['cant_resultados'] = $resultados_total->num_rows();
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
            $data['url_paginacion'] = base_url("grupos/explorar/?{$busqueda_str}");
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Grupos';
            $data['subtitulo_pagina'] = $data['cant_resultados'];
            $data['vista_a'] = 'grupos/explorar/explorar_v';
            if ( $this->input->get('vista') == 'n' ) { $data['vista_a'] = 'grupos/explorar_v_n'; }
            $data['vista_menu'] = 'grupos/explorar/menu_v';
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
            $resultados_total = $this->Grupo_model->buscar($busqueda); //Para calcular el total de resultados
        
            if ( $resultados_total->num_rows() <= MAX_REG_EXPORT )
            {
                //Preparar datos
                    $datos['nombre_hoja'] = 'Grupos';
                    $datos['query'] = $resultados_total;

                //Preparar archivo
                    $objWriter = $this->Pcrn_excel->archivo_query($datos);

                $data['objWriter'] = $objWriter;
                $data['nombre_archivo'] = date('Ymd_His'). '_grupos';

                $this->load->view('comunes/descargar_phpexcel_v', $data);
            } else {
                $data['titulo_pagina'] = NOMBRE_APP;
                $data['mensaje'] = "El número de registros es de {$resultados_total->num_rows()}. El máximo permitido es de " . MAX_REG_EXPORT . " registros. Puede filtrar los datos por algún criterio para poder exportarlos.";
                $data['link_volver'] = "grupos/explorar/?{$busqueda_str}";
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
        
        foreach ( $seleccionados as $elemento_id )
        {
            $this->Grupo_model->eliminar($elemento_id);
        }
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(count($seleccionados));
    }
    
//GROCERY CRUD
//---------------------------------------------------------------------------------------------------
    
    function editar()
    {
        //Cargando datos básicos
            $grupo_id = $this->uri->segment(4);
            $data = $this->Grupo_model->basico($grupo_id);
            
        //Render del grocery crud
            $gc_output = $this->Grupo_model->crud_basico();
            
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Editar';
            $data['vista_b'] = 'comunes/gc_v';
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(PTL_ADMIN, $output);
    }
    
    function eliminar($grupo_id, $institucion_id)
    {
        $this->db->where('id', $grupo_id);
        $this->db->delete('grupo');
        
        $this->Grupo_model->eliminar_cascada();
        
        redirect("instituciones/grupos/{$institucion_id}");
    }
//---------------------------------------------------------------------------------------------------
    
    function profesores($grupo_id, $gp_id = NULL)
    {
        //Cargando datos básicos
            $data = $this->Grupo_model->basico($grupo_id);
            
        //Variables
            $this->db->select('*, id AS gp_id');
            $this->db->where('grupo_profesor.grupo_id', $grupo_id);
            $profesores = $this->db->get('grupo_profesor');
            
            $this->db->where('institucion_id', $data['row']->institucion_id);
            $this->db->where('rol_id IN (3, 4, 5)');
            $usuarios = $this->db->get('usuario');
            
        //Cargando $data
            $data['gp_id'] = $gp_id;
            $data['profesores'] = $profesores;
            $data['usuarios'] = $usuarios;
            $data['subseccion'] = 'profesores';
            $data['destino_form'] = "grupos/asignar_profesor/{$data['row']->id}";
            
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Profesores';
            $data['vista_b'] = 'grupos/profesores_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Edición masiva de los estudiantes que conforman un grupo
     */
    function editar_estudiantes()
    {
        
        $grupo_id = $this->uri->segment(4);
        $data = $this->Grupo_model->basico($grupo_id);
        
        //Cargar librería
            $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('grupo');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_list();
        $crud->unset_back_to_list();
        
        
        //Cargando registro actual
            $condicion_estudiantes = "rol_id = 6";
            if ( $this->uri->segment(4) ){
                $grupo_id = $this->uri->segment(4);
                $row_grupo = $this->Pcrn->registro('grupo', "id = {$grupo_id}");
                $condicion_estudiantes .= " AND institucion_id = {$row_grupo->institucion_id}";
            }
        
        //Relaciones
            $crud->set_relation_n_n('estudiantes', 'usuario_grupo', 'usuario', 'usuario_grupo.grupo_id', 'usuario_id', '{apellidos} {nombre} ({username})', 'orden', $condicion_estudiantes);
        
        //Formulario de edición
            $crud->edit_fields('director_id', 'estudiantes');
        
        //Formato campos
            $crud->change_field_type('director_id', 'hidden');
            
        //Procesos
            $crud->callback_after_update(array($this, '_after_estudiantes'));
        
        $output = $crud->render();
        
        //Head includes específicos para la página
            $head_includes[] = 'grocery_crud';
            $data['head_includes'] = $head_includes;
            
        //Array $data
            $data['row'] = $row_grupo;
            $data['subseccion'] = 'editar_estudiantes';
        
        //Solicitar vista
            $data['titulo_pagina'] = "Editar estudiantes de grupos";
            $data['vista_b'] = 'grupos/editar_estudiantes_v';

        $output = array_merge($data,(array)$output);
        $this->load->view(PTL_ADMIN, $output);
        
    }
    
    /**
     * Después de actualizar los estudiantes de un grupo
     * 
     * @param type $post_array
     * @param type $primary_key
     * @return boolean
     */
    function _after_estudiantes($post_array, $primary_key)
    {
        $grupo_id = $primary_key;
        $this->Grupo_model->act_grupo_actual($grupo_id);
        
        return TRUE;
    }
    
    /**
     * Mostrar los flipbooks que han sido asignados a los estudiantes de los grupos de un profesor
     * @param type $grupo_id 
     */
    function flipbooks($grupo_id)
    {
        
        //Cargando datos básicos (_basico)
        $data = $this->Grupo_model->basico($grupo_id);
            
        //Cargando array $data
            $data['subseccion'] = 'anotaciones';
            $data['grupo_id'] = $grupo_id;
            $data['flipbooks'] = $this->Grupo_model->flipbooks($grupo_id);
            $data['grupos'] = $this->Grupo_model->grupos_profesor($this->session->userdata('usuario_id'));
        
        //Solicitar vista
            $data['titulo_pagina'] = $data['titulo_pagina'] . ' | Flipbooks asignados';
            $data['vista_b'] = 'grupos/flipbooks_v';
            $data['menu_sub'] = 'grupos/menu_sub_flipbooks_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Mostrar los flipbooks que han sido asignados a los estudiantes de los grupos de un profesor
     * @param type $institucion_id 
     */
    function anotaciones($grupo_id, $flipbook_id = NULL, $tema_id = 0)
    {
        //$this->output->enable_profiler(TRUE);
        
        $this->load->model('Flipbook_model');
        
        $data = $this->Grupo_model->basico($grupo_id);
            
        $flipbooks = $this->Grupo_model->flipbooks($grupo_id);
            
        //Identificando Flipbook
        if ( $flipbooks->num_rows() > 0 ) 
        {
            $flipbook_id = $this->Pcrn->si_nulo($flipbook_id, $flipbooks->row()->flipbook_id);
        } else {
            $flipbook_id = 0;
        }
        
        
        //Identificando tema
        $temas = $this->Flipbook_model->temas($flipbook_id);
        //$tema_id = $this->Pcrn->si_nulo($tema_id, $temas->row()->id);
            
            
        //Cargando array $data
            $data['flipbooks'] = $flipbooks;
            $data['flipbook_id'] = $flipbook_id;
            $data['temas'] = $temas;
            $data['tema_id'] = $tema_id;
            $data['anotaciones'] = $this->Flipbook_model->anotaciones_grupo($flipbook_id, $grupo_id, $tema_id);
            $data['subseccion'] = 'anotaciones';
            $data['grupo_id'] = $grupo_id;
            $data['flipbooks'] = $flipbooks;
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Contenidos asignados';
            $data['vista_b'] = 'grupos/anotaciones_v';
            $data['menu_sub'] = 'grupos/menu_sub_flipbooks_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Quices a los que está asignado un grupo a través de los flipbooks
     * 
     * @param type $grupo_id
     * @param type $flipbook_id
     * @param type $quiz_id
     */
    function quices($grupo_id, $flipbook_id = 0, $quiz_id = 0)
    {
        $this->load->model('Flipbook_model');
        $this->load->model('Usuario_model');
        
        //Flipbooks del grupo
            $flipbooks = $this->Grupo_model->flipbooks($grupo_id);
            
        //Identificar $flipbook_id
            if ( $flipbooks->num_rows() > 0 ) { $flipbook_id = $this->Pcrn->si_cero($flipbook_id, $flipbooks->row()->flipbook_id); }
            
        //Quices del flipbook
            $quices = $this->Flipbook_model->quices_total($flipbook_id);
            
        //Identificar $quiz_id
            if ( count($quices) > 0 ) { $quiz_id = $this->Pcrn->si_cero($quiz_id, $quices[0]['id']); }  //Si es cero, el primer quiz del array
        
        //Cargando datos básicos (_basico)
            $data = $this->Grupo_model->basico($grupo_id);
            
        //Cargando array $data
            $data['grupo_id'] = $grupo_id;
            $data['flipbook_id'] = $flipbook_id;
            $data['quiz_id'] = $quiz_id;
            $data['tema_id'] = $this->Pcrn->campo_id('quiz', $quiz_id, 'tema_id');
            $data['flipbooks'] = $flipbooks;
            $data['quices'] = $quices;
            $data['estudiantes'] = $this->Grupo_model->estudiantes($grupo_id);
            $data['grupos'] = $this->Grupo_model->grupos_profesor($this->session->userdata('usuario_id'));
            
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Resultados quices';
            $data['vista_b'] = 'grupos/quices_v';
            $data['menu_sub'] = 'grupos/menu_sub_flipbooks_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function quices_exportar($grupo_id, $quiz_id)
    {
        $this->load->model('Pcrn_excel');
        $this->load->model('Usuario_model');
        
        $data['objWriter'] = $this->Grupo_model->archivo_quices_exportar($grupo_id, $quiz_id);
        $data['nombre_archivo'] = date('Ymd_His'). '_evidencia_resultado'; //save our workbook as this file name
        
        $this->load->view('app/descargar_phpexcel_v', $data);    
    }
    
// IMPORTAR CAMBIO DE AÑO DE GENERACIÓN
//-----------------------------------------------------------------------------
    
    /**
     * Mostrar formulario de importación de datos de años de generación de grupos 
     * con archivo Excel. El resultado del formulario se envía a 
     * 'grupos/importar_editar_anios_e'
     * 
     */
    function importar_editar_anios()
    {
        //Iniciales
            $nombre_archivo = '24_formato_editar_anios_grupos.xlsx';
            $parrafos_ayuda = array(
                'Las columnas [ID grupo], [Año] no pueden estar vacías.',
                'Verifique que el <span class="resaltar">ID del grupo</span> existe en la plataforma.',
                'El <span class="resaltar">Año</span> debe ser un dato numérico.'
            );
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo editar años de generación de grupos?';
            $data['nota_ayuda'] = 'Se importarán datos de años de generación de grupos existentes, a los grupos.';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = "grupos/importar_editar_anios_e";
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'grupo_anio';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            //$data['ayuda_id'] = 97;
            $data['titulo_pagina'] = 'Grupos';
            $data['subtitulo_pagina'] = 'Importar años de generación';
            $data['vista_a'] = 'comunes/importar_v';
            $data['vista_menu'] = 'grupos/explorar/menu_v';
            $data['vista_submenu'] = 'grupos/importar_menu_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Importar datos de años de generación de grupos, (e) ejecutar.
     */
    function importar_editar_anios_e()
    {
        //Proceso
            $this->load->model('Pcrn_excel');
            $this->load->model('Esp');
            $letra_columna = 'B';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $res_importacion = $this->Grupo_model->importar_editar_anios($resultado['array_hoja']);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $res_importacion['no_importados'];
            $data['destino_volver'] = "grupos/explorar/";
        
        //Cargar vista
            $data['titulo_pagina'] = 'Grupos';
            $data['subtitulo_pagina'] = 'Resultado importación años generación';
            $data['vista_a'] = 'comunes/resultado_importacion_v';
            $data['vista_menu'] = 'grupos/explorar/menu_v';
            $data['vista_submenu'] = 'grupos/importar_menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
// DESASIGNAR PROFESORES CON ARCHIVO EXCEL
//-----------------------------------------------------------------------------
    
    /**
     * Mostrar formulario de importación de datos de años de generación de grupos 
     * con archivo Excel. El resultado del formulario se envía a 
     * 'grupos/importar_editar_anios_e'
     * 
     */
    function desasignar_profesores()
    {
        //Iniciales
            $nombre_archivo = '28_formato_desasignar.xlsx';
            $parrafos_ayuda = array(
                'Las columnas [ID grupo], no puede estar vacías.',
                'No se eliminan profesores ni grupos, solo la asignaciones'
            );
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo desasignar los profesores de un grupo?';
            $data['nota_ayuda'] = 'Se eliminarán las asignaciones de profesores de los grupos en el archivo Excel';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = "grupos/desasignar_profesores_e";
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'grupos_desasignar';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            //$data['ayuda_id'] = 97;
            $data['titulo_pagina'] = 'Grupos';
            $data['subtitulo_pagina'] = 'Desasignar profesores';
            $data['vista_a'] = 'comunes/importar_v';
            $data['vista_menu'] = 'grupos/explorar/menu_v';
            $data['vista_submenu'] = 'grupos/importar_menu_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Importar datos de años de generación de grupos, (e) ejecutar.
     */
    function desasignar_profesores_e()
    {
        //Proceso
            $this->load->model('Pcrn_excel');
            $this->load->model('Esp');
            $letra_columna = 'A';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $res_importacion = $this->Grupo_model->desasignar_profesores($resultado['array_hoja']);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $res_importacion['no_importados'];
            $data['destino_volver'] = "grupos/explorar/";
        
        //Cargar vista
            $data['titulo_pagina'] = 'Grupos';
            $data['subtitulo_pagina'] = 'Resultado desasignar profesores';
            $data['vista_a'] = 'comunes/resultado_importacion_v';
            $data['vista_menu'] = 'grupos/explorar/menu_v';
            $data['vista_submenu'] = 'grupos/importar_menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }

//---------------------------------------------------------------------------------------------------
//ASIGNACIÓN DE PROFESORES A UN GRUPO
    
    /* Validación del formulario proveniente de grupos/profesores
    * 
    */
    function asignar_profesor($grupo_id)
    {
        
        $this->load->library('form_validation');
        
        //Reglas
            $this->form_validation->set_rules('profesor_id', 'Profesor', 'required');
            $this->form_validation->set_rules('area_id', 'Área', 'required');
        
        //Mensajes de validación
            $this->form_validation->set_message('required', "%s es obligatorio");
        
        //Comprobar validación
            if ( $this->form_validation->run() == FALSE ){
                //No se cumple la validación, se regresa al formulario
                $this->profesores($grupo_id);
            } else {
                //Se cumple la validación,
                $registro['grupo_id'] = $grupo_id;
                $registro['profesor_id'] = $this->input->post('profesor_id');
                $registro['area_id'] = $this->input->post('area_id');
                $resultado = $this->Grupo_model->guardar_gp($registro);    //gp = grupo_profesor
                
                $this->session->set_flashdata('resultado', $resultado);
                redirect("grupos/profesores/{$grupo_id}/{$resultado['gp_id']}");
            }
    }
    
    function quitar_profesor($grupo_id, $gp_id)
    {
        //Eliminar
            $this->db->where('id', $gp_id);
            $this->db->delete('grupo_profesor');
            
        //Redireccionar
            redirect("grupos/profesores/{$grupo_id}");
    }
    
    
//---------------------------------------------------------------------------------------------------
//ASIGNACIÓN DE ESTUDIANTES DEL GRUPO A UN CUESTIONARIO
    
    
    

//PROMOVER UN GRUPO A OTRO NIVEL
//---------------------------------------------------------------------------------------------------
    
    /**
     * Formulario para promover un grupo a otro nivel
     * 
     * @param type $grupo_id
     */
    function promover($grupo_id, $tipo_promocion = 1)
    {
        //Cargando datos básicos (_basico)
            $data = $this->Grupo_model->basico($grupo_id);
            
        //Cargando array $data
            $data['grupo_id'] = $grupo_id;
            $data['tipo_promocion'] = $tipo_promocion;
            $data['estudiantes'] = $this->Grupo_model->estudiantes($grupo_id);
            $data['destino_form'] = "grupos/promover_v/{$grupo_id}/{$tipo_promocion}";
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Promover grupo';
            $data['vista_b'] = 'grupos/promover_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Promover validar, validación del formulario de validación de la promoción
     * de grupos
     * 
     * @param type $grupo_id
     */
    function promover_v($grupo_id, $tipo_promocion)
    {
        
        $this->load->library('form_validation');
        
        //La validación depende del tipo de promoción
        if ( $tipo_promocion == 1 ) {
            //Grupo nuevo

            //Reglas
            $this->form_validation->set_rules('grupo', 'Grupo', 'required|alpha_numeric');
            $this->form_validation->set_rules('nivel', 'Nivel', 'less_than[12]');   //El nuevo nivel debe ser menor o igual a 11, máximo grado de la secundaria

            //Mensajes de validación
                $this->form_validation->set_message('required', "'%s' es obligatorio");
                $this->form_validation->set_message('alpha_numeric', "'%s' debe ser un número o una letra");
                $this->form_validation->set_message('less_than', "'%s' no aceptado");

        } elseif ( $tipo_promocion == 2 ){
            //Grupo Existente

            $this->form_validation->set_rules('grupo_existente_id', 'Grupo existente', 'required');

            //Mensajes de validación
                $this->form_validation->set_message('required', "'%s' es obligatorio");
        }
        
        //Comprobar validación
            if ( $this->form_validation->run() == FALSE ){
                //No se cumple la validación, se regresa al formulario
                $this->promover($grupo_id);
                //echo 'Error de validación';
            } else {
                //Se cumple la validación, se ejecuta
                $this->promover_e($grupo_id, $tipo_promocion);
            }   
    }
    
    /**
     * Después de validados ejecuta el proceso de promoción de los estudiantes de un grupo
     * 
     * Promover Ejecutar (e) implica crear un nuevo grupo con un nivel superior, 
     * asignar los estudiantes a ese grupo en la tabla usuario_grupo y cambiar
     * el campo usuario.grupo_id (grupo actual de un usuario)
     * 
     * @param type $grupo_id
     */
    function promover_e($grupo_id, $tipo_promocion)
    {
        //Grupo destino
        
            if ( $tipo_promocion == 1 ){
                //Grupo nuevo
                $registro = array(
                    'nivel' => $this->input->post('nivel'),
                    'grupo' => $this->input->post('grupo'),
                    'institucion_id' => $this->input->post('institucion_id'),
                    'anio_generacion' => $this->input->post('anio_generacion'),
                    'anterior_grupo_id' => $this->input->post('anterior_grupo_id'),
                );
                
                $grupo_destino_id = $this->Grupo_model->crear_grupo($registro);    
            } elseif ( $tipo_promocion == 2 ) {
                //Grupo existente
                echo 'Existente: ' . $grupo_destino_id;
                $grupo_destino_id = $this->input->post('grupo_existente_id');
            }
        
            
        //Se carga la lista de estudiantes que pertenecen al grupo original
            $estudiantes = $this->Grupo_model->estudiantes($grupo_id);
            
            
            
            $registro_ug['grupo_id'] = $grupo_destino_id; //Array del registro nuevo para la tabla usuario_grupo (ug)

            foreach ($estudiantes->result() as $row_estudiante){
                
                //Verificar si la casilla correspondiente a cada estudiante fue marcada
                if ( $this->input->post($row_estudiante->id) ){
                    
                    //Crear registros en la tabla usuario_grupo (ug)
                        $registro_ug['usuario_id'] = $row_estudiante->id;
                        $this->Grupo_model->insertar_ug($registro_ug);
                    
                    //Modificar el campo usuario.grupo_id, nuevo grupo actual
                        $registro_u = array('grupo_id' => $grupo_destino_id); //registro usuario
                        $this->db->where('id', $row_estudiante->id);
                        $this->db->update('usuario', $registro_u);
                }
            }
            
        //Mostrar vista
            redirect("grupos/estudiantes/{$grupo_destino_id}");
            
    }
    
//---------------------------------------------------------------------------------------------------
//ASIGNACIÓN DE ESTUDIANTES DEL GRUPO A UN FLIPBOOK
    
    /* Formulario para la asignación de un flipbook a los estudiantes de un grupo ($grupo_id)
     * 
     */
    function asignar_flipbook($grupo_id)
    {
        //Cargando datos básicos (_basico)
            $data = $this->Grupo_model->basico($grupo_id);
            
        //Cargando array $data
            $data['subseccion'] = 'asignar_flipbook';
            $data['grupo_id'] = $grupo_id;
            $data['estudiantes'] = $this->Grupo_model->estudiantes($grupo_id);
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Asignar contenido al grupo';
            $data['vista_b'] = 'grupos/asignar_flipbook_v';
            $this->load->view(PTL_ADMIN, $data);
            
    }
    
    /* 
     * validad_asignacion_f => validar asignación de flipbook
     * 
     * Validación del formulario proveniente de grupos/asignar_flipbook
     */
    function validar_asignacion_f($grupo_id)
    {
        $this->load->library('form_validation');
        
        //Reglas
            $this->form_validation->set_rules('flipbook_id', 'Contenido', 'required');
        
        //Mensajes de validación
            $this->form_validation->set_message('required', 'El %s no puede ser vacío');
        
        //Comprobar validación
            if ( $this->form_validation->run() == FALSE ){
                //No se cumple la validación, se regresa al formulario
                $this->asignar_flipbook($grupo_id);
            } else {
                //Se cumple la validación, 
                $this->guardar_asignacion_f($grupo_id);    
            }
        
    }
    
    /* 
     * Crea registros en la tabla 'usuario_flipbook'
     * 
     * Guarda los registros enviados desde el formulario de asignar_flipbook
     * se ejecuta después de la validación en grupos/validar_asignacion_f
     */
    function guardar_asignacion_f($grupo_id)
    {
        
        //Cargando modelo de cuestionarios
        $this->load->model('Flipbook_model');
        
        //El array resultdos contiene variables con los resultados del proceso de creación de registros
        $resultado['num_insertados'] = 0;
        
        //Creando registro
            //Variables comunes
            $registro['flipbook_id'] = $this->input->post('flipbook_id');
        
        //Se carga la lista de todos los estudiantes del grupo
            $estudiantes = $this->Grupo_model->estudiantes($grupo_id);

            foreach ($estudiantes->result() as $row_estudiante)
            {
                //Si la casilla del estudiante en el formulario se ha marcado (TRUE) se agrega el registro
                if ( $this->input->post($row_estudiante->id) )
                {
                    $registro['usuario_id'] = $row_estudiante->id;
                    $resultado['num_insertados'] += $this->Flipbook_model->agregar_uf($registro);
                }
            }
        
        $this->session->set_flashdata('resultado', $resultado);
        
        redirect("grupos/asignar_flipbook/{$grupo_id}");
        
    }
    
// ELIMINAR ASIGNACIÓN DE FLIPBOOKS
//---------------------------------------------------------------------------------------------------
    
    /* 
     * Formulario para QUITAR la asignación de un flipbook a los estudiantes de un grupo ($grupo_id)
     * 
     */
    function quitar_flipbook($grupo_id, $flipbook_id = NULL)
    {
        
        //Cargando datos básicos (_basico)
            $data = $this->Grupo_model->basico($grupo_id);
            
        //Identificando segundo parámetro
            $data['flipbooks'] = $this->Grupo_model->flipbooks($grupo_id);
            
            if ( $data['flipbooks']->num_rows() > 0 ){
                //Tiene al menos un flipbook asociado
                $flipbook_id_defecto = $data['flipbooks']->row()->flipbook_id;
            } else {
                //No tiene flipbooks asociados
                $flipbook_id_defecto = 0;
            }
            $flipbook_id = $this->Pcrn->si_nulo($flipbook_id, $flipbook_id_defecto);
            
            
        //Lista de estudiantes, estudiantes que están asociados a un flipbook
            $this->db->where('flipbook_id', $flipbook_id);
            $this->db->where('usuario.grupo_id', $grupo_id);
            $this->db->join('usuario', 'usuario.id = usuario_flipbook.usuario_id');
            
            $data['estudiantes'] = $this->db->get('usuario_flipbook');
            
        //Cargando array $data
            $data['subseccion'] = 'quitar_flipbook';
            $data['grupo_id'] = $grupo_id;
            $data['flipbook_id'] = $flipbook_id;
            $data['nombre_flipbook'] = $this->App_model->nombre_flipbook($flipbook_id);
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Quitar contenido a estudiantes del grupo';
            $data['vista_b'] = 'grupos/quitar_flipbook_v';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    /* Elimina los registros seleccionados desde el formulario de grupos/quitar_flipbook
    */
    function eliminar_asignacion_f($grupo_id)
    {
        
        $this->output->enable_profiler(TRUE);
        
        //Cargando modelo de flipbooks
        $this->load->model('Flipbook_model');
        
        $resultado['num_insertados'] = 0;
        
        //
        $flipbook_id = $this->input->post('flipbook_id');
        
        //Creando registro
            //Variables comunes
            $condicion['flipbook_id'] = $flipbook_id;
        
        //Lista de estudiantes, estudiantes que están asociados a un flipbook
            $this->db->where('flipbook_id', $flipbook_id);
            $this->db->where('usuario.grupo_id', $grupo_id);
            $this->db->join('usuario', 'usuario.id = usuario_flipbook.usuario_id');
            
            $estudiantes = $this->db->get('usuario_flipbook');
            
        //
            
        $resultado['num_eliminados'] = 0;
        
        //Se recorre toda la lista de estudiantes del grupo
        foreach ($estudiantes->result() as $row_estudiante){
            
            //Si para el estudiante la casilla del formulario está marcada, se elimina el registro
            if ( $this->input->post($row_estudiante->id) ){
                //Se completa el array condición para ser utilizado en $this->where
                $condicion['usuario_id'] = $row_estudiante->id;
                $resultado['num_eliminados'] += $this->Flipbook_model->eliminar_uf($condicion);
            }
        }
        
        $this->session->set_flashdata('resultado', $resultado);
        
        redirect("grupos/quitar_flipbook/{$grupo_id}/{$flipbook_id}");
        
    }

// PREGUNTAS ABIERTAS ASIGNADAS A GRUPOS
//-----------------------------------------------------------------------------

    /**
     * Asignar pregunta abierta a grupo, desde contenidos de tipo clase dinámica
     * Se guarda en la tabla meta
     * 2019-09-10
     */
    function asignar_pa($grupo_id, $pregunta_id = 0)
    {
        if ( $pregunta_id == 0 )
        {
            //Crear nueva pregunta abierta, con datos del formulario
            $this->load->model('Tema_model');
            $data_pa = $this->Tema_model->save_pa($this->input->post('tema_id'), 0);
            
            //Si la creación de pregunta abierta fue exitosa
            if ( $data_pa['status'] ) { $pregunta_id = $data_pa['saved_id']; }
        }

        //Se realiza la asignación en la tabla meta
        $data = $this->Grupo_model->asignar_pa($grupo_id, $pregunta_id);

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Listado de preguntas asignadas para un grupo y área determinadas
     * Se solicita desde el flipook del tipo Clase Dinámica
     * 2019-09-11
     */
    function pa_asignadas($grupo_id, $area_id)
    {
        $pa_asignadas = $this->Grupo_model->pa_asignadas($grupo_id, $area_id);

        $data['pa_asignadas'] = $pa_asignadas->result();

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
    
//---------------------------------------------------------------------------------------------------
//ASIGNACIÓN DE ESTUDIANTES DEL GRUPO A UN ARCHIVO
    
    /* Formulario para la asignación de un archivo a los estudiantes de un grupo ($grupo_id)
     * 
     */
    function asignar_archivo($grupo_id = NULL)
    {
        //Cargando datos básicos (_basico)
            $grupo_id = $this->Pcrn->si_nulo($grupo_id, $this->_grupo_id());
            $data = $this->Grupo_model->basico($grupo_id);
            
        //Cargando array $data
            $data['grupo_id'] = $grupo_id;
            $data['estudiantes'] = $this->Grupo_model->estudiantes($grupo_id);
        
        //Solicitar vista
            $data['titulo_pagina'] = "Asignar archivo al grupo " . $data['titulo_pagina'];
            //$data['menu_sub'] = 'grupos/menu_sub_estudiantes_v';
            $data['vista_b'] = 'grupos/asignar_archivo_v';
            $this->load->view(PTL_ADMIN, $data);
            
    }
    
    /* 
     * validad_asignacion_f => validar asignación de archivo
     * 
     * Validación del formulario proveniente de grupos/asignar_archivo
     */
    function validar_asignacion_a($grupo_id)
    {
        $this->load->library('form_validation');
        
        //Reglas
            $this->form_validation->set_rules('archivo_id', 'Archivo', 'required');
        
        //Mensajes de validación
            $this->form_validation->set_message('required', "%s es obligatorio");
        
        //Comprobar validación
            if ( $this->form_validation->run() == FALSE ){
                //No se cumple la validación, se regresa al formulario
                $this->asignar_archivo($grupo_id);
            } else {
                //Se cumple la validación, 
                $this->guardar_asignacion_a($grupo_id);    
            }
        
    }
    
    /* 
     * Crea registros en la tabla 'usuario_asignacion'
     * 
     * Guarda los registros enviados desde el formulario de asignar_archivo
     * se ejecuta después de la validación en grupos/validar_asignacion_a
     */
    function guardar_asignacion_a($grupo_id)
    {
        
        //$this->output->enable_profiler(TRUE);
        
        //Cargando modelo de archivos
        $this->load->model('Usuario_model');
        
        //El array resultdos contiene variables con los resultados del proceso de creación de registros
        $resultado['num_insertados'] = 0;
        
        //Creando registro
            //Variables comunes
            $registro['referente_id'] = $this->input->post('archivo_id');
            $registro['tipo_asignacion_id'] = 598;  //Ver tabla item, categoria_id = 16
            $registro['editado_usuario_id'] = $this->session->userdata('usuario_id');
        
        //Se carga la lista de todos los estudiantes del grupo
            $estudiantes = $this->Grupo_model->estudiantes($grupo_id);

            foreach ($estudiantes->result() as $row_estudiante)
            {
                //Si la casilla del estudiante en el formulario se ha marcado (TRUE) se agrega el registro
                if ( $this->input->post($row_estudiante->id) )
                {
                    $registro['usuario_id'] = $row_estudiante->id;
                    $resultado['num_insertados'] += $this->Usuario_model->agregar_ua($registro);
                }
            }
        
        $this->session->set_flashdata('resultado', $resultado);
        
        redirect("grupos/asignar_archivo/{$grupo_id}");
        
    }
    
// ELIMINAR ASIGNACIÓN DE FLIPBOOKS
//---------------------------------------------------------------------------------------------------
    
    /* 
     * Formulario para QUITAR la asignación de un archivo a los estudiantes de un grupo ($grupo_id)
     * 
     */
    function quitar_archivo($grupo_id = NULL, $archivo_id = NULL)
    {
        
        //Cargando datos básicos (_basico)
            $grupo_id = $this->Pcrn->si_nulo($grupo_id, $this->_grupo_id(), $grupo_id);
            $data = $this->Grupo_model->basico($grupo_id);
            
        //Identificando segundo parámetro
            $data['archivos'] = $this->Grupo_model->archivos($grupo_id);
            
            if ( $data['archivos']->num_rows() > 0 ){
                //Tiene al menos un archivo asociado
                $archivo_id_defecto = $data['archivos']->row()->referente_id;
            } else {
                //No tiene archivos asociados
                $archivo_id_defecto = 0;
            }
            $archivo_id = $this->Pcrn->si_nulo($archivo_id, $archivo_id_defecto);
            
            
        //Lista de estudiantes, estudiantes que están asociados a un archivo
            $this->db->where('referente_id', $archivo_id);
            $this->db->where("usuario_id IN (SELECT usuario_id FROM usuario_grupo WHERE grupo_id = {$grupo_id})");
            $this->db->where('tipo_asignacion_id', 598);    //Ver tabla item, categoria_id = 16;
            $this->db->join('usuario', 'usuario.id = usuario_asignacion.usuario_id');
            
            $data['estudiantes'] = $this->db->get('usuario_asignacion');
            
        //Cargando array $data
            $data['grupo_id'] = $grupo_id;
            $data['archivo_id'] = $archivo_id;
            $data['nombre_archivo'] = $this->Pcrn->campo('archivo', "id = {$archivo_id}", 'titulo_archivo');
        
        //Solicitar vista
            $data['titulo_pagina'] = "Quitar archivo a estudiantes del grupo " . $data['titulo_pagina'];
            //$data['menu_sub'] = '';
            $data['vista_b'] = 'grupos/quitar_archivo_v';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    /* Elimina los registros seleccionados desde el formulario de grupos/quitar_archivo
    */
    function eliminar_asignacion_a($grupo_id)
    {
        
        //$this->output->enable_profiler(TRUE);
        
        //Cargando modelo de archivos
        $this->load->model('Usuario_model');
        
        $resultado['num_insertados'] = 0;
        
        //
        $archivo_id = $this->input->post('archivo_id');
        
        //Creando registro
            //Variables comunes
            $condicion['referente_id'] = $archivo_id;
            $condicion['tipo_asignacion_id'] = 598; //Ver tabla item, categoria_id = 16
        
        //Lista de estudiantes, estudiantes que están asociados a un archivo
            $this->db->where('referente_id', $archivo_id);
            $this->db->where("usuario_id IN (SELECT usuario_id FROM usuario_grupo WHERE grupo_id = {$grupo_id})");
            $this->db->where('tipo_asignacion_id', 598);    //Ver tabla item, categoria_id = 16
            $this->db->join('usuario', 'usuario.id = usuario_asignacion.usuario_id');
            
            $estudiantes = $this->db->get('usuario_asignacion');
            
        //
            
        $resultado['num_eliminados'] = 0;
        
        //Se recorre toda la lista de estudiantes del grupo
        foreach ($estudiantes->result() as $row_estudiante){
            
            //Si para el estudiante la casilla del formulario está marcada, se elimina el registro
            if ( $this->input->post($row_estudiante->id) ){
                //Se completa el array condición para ser utilizado en $this->where
                $condicion['usuario_id'] = $row_estudiante->id;
                $resultado['num_eliminados'] += $this->Usuario_model->eliminar_ua($condicion);
            }
        }
        
        $this->session->set_flashdata('resultado', $resultado);
        
        redirect("grupos/quitar_archivo/{$grupo_id}/{$archivo_id}");
        
    }
    
    
// GESTIÓN DE USUARIOS
//---------------------------------------------------------------------------------------------------
    
    function estudiantes($grupo_id)
    {
        //$this->output->enable_profiler(TRUE);
        
        $this->load->model('Esp');
        $this->load->model('Usuario_model');
        $this->load->model('Evento_model');
        
        //Cargando datos básicos (_basico)
            $data = $this->Grupo_model->basico($grupo_id);
            
        //Grupos nivel
            $this->db->where('institucion_id', $data['row']->institucion_id);
            $this->db->where('nivel', $data['row']->nivel);
            $this->db->where('anio_generacion', $data['row']->anio_generacion);
            $this->db->where("id <> {$grupo_id}");
            $this->db->order_by('grupo', 'ASC');
            $grupos_nivel = $this->db->get('grupo');
            
        //Cargando array $data
            $data['grupo_id'] = $grupo_id;
            $data['estudiantes'] = $this->Grupo_model->estudiantes($grupo_id);
            $data['grupos_nivel'] = $grupos_nivel;
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Listado de estudiantes';
            $data['subseccion'] = 'listado';
            $data['menu_sub'] = 'grupos/submenu_estudiantes_v';
            $data['vista_b'] = 'grupos/estudiantes_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * AJAX JSON
     * 2019-06-11
     * Ejecuta un proceso específico a un conjunto de usuarios estudiantes seleccionados en la sección
     * grupos/estudiantes.
     */
    function ejecutar_proceso($grupo_id)
    {
        $this->load->model('Usuario_model');
        
        $data['quan_executed'] = 0;
        
        //Se carga la lista de estudiantes que pertenecen un grupo
        $estudiantes = $this->Grupo_model->estudiantes($grupo_id);
        
        foreach ($estudiantes->result() as $row_estudiante){
            
            if ( $this->input->post($row_estudiante->usuario_id) ){
                
                //$proceso_id = str_replace('p', '', $this->input->post('proceso') );
                $proceso_id = substr($this->input->post('proceso'), 1, 1);
                
                if ( $proceso_id == 1 ){
                    $data['process'] = 'Activar';
                    $this->Usuario_model->cambiar_activacion($row_estudiante->usuario_id, 1);
                } elseif ( $proceso_id == 2 ) {
                    $data['process'] = 'Desactivar';
                    $this->Usuario_model->cambiar_activacion($row_estudiante->usuario_id, 0);
                } elseif ( $proceso_id == 3 ) {
                    $this->Usuario_model->restaurar_contrasena($row_estudiante->usuario_id);
                    $data['process'] = 'Restaurar contraseña';
                } elseif ( $proceso_id == 4 ) {
                    $this->Usuario_model->eliminar($row_estudiante->usuario_id);
                    $data['process'] = 'Eliminar';
                } elseif ( $proceso_id == 5 ) {
                    $this->Usuario_model->marcar_pagado($row_estudiante->usuario_id);
                    $data['process'] = 'Marcar como pagado';
                } elseif ( $proceso_id == 6 ) {
                    $this->Usuario_model->marcar_no_pagado($row_estudiante->usuario_id);
                    $data['process'] = 'Marcar como NO pagado';
                } elseif ( $proceso_id == 7 ) {
                    $grupo_destino_id = substr($this->input->post('proceso'), -6, 6);
                    $row_grupo = $this->Pcrn->registro_id('grupo', $grupo_destino_id);
                    $this->Usuario_model->cambiar_grupo($row_estudiante->usuario_id, $grupo_id, $grupo_destino_id);
                    $data['process'] = "Mover al grupo {$row_grupo->nivel}-{$row_grupo->grupo}" ;
                } elseif ( $proceso_id == 8 ) {
                    $data['process'] = 'Retirar (Sin eliminar)';
                    $this->Grupo_model->eliminar_ug($grupo_id, $row_estudiante->usuario_id);
                }
                
                $data['quan_executed'] += 1;
            }
        }
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
    
// GESTIÓN DE CUESTIONARIOS PARA GRUPOS
//-----------------------------------------------------------------------------
    
    function cuestionarios($grupo_id, $area_id = NULL)
    {   
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Cuestionario_model');
        
        //Cargando datos básicos
            $data = $this->Grupo_model->basico($grupo_id);
            
        //Variables
            $cuestionarios = $this->Grupo_model->cuestionarios($grupo_id, $area_id);
            
        //Cargando $data
            $data['cuestionarios'] = $cuestionarios;
            $data['subseccion'] = 'listado';
            
        //Areas
            $this->db->where('categoria_id', 1);    //Áreas
            $this->db->where('item_grupo', 1);  //Principales áreas
            $data['areas'] = $this->db->get('item');
            
        //Variables
            $data['area_id'] = $area_id;
            
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Cuestionarios';
            $data['vista_b'] = 'grupos/cuestionarios/cuestionarios_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function cuestionarios_flipbooks($grupo_id)
    {
        //Cargando datos básicos (_basico)
        $data = $this->Grupo_model->basico($grupo_id);
            
        //Cargando array $data
            $data['grupo_id'] = $grupo_id;
            $data['flipbooks'] = $this->Grupo_model->flipbooks($grupo_id);
            $data['subseccion'] = 'en_linea';
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Cuestionarios desde Contenidos';
            $data['vista_b'] = 'grupos/cuestionarios_flipbooks_v';
            $data['menu_sub'] = 'grupos/menu_sub_flipbooks_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function cuestionarios_resumen01($grupo_id, $area_id = 50)
    {
        //$this->output->enable_profiler(TRUE);
        
        $this->load->model('Cuestionario_model');
        
        //Cargando datos básicos (_basico)
            $data = $this->Grupo_model->basico($grupo_id);
        
        //Head includes específicos para esta función
            $head_includes[] = 'highcharts';
            
        //Cuestionarios
            $this->db->select('cuestionario_id');
            $this->db->where('grupo_id', $grupo_id);
            $this->db->join('cuestionario', 'dw_usuario_pregunta.cuestionario_id = cuestionario.id');
            $this->db->where('dw_usuario_pregunta.area_id', $area_id);
            $this->db->where('tipo_id IN (1, 2, 3)'); //Solo cuestionarios internos de Enlace
            $this->db->group_by('cuestionario_id');
            $cuestionarios = $this->db->get('dw_usuario_pregunta');
            
        //Variables
            $arr_competencias = $this->Cuestionario_model->arr_competencias($area_id);
        
        //$data Específico
            $data['areas'] = $this->db->get_where('item', "categoria_id = 1 AND item_grupo = 1");
            $data['grupo_id'] = $grupo_id;
            $data['area_id'] = $area_id;
            $data['subseccion'] = 'resumen01';
            $data['head_includes'] = $head_includes;
            $data['cuestionarios'] = $cuestionarios;
            $data['competencias'] = $this->Cuestionario_model->competencias_area($area_id);     //Query competencias
            $data['arr_competencias'] = $arr_competencias;  //Array competencias
            $data['cant_competencias'] = count($arr_competencias);
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Desempeño por competencias';
            $data['vista_b'] = 'grupos/cuestionarios/res01_v';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    /**
     * Gráfico de desempeño del grupo por competencias
     * agrupado por acumulador (usuario_pregunta.acumulador)
     */
    function cuestionarios_resumen02($grupo_id, $area_id = 50)
    {
        //$this->output->enable_profiler(TRUE);
        
        $this->load->model('Cuestionario_model');
        
        //Cargando datos básicos (_basico)
            $data = $this->Grupo_model->basico($grupo_id);
        
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
            $filtros['grupo_id'] = $grupo_id;
            $filtros['area_id'] = $area_id;
            $cant_acumuladores = $this->Cuestionario_model->cant_acumuladores($filtros);
            
        
        //$data Específico
            $data['areas'] = $this->db->get_where('item', "categoria_id = 1 AND item_grupo = 1");
            $data['grupo_id'] = $grupo_id;
            $data['cant_acumuladores'] = $cant_acumuladores;
            $data['area_id'] = $area_id;
            $data['subseccion'] = 'resumen02';
            $data['head_includes'] = $head_includes;
            $data['nombres_competencias'] = $nombres_competencias;
            $data['estudiantes'] = $this->Grupo_model->estudiantes($grupo_id);
            $data['vista_b'] = 'grupos/res02_v';
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Desempeño por competencias';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    /**
     * Gráfico de desempeño del grupo por competencias
     * agrupado por acumulador mixto (usuario_pregunta.acumulador_2)
     */
    function cuestionarios_resumen03($grupo_id, $area_id = 50)
    {
        //$this->output->enable_profiler(TRUE);
        
        $this->load->model('Cuestionario_model');
        
        //Cargando datos básicos (_basico)
            $data = $this->Grupo_model->basico($grupo_id);
        
        //Head includes específicos para esta función
            $head_includes[] = 'highcharts';
            
        //Identificar acumuladores de la gráfica
            $filtros['usuario_cuestionario.grupo_id'] = $grupo_id;
            $filtros['area_id'] = $area_id;
            $acumuladores = $this->Cuestionario_model->acumuladores_2($filtros);
        
        //$data Específico
            $data['areas'] = $this->db->get_where('item', "categoria_id = 1 AND item_grupo = 1");
            $data['area_id'] = $area_id;
            $data['acumuladores'] = $acumuladores;
            $data['grupo_id'] = $grupo_id;
            $data['subseccion'] = 'resumen03';
            $data['head_includes'] = $head_includes;
            $data['competencias'] = $this->Cuestionario_model->competencias_area($area_id); //Query competencias
            $data['estudiantes'] = $this->Grupo_model->estudiantes($grupo_id);
            $data['vista_b'] = 'grupos/cuestionarios/res03_v';
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Desempeño por competencias';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
// RESULTADOS DE CUESTIONARIOS
//---------------------------------------------------------------------------------------------------
    
    function resultados_grupo($grupo_id, $cuestionario_id = NULL)
    {
        
        /* Muestra el resultado obtenido por los grupos de una institución en la ejecución de un cuestionario,
         * los resultados se muestran en un gráfico.
         */
        
                
        //Cargando datos básicos (_basico)
        $grupo_id = $this->Pcrn->si_nulo($grupo_id, $this->_grupo_id(), $grupo_id);
        $data = $this->Grupo_model->basico($grupo_id);
        
        //Definiendo segundo argumento de la función
        $cuestionario_id = $this->Pcrn->si_nulo($cuestionario_id, $data['cuestionarios']->row()->cuestionario_id, $cuestionario_id);
        
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
            $grupos = $this->Grupo_model->grupos_cuestionario($grupo_id, $cuestionario_id);

            //Se carga para cada grupo, un array de resultados
            foreach ($grupos->result() as $row) {
                $resultados[$row->grupo_id] = $this->App_model->res_cuestionario($cuestionario_id, "grupo_id = {$row->grupo_id}");
            }
            
            //Se carga un array con el valor de las preguntas correctas
            foreach ( $resultados as $key => $value ){
                $correctas[] = $value['correctas'];
            }
            
        //Cargando array $data
            $data['grupos'] = $grupos;
            $data['correctas'] = $correctas;
            $data['resultados'] = $resultados;
            $data['menu_sub'] = 'grupos/menu_sub_resultados_v';
        
        //Solicitar vista
        $data['titulo_pagina'] = "Resultados por grupo | " . $data['titulo_pagina'];
        $data['vista_b'] = 'grupos/resultados_grupo_v';
        $this->load->view(PTL_ADMIN, $data);
        
    }
    
    /* Muestra el resultado obtenido por los grupos de una institución en la ejecución de un cuestionario,
     * los resultados se muestran en un gráfico.
     */
    function resultados_area($grupo_id, $cuestionario_id = NULL)
    {                
        //Cargando datos básicos (_basico)
        $grupo_id = $this->Pcrn->si_nulo($grupo_id, $this->_grupo_id(), $grupo_id);
        $data = $this->Grupo_model->basico($grupo_id);
        
        //Definiendo segundo argumento de la función
        $cuestionario_id = $this->Pcrn->si_nulo($cuestionario_id, $data['cuestionarios']->row()->cuestionario_id);
        
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
                $resultados[$row->area_id] = $this->App_model->res_cuestionario($cuestionario_id, "grupo_id = {$grupo_id}", "area_id = {$row->area_id}");
            }
            
            //Se carga un array con el valor de las preguntas correctas
            foreach ( $resultados as $key => $value ){
                $correctas[] = $value['correctas'];
            }
            
            foreach ( $resultados as $key => $value ){
                $num_preguntas_area[] = $value['num_preguntas'];
            }
            
        //Cargando array $data
            $data['areas'] = $areas;
            $data['correctas'] = $correctas;
            $data['num_preguntas_area'] = $num_preguntas_area;
            $data['resultados'] = $resultados;
            $data['menu_sub'] = 'grupos/menu_sub_resultados_v';
        
        //Solicitar vista
        $data['titulo_pagina'] = "Resultados por área | " . $data['titulo_pagina'];
        $data['vista_b'] = 'grupos/resultados_area_v';
        $this->load->view(PTL_ADMIN, $data);
        
    }
    
    function resultados_lista($grupo_id, $cuestionario_id = NULL)
    {
        
        //$this->output->enable_profiler(TRUE);
        
        //Cargando datos básicos (_basico)
        $grupo_id = $this->Pcrn->si_nulo($grupo_id, $this->_grupo_id(), $grupo_id);
        $data = $this->Grupo_model->basico($grupo_id);
        
        $data['cuestionarios_grupos'] = $this->Grupo_model->cuestionarios_grupos($grupo_id);
        
        //Definiendo segundo argumento de la función
        $grupo_id = $this->Pcrn->si_nulo($grupo_id, $data['cuestionarios_grupos']->row()->grupo_id, $grupo_id);
        
        //Definiendo tercer argumento de la función
        $cuestionario_id = $this->Pcrn->si_nulo($cuestionario_id, $data['cuestionarios_grupos']->row()->cuestionario_id, $cuestionario_id);
        
        //Obtener información del cuestionario
            $this->load->model('Cuestionario_model');
            $data['row_cuestionario'] = $this->Cuestionario_model->datos_cuestionario($cuestionario_id);
        
        $data['lista'] = $this->Grupo_model->resultados_lista($grupo_id, $cuestionario_id);
        
        $data['grupo_id'] = $grupo_id;
        
        
        //Cargando array $data
            $data['menu_sub'] = 'grupos/menu_sub_lista_v';
        
        //Solicitar vista
        $data['titulo_pagina'] = "Lista de resultados | " . $data['titulo_pagina'];
        $data['vista_b'] = 'grupos/resultados_lista_v';
        $this->load->view(PTL_ADMIN, $data);
        
    }
    
    function resultados_componente($grupo_id, $cuestionario_id, $area_id = NULL)
    {
        
        /* Muestra el resultado obtenido por los grupos de una institución en la ejecución de un cuestionario,
         * los resultados se muestran en un gráfico.
         */
                
        //Cargando datos básicos (_basico)
        $grupo_id = $this->Pcrn->si_nulo($grupo_id, $this->_grupo_id(), $grupo_id);
        $data = $this->Grupo_model->basico($grupo_id);
        
        //Definiendo segundo argumento de la función
        $cuestionario_id = $this->Pcrn->si_nulo($cuestionario_id, $data['cuestionarios']->row()->cuestionario_id, $cuestionario_id);
        
        //Definiendo tercer argumento de la función
        $this->load->model('Cuestionario_model');
        $areas = $this->Cuestionario_model->areas($cuestionario_id);
        $area_id = $this->Pcrn->si_nulo($area_id, $areas->row()->area_id, $area_id);
        
        $data['cuestionarios_grupos'] = $this->Grupo_model->cuestionarios_grupos($grupo_id);
        
        //Head includes específicos para la página, para gráficos
            $head_includes[] = 'highcharts';
            $head_includes[] = 'grafico_componentes';
            $data['head_includes'] = $head_includes;
        
        //Variables para el gráfico
            
            //Obtener información del cuestionario
            $data['row_cuestionario'] = $this->Cuestionario_model->datos_cuestionario($cuestionario_id);
            $data['titulo_grafico'] = $data['row_cuestionario']->nombre_cuestionario .  " - " . $this->App_model->nombre_item($area_id, 1);;
            $data['num_preguntas'] = $data['row_cuestionario']->num_preguntas;

            $componentes = $this->Cuestionario_model->componentes($cuestionario_id, $area_id);
            
            //Se carga para cada componente, un array de resultados
            foreach ($componentes->result() as $row_componente) {
                $resultados[$row_componente->componente_id] = $this->App_model->res_cuestionario($cuestionario_id, "grupo_id = {$grupo_id}", "componente_id = {$row_componente->componente_id}");
            }

            //Se carga un array con el valor de las preguntas correctas
            foreach ( $resultados as $value ){
                $correctas[] = $value['correctas'];
            }

            foreach ( $resultados as $value ){
                $num_preguntas_componente[] = $value['num_preguntas'];
            }
            
        //Cargando array $data
            $data['area_id'] = $area_id;
            $data['areas'] = $areas;
            $data['componentes'] = $componentes;
            $data['correctas'] = $correctas;
            $data['num_preguntas_componente'] = $num_preguntas_componente;
            $data['resultados'] = $resultados;
            $data['menu_sub'] = 'grupos/menu_sub_resultados_v';
        
        //Solicitar vista
        $data['titulo_pagina'] = "Resultados por componente | " . $data['titulo_pagina'];
        $data['vista_b'] = 'grupos/resultados_componentes_v';
        $this->load->view(PTL_ADMIN, $data);
        
    }
    
    function resultados_competencia($grupo_id, $cuestionario_id, $area_id = NULL)
    {
        
        /* Muestra el resultado obtenido por la grupoen la ejecución de un cuestionario,
         * los resultados se muestran en un gráfico. Clasificando los resultados por competencias
         */
                
        //Cargando datos básicos (_basico)
        $grupo_id = $this->Pcrn->si_nulo($grupo_id, $this->_grupo_id(), $grupo_id);
        $data = $this->Grupo_model->basico($grupo_id);
        
        //Definiendo segundo argumento de la función
        $cuestionario_id = $this->Pcrn->si_nulo($cuestionario_id, $data['cuestionarios']->row()->cuestionario_id, $cuestionario_id);
        
        //Definiendo tercer argumento de la función
        $this->load->model('Cuestionario_model');
        $areas = $this->Cuestionario_model->areas($cuestionario_id);
        $area_id = $this->Pcrn->si_nulo($area_id, $areas->row()->area_id, $area_id);
        
        $data['cuestionarios_grupos'] = $this->Grupo_model->cuestionarios_grupos($grupo_id);
        
        //Head includes específicos para la página, para gráficos
            $head_includes[] = 'highcharts';
            $head_includes[] = 'grafico_competencias';
            $data['head_includes'] = $head_includes;
        
        //Variables para el gráfico
            
            //Obtener información del cuestionario
            $data['row_cuestionario'] = $this->Cuestionario_model->datos_cuestionario($cuestionario_id);
            $data['titulo_grafico'] = $data['row_cuestionario']->nombre_cuestionario .  " - " . $this->App_model->nombre_item($area_id, 1);;
            $data['num_preguntas'] = $data['row_cuestionario']->num_preguntas;

            $competencias = $this->Cuestionario_model->competencias($cuestionario_id, $area_id);
            
            //Se carga para cada competencia, un array de resultados
            foreach ($competencias->result() as $row_competencia) {
                $resultados[$row_competencia->competencia_id] = $this->App_model->res_cuestionario($cuestionario_id, "grupo_id = {$grupo_id}", "competencia_id = {$row_competencia->competencia_id}");
            }

            //Se carga un array con el valor de las preguntas correctas
            foreach ( $resultados as $value ){
                $correctas[] = $value['correctas'];
            }

            foreach ( $resultados as $value ){
                $num_preguntas_competencia[] = $value['num_preguntas'];
            }
            
        //Cargando array $data
            $data['area_id'] = $area_id;
            $data['areas'] = $areas;
            $data['competencias'] = $competencias;
            $data['correctas'] = $correctas;
            $data['num_preguntas_competencia'] = $num_preguntas_competencia;
            $data['resultados'] = $resultados;
            $data['menu_sub'] = 'grupos/menu_sub_resultados_v';
        
        //Solicitar vista
        $data['titulo_pagina'] = "Resultados por competencia | " . $data['titulo_pagina'];
        $data['vista_b'] = 'grupos/resultados_competencias_v';
        $this->load->view(PTL_ADMIN, $data);
        
    }
    
// CARGA MASIVA DE ESTUDIANTES
//---------------------------------------------------------------------------------------------------
    /**
     * Mostrar formulario de cargue de estudiantes mediante archivos de excel.
     * El resultado del formulario se envía a 'grupos/resultado_cargue'    
     * 
     * @param type $grupo_id
     */
    function cargar_estudiantes($grupo_id)
    {

        //Cargando datos básicos (basico)
        $data = $this->Grupo_model->basico($grupo_id);
        
        $data['subseccion'] = 'cargue';
        $data['titulo_pagina'] = 'Cargar estudiantes al grupo';
        $data['vista_b'] = 'grupos/cargar_estudiantes_v';
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function procesar_cargue($grupo_id)
    {
        $this->load->library('form_validation');
        
        //Reglas
            $this->form_validation->set_rules('nombre_hoja', 'Nombre hoja', 'required');
        
        //Mensajes de validación
            $this->form_validation->set_message('required', "%s no puede quedar vacío");
        
        //Comprobar validación
            if ( $this->form_validation->run() == FALSE ){
                //No se cumple la validación, se regresa al cuestionario
                $this->cargar_usuarios($grupo_id);
            } else {
                //Se cumple la validación, 
                $this->resultado_cargue($grupo_id);
            }    
    }
    
    function resultado_cargue($grupo_id)
    {
        
        //Cargando datos básicos (_basico)
        $data = $this->Grupo_model->basico($grupo_id);
        
        //Variables
        $usuarios_insertados = array();
        
        $archivo = $_FILES['file']['tmp_name'];    //Se crea un archivo temporal, no se sube al servidor, se toma el nombre temporal
        $nombre_hoja = $this->input->post('nombre_hoja');   //Nombre de hoja digitada por el usuario en el formulario
        
        $this->load->model('Pcrn_excel');
        $resultado = $this->Pcrn_excel->array_hoja($archivo, $nombre_hoja, 'E');
        $usuarios = $resultado['array_hoja'];
        
        if ( $resultado['cargado'] ) {
            $this->load->model('Usuario_model');
            $usuarios_insertados = $this->Usuario_model->insert_estudiantes($grupo_id, $usuarios);
        }
        
        //Cargue de variabls
            $data['cargado'] = $resultado['cargado'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['usuarios'] = $usuarios;
            $data['nombre_hoja'] = $nombre_hoja;
            $data['usuarios_insertados'] = $usuarios_insertados;
        
        //Cargar vista
            $data['subseccion'] = 'cargue';
            $data['vista_b'] = 'grupos/resultado_cargue_v';
            $data['titulo_pagina'] .= ' - Cargue de usuarios';
            $this->load->view(PTL_ADMIN, $data);
    }
    
// PROCESOS MASIVOS
//---------------------------------------------------------------------------------------------------
    /**
     * Actualizar el campo grupo.nombre_grupo de todos los grupos
     */
    function renombrar_grupos()
    {
        //Variables específicas
        $resultado = $this->Grupo_model->act_nombres();
        
        $this->session->set_flashdata('resultado', $resultado);
        redirect('develop/procesos');
    }
    
    
    
}