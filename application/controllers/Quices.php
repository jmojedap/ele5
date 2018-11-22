<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quices extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        $this->load->model('Quiz_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($quiz_id = NULL)
    {   
        if ( is_null($quiz_id) ) {
            $this->explorar();
        } else {
            redirect("quices/construir/{$quiz_id}");
        }
    }

//INFORMACIÓN DE QUIZ
//---------------------------------------------------------------------------------------------------
    
    function explorar()
    {
        $this->load->model('Busqueda_model');
        $this->load->model('Esp');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Quiz_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(2);
            $config['base_url'] = base_url("quices/explorar/?{$busqueda_str}");
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Quiz_model->buscar($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['resultados'] = $resultados;
            $data['ayuda_id'] = 87;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Quices';
            $data['subtitulo_pagina'] = $resultados_total->num_rows();
            $data['vista_a'] = 'quices/explorar_v';
            $data['vista_menu'] = 'quices/menu_explorar_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Exporta el resultado de la búsqueda a un archivo de Excel
     */
    function exportar_org()
    {
        
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso
        //Cargando
            $this->load->model('Busqueda_model');
            $this->load->model('Pcrn_excel');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Quiz_model->buscar($busqueda); //Para calcular el total de resultados
        
            if ( $resultados_total->num_rows() <= MAX_REG_EXPORT ) 
            {
                //Preparar datos
                    $datos['nombre_hoja'] = 'Evidencias';
                    $datos['query'] = $resultados_total;

                //Preparar archivo
                    $objWriter = $this->Pcrn_excel->archivo_query($datos);

                $data['objWriter'] = $objWriter;
                $data['nombre_archivo'] = date('Ymd_His'). '_evidencias'; //save our workbook as this file name

                $this->load->view('comunes/descargar_phpexcel_v', $data);
            } else {
                $data['titulo_pagina'] = 'Plataforma Enlace';
                $data['mensaje'] = "El número de registros es de {$resultados_total->num_rows()}. El máximo permitido es de " . MAX_REG_EXPORT . " registros. Puede filtrar los datos por algún criterio para poder exportarlos.";
                $data['link_volver'] = "quices/explorar/?{$busqueda_str}";
                $data['vista_a'] = 'app/mensaje_v';
                
                $this->load->view(PTL_ADMIN, $data);
            }
            
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
            $resultados_total = $this->Quiz_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Preparar datos
            $datos['nombre_hoja'] = 'Quices';
            $datos['query'] = $resultados_total;
            
        //Preparar archivo
            $objWriter = $this->Pcrn_excel->archivo_query($datos);
        
        $data['objWriter'] = $objWriter;
        $data['nombre_archivo'] = date('Ymd_His'). '_quices'; //save our workbook as this file name
        
        $this->load->view('comunes/descargar_phpexcel_v', $data);
            
    }
    
    function reciente()
    {
        $this->db->order_by('id', 'DESC');
        $quices = $this->db->get('quiz');
        
        $quiz_id = $quices->row()->id;
        
        redirect("quices/construir/{$quiz_id}");
    }
    
    function crear($tema_id, $tipo_quiz_id)
    {
        $quiz_id = $this->Quiz_model->crear($tema_id, $tipo_quiz_id);
        redirect("quices/construir/{$quiz_id}");
    }
    
    function detalle($quiz_id)
    {
        //$this->output->enable_profiler(TRUE);
        
        $data = $this->Quiz_model->basico($quiz_id);
        
        $data['vista_b'] = "quices/detalle_v";
        $this->load->view(PTL_ADMIN, $data);
    }
    
// GESTIÓN DE TEMAS
//---------------------------------------------------------------------------------------------------------
    
    /**
     * Listado de temas relacionados con un quiz
     * @param type $quiz_id
     */
    function temas($quiz_id)
    {
        $data = $this->Quiz_model->basico($quiz_id);
        
        $data['temas'] = $this->Quiz_model->temas($quiz_id);
        
        $data['vista_b'] = "quices/temas_v";
        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * REDIRECT
     * 
     * @param type $quiz_id
     */
    function quitar_tema($quiz_id, $tema_id)
    {
        $this->load->model('Tema_model');
        $cant_eliminados = $this->Tema_model->quitar_quiz($tema_id, $quiz_id);
        
        $resultado['ejecutado'] = 1;
        $resultado['mensaje'] = "Se eliminaron {$cant_eliminados} registros";
        $resultado['clase'] = 'alert-success';
        $resultado['icono'] = 'fa-check';
        
        $this->session->set_flashdata('resultado', $resultado);
        
        redirect("quices/temas/{$quiz_id}");
    }
    
    /**
     * REDIRECT
     * 2017-05-09, para evitar error en links
     * 
     * @param type $quiz_id
     */
    function ver($quiz_id)
    {
        redirect("quices/construir/{$quiz_id}");
    }
    
    /**
     * Inicia el proceso de respuesta de un quiz, por parte de un usuario,
     * Crea los registros para guardar la información del proceso de respuesta
     * 
     * @param type $quiz_id
     */
    function iniciar($quiz_id = NULL)
    {
        if ( ! is_null($quiz_id) )
        {
            //Crear registro en la tabla usuario_asignación
                $ua_id = $this->Quiz_model->iniciar($quiz_id);   //Al abrir, establecer por defecto: incorrecto

            //Registrar inicio de respuesta en la tabla evento
                $this->load->model('Evento_model');
                $this->Evento_model->guardar_inicia_quiz($quiz_id, $ua_id);

            redirect("quices/resolver/{$quiz_id}");  
        }
        else
        {
            $data['titulo_pagina'] = 'Prueba no encontrada';
            $data['vista_a'] = 'app/mensaje_v';
            $data['mensaje'] = '<i class="fa fa-info-circle"></i> La evidencia no fue encontrada o no está asignada correctamente. Consulte a su asesor.';
            $this->load->view(PTL_ADMIN, $data);
        }
        
    }
    
    function resolver($quiz_id)
    {   
        //Registrar en evento
        
        $data = $this->Quiz_model->basico($quiz_id);
        $data['elementos'] = $this->Quiz_model->elementos($quiz_id);
        $data['imagen'] = $this->Quiz_model->imagen($quiz_id);
        $data['row_tipo_quiz'] = $this->Quiz_model->row_tipo_quiz($data['row']->tipo_quiz_id);
        $data['row_tema'] = $this->Pcrn->registro_id('tema', $data['row']->tema_id);
        
        $tipo_quiz_id = $data['row']->tipo_quiz_id;
        
        $data['vista_a'] = "quices/resolver/resolver_{$tipo_quiz_id}_v";
        $this->load->view('quices/resolver/resolver_v', $data);
        
        //$this->output->enable_profiler(TRUE);
        
    }
    
    function editar()
    {
        //Cargando datos básicos
            $quiz_id = $this->uri->segment(4);
            $data = $this->Quiz_model->basico($quiz_id);
            
        //Render del grocery crud
            $gc_output = $this->Quiz_model->crud_editar($quiz_id);
            
        //Solicitar vista
            $data['vista_b'] = 'comunes/gc_v';
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(PTL_ADMIN, $output);
    }
    
    function eliminar($quiz_id, $tema_id = NULL)
    {
        $this->Quiz_model->eliminar($quiz_id);
        
        if ( is_null($tema_id) ) {
            redirect("quices/explorar");
        } else {
            redirect("temas/quices/{$tema_id}");
        }
    }
    
    function construir($quiz_id)
    {
        //$this->output->enable_profiler(TRUE);
        $data = $this->Quiz_model->basico($quiz_id);
        $data['elementos_quiz'] = $this->Quiz_model->elementos($quiz_id);
        $data['imagenes'] = $this->Quiz_model->imagenes($quiz_id);
        $data['imagen'] = $this->Quiz_model->imagen($quiz_id);
        $data['arr_elementos'] = $this->Quiz_model->arr_elementos($quiz_id);
        
        $data['ayuda_id'] = $this->Quiz_model->ayuda_id_tipo($data['row']->tipo_quiz_id);
        
        $tipo_quiz_id = $data['row']->tipo_quiz_id;
        
        $data['vista_b'] = "quices/construir/construir_{$tipo_quiz_id}_v";
        $data['subtitulo_pagina'] = 'Quiz';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function elementos($quiz_id)
    {
        
        //Cargando datos básicos
            $data = $this->Quiz_model->basico($quiz_id);
            $data['vista_b'] = 'quices/elementos_v';
            
        //Head includes específicos para la página
            $head_includes[] = 'grocery_crud';
            $data['head_includes'] = $head_includes;
            $output = $this->Quiz_model->crud_elemento($quiz_id);
            
        //Información
            $output = array_merge($data,(array)$output);
            $this->load->view(PTL_ADMIN, $output);
        
    }
    
//GESTIÓN DE ELEMENTOS
//---------------------------------------------------------------------------------------------------
    
    /**
     * AJAX
     * edita o crea registro en la tabla usuario_asignacion
     * 
     * El tipo detalle 'Quiz' corresponde al tipo_asignacion_id = 3,
     * tabla: item.categoria_id = 16
     */
    function guardar_resultado()
    {
        
        $ua_id = $this->Quiz_model->guardar_resultado();
        
        $this->load->model('Evento_model');
        $this->Evento_model->guardar_fin_quiz($ua_id);
        
        echo $ua_id;
        
        $this->output->enable_profiler(TRUE);
        
    }
    
    /**
     * AJAX
     * Crea un registro de anotación en la tabla 'quiz_elemento'
     * 
     */
    function guardar_elemento()
    {
        //Valor por defecto
        $ua_id = 0;
        
        //Si es una página existente
        if ( $this->input->post('quiz_id') > 0 ){
            //Construir el registro que se va a insertar
            $registro = array(
                'id_alfanumerico' => $this->input->post('id_alfanumerico'),
                'quiz_id' => $this->input->post('quiz_id'),
                'tipo_id' => $this->input->post('tipo_id'),
                'orden' => $this->input->post('orden'),
                'texto' => $this->input->post('texto'),
                'detalle' => $this->input->post('detalle'),
                'clave' => $this->input->post('clave'),
                'x' => $this->input->post('x'),
                'y' => $this->input->post('y'),
                'alto' => $this->input->post('alto'),
                'ancho' => $this->input->post('ancho')
            );

            $ua_id = $this->Quiz_model->guardar_elemento($registro);
        }

        //Respuesta
        echo $ua_id;
    }
    
    /**
     * AJAX
     * Elimina un registro de la tabla 'quiz_elemento'
     * 
     */
    function eliminar_elemento($id_alfanumerico)
    {   
        $this->Quiz_model->eliminar_elemento($id_alfanumerico);
    }
    
    /**
     * AJAX
     * Crea un registro de anotación en la tabla 'quiz_elemento', con posición
     * 
     */
    function guardar_elemento_pos()
    {
        //Valor por defecto
        $ua_id = 0;
        
        //Si es una página existente
        if ( $this->input->post('quiz_id') > 0 ){
            //Construir el registro que se va a insertar
            $registro = array(
                'id_alfanumerico' => $this->input->post('id_alfanumerico'),
                'x' => $this->input->post('x'),
                'y' => $this->input->post('y'),
                'alto' => $this->input->post('alto'),
                'ancho' => $this->input->post('ancho')
            );

            $ua_id = $this->Quiz_model->guardar_elemento($registro);
        }

        //Respuesta
        echo $ua_id;
    }
    
//IMÁGENES
//---------------------------------------------------------------------------------------------------
    
    function cargar_imagen($quiz_id)
    {
        $results = $this->Quiz_model->cargar_imagen();
        
        //Cargue exitoso, se crea registro asociado
            if ( $results['result'] ) { $this->Quiz_model->guardar_imagen($results['upload_data']); }
        
        $this->session->set_flashdata('message', $results['message']);
        redirect("quices/construir/{$quiz_id}");
    }
    
    function eliminar_archivo($quiz_id, $id_alfanumerico)
    {
        $row = $this->Pcrn->registro('quiz_elemento', "id_alfanumerico = '{$id_alfanumerico}'");
        $ruta = RUTA_UPLOADS . 'quices/' . $row->archivo;
        unlink($ruta);
        
        $this->Quiz_model->eliminar_elemento($id_alfanumerico);
        
        redirect("quices/construir/{$quiz_id}");
    }
    
    function cargar_imagen_elemento($quiz_id)
    {
        $results = $this->Quiz_model->cargar_imagen();
        
        if ( $results['result'] ) { 
            $elemento_id = $this->input->post('elemento_id');
            $this->Quiz_model->asignar_archivo($elemento_id, $results['upload_data']); 
        }
        
        $this->session->set_flashdata('mensaje_elemento', $results['message']);
        redirect("quices/construir/{$quiz_id}");
        
    }
    
    function cargar_img_elemento_nuevo($quiz_id)
    {
        $results = $this->Quiz_model->cargar_imagen();
        
        if ( $results['result'] ) {
            
            //Preparar registro
                $registro['id_alfanumerico'] = $this->input->post('id_alfanumerico');
                $registro['quiz_id'] = $this->input->post('quiz_id');
                $registro['tipo_id'] = $this->input->post('tipo_id');
                $registro['orden'] = $this->input->post('orden');
                $registro['clave'] = $this->input->post('clave');
                $registro['x'] = 10;
                $registro['y'] = 10;
                $registro['alto'] = $results['upload_data']['image_height'];
                $registro['ancho'] = $results['upload_data']['image_width'];
                
            //Guardar elemento
                $elemento_id = $this->Quiz_model->guardar_elemento($registro);
                
            //Asignar la imagen cargada al elemento
                $this->Quiz_model->asignar_archivo($elemento_id, $results['upload_data']); 
        }
        
        $this->session->set_flashdata('mensaje_elemento', $results['message']);
        redirect("quices/construir/{$quiz_id}");
        
    }
    
    function form_imagen_elemento($id_alfanumerico)
    {
        $row_elemento = $this->Pcrn->registro('quiz_elemento', "id_alfanumerico = '{$id_alfanumerico}'");
        $data['elemento_id'] = $row_elemento->id;
        $vista = $this->load->view('quices/construir/form_imagen_elemento_v', $data);
        
        echo $vista;
    }

//PENDIENTE
//---------------------------------------------------------------------------------------------------
    
    function crear_elemento()
    {
        $this->db->order_by('id', 'DESC');
        $data['elementos'] = $this->db->get('quiz_elemento', 100);
        
        $data['titulo_pagina'] = 'Creación de elementos';
        $data['vista_a'] = "quices/crear_elemento_v";
        $this->load->view(PTL_ADMIN, $data);
        
    }
    
//PROCESOS MASIVOS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Cargar asignación de quices en la tabla recurso
     */
    function actualizar_recurso()
    {
        $recursos = array();
        $quices = $this->db->get('quiz');
        
        foreach( $quices->result() as $row_quiz ){
            $recursos[] = $this->Quiz_model->guardar_recurso($row_quiz->id, $row_quiz->tema_id);
        }
        
        $cant_recursos = count($recursos);
        
        $data['mensaje'] = "Se actualizaron {$cant_recursos} quices en la tabla recurso";
        $data['volver'] = 'quices/explorar';
        $data['titulo_pagina'] = 'Quices > Recursos';
        
        $data['vista_a'] = 'app/mensaje_v';
        $this->load->view(PTL_ADMIN, $data);
        
    }

}
