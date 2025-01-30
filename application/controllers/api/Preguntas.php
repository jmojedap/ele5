<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Preguntas extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        $this->load->model('Pregunta_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($pregunta_id = NULL)
    {
        
        if ( is_null($pregunta_id) ) 
        {
            redirect("preguntas/explorar/");
        } else {
            redirect("preguntas/detalle/{$pregunta_id}");
        }
        
    }

//EXLPORACIÓN
//------------------------------------------------------------------------------------------

    /**
     * Listado de Pregunas, filtrados por búsqueda, JSON
     */
    function get($num_page = 1)
    {
        $data = $this->Pregunta_model->get($num_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX
     * Eliminar un grupo de instituciones seleccionados
     */
    function delete_selected()
    {
        $this->load->model('Tema_model');
        
        $str_seleccionados = $this->input->post('seleccionados');
        
        $seleccionados = explode('-', $str_seleccionados);
        
        foreach ( $seleccionados as $elemento_id ) 
        {
            $this->Pregunta_model->eliminar($elemento_id);
        }
        
        echo count($seleccionados);
    }

// EDICIÓN
//-----------------------------------------------------------------------------

    /**
     * Guardar datos de una pregunta
     */
    function save($pregunta_id)
    {
        $data = $this->Pregunta_model->save($pregunta_id);

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Recibe el archivo en formulario de preguntas/editar
     * y se lo asigna como imagen asociada a la $pregunta_id
     * 2019-10-04
     */
    function set_image($pregunta_id)
    {
        $data = $this->Pregunta_model->set_image($pregunta_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Elimina archivo imagen asociado a una pregunta, y modifica
     * el campo pregunta.archivo_imagen
     */
    function delete_archivo_imagen($pregunta_id)
    {
        $data = $this->Pregunta_model->delete_archivo_imagen($pregunta_id);

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function upload_image()
    {
        $data = $this->Pregunta_model->upload_image();
        $data['src'] = URL_UPLOADS . 'preguntas/' . $data['upload_data']['file_name'];

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// GESTIÓN DE VERSIONES DE PREGUNTAS
//-----------------------------------------------------------------------------

    

    /**
     * Crea una copia de la pregunta, en la tabla pregunta, con el tipo_id = 5
     * 2019-10-07
     */
    function create_version($pregunta_id)
    {
        $data = $this->Pregunta_model->create_version($pregunta_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Guardar datos de una pregunta versión alterna de otra
     * 2019-10-08
     */
    function save_version($version_id)
    {
        $data = $this->Pregunta_model->save($version_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Incorpora los cambios de la versión propuesta de la pregunta, a la pregunta
     * original.
     * 2019-10-09
     */
    function approve_version($pregunta_id, $version_id)
    {
        $data = $this->Pregunta_model->approve_version($pregunta_id, $version_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Eliminar o descartar la versión propuesta de una pregunta
     * 2019-10-21
     */
    function delete_version($pregunta_id, $version_id)
    {
        $data = $this->Pregunta_model->delete_version($pregunta_id, $version_id);
        
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// CÁLCULO DE TOTALES Y PARÁMETROS DE PREGUNTAS
//-----------------------------------------------------------------------------

    function update_totals()
    {
        $data = array('status' => 0, 'affected_rows' => '0', 'message' => 'Mensaje no ejecutado');

        $data['affected_rows'] = $this->Pregunta_model->update_totals();
        if ( $data['affected_rows'] >= 0 )
        {
            $data['status'] = 1;
            $data['message'] = "Preguntas actualizadas: " . $data['affected_rows'];
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Actualiza el campo pregunta.palabras_clave de forma automática
     */
    function update_palabras_clave_auto()
    {
        $data = $this->Pregunta_model->update_palabras_clave_auto();
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// SELECTORP (Seleccionador de preguntas)
//-----------------------------------------------------------------------------

    /**
     * Agregar pregunta o grupo de preguntas al listado en variables de sesión para
     * construir un nuevo cuestionario.
     * 2020-03-16
     */
    function selectorp_add($pregunta_id = 0)
    {
        $arr_selectorp_pre = $this->session->userdata('arr_selectorp');
        
        if ( $pregunta_id > 0 ) {
            $selected = array($pregunta_id);    //Pregunta individual
        } else {
            $selected = explode(',', $this->input->post('selected'));   //Preguntas seleccionadas en listado explorar
        }
        
        foreach ( $selected as $pregunta_id ) 
        {
            $arr_selectorp_pre[] = $pregunta_id;
        }

        $arr_selectorp = array_unique($arr_selectorp_pre);   //Solo elementos únicos
        $this->session->set_userdata('arr_selectorp', $arr_selectorp);      //Cargar en variable de sesión 

        $data['qty_selectorp'] = count($arr_selectorp);
        $data['qty_added'] = count($arr_selectorp) - count($arr_selectorp_pre);

        //Establecer resultado
        $data['status'] = 0;
        if ( $data['qty_added'] >= 0 ) { $data['status'] = 1; }
        $data['arr_selectorp'] = $arr_selectorp;
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Quita una pregunta del listado en las variables de sesión. Devuelve listado de preguntas
     * y calcula el nuevo nivel de dificultad.
     * 2020-03-17
     */
    function selectorp_remove($pregunta_id)
    {
        $arr_selectorp_pre = $this->session->userdata('arr_selectorp');

        $arr_selectorp = array_diff($arr_selectorp_pre, array($pregunta_id));

        $this->session->set_userdata('arr_selectorp', $arr_selectorp);

        $data = array('status' => 0);
        if ( count($arr_selectorp_pre) - count($arr_selectorp) )
        {
            $preguntas = $this->Pregunta_model->selectorp_preguntas();

            $data['preguntas'] = $preguntas->result();
            $data['avg_difficulty'] = $this->Pregunta_model->selectorp_avg_difficulty($preguntas);
            $data['status'] = 1;
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}