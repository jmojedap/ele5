<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recursos extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        $this->load->model('Recurso_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index()
    {        
        $this->explorar();
    }

//INFORMACIÓN DE RECURSOS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Controla y redirecciona las búsquedas de exploración
     * para cada elemento (explorador), evita el problema de reenvío del
     * formulario al presionar el botón "atrás" del browser
     * 
     * @param type $elemento
     */
    function explorar_redirect($elemento = 'links')
    {
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Busqueda_model');
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        redirect("recursos/{$elemento}/?{$busqueda_str}");
    }
    
    function explorar()
    {
        
    }
    
//ARCHIVOS
//---------------------------------------------------------------------------------------------------
    
    function archivos()
    {
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Busqueda_model');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Recurso_model->buscar_archivos($busqueda); //Para calcular el total de resultados
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(2);
            $config['base_url'] = base_url("recursos/archivos/?{$busqueda_str}");
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Recurso_model->buscar_archivos($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Archivos';
            $data['subtitulo_pagina'] = $resultados_total->num_rows();
            $data['vista_a'] = 'recursos/archivos_v';
            $this->load->view(PTL_ADMIN, $data);
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
            $this->Recurso_model->eliminar($elemento_id);
        }
        
        echo count($seleccionados);
    }    
    
// Importación de asignación de archivos
//-----------------------------------------------------------------------------
    
    /**
     * Mostrar formulario de importación de asignación de archivos mediante archivo MS Excel.
     * El resultado del formulario se envía a 'recursos/asignar_e'
     * 
     * @param type $programa_id
     */
    function asignar()
    {
        
        //Iniciales
            $nombre_archivo = '05_formato_asignacion_archivos.xlsx';
            $parrafos_ayuda = array();
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo asignar archivos?';
            $data['nota_ayuda'] = 'Se asignarán archivos los temas de la Plataforma.';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = 'recursos/asignar_e';
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'archivos';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            $data['titulo_pagina'] = 'Archivos';
            $data['subtitulo_pagina'] = 'Asignar archivos';
            $data['vista_a'] = 'comunes/importar_v';
            $data['vista_menu'] = 'recursos/menu_archivos_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Asignar archivos, (e) ejecutar.
     */
    function asignar_e()
    {
        
        //Proceso
            $this->load->model('Pcrn_excel');
            $no_importados = array();
            $letra_columna = 'C';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                //$this->load->model('_model');
                $no_importados = $this->Recurso_model->asignar($resultado['array_hoja']);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $no_importados;
            $data['destino_volver'] = "recursos/archivos/";
        
        //Cargar vista
            $data['titulo_pagina'] = 'Archivos';
            $data['subtitulo_pagina'] = 'Asignar';
            $data['vista_a'] = 'comunes/resultado_importacion_v';
            $data['vista_menu'] = 'recursos/menu_archivos_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function procesos_archivos()
    {
        
        $this->db->where('categoria_id', 20);   //Recursos
        $this->db->where('item_grupo', 1);      //Archivos
        $carpetas = $this->db->get('item');
        
        $data['carpetas'] = $carpetas;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Archivos';
            $data['subtitulo_pagina'] = 'Asociación automática';
            $data['vista_a'] = 'recursos/procesos_archivos_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * 
     * 
     * @param type $tipo_archivo_id
     */
    function asociar_archivos_e($tipo_archivo_id = 619)
    {   
        $cant_asociados = $this->Recurso_model->asociar_archivos($tipo_archivo_id);
        $this->session->set_flashdata('mensaje', "Se asociaron {$cant_asociados} archivos");
        redirect('recursos/procesos_archivos');
    }
    
    /**
     * Función de transición V2 a V3, cambio de nombres de archivos
     * 
     * @param type $tipo_archivo_id
     */
    function cambiar_nombres_e($tipo_archivo_id = 619)
    {
        $cant_archivos = $this->Recurso_model->cambiar_nombres($tipo_archivo_id);
        $this->session->set_flashdata('mensaje', "Se cambió el nombre de {$cant_archivos} archivos");
        redirect('recursos/procesos_archivos');
    }
    
    /**
     * Actualizar el campo, recurso.disponible
     * 
     * @param type $tipo_archivo_id
     */
    function act_archivos_disponibles($tipo_archivo_id = 619)
    {   
        $cant_no_disponibles = $this->Recurso_model->act_archivos_disponibles($tipo_archivo_id);
        $this->session->set_flashdata('mensaje', "De los archivos asignados a los temas, {$cant_no_disponibles} no se encuentran disponibles en el servidor de la Plataforma");
        redirect('recursos/procesos_archivos');
    }
    
    function archivos_no_asignados($tipo_archivo_id = 619)
    {   
        //Variables
            $data['archivos'] = $this->Recurso_model->archivos_no_asignados($tipo_archivo_id, 25);
            $data['tipo_archivo_id'] = $tipo_archivo_id;
            $data['carpeta_uploads'] = base_url() . RUTA_UPLOADS . $this->Pcrn->campo_id('item', $tipo_archivo_id, 'slug') . '/';
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Archivos';
            $data['subtitulo_pagina'] = 'Disponibles sin asignar a un tema';
            $data['vista_a'] = 'recursos/archivos_no_asignados_v';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    function ajax_cambiar_nombre()
    {
        
        $this->load->helper('string');
        
        $tipo_archivo_id = $this->input->post('tipo_archivo_id');
        $recurso_id = 0;
        $asignado = 0;
        $tema_id = 0;
        $nombre_tema = '';
        $mensaje = 'El archivo no se asignó a ningún tema';
        
        //Nombres de archivo
            $basename_actual = $this->input->post('nombre_actual') . '.' . $this->input->post('extension');
            $basename_nuevo = $this->input->post('nombre_nuevo') . '-' . random_string('numeric', 6) . '.' . $this->input->post('extension');
        
        //Carpeta y rutas
            $carpeta = $this->Recurso_model->carpeta($tipo_archivo_id);

            $ruta_actual = $carpeta . $basename_actual;
            $ruta_nuevo = $carpeta . $basename_nuevo;
        
        //Cambiar nombre
            rename($ruta_actual, $ruta_nuevo);
            
            if ( file_exists($ruta_nuevo) ) { $recurso_id = $this->Recurso_model->asociar_archivo($ruta_nuevo, $tipo_archivo_id); }
            if ( $recurso_id > 0 ) { $asignado = 1; }
            if ( $asignado ) { $tema_id = $this->Pcrn->campo_id('recurso', $recurso_id, 'tema_id'); }
            if ( $tema_id > 0 ) { $nombre_tema = $this->Pcrn->campo_id('tema', $tema_id, 'nombre_tema'); }
            if ( $asignado ) { $mensaje = "El archivo se asignó al tema: [{$nombre_tema}]"; }
            
        //Preparando respuesta
            $respuesta['basename_nuevo'] = $basename_nuevo;
            $respuesta['asignado'] = $asignado;
            $respuesta['recurso_id'] = $recurso_id;
            $respuesta['tema_id'] = $tema_id;
            $respuesta['nombre_tema'] = $nombre_tema;
            $respuesta['mensaje'] = $mensaje;
            
            
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($respuesta));
    }
    
//LINKS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Exploración de recursos tipo link externo
     */    
    function links()
    {
        $this->load->model('Busqueda_model');
        $this->load->helper('text');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Recurso_model->links($busqueda); //Para calcular el total de resultados
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(2);
            $config['base_url'] = base_url("recursos/links/?{$busqueda_str}");
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Recurso_model->links($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Links';
            $data['subtitulo_pagina'] = number_format($data['cant_resultados'],0,',', '.');
            $data['vista_a'] = 'recursos/links/explorar_v';
            $data['vista_menu'] = 'recursos/links/explorar_menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Exporta el resultado de la búsqueda a un archivo de Excel
     */
    function exportar_links()
    {
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso
        //Cargando
            $this->load->model('Busqueda_model');
            $this->load->model('Pcrn_excel');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Recurso_model->links($busqueda); //Para calcular el total de resultados
        
            if ( $resultados_total->num_rows() <= MAX_REG_EXPORT )
            {
                //Preparar datos
                    $datos['nombre_hoja'] = 'Links';
                    $datos['query'] = $resultados_total;

                //Preparar archivo
                    $objWriter = $this->Pcrn_excel->archivo_query($datos);

                $data['objWriter'] = $objWriter;
                $data['nombre_archivo'] = date('Ymd_His'). '_links'; //save our workbook as this file name

                $this->load->view('app/descargar_phpexcel_v', $data);
            } else {
                $data['titulo_pagina'] = 'Plataforma Enlace';
                $data['mensaje'] = "El número de registros es de {$resultados_total->num_rows()}. El máximo permitido es de " . MAX_REG_EXPORT . " registros. Puede filtrar los datos por algún criterio para poder exportarlos.";
                $data['link_volver'] = "recursos/links/?{$busqueda_str}";
                $data['vista_a'] = 'app/mensaje_v';
                
                $this->load->view(PTL_ADMIN, $data);
            }
    }
    
    /**
     * Mostrar formulario de importación de links mediante archivo MS Excel.
     * El resultado del formulario se envía a 'recursos/importar_links_e'
     * 
     * @param type $programa_id
     */
    function importar_links()
    {
        
        //Iniciales
            $nombre_archivo = '06_formato_cargue_links.xlsx';
            $parrafos_ayuda = array();
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo importar links?';
            $data['nota_ayuda'] = 'Se importarán recursos tipo link a la Plataforma.';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = 'recursos/importar_links_e';
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'links';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            $data['titulo_pagina'] = 'Links';
            $data['subtitulo_pagina'] = 'Importar links';
            $data['vista_a'] = 'comunes/importar_v';
            $data['vista_menu'] = 'recursos/links/explorar_menu_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Importar links, (e) ejecutar.
     */
    function importar_links_e()
    {
        
        //Proceso
            $this->load->model('Pcrn_excel');
            $no_importados = array();
            $letra_columna = 'C';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $this->load->model('Tema_model');
                $no_importados = $this->Recurso_model->importar_links($resultado['array_hoja']);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $no_importados;
            $data['destino_volver'] = "programas/explorar/";
        
        //Cargar vista
            $data['titulo_pagina'] = 'Links';
            $data['subtitulo_pagina'] = 'Resultado importación';
            $data['vista_a'] = 'comunes/resultado_importacion_v';
            $data['vista_menu'] = 'recursos/links/explorar_menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }
}