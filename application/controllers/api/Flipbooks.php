<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Flipbooks extends CI_Controller{
    
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Flipbook_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    
//---------------------------------------------------------------------------------------------------
//

    /**
     * String JSON para construir el flipbook para leer, vista completa para 
     * estudiantes y profesores. 1) Verifica si el archivo JSON del flipbook
     * existe, si no existe se crea.
     *
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
            
        $this->output->set_content_type('application/json')->set_output($data_str);
    }

// CRUD
//-----------------------------------------------------------------------------

    /**
     * Guardar datos registro en la tabla flipbook
     * 2024-02-22
     */
    function save()
    {
        $data = $this->Flipbook_model->save();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// Artículos de temas
//-----------------------------------------------------------------------------

    function get_articulo($articulo_id)
    {
        $data['articulo'] = $this->Flipbook_model->articulo($articulo_id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// Guardar anotación
//-----------------------------------------------------------------------------

    /**
     * AJAX
     * Crea un registro de anotación en la tabla 'pagina_flipbook_detalle'
     * El tipo detalle 'Anotación' corresponde al tipo_detalle_id = 3
     * 2023-09-20
     */
    function save_anotacion()
    {
        $aRow = $this->input->post();
        $aRow['tipo_detalle_id'] = 3;
        $aRow['usuario_id'] = $this->session->userdata('user_id');
        $aRow['editado'] = date('Y-m-d H:i:s');

        $condition = "pagina_id = {$aRow['pagina_id']} AND tabla_contenido = {$aRow['tabla_contenido']} AND
            tipo_detalle_id = 3 AND usuario_id = {$aRow['usuario_id']}";

        $data['saved_id'] = $this->Db_model->save('pagina_flipbook_detalle', $condition, $aRow);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * JSON
     * Devuelve las anotaciones del usuario en sesión relizadas en un flipbook
     * específico.
     * 
     * @param int $flipbook_id
     */
    function get_anotaciones($flipbook_id)
    {
        $data['anotaciones'] = $this->Flipbook_model->anotaciones_estudiante_tema($flipbook_id)->result();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));   
    }

}