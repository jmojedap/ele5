<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Endroid\QrCode\QrCode;

class Respuestas extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        $this->load->model('Cuestionario_model');
        $this->load->model('Respuesta_model');
        
        //Para formato de horas
        date_default_timezone_set("America/Bogota");
        
    }
    
// RESPUESTAS DE CUESTIONARIOS POR FORMATO
//-----------------------------------------------------------------------------

    function formatos($cuestionario_id, $grupo_id)
    {
        require 'vendor/autoload.php';

        $asignaciones = $this->Respuesta_model->asignaciones($cuestionario_id, $grupo_id);
        $data['row_cuestionario'] = $this->Pcrn->registro_id('cuestionario', $cuestionario_id);
        $data['row_grupo'] = $this->Pcrn->registro_id('grupo', $grupo_id);
        $data['nombre_institucion'] = $this->Pcrn->campo_id('institucion', $data['row_grupo']->institucion_id, 'nombre_institucion');

        $paginas = array();

        //Crear HTML de cada asignación
        foreach ( $asignaciones->result() as $row_uc )
        {
            $data['row_uc'] = $row_uc;
            $data['qr_code'] = new QrCode($row_uc->uc_id);
            $paginas[] = $this->load->view('respuestas/formato_v', $data, true);        
        }

        //Crear documento PDF
        $mpdf = new \Mpdf\Mpdf();
        foreach ( $paginas as $pagina )
        {
            $mpdf->WriteHTML($pagina);
        }

        //$mpdf->Output('Formatos Respuestas.pdf', 'D');
        $mpdf->Output();
    }

    function array_ejemplo()
    {
        $hoja['asignacion_id'] = 1991241;
        $hoja['respuestas'] = array(1,3,1,4,2,2);

        $hoja2['asignacion_id'] = 1991242;
        $hoja2['respuestas'] = array(1,3,1,4,2,3);

        $hoja3['asignacion_id'] = 1991243;
        $hoja3['respuestas'] = array(1,3,1,4,2,3);

        $cargue = array($hoja, $hoja2, $hoja3);

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($cargue));
    }
}