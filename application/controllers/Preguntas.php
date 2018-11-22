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
    
//CRUD
//------------------------------------------------------------------------------------------

    function explorar()
    {
        //Cargando
            $this->load->model('Busqueda_model');
            $this->load->helper('text');
        
        //Grupos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            
            $resultados_total = $this->Pregunta_model->buscar($busqueda); //Para calcular el total de resultados
            
        //Generar resultados para mostrar
            $data['per_page'] = 15; //Cantidad de registros por página
            $data['offset'] = $this->input->get('per_page');
            $resultados = $this->Pregunta_model->buscar($busqueda, $data['per_page'], $data['offset']);
        
        //Variables para vista
            $data['cant_resultados'] = $resultados_total->num_rows();
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
            $data['url_paginacion'] = base_url("preguntas/explorar/?{$busqueda_str}");
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Preguntas';
            $data['subtitulo_pagina'] = $data['cant_resultados'];
            $data['vista_a'] = 'preguntas/explorar/explorar_v';
            $data['vista_menu'] = 'preguntas/explorar/menu_v';
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
            $resultados_total = $this->Pregunta_model->buscar($busqueda); //Para calcular el total de resultados
            $max_reg_export = 10000;
        
            if ( $resultados_total->num_rows() <= $max_reg_export ) 
            {
                //Preparar datos
                    $datos['nombre_hoja'] = 'Preguntas';
                    $datos['query'] = $resultados_total;

                //Preparar archivo
                    $objWriter = $this->Pcrn_excel->archivo_query($datos);

                $data['objWriter'] = $objWriter;
                $data['nombre_archivo'] = date('Ymd_His'). '_preguntas'; //save our workbook as this file name

                $this->load->view('app/descargar_phpexcel_v', $data);
            } else {
                $data['titulo_pagina'] = 'Plataforma Enlace';
                $data['mensaje'] = "El número de registros es de {$resultados_total->num_rows()}. El máximo permitido es de " . $max_reg_export . " registros. Puede filtrar los datos por algún criterio para poder exportarlos.";
                $data['link_volver'] = "preguntas/explorar/?{$busqueda_str}";
                $data['vista_a'] = 'app/mensaje_v';
                
                $this->load->view(PTL_ADMIN, $data);
            }
            
    }
    
    /**
     * AJAX
     * Eliminar un grupo de instituciones seleccionados
     */
    function eliminar_seleccionados()
    {
        $this->load->model('Tema_model');
        
        $str_seleccionados = $this->input->post('seleccionados');
        
        $seleccionados = explode('-', $str_seleccionados);
        
        foreach ( $seleccionados as $elemento_id ) {
            $this->Pregunta_model->eliminar($elemento_id);
        }
        
        echo count($seleccionados);
    }
    
    function editar()
    {
        //Cargando datos básicos
            $pregunta_id = $this->uri->segment(4);
            $data['row'] = $this->Pcrn->registro_id('pregunta', $pregunta_id);
            $data['editable'] = $this->Pregunta_model->editable($data['row']);
            $data['vista_a'] = 'preguntas/pregunta_v';
            
        //Render del grocery crud
            if ( in_array($this->session->userdata('rol_id'), array(3,4,5)) ) 
            {
                $output = $this->Pregunta_model->crud_editar_institucional();
            } else {
                $output = $this->Pregunta_model->crud_editar();
            }
            
        //Vista según permisos
            $vista_b = 'comunes/gc_v';
            if ( ! $data['editable'] ) { $vista_b = 'app/no_permitido_v'; }
            
        //Solicitar vista
            $data['titulo_pagina'] = 'Editar pregunta';
            $data['vista_b'] = $vista_b;
            $output = array_merge($data,(array)$output);
            $this->load->view(PTL_ADMIN, $output);
    }
    
    function eliminar($pregunta_id)
    {
        $this->Datos_model->pregunta_eliminar($pregunta_id);
        
        $this->load->model('Busqueda_model');
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        
        redirect("datos/preguntas/?{$busqueda_str}");
    }
    
// IMPORTAR
//-----------------------------------------------------------------------------
    
    /**
     * Mostrar formulario de importación de preguntas mediante archivo MS Excel.
     * El resultado del formulario se envía a 'preguntas/importar_e'
     * 
     */
    function importar()
    {
        
        //Iniciales
            $nombre_archivo = '16_formato_cargue_preguntas.xlsx';
            $parrafos_ayuda = array();
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo importar preguntas?';
            $data['nota_ayuda'] = 'Se importarán preguntas a la Plataforma.';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = 'preguntas/importar_e';
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'preguntas';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            $data['titulo_pagina'] = 'Preguntas';
            $data['subtitulo_pagina'] = 'Importar preguntas';
            $data['vista_a'] = 'comunes/importar_v';
            $data['vista_menu'] = 'preguntas/explorar_menu_v';
        
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
            $letra_columna = 'K';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $this->load->model('Pregunta_model');
                $no_importados = $this->Pregunta_model->importar($resultado['array_hoja']);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $no_importados;
            $data['destino_volver'] = "preguntas/explorar/";
        
        //Cargar vista
            $data['titulo_pagina'] = 'Preguntas';
            $data['subtitulo_pagina'] = 'Resultado cargue';
            $data['vista_a'] = 'comunes/resultado_importacion_v';
            $data['vista_menu'] = 'preguntas/explorar_menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }

//CARGUE MASIVO
//---------------------------------------------------------------------------------------------------
    
    function cargar_masivo()
    {
        //Solicitar vista
            $data['titulo_pagina'] = 'Preguntas';
            $data['subtitulo_pagina'] = 'Cargar masivamente';
            $data['ayuda_id'] = 263;
            $data['vista_a'] = 'preguntas/cargar_masivo_v';
            $this->load->view(PTL_ADMIN, $data);   
    }
    
    function cargar_masivo_e()
    {
        
        //Variables
            $filtro = '';
        
        $archivo = $_FILES['file']['tmp_name'];             //Se crea un archivo temporal, no se sube al servidor, se toma el nombre temporal
        $nombre_hoja = $this->input->post('nombre_hoja');   //Nombre de hoja digitada en el formulario
        
        $this->load->model('Pcrn_excel');
        $cargue_excel = $this->Pcrn_excel->array_hoja($archivo, $nombre_hoja, 'K');    //Hasta la columna de la hoja de cálculo
        $mensaje = $cargue_excel['mensaje'];
        $array_excel = $cargue_excel['array_hoja'];
        
        if ( $cargue_excel['cargado'] ) {
            
            $res_proceso = $this->Pregunta_model->cargar($array_excel);
            
            $filtro = "?e={$res_proceso['e']}";
            $cant_no_cargados = count($array_excel) - $res_proceso['cant_cargados'];
            
            //Preparar mensaje
            $mensaje = '<i class="fa fa-check"></i>' .  " Se cargaron {$res_proceso['cant_cargados']} registros. <br/>";
            if ( $cant_no_cargados > 0 ) { $mensaje .=  '<i class="fa fa-times"></i>' . " No se cargaron {$cant_no_cargados} registros."; }
            
            echo $mensaje;
            
        }
        
        //Cargue de variables para flashdata
            $res_proceso['mensaje'] = $mensaje;
            $res_proceso['cargado'] = $cargue_excel['cargado'];
        
        //Cargar flashdata
          $this->session->set_flashdata('res_proceso', $res_proceso);
        
        $destino = "preguntas/explorar/{$filtro}";
        
        
        redirect($destino);
    }
    
//PERFIL DE PREGUNTA
//---------------------------------------------------------------------------------------------------

    function detalle($pregunta_id)
    {
        $data = $this->Pregunta_model->basico($pregunta_id);
        $row = $data['row'];
        
        //Enunciado
            $row_enunciado = NULL;
            if ( ! is_null($row->post_id) ) {
                $row_enunciado = $this->Pcrn->registro_id('post', $row->post_id);
            }
            
        
        //Solicitar vista
            $data['row_pregunta'] = $row;
            $data['row_enunciado'] = $row_enunciado;
            $data['vista_b'] = 'preguntas/detalle_v';
            
            
        
        $this->load->view(PTL_ADMIN, $data);
        
    }
    
    /**
     * Cuestionarios en los que aparece la pregunta
     * 
     * @param type $pregunta_id
     */
    function cuestionarios($pregunta_id)
    {
        $data = $this->Pregunta_model->basico($pregunta_id);
            
        //Array $data
            $data['cuestionarios'] = $this->Pregunta_model->cuestionarios($pregunta_id);
        
        //Solicitar vista
        $data['row_pregunta'] = $data['row'];
        $data['vista_b'] = 'preguntas/cuestionarios_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Estadísticas de la pregunta
     * 
     * @param type $pregunta_id
     */
    function estadisticas($pregunta_id)
    {
        
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Cuestionario_model');
        $data = $this->Pregunta_model->basico($pregunta_id);
        
            $head_includes[] = 'highcharts';
            $data['head_includes'] = $head_includes;
            
        //Array $data
            $filtros['pregunta_id'] = $pregunta_id;
            $data['resultado'] = $this->Cuestionario_model->up_resultado($filtros);
        
        //Solicitar vista
        $data['row_pregunta'] = $data['row'];
        $data['vista_b'] = 'preguntas/estadisticas_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    
    /**
     * Cargar una pregunta a un cuestionario o a un tema
     * 
     * @param type $referente_id
     * @param type $orden
     */
    function cargar($referente_id, $orden, $cargar_en = 'cuestionario')
    {
     
        $tipo_mostrar = '';
        
        if ( $cargar_en == 'cuestionario' ){
            $this->load->model('Cuestionario_model');
            $data = $this->Cuestionario_model->basico($referente_id);
            $tipo_mostrar = 'cuestionario';
            $referente_nombre = $data['row']->nombre_cuestionario;
        } elseif ( $cargar_en == 'tema' ) {
            $this->load->model('Tema_model');
            $data = $this->Tema_model->basico($referente_id);
            $tipo_mostrar = 'tema';
            $referente_nombre = $data['row']->nombre_tema;
        }
        
        //Cargando datos básicos (basico)
            
            $incluir_duplicados = 0;
            $busqueda = array();

        //Busquedas
            if ( $this->input->post() ){
                //Se ha hecho una consulta, por post
                $q = $this->input->post('q');
                $busqueda['q'] = $q;   
                
                $incluir_duplicados = $this->input->post('incluir_duplicados');
                $busqueda['incluir_duplicados'] =  $incluir_duplicados;
                
            }

            if ( count($busqueda) > 0 ){
                $preguntas = $this->App_model->buscar_preguntas($busqueda);
            } else {
                $preguntas = NULL;
            }
            
        //Variables
            $data['referente_id'] = $referente_id;
            $data['orden'] = $orden;
            $data['cargar_en'] = $cargar_en;
            $data['preguntas'] = $preguntas;
            $data['incluir_duplicados'] = $incluir_duplicados;
        
        //Solicitar vista
            $data['titulo_pregunta'] = "Cargar pregunta al {$tipo_mostrar}: {$referente_nombre}";
            $data['vista_b'] = 'preguntas/cargar_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Inserta una pregunta existente en cuestionario_pregunta si se carga en un cuestionario
     * Modifica el campo pregunta.tema_id, si se carga en un tema
     * 2013-09-26
     */
    function insertar($referente_id, $pregunta_id, $orden, $cargar_en = 'cuestionario')
    {
        
        if ( $cargar_en == 'cuestionario' ){
            //Cargar en cuestionario
            $this->load->model('Cuestionario_model');
            
            $registro['cuestionario_id'] = $referente_id;
            $registro['pregunta_id'] = $pregunta_id;
            $registro['orden'] = $orden;
            $this->Cuestionario_model->insertar_cp($registro);
            
            $data['url'] = base_url() . "cuestionarios/preguntas/{$referente_id}";
        } elseif ( $cargar_en == 'tema' ) {
            //Asignar pregunta al tema
            
            $registro['tema_id'] = $referente_id;
            $registro['orden'] = $orden;
            $this->Pregunta_model->asignar_tema($pregunta_id, $registro);
            
            $data['url'] = base_url() . "temas/preguntas/{$referente_id}";
        }

        //Regresar a la pregunta
        $this->load->view('app/redirect_v', $data);
        
    }
    
    /**
     * Problema Grocery Crud, no permite eliminar imágenes cuyo nombre tiene paréntesis
     * 2014-11-28
     */
    function corregir_parentesis()
    {
        $this->db->where('archivo_imagen LIKE "%(%"');
        $this->db->or_where('archivo_imagen LIKE "%)%"');
        $preguntas = $this->db->get('pregunta');
        
        $carpeta = RUTA_UPLOADS . 'preguntas/';
        
        echo $preguntas->num_rows();
        
        foreach ( $preguntas->result() as $row_pregunta )
        {
            $nuevo_nombre = str_replace('(', '', $row_pregunta->archivo_imagen);
            $nuevo_nombre = str_replace(')', '', $nuevo_nombre);
            $registro['archivo_imagen'] = $nuevo_nombre;
            
            //Cambiar nombre de archivo
            if (file_exists($carpeta . $row_pregunta->archivo_imagen ) ){
                rename($carpeta . $row_pregunta->archivo_imagen, $carpeta .  $nuevo_nombre);
            }
            
            
            //Guardar
            $this->db->where('id', $row_pregunta->id);
            $this->db->update('pregunta', $registro);
            
            echo $nuevo_nombre;
            echo '<br/>';
        }
    }
    
    function clonar($pregunta_id)
    {
        $nueva_pregunta_id = $this->Pregunta_model->clonar($pregunta_id, 555);
        redirect("preguntas/detalle/{$nueva_pregunta_id}");
        
    }
    
}