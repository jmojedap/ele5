<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datos extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Datos_model');

        $this->load->library('grocery_CRUD');
        
        //Para formato de horas
        date_default_timezone_set("America/Bogota");

    }
        
    function sis_opcion()
    {
        $this->load->model('Develop_model');
        //Render del grocery crud
            $gc_output = $this->Develop_model->crud_sis_opcion();
        
        //Array data espefícicas
            $data['titulo_pagina'] = 'Opciones del sistema';
            $data['vista_a'] = 'comunes/gc_v';
            $data['vista_menu'] = 'datos/parametros_menu_v';
        
        $output = array_merge($data,(array)$gc_output);
        
        $this->load->view(PTL_ADMIN, $output);
    }
    
//ENUNCIADOS
//------------------------------------------------------------------------------------------
    
    /**
     * Controla y redirecciona las búsquedas de exploración de enunciados
     * evita el problema de reenvío del formulario al presionar el botón
     * "atrás" del browser
     * 
     * @param type $controlador
     */
    function enunciados_redirect()
    {
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Busqueda_model');
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        redirect("datos/enunciados/?{$busqueda_str}");
    }

    function enunciados()
    {
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Busqueda_model');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
        
        //Paginación
            $resultados_total = $this->Datos_model->buscar_enunciados($busqueda); //Para calcular el total de resultados
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(2);
            $config['base_url'] = base_url("datos/enunciados/?{$busqueda_str}");
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Datos_model->buscar_enunciados($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['resultados'] = $resultados;
            $data['destino_form'] = "datos/enunciados_redirect/";
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Lecturas';
            $data['subtitulo_pagina'] = $config['total_rows'];
            $data['vista_a'] = 'datos/enunciados/explorar_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function enunciados_ver($enunciado_id)
    {
        $data = $this->Datos_model->enunciado_basico($enunciado_id);
        
        $data['vista_b'] = 'datos/enunciados/enunciado_ver_v';
        $this->load->view(PTL_ADMIN, $data);
        
    }
    
    function enunciados_editar()
    {
        //Cargando datos básicos
            $enunciado_id = $this->uri->segment(4);
            $data['enunciado_id'] = $enunciado_id;
            $data['row'] = $this->Pcrn->registro_id('post', $enunciado_id);
            $data['titulo_pagina'] = 'Editar enunciado';
            $data['vista_a'] = 'datos/enunciados/enunciado_v';
            
        //Render del grocery crud
            $output = $this->Datos_model->crud_enunciados();

        //Solicitar vista
            $data['subtitulo_pagina'] = 'Editar';
            $data['vista_b'] = 'comunes/gc_v';
            $output = array_merge($data,(array)$output);
            $this->load->view(PTL_ADMIN, $output);
    }
    
    function enunciados_nuevo()
    {
            
        //Render del grocery crud
            $output = $this->Datos_model->crud_enunciados();

        //Solicitar vista
            $data['titulo_pagina'] = 'Enunciados';
            $data['subtitulo_pagina'] = 'Nuevo';
            $data['vista_a'] = 'comunes/gc_v';
            $data['vista_menu'] = 'datos/enunciados/explorar_menu_v';
            $output = array_merge($data,(array)$output);
            $this->load->view(PTL_ADMIN, $output);
    }
    
    function enunciados_eliminar($enunciado_id)
    {
        $this->Datos_model->enunciado_eliminar($enunciado_id);
        
        $this->load->model('Busqueda_model');
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        
        redirect("datos/enunciados/?{$busqueda_str}");
    }
    
//AYUDA
//------------------------------------------------------------------------------------------

    /**
     * Controla y redirecciona las búsquedas de artículos de ayuda
     * evita el problema de reenvío del formulario al presionar el 
     * botón "atrás" del browser
     * 
     * @param type $funcion_controlador
     */
    function ayudas_redirect($funcion_controlador)
    {
        $this->load->model('Busqueda_model');
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        redirect("datos/{$funcion_controlador}/?{$busqueda_str}");
    }
    
    function ayudas_explorar()
    {
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Busqueda_model');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            
        //Especificaciones adicionales
            $busqueda['tp'] = 14;   //Ayuda
        
        //Paginación
            $resultados_total = $this->Busqueda_model->items($busqueda); //Para calcular el total de resultados
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(2);
            $config['base_url'] = base_url("datos/ayudas_explorar/?{$busqueda_str}");
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Busqueda_model->items($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['resultados'] = $resultados;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Artículos de ayuda';
            $data['subtitulo_pagina'] = $config['total_rows'];
            $data['vista_a'] = 'datos/ayudas/explorar_v';
            $data['vista_menu'] = 'datos/ayudas/explorar_menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function ayudas()
    {
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Busqueda_model');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            
        //Especificaciones adicionales
            $busqueda['condicion'] = "item_corto LIKE '%-{$this->session->userdata('rol_id')}-%'";
            $busqueda['tp'] = 14;   //Ayuda
        
        //Paginación
            $resultados_total = $this->Busqueda_model->items($busqueda); //Para calcular el total de resultados
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(2);
            $config['base_url'] = base_url() . "datos/ayudas/?{$busqueda_str}";
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Busqueda_model->items($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['resultados'] = $resultados;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Ayuda de la Plataforma Enlace';
            $data['vista_a'] = 'datos/ayudas/ayudas_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function ayudas_ver($ayuda_id)
    {
        $data = $this->Datos_model->ayuda_basico($ayuda_id);
        
        $data['vista_b'] = 'datos/ayudas/ayuda_ver_v';
        $this->load->view(PTL_ADMIN, $data);
        
    }
    
    function ayudas_editar()
    {
        //Cargando datos básicos
            $ayuda_id = $this->uri->segment(4);
            $data = $this->Datos_model->ayuda_basico($ayuda_id);
            
        //Render del grocery crud
            $gc_output = $this->Datos_model->crud_ayudas();

        //Solicitar vista
            $data['subtitulo_pagina'] = 'Editar';
            $data['vista_b'] = 'comunes/gc_v';
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(PTL_ADMIN, $output);
    }
    
    function ayudas_nuevo()
    {
            
        //Render del grocery crud
            $gc_output = $this->Datos_model->crud_ayudas();

        //Solicitar vista
            $data['titulo_pagina'] = 'Artículos de ayuda';
            $data['subtitulo_pagina'] = 'Nuevo';
            $data['vista_a'] = 'comunes/gc_v';
            $data['vista_menu'] = 'datos/ayudas/explorar_menu_v';
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(PTL_ADMIN, $output);
    }
    
    function ayudas_eliminar($ayuda_id)
    {
        //$this->Datos_model->ayuda_eliminar($ayuda_id);
        $this->db->where('id', $ayuda_id);
        $this->db->delete('item');
        
        $this->load->model('Busqueda_model');
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        
        redirect("datos/ayudas_explorar/?{$busqueda_str}");
    }
        
//---------------------------------------------------------------------------------------------------
//CRUD DE TABLA item
        
    function areas()
    {
        $gc_output = $this->Datos_model->crud_areas();

        //Solicitar vista
            $data['titulo_pagina'] = 'Parámetros';
            $data['subtitulo_pagina'] = 'Áreas';
            $data['vista_menu'] = 'datos/parametros_menu_v';
            $data['vista_a'] = 'comunes/gc_v';

        $output = array_merge($data,(array)$gc_output);
        $this->load->view(PTL_ADMIN, $output);
    }

    function competencias()
    {
        $gc_output = $this->Datos_model->crud_competencias();

        //Variables
            $data['titulo_pagina'] = 'Parámetros';
            $data['subtitulo_pagina'] = 'Competencias';
            $data['vista_a'] = 'comunes/gc_v';
            $data['vista_menu'] = 'datos/parametros_menu_v';

        //Solicitar vista
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(PTL_ADMIN, $output);
    }

    function componentes()
    {
        $gc_output = $this->Datos_model->crud_componentes();

        //Solicitar vista
            $data['titulo_pagina'] = 'Parámetros';
            $data['subtitulo_pagina'] = 'Componentes';
            $data['vista_menu'] = 'datos/parametros_menu_v';
            $data['vista_a'] = 'comunes/gc_v';

        $output = array_merge($data,(array)$gc_output);
        $this->load->view(PTL_ADMIN, $output);

    }
    
    function tipos_recurso()
    {
        $gc_output = $this->Datos_model->crud_tipos_recurso();

        //Solicitar vista
            $data['titulo_pagina'] = 'Parámetros';
            $data['subtitulo_pagina'] = 'Tipos recurso';
            $data['vista_menu'] = 'datos/parametros_menu_v';
            $data['vista_a'] = 'comunes/gc_v';

        $output = array_merge($data,(array)$gc_output);
        $this->load->view(PTL_ADMIN, $output);

    }
    
//REPORTES INFORMES ESPECIALES
//---------------------------------------------------------------------------------------------------
    
    function report_usuarios_01()
    {
        ini_set('memory_limit','-1');

        //Render del grocery crud
            $gc_output = $this->Datos_model->crud_report_usuarios_01();
        
        //Array data espefícicas
            $data['titulo_pagina'] = 'Reporte de usuarios';
            $data['subtitulo_pagina'] = 'Activación y pago';
            $data['vista_a'] = 'datos/report_v';
            $data['vista_b'] = 'app/gc_v';
        
        $output = array_merge($data,(array)$gc_output);
        
        $this->load->view(PTL_ADMIN, $output);
    }
    
    function reporte_general($nombre_reporte)
    {
        
        $gc_output = array();
        
        if ( $nombre_reporte == 'reporte_instituciones_01' ) {
            $gc_output = $this->Datos_model->crud_report_instituciones_01();
        } elseif ( $nombre_reporte == 'reporte_temas_01' ) {
            $gc_output = $this->Datos_model->crud_report_temas_01();
        }
        
        //Array data espefícicas
            $data['titulo_pagina'] = 'Reporte de instituciones';
            $data['subtitulo_pagina'] = 'Datos de instituciones';
            $data['vista_a'] = 'datos/report_v';
            $data['vista_b'] = 'app/gc_v';
        
        $output = array_merge($data,(array)$gc_output);
        
        $this->load->view(PTL_ADMIN, $output);
    }
    
    function report_instituciones_01()
    {
        
        $this->load->model('Institucion_model');
        
        $this->db->select('institucion.id, nombre_institucion, COUNT(evento.id) AS cant_login');
        $this->db->where('tipo_id', 101);
        $this->db->where('usuario.rol_id', 6);
        $this->db->join('usuario', 'usuario.institucion_id = institucion.id', 'LEFT');
        $this->db->join('evento', 'evento.referente_id = usuario.id', 'LEFT');
        $this->db->group_by('institucion.id, nombre_institucion');
        $this->db->order_by('COUNT(evento.id)', 'DESC');
        $query = $this->db->get('institucion');
        
        $instituciones = $query;
        
        //Variables
            $data['instituciones'] = $instituciones;
        
        //Array data generales
            $data['titulo_pagina'] = 'Reporte de Instituciones';
            $data['subtitulo_pagina'] = 'Acceso de Usuarios';
            $data['vista_a'] = 'datos/report_v';
            $data['vista_b'] = 'datos/report_instituciones_01_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function reporte_temas_02($descargar = 0)
    {
     
        if ( $descargar == 0 ) {
            //Data
                $data['temas'] = $this->Datos_model->reporte_temas_02(100);

            //Array data espefícicas
                $data['titulo_pagina'] = 'Reporte de temas';
                $data['subtitulo_pagina'] = 'Temas por programa';
                $data['vista_a'] = 'datos/report_v';
                $data['vista_b'] = 'datos/reporte_temas_02_v';

            $this->load->view(PTL_ADMIN, $data);
        } else {
            //2015-05-15 solucionar problema memory limit y tiempo de ejecución
            ini_set('memory_limit', '2048M');   
            set_time_limit(480);    //6 minutos
            
            //Cargando
                $this->load->model('Pcrn_excel');

            //Preparar datos
                $datos['nombre_hoja'] = 'Programas y temas';
                $datos['query'] = $this->Datos_model->reporte_temas_02(250000);

            //Preparar archivo
                $objWriter = $this->Pcrn_excel->archivo_query($datos);

            $data['objWriter'] = $objWriter;
            $data['nombre_archivo'] = date('Ymd_His'). '_temas.xlsx'; //save our workbook as this file name

            $this->load->view('app/descargar_phpexcel_v', $data);
        }
        
    }
    
    function reporte_programas_01()
    {
        //Render del grocery crud
            $gc_output = $this->Datos_model->crud_reporte_programas_01();
        
        //Array data espefícicas
            $data['titulo_pagina'] = 'Reporte de programas';
            $data['subtitulo_pagina'] = 'Programas y Flipbooks';
            $data['vista_a'] = 'datos/report_v';
            $data['vista_b'] = 'app/gc_v';
        
        $output = array_merge($data,(array)$gc_output);
        
        $this->load->view(PTL_ADMIN, $output);
    }
    
    function reporte_quices_01()
    {
        //Render del grocery crud
            $gc_output = $this->Datos_model->crud_reporte_quices_01();
        
        //Array data espefícicas
            $data['titulo_pagina'] = 'Reporte de quices';
            $data['subtitulo_pagina'] = 'Quices';
            $data['vista_a'] = 'datos/report_v';
            $data['vista_b'] = 'app/gc_v';
        
        $output = array_merge($data,(array)$gc_output);
        
        $this->load->view(PTL_ADMIN, $output);
    }
    
    function reporte_links_01()
    {
        //Render del grocery crud
            $gc_output = $this->Datos_model->crud_reporte_links_01();
        
        //Array data espefícicas
            $data['titulo_pagina'] = 'Reporte de Enlaces';
            $data['subtitulo_pagina'] = 'Links';
            $data['vista_a'] = 'datos/report_v';
            $data['vista_b'] = 'app/gc_v';
        
        $output = array_merge($data,(array)$gc_output);
        
        $this->load->view(PTL_ADMIN, $output);
    }
    
}

/* Fin del archivo datos.php */