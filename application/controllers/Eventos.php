<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eventos extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        $this->load->model('Evento_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($evento_id)
    {   
        $this->explorar($evento_id);
    }

//INFORMACIÓN DE EVENTOS
//---------------------------------------------------------------------------------------------------
    
    function explorar()
    {
        $this->load->model('Busqueda_model');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Busqueda_model->eventos($busqueda); //Para calcular el total de resultados
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(3);
            $config['base_url'] = base_url() . "eventos/explorar/?{$busqueda_str}";
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Busqueda_model->eventos($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Eventos';
            $data['subtitulo_pagina'] = $config['total_rows'] . ' encontrados';
            $data['vista_a'] = 'eventos/explorar_v';
            $this->load->view('plantilla_apanel/plantilla', $data);
        
    }
    
    /**
     * AJAX
     * Eliminar un grupo de registros seleccionados
     * 2019-08-01
     */
    function eliminar_seleccionados()
    {
        $data = array('status' => 0, 'message' => 'No se eliminaron registros');

        $str_seleccionados = $this->input->post('seleccionados');
        $seleccionados = explode('-', $str_seleccionados);
        $cant_eliminados = 0;
        
        foreach ( $seleccionados as $elemento_id )
        {
            $cant_eliminados += $this->Evento_model->eliminar($elemento_id);
        }

        if ( $cant_eliminados > 0) {
            $data = array('status' => 1, 'message' => 'Registros eliminados: ' . $cant_eliminados);
        }

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
    
    function nuevo()
    {
        
        //Render del grocery crud
            $output = $this->Evento_model->crud_basico();
        
        //Head includes específicos para la página
            $head_includes[] = 'grocery_crud';
            $data['head_includes'] = $head_includes;
        
        //Array data espefícicas
            $data['titulo_pagina'] = 'Eventos';
            $data['subtitulo_pagina'] = 'Nuevo';
            $data['submenu'] = 'eventos/explorar_menu_v';
            $data['vista_a'] = 'app/nuevo_v';
        
        $output = array_merge($data,(array)$output);
        
        $this->load->view('plantilla_apanel/plantilla', $output);
    }
    
    function editar()
    {
        //Cargando datos básicos
            $tema_id = $this->uri->segment(4);
            $data = $this->Evento_model->basico($tema_id);
            
        //Render del grocery crud
            $output = $this->Evento_model->crud_editar();
        
        //Head includes específicos para la página
            $head_includes[] = 'grocery_crud';
            $data['head_includes'] = $head_includes;
            
        //Solicitar vista
            $data['vista_b'] = 'app/gc_v';
            $output = array_merge($data,(array)$output);
            $this->load->view('plantilla_apanel/plantilla', $output);
    }
    
//---------------------------------------------------------------------------------------------------
//PROGRAMADOR
    
    function calendario()
    {
        if ($this->input->get('profiler')){ $this->output->enable_profiler(TRUE); }
        
        $this->load->model('Usuario_model');
        $this->load->model('Busqueda_model');
        
        $busqueda = $this->Busqueda_model->busqueda_array();
        $tipos_evento = '0';
        
        if ( $this->session->userdata('srol') =='estudiante' ) 
        {
            //Estudiante
            $tipos_evento = '1,2,3';
            $eventos[1] = $this->Evento_model->evs_cuestionarios_ant($busqueda);    //Asignación de cuestionarios
            $eventos[2] = $this->Evento_model->evs_temas($busqueda);                //Programación de temas
            $eventos[3] = $this->Evento_model->evs_quices($busqueda);               //Programación de quices
            $eventos[4] = $this->Evento_model->evs_links($busqueda);                //Programación de links
            //$eventos[22] = $this->Evento_model->evs_cuestionarios($busqueda);       //Asignación de cuestionarios
            $view_a = 'eventos/calendario/calendario_v';
        } else {
            //Los demás usuarios
            $tipos_evento = '2,4,22';
            //$eventos[1] = $this->Evento_model->evs_cuestionarios_prf($busqueda);     //Asignación de cuestionarios
            $eventos[2] = $this->Evento_model->evs_temas($busqueda);                 //Programación de temas
            $eventos[4] = $this->Evento_model->evs_links($busqueda);                 //Programación de links
            $eventos[22] = $this->Evento_model->evs_cuestionarios_prf($busqueda);     //Asignación de cuestionarios
            $view_a = 'eventos/calendario/calendario_prf_v';
        }
        
        $data['eventos'] = $eventos;
        $data['areas'] = $this->App_model->areas('item_grupo = 1');
        $data['tipos'] = $this->db->get_where('item', 'categoria_id = 13 AND id_interno IN (' . $tipos_evento . ')');
        $data['grupos'] = $this->Usuario_model->grupos_usuario($this->session->userdata('usuario_id'));
        $data['busqueda'] = $busqueda;
        $data['destino_filtros'] = "eventos/calendario/";
        
        if ( $this->input->get('profiler') == 1 )
        {
            $this->output->enable_profiler(TRUE);
        }
        
        /*$data['head_title'] = 'Programador';
        $data['view_a'] = $view_a;
        $data['nav_2'] = 'usuarios/biblioteca_menu_v';*/

        $data['titulo_pagina'] = 'Programador';
        $data['vista_a'] = $view_a;
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function imprimir_calendario($mes = NULL)
    {
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Usuario_model');
        $this->load->model('Busqueda_model');
        
        $busqueda = $this->Busqueda_model->busqueda_array();
        $tipos_evento = '0';
        
        if ( $this->session->userdata('srol') =='estudiante' ) {
            //Estudiante
            $tipos_evento = '1,2,3';
            $eventos[1] = $this->Evento_model->evs_cuestionarios($busqueda);    //Asignación de cuestionarios
            $eventos[2] = $this->Evento_model->evs_temas($busqueda);            //Programación de temas
            $eventos[3] = $this->Evento_model->evs_quices($busqueda);           //Programación de quices
            $eventos[4] = $this->Evento_model->evs_links($busqueda);            //Programación de links
            $vista_a = 'eventos/calendario/calendario_v';
        } else {
            //Los demás usuarios
            $tipos_evento = '1,2,4';
            $eventos[1] = $this->Evento_model->evs_cuestionarios_prf($busqueda);     //Asignación de cuestionarios
            $eventos[2] = $this->Evento_model->evs_temas($busqueda);                 //Programación de temas
            $eventos[4] = $this->Evento_model->evs_links($busqueda);                 //Programación de links
            $vista_a = 'eventos/calendario/imprimir_calendario_prf_v';
        }
        
        //Establecer mes
            if ( is_null($mes) ) { $mes = date('Y-m'); }
        
        $data['eventos'] = $eventos;
        $data['areas'] = $this->App_model->areas('item_grupo = 1');
        $data['tipos'] = $this->db->get_where('item', 'categoria_id = 13 AND id_interno IN (' . $tipos_evento . ')');
        $data['grupos'] = $this->Usuario_model->grupos_usuario($this->session->userdata('usuario_id'));
        $data['busqueda'] = $busqueda;
        $data['destino_filtros'] = "eventos/calendario/";
        $data['mes'] = $mes;
        
        
        $data['head_title'] = 'Programación';
        $data['view_a'] = $vista_a;
        $this->load->view('templates/print/main_v', $data);
    }
    
    
    
//MURO DE NOTICIAS
//---------------------------------------------------------------------------------------------------

    /**
     * Vista muro de noticias para el usuario
     */
    function noticias()
    {
        $this->load->model('Usuario_model');
        $this->load->model('Busqueda_model');
    
        $busqueda = $this->Busqueda_model->busqueda_array();
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        
        //Filtros de eventos
            $filtros['interno'] = 'g1';
            $filtros['institucional'] = 'g2';
            $filtros['estudiante'] = 'g1';
            $srol = $this->session->userdata('srol');
        
            $condicion_eventos = 'categoria_id = 13 AND filtro LIKE "%-' . $filtros[$srol] . '-%"';
            
        //Cantidad de noticias para mostrar
            $limit = 20;
        
        //Variables
            $data['limit'] = $limit;
            $data['noticias'] = $this->Evento_model->noticias($busqueda, $limit);
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['config_form'] = $this->Evento_model->config_form_publicacion();
            $data['areas'] = $this->App_model->areas('item_grupo = 1');
            $data['tipos'] = $this->db->get_where('item', $condicion_eventos);
            $data['grupos'] = $this->Usuario_model->grupos_usuario($this->session->userdata('usuario_id'));
            $data['destino_form'] = 'eventos/crear_publicacion';
            $data['destino_filtros'] = "eventos/noticias/";
            $data['url_mas'] = base_url('eventos/mas_noticias/');
        
        //Variables vista
        $data['titulo_pagina'] = 'Noticias';
        $data['vista_a'] = 'eventos/noticias/noticias_v';
        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Recibe los datos del formulario de eventos/noticias
     * Crea registro en la tabla post y lo referencia en la tabla evento
     */
    function crear_publicacion()
    {
        //Cargue
            $this->load->model('Post_model');
        
        //Crear publicación en tabla post
            $reg_post['tipo_id'] = 3;   //Publicación, ver item categoria_id = 33, tipos de post
            $reg_post['contenido'] = strip_tags($this->input->post('contenido'));
            $reg_post['texto_1'] = strip_tags($this->input->post('texto_1'));

            $post_id = $this->Post_model->guardar_post('id = 0', $reg_post);    //Condición imposible, se crea nuevo
            
        //Registrar publicación creada en la tabla evento
            $this->Evento_model->guardar_ev_publicacion($post_id);
            
        redirect('eventos/noticias');
    }
    
    /**
     * AJAX, elimina un evento
     * @param type $evento_id
     */
    function eliminar($evento_id)
    {
        $cant_registros = $this->Evento_model->eliminar($evento_id);
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($cant_registros));
    }
    
    /**
     * AJAX, envía un objeto JSON con el html de noticias adicionales para mostrarse
     * al final del muro de noticias cuando el usuario hace clic en el botón [Más]
     * 
     * @param type $limit
     * @param type $offset
     */
    function mas_noticias($limit, $offset)
    {
        
        $this->load->model('Usuario_model');
        $this->load->model('Busqueda_model');
    
        $busqueda = $this->Busqueda_model->busqueda_array();
        
        $noticias = $this->Evento_model->noticias($busqueda, $limit, $offset);
        //$cant_noticias = $noticias->num_rows();
        
        $data['noticias'] = $noticias;
        
        $html = $this->load->view('eventos/noticias/listado_noticias_p_v', $data, TRUE);
        
        $respuesta['html'] = $html;
        $respuesta['cant_noticias'] = $noticias->num_rows();
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($respuesta));
    }

    
//EVENTO LINK DE CALENDARIO
//---------------------------------------------------------------------------------------------------
    
    /**
     * Recibe los datos del formulario de eventos/noticias
     * Crea registro en la tabla post y lo referencia en la tabla evento
     */
    function crear_ev_link()
    { 
        
        $registro['tipo_id'] = 4;   //Link asignado
        $registro['fecha_inicio'] = $this->input->post('fecha_inicio');   //Link asignado
        $registro['referente_id'] = time();   //Para identificarlo
        $registro['url'] = $this->input->post('url');
        $registro['institucion_id'] = $this->session->userdata('institucion_id');
        $registro['grupo_id'] = $this->input->post('grupo_id');
        
        if ( $this->input->post('evento_id') == 0 ) {
            $this->Evento_model->guardar_evento($registro);
        } else {
            $condicion = "id = {$this->input->post('evento_id')}";
            $this->Pcrn->guardar('evento', $condicion, $registro);
        }
        
        
            
        redirect('eventos/calendario');
    }
    
//PROGRAMACIÓN DE TEMAS
//---------------------------------------------------------------------------------------------------
    
    /**
     * AJAX
     * Guarda un registro en la tabla tema con la programación de un tema de
     * un contenido a un grupo de estudiantes. La programación corresponde a la
     * definición de una fecha para que los estudiantes del grupo lean ese tema
     */
    function programar_tema()
    {
        $datos['tema_id'] = $this->input->post('tema_id');
        $datos['grupo_id'] = $this->input->post('grupo_id');
        $datos['fecha_inicio'] = $this->input->post('fecha_inicio');
        $datos['flipbook_id'] = $this->input->post('flipbook_id');
        $datos['num_pagina'] = $this->input->post('num_pagina');   //Página en la que está el tema dentro del flipbook
        
        $evento_id = $this->Evento_model->programar_tema($datos);

        $this->output
        ->set_content_type('application/json')
        ->set_output($evento_id);
    }
    
}