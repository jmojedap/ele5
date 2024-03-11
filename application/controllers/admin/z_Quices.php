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

    /**
     * Vista listado de quices, filtros exploración
     * 2021-05-12
     */
    function explorar($num_page = 1)
    {        
        //Identificar filtros de búsqueda
            $this->load->model('Search_model');
            $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Quiz_model->explore_data($filters, $num_page);
        
        //Opciones de filtros de búsqueda
            $data['options_area'] = $this->Item_model->opciones_id('categoria_id = 1', 'Área');
            $data['options_tipo'] = $this->Item_model->opciones('categoria_id = 9', 'Tipo evidencia');
            $data['options_nivel'] = $this->Item_model->opciones('categoria_id = 3', 'Todos');
            
        //Arrays con valores para contenido en la tabla
            $data['arr_areas'] = $this->Item_model->arr_item('1', 'id_nombre_corto');
            $data['arr_nivel'] = $this->Item_model->arr_interno('categoria_id = 3');
            $data['arr_tipo'] = $this->Item_model->arr_interno('categoria_id = 9');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN_NEW, $data);
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
    
    function detalle($quiz_id)
    {
        $data = $this->Quiz_model->basico($quiz_id);
        
        $data['view_a'] = "quices/detalle_v";
        $this->load->view(TPL_ADMIN_NEW, $data);
    }

// CRUD
//-----------------------------------------------------------------------------

    /**
     * Formulario de edición de los quices
     * 2023-11-23
     */
    function editar($quiz_id)
    {
        $data = $this->Quiz_model->basico($quiz_id);

        $data['options_tipo_quiz_id'] = $this->Item_model->options('categoria_id = 9', 'Todos los tipos');

        $view_a = 'quices/editar_v';
        if ( $data['row']->tipo_quiz_id == 202  ) {
            $view_a = 'quices/editar/editar_202_v';
        } else if ( $data['row']->tipo_quiz_id == 203 ) {
            $view_a = 'quices/editar/editar_203_v';
        }
        
        $data['view_a'] = $view_a;
        $this->App_model->view(TPL_ADMIN_NEW, $data);
    }
    
// GESTIÓN DE TEMAS
//---------------------------------------------------------------------------------------------------------
    
    /**
     * Listado de temas relacionados con un quiz
     * 2023-08-18
     * @param int $quiz_id
     */
    function temas($quiz_id)
    {
        $data = $this->Quiz_model->basico($quiz_id);
        
        $data['temas'] = $this->Quiz_model->temas($quiz_id);
        $data['view_a'] = "quices/temas_v";

        $this->load->view(TPL_ADMIN_NEW, $data);
    }
    
    function construir($quiz_id)
    {
        $data = $this->Quiz_model->basico($quiz_id);
        
        $data['elementos_quiz'] = $this->Quiz_model->elementos($quiz_id);
        $data['imagenes'] = $this->Quiz_model->imagenes($quiz_id);
        $data['imagen'] = $this->Quiz_model->imagen($quiz_id);
        $data['arr_elementos'] = $this->Quiz_model->arr_elementos($quiz_id);
        
        $data['ayuda_id'] = $this->Quiz_model->ayuda_id_tipo($data['row']->tipo_quiz_id);
        
        $tipo_quiz_id = $data['row']->tipo_quiz_id;
        
        $data['view_a'] = "quices/construir/construir_{$tipo_quiz_id}_v";

        //Nuevos tipos
        if ( $tipo_quiz_id > 100 && $tipo_quiz_id < 199 )
        {
            $data['view_a'] = "quices/construir_v2/{$tipo_quiz_id}/construir_v";
        } else if ( $tipo_quiz_id >= 200 )
        {
            $data['view_a'] = "quices/construir_v3/{$tipo_quiz_id}/construir_v";
        }

        $data['head_subtitle'] = 'Construir';
        
        $this->load->view(TPL_ADMIN_NEW, $data);
    }
    
    function elementos($quiz_id)
    {   
        //Cargando datos básicos
            $data = $this->Quiz_model->basico($quiz_id);
            $data['view_a'] = 'common/bs4/gc_fluid_v';
            
        //Head includes específicos para la página
            $output = $this->Quiz_model->crud_elemento($quiz_id);
            
        //Información
            $output = array_merge($data,(array)$output);
            $this->load->view(TPL_ADMIN_NEW, $output);
        
    }
    
//IMÁGENES
//---------------------------------------------------------------------------------------------------
    
    function cargar_imagen($quiz_id)
    {
        $data = $this->Quiz_model->cargar_imagen();
        
        //Cargue exitoso, se crea registro asociado
            if ( $data['status'] ) { $this->Quiz_model->guardar_imagen($data['upload_data']); }
        
        $this->session->set_flashdata('html', $data['html']);
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
        $data = $this->Quiz_model->cargar_imagen();
        
        if ( $data['status'] )
        { 
            $elemento_id = $this->input->post('elemento_id');
            $this->Quiz_model->asignar_archivo($elemento_id, $data['upload_data']); 
        }
        
        $this->session->set_flashdata('html', $data['html']);
        redirect("quices/construir/{$quiz_id}");
        
    }
    
    function cargar_img_elemento_nuevo($quiz_id)
    {
        $results = $this->Quiz_model->cargar_imagen();
        
        if ( $results['status'] ) {
            
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
        
        $this->session->set_flashdata('html', $results['html']);
        redirect("quices/construir/{$quiz_id}");
        //$this->output->enable_profiler(TRUE);
        
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

    function demo()
    {
        $data['head_title'] = 'Evidencias M2';
        $data['view_a'] = 'quices/demo/demo_v';

        $this->load->view('quices/demo/template_v', $data);
    }

// Gestión versiónes 2
//-----------------------------------------------------------------------------

    /**
     * Guardar registro en la tabla quiz_element
     */
    function save_element()
    {        
        $arr_row = $this->input->post();
        $data['arr_row'] = $arr_row;        

        $data['saved_id'] = $this->Quiz_model->save_element($arr_row);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Eliminar elemento, tabla quiz_elemento
     * 2021-05-14
     */
    function delete_element($quiz_id, $elemento_id)
    {
        $data['qty_deleted'] = $this->Quiz_model->delete_element($quiz_id, $elemento_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Listado de elementos que componen un quiz
     * 2021-05-14
     */
    function get_elements($quiz_id)
    {
        $elementos = $this->Quiz_model->elementos($quiz_id);
        $imagenes = $this->Quiz_model->imagenes($quiz_id);

        $data['elementos'] = $elementos->result();
        $data['imagenes'] = $imagenes->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function upload_image($quiz_id)
    {
        $data = $this->Quiz_model->cargar_imagen();
        
        //Cargue exitoso, se crea registro asociado
        if ( $data['status'] )
        {
            $this->Quiz_model->guardar_imagen($data['upload_data']);
            $data['imagen'] = $this->Quiz_model->imagen($quiz_id);
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}
