<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mensajes extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        $this->load->model('Mensaje_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function explorar()
    {
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Busqueda_model');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Mensaje_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(2);
            $config['base_url'] = base_url("mensajes/explorar/?{$busqueda_str}");
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Mensaje_model->buscar($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['resultados'] = $resultados;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Conversaciones';
            $data['subtitulo_pagina'] = $resultados_total->num_rows();
            $data['vista_a'] = 'mensajes/explorar_v';
            $data['vista_menu'] = 'mensajes/explorar_menu_v';
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
            if ( $this->session->userdata('rol_id') <= 1 ) {
                $this->Mensaje_model->eliminar($elemento_id);
            } else {
                $this->Mensaje_model->abandonar($elemento_id);
            }
        }
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(count($seleccionados));
    }
    
    function buscar_conversacion()
    {
        $this->load->model('Busqueda_model');
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        redirect("mensajes/conversacion/?{$busqueda_str}");
    }
    
// DATOS DE CONVERSACIONES
//-----------------------------------------------------------------------------
    
    function mensajes($conversacion_id)
    {
        //Variables específicas
            $data = $this->Mensaje_model->basico($conversacion_id);
            $data['vista_a'] = 'mensajes/lectura/conversacion_v';
            
        //Mensajes
            $this->db->where('conversacion_id', $conversacion_id);
            $this->db->order_by('enviado', 'DESC');
            $data['mensajes'] = $this->db->get('mensaje');

        //Variables generales
            //$data['subtitulo_pagina'] = '';
            $data['vista_b'] = 'mensajes/lectura/mensajes_v';
            $data['vista_menu'] = 'usuarios/explorar_menu_v';

        $this->load->view(PTL_ADMIN, $data);
    }
    
    function usuarios($conversacion_id)
    {
        //Variables específicas
            $data = $this->Mensaje_model->basico($conversacion_id);
            $data['vista_a'] = 'mensajes/lectura/conversacion_v';
            
        //Mensajes
            $this->db->where("id IN (SELECT usuario_id FROM usuario_asignacion WHERE referente_id = {$conversacion_id} AND tipo_asignacion_id = 5)");
            $data['usuarios'] = $this->db->get('usuario');

        //Variables generales
            $data['subtitulo_pagina'] = 'Usuarios';
            $data['vista_b'] = 'mensajes/lectura/usuarios_v';
            $data['vista_menu'] = 'usuarios/explorar_menu_v';

        $this->load->view(PTL_ADMIN, $data);
    }
    
// GESTIÓN DE CONVERSACIONES Y MENSAJES
//-----------------------------------------------------------------------------
    
    /**
     * Muestra los mensajes de una conversación
     * 
     * @param type $conversacion_id
     */
    function conversacion($conversacion_id = NULL)
    {
        //Datos de consulta, construyendo array de búsqueda
            $this->load->model('Busqueda_model');
            $busqueda = $this->Busqueda_model->busqueda_array();
            $conversaciones = $this->Mensaje_model->conversaciones($busqueda, 10); //Para calcular el total de resultados

        //Si la conversación no está definida
            if ( $conversaciones->num_rows() > 0 )
            {
                $conversacion_id = $this->Pcrn->si_nulo($conversacion_id, $conversaciones->row()->id);
            }
            
        //Marcar mensajes de la conversación como leídos
            $this->Mensaje_model->marcar_leido($conversacion_id);
        
        //Valores básico
            $data = $this->Mensaje_model->basico($conversacion_id);
            
        //Generar resultados para mostrar
            $mensajes_total = $this->Mensaje_model->mensajes($data['row']);
            $mensajes = $this->Mensaje_model->mensajes($data['row'], 70, 0);    //70 mensajes más recientes de la conversación
            
        //Cargando $data
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $this->Busqueda_model->busqueda_str();
            $data['conversacion_id'] = $conversacion_id;
            $data['cant_mensajes'] = $mensajes_total->num_rows();
            $data['cant_no_mostrados'] = $mensajes_total->num_rows() - $mensajes->num_rows();
            $data['mensajes'] = $mensajes;
            $data['total_no_leidos'] = $this->Mensaje_model->no_leidos();
            $data['conversaciones'] = $conversaciones;
            $data['destino_form'] = 'mensajes/buscar_conversacion';
            
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function conversacion_total($conversacion_id)
    {
        //Variables específicas 
            $data = $this->Mensaje_model->basico($conversacion_id);

        //Variables generales
            $data['subtitulo_pagina'] = 'En construcción';
            $data['vista_a'] = 'app/en_construccion_v';

        $this->load->view(PTL_ADMIN, $data);
    }
    
    function nuevo()
    {
        $registro['tipo_id'] = 1;   //Nuevo mensaje, tipo 1, normal
        $conversacion_id = $this->Mensaje_model->nuevo($registro);
        redirect("mensajes/conversacion/{$conversacion_id}");   
    }
    
    function nuevo_grupal($grupo_id)
    {
        $conversacion_id = $this->Mensaje_model->nuevo_grupal($grupo_id);
        redirect("mensajes/conversacion/{$conversacion_id}");   
    }
    
    function nuevo_institucional($institucion_id, $tipo_id = 3)
    {
        $conversacion_id = $this->Mensaje_model->nuevo_institucional($institucion_id, $tipo_id);
        redirect("mensajes/conversacion/{$conversacion_id}");   
    }
    
    /**
     * Elimina conversación y sus mensajes.
     * 
     * @param type $conversacion_id
     */
    function eliminar($conversacion_id)
    {
        $this->Mensaje_model->eliminar($conversacion_id);
        $reciente_id = $this->Mensaje_model->conversacion_id();
        redirect("mensajes/conversacion/{$reciente_id}");
    }
    
    /**
     * Abandonar conversación
     */
    function abandonar($conversacion_id)
    {
        $this->Mensaje_model->abandonar($conversacion_id);
        $reciente_id = $this->Mensaje_model->conversacion_id();
        redirect("mensajes/conversacion/{$reciente_id}");
    }
    
    
    
    /**
     * Envía el mensaje, recibe los datos del formulario en mensajes/conversacion
     * guarda los datos en la tabla [mensaje_usuario], y lo "envía" en asociándolo
     * en la tabla mensaje_usuario
     * 
     * @param type $conversacion_id
     */
    function enviar($conversacion_id)
    {
        //$this->output->enable_profiler(TRUE);
        $this->Mensaje_model->actualizar_conversacion();
        
        $mensaje_id = $this->Mensaje_model->guardar();
        $this->Mensaje_model->enviar($mensaje_id);
        
        $destino = "mensajes/conversacion/{$conversacion_id}/1";
        redirect($destino);
    }
    
    /**
     * Elimina un mensaje particular de la conversación
     */
    function eliminar_mensaje($conversacion_id, $mensaje_id)
    {
        $this->output->enable_profiler(TRUE);
        $this->Mensaje_model->eliminar_mensaje($mensaje_id, $this->session->userdata('usuario_id'));
        
        $destino = "mensajes/conversacion/{$conversacion_id}/1";
        redirect($destino);
    }
    
// GESTIÓN DE USUARIOS
//-----------------------------------------------------------------------------
    
    /**
     * AJAX, envía JSON con array de id y nombre de usuario para agregar a una conversación
     * Con nombre de usuario que coincida con la búsqueda q
     * Que no esté ya incluído en la conversación
     * 
     * @param type $conversacion_id
     */
    function usuarios_agregables($conversacion_id)
    {
        $this->load->model('Usuario_model');
        $this->load->model('Busqueda_model');
        
        $busqueda = $this->Busqueda_model->busqueda_array();
        $busqueda['q'] = $this->input->post('query');
        $busqueda['condicion'] = "id NOT IN (SELECT usuario_id FROM usuario_asignacion WHERE tipo_asignacion_id = 5 AND referente_id = {$conversacion_id})";
        
        $resultados = $this->Usuario_model->autocompletar($busqueda);
        
        $arr_elementos = $resultados->result_array();
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($arr_elementos));
    }
    
    /**
     * AJAX
     * Agrega un usuario a una conversación
     * tabla usuario_asignacion
     * 
     */
    function agregar_usuario()
    {  
        $conversacion_id = $this->input->post('conversacion_id');
        $usuario_id = $this->input->post('usuario_id');
        
        $ua_id = $this->Mensaje_model->agregar_usuario($conversacion_id, $usuario_id);
        echo $ua_id;
    }
    
    /**
     * Elimina a un usuario de una conversación
     * tabla usuario_asignacion
     * 
     * @param type $conversacion_id
     * @param type $usuario_id
     */
    function quitar_usuario($conversacion_id, $usuario_id)
    {
        $conversacion_id = $this->input->post('conversacion_id');
        $usuario_id = $this->input->post('usuario_id');
        $this->Mensaje_model->quitar_usuario($conversacion_id, $usuario_id);
    }
    
//DEPURACIÓN
//--------------------------------------------------------------------------------------------------
    
    function depurar($usuario_id)
    {
        $this->Mensaje_model->depurar($usuario_id);
        redirect('mensajes/conversacion');
    }
    
    /**
     * Depurar datos de conversaciones y mensajes
     * 
     * 
     */
    function depuracion()
    {
        //Eliminas mensajes de conversaciones que no existen en la tabla conversacion
        $consultas[] = "DELETE FROM mensaje WHERE conversacion_id NOT IN (SELECT id FROM conversacion);";
        
        //Eliminar asignaciones de mensaje, de mensajes que no existen en la tabla mensaje
        $consultas[] = "DELETE FROM mensaje_usuario WHERE mensaje_id NOT IN (SELECT id FROM mensaje);";
        
        foreach ($consultas as $sql) {
            $this->db->query($sql);
        }
        
        $this->Mensaje_model->depurar($this->session->userdata('usuario_id'));
        
        redirect('develop/procesos');
    }
    
    function test()
    {
        $this->output->enable_profiler(TRUE);
        
        $this->load->model('Busqueda_model');
        $busqueda = $this->Busqueda_model->busqueda_array();
        
        $data['conversaciones_q'] = $this->Mensaje_model->conversaciones_q($busqueda);
        $data['conversaciones'] = $this->Mensaje_model->conversaciones($busqueda);

        //Variables generales
            $data['titulo_pagina'] = 'Test';
            $data['vista_a'] = 'app/prueba_v';

        $this->load->view(PTL_ADMIN, $data);
    }

    
}