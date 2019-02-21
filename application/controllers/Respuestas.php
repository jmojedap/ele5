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

//CARGAR RESPUESTAS CON MS-EXCEL
//------------------------------------------------------------------------------------------
    
    /**
     * Mostrar formulario de cargue de respuestas mediante archivos de excel.
     * El resultado del formulario se envía a 'cuestionarios/responder_masivo_e'
     * 
     * @param type $grupo_id
     */
    function cargar_json()
    {
        //Iniciales
            $nombre_archivo = '23_formato_cargue_respuestas.xlsx';
            $parrafos_ayuda = array();
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo cargar respuestas?';
            $data['nota_ayuda'] = 'Se importarán respuestas de cuestionarios a la Plataforma.';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = 'respuestas/cargar_json_e';
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'respuestas';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            $data['titulo_pagina'] = 'Cuestionarios';
            $data['subtitulo_pagina'] = 'Importar respuestas';
            $data['vista_a'] = 'comunes/bs4/importar_v';
            $data['vista_menu'] = 'cuestionarios/explorar/menu_v';
        
        $this->load->view(PTL_ADMIN_2, $data);
    }
    
    function cargar_json_e()
    {
        //Cargando datos básicos (basico)
        $letra_columna = 'D';
        
        //Variables
        $no_cargados = array();
        
        $archivo = $_FILES['file']['tmp_name'];    //Se crea un archivo temporal, no se sube al servidor, se toma el nombre temporal
        $nombre_hoja = $this->input->post('nombre_hoja');   //Nombre de hoja digitada por el usuario en el formulario
        
        $this->load->model('Pcrn_excel');
        $resultado = $this->Pcrn_excel->array_hoja($archivo, $nombre_hoja, $letra_columna);
        $array_hoja = $resultado['array_hoja'];
        
        if ( $resultado['valido'] ) {
            $no_cargados = $this->Cuestionario_model->responder_masivo($array_hoja);
        }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $nombre_hoja;
            $data['no_cargados'] = $no_cargados;
        
        //Cargar vista
            $data['titulo_pagina'] = 'Cuestionarios';
            $data['subtitulo_pagina'] = 'Resultado respuestas masivas';
            $data['vista_menu'] = 'cuestionarios/menu_explorar_v';
            $data['vista_a'] = 'app/resultado_cargue_v';
            $data['subtitulo_pagina'] = 'Resultado cargue';
            $data['ayuda_id'] = 143;
            $this->load->view(PTL_ADMIN, $data);
    }
}