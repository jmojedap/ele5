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

    function hoja_respuestas($uc_id)
    {
        $data = $this->Respuesta_model->basico($uc_id);

        $data['titulo_pagina'] = $data['row_uc']->id;
        $data['vista_a'] = 'respuestas/hoja_respuestas_v';

        $this->load->view('templates/bs4_print/main_v', $data);
    }

    function formato($uc_id)
    {
        $data = $this->Respuesta_model->basico($uc_id);

        $data['titulo_pagina'] = $data['row_uc']->id;
        $data['vista_a'] = 'respuestas/hoja_respuestas_v';

        //$html = $this->load->view('templates/bs4_print/head_v', $data, true);
        $html = $this->load->view('respuestas/hoja_respuestas_v', $data, true);
        
        require 'vendor/autoload.php';

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function qr_code($uc_id)
    {
        require 'vendor/autoload.php';

        $qrCode = new QrCode($uc_id);

        $data['qrCode'] = $qrCode;

        $this->load->view('respuestas/qr_code_v', $data);
    }
}