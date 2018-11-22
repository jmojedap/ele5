<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sincro extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        
        //Específicos
        $this->load->model('Develop_model');
        
        //Para formato de horas
        date_default_timezone_set("America/Bogota");
        
        //Para permitir acceso remoto a funciones ajax en el CGR
        header('Access-Control-Allow-Origin: *'); 
        header('Access-Control-Allow-Methods: GET');
        //header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');        

    }
    
// FUNCIONES LOCALES
//-----------------------------------------------------------------------------
    
    /**
     * Vista para el proceso de sincronización de la base de datos local, con la
     * base de datos en el servidor. Listado de tablas.
     */
    function panel($metodo_id = 0)
    {
        
        //Procesos iniciales
            $condicion = $this->Develop_model->condicion_sincro();
            $arr_estado_tablas = $this->Develop_model->arr_estado_tablas($condicion);
            $this->Develop_model->act_max_idl($arr_estado_tablas);
            
        //Variables específicas
            $data['metodo_id'] = $metodo_id;    //Método de sincronización.
            $data['tablas'] = $this->Develop_model->tablas($condicion);
            $data['limit'] = 5000;             //Número máximo de registros a transferir por ciclo
            $data['sincro_url'] = $this->Pcrn->campo_id('sis_opcion', 2, 'valor');
            
        //Se puede sincronizar solo si es versión local, backup
            $vista_a = 'app/mensaje_v';
            if ( VER_LOCAL ) 
            {
                $vista_a = 'sistema/sincro/panel_v'; 
                $data['mensaje'] = '<i class="fa fa-info-circle"></i> La sincronización está permitida solo en la versión local';
            }
            
        //Variables vista
            $data['titulo_pagina'] = 'Sincronización DB';
            $data['vista_a'] = $vista_a;
            $data['vista_menu'] = 'sistema/develop/database_menu_v';
        
        //Cargar vista
            $this->load->view(PTL_ADMIN, $data);   
    }
    
    /**
     * AJAX
     * Actualiza el campo sis_tabla. Se marca el momento de inicio
     *
     * @param type $tabla
     */
    function iniciar_sincro($tabla)
    {        
        //Marcar momento de inicio
            $registro['fecha_inicio'] = date('Y-m-d H:i:s');
            $condicion = "nombre_tabla = '{$tabla}'";
            
            $this->Pcrn->guardar('sis_tabla', $condicion, $registro);
    }
    
    /**
     * AJAX
     * Elimina todos los datos de una tabla
     *
     * @param type $tabla
     */
    function limpiar_tabla($tabla)
    {
        
        set_time_limit(360);    //360 segundos, 6 minutos por ciclo
        
        //Marcar momento de inicio
            $registro['fecha_inicio'] = date('Y-m-d H:i:s');
            $condicion = "nombre_tabla = '{$tabla}'";
            
            $this->Pcrn->guardar('sis_tabla', $condicion, $registro);
        
        //Eliminar los datos de tabla
            $this->Develop_model->limpiar_tabla($tabla);
    }
    
    /**
     * AJAX
     * Carga los datos descargados del servidor en la tabla local
     * 
     * @param type $tabla
     */
    function cargar_datos($tabla)
    {
        //2015-12-04 solucionar problema memory limit y tiempo de ejecución
            ini_set('memory_limit', '2048M');   
            set_time_limit(120);    //120 segundos, dos minutos por ciclo
        
        $json_descarga = $this->input->post('json_descarga');
        $descarga = json_decode($json_descarga);
        $respuesta = $this->Develop_model->cargar_registros($tabla, $descarga);
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($respuesta));
    }
    
    /**
     * AJAX
     * Actualiza estado tablas servidor, en la tabla sis_tabla
     */
    function act_estado_servidor()
    {
        $arr_estado_servidor = $this->input->post('json_estado_tablas');
        $this->Develop_model->act_estado_servidor($arr_estado_servidor);
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(count($arr_estado_servidor));
    }
    
    /**
     * AJAX
     * Establece la fecha actual, como fecha de sincronización reciente
     * @param type $tabla
     */
    function act_datos_sincro($tabla)
    {
        //Calcular estado de la tabla
        $estado_tabla = $this->Develop_model->estado_tabla($tabla);
        
        //Construir registro
        $registro['fecha_sincro'] = date('Y-m-d H:i:s');
        $registro['cant_registros'] = $estado_tabla['cant_registros'];
        $registro['max_id'] = $estado_tabla['max_id'];
        
        //Actualizar en sis_tabla
        $this->db->where('nombre_tabla', $tabla);
        $this->db->update('sis_tabla', $registro);
    }
    
// FUNCIONES EN SERVIDOR
//-----------------------------------------------------------------------------
    
    /**
     * AJAX, registros de una tabla en formato json
     * 
     * @param type $tabla
     * @param type $offset
     */
    function registros_json($tabla, $limit = 50000, $offset = 0)
    {
        $this->load->model('Develop_model');
        
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get($tabla, $limit, $offset);
        
        $resultado['campos'] = $query->list_fields();
        $resultado['registros'] = $this->Develop_model->query_liviano($query);
        
        echo json_encode($resultado);
    }
    
    /**
     * AJAX, registros de una tabla en formato json, con el ID mayor al valor de
     * la variable $desde_id
     * 
     * @param type $tabla
     * @param type $desde_id
     */
    function registros_json_id($tabla, $limit = 50000, $desde_id = 0)
    {
        $this->load->model('Develop_model');
        
        $this->db->order_by('id', 'ASC');
        $this->db->where('id >', $desde_id);
        $query = $this->db->get($tabla, $limit);
        
        $resultado['campos'] = $query->list_fields();
        $resultado['registros'] = $this->Develop_model->query_liviano($query);
        
        echo json_encode($resultado);
    }
    
    /**
     * AJAX
     * devuelve el número de registros que tiene una tabla
     * 
     * @param type $tabla
     * @return type
     */
    function cant_registros($tabla, $desde_id = 0)
    {
        $sql = "SELECT COUNT(id) AS cant_registros FROM {$tabla} WHERE id > {$desde_id}";
        $query = $this->db->query($sql);
        
        $cant_registros = 0;
        if ( $query->num_rows() > 0 ) { $cant_registros = $query->row()->cant_registros; }
        
        echo $cant_registros;
    }
    
    /**
     * AJAX
     * Objeto json con datos de estado de tablas, cantidad de registros y
     * ID máximo
     */
    function json_estado_tablas()
    {
        $condicion = $this->Develop_model->condicion_sincro();
        $arr_estado_tablas = $this->Develop_model->arr_estado_tablas($condicion);
        $json_estado_tablas = json_encode($arr_estado_tablas);
        
        $this->output
        ->set_content_type('application/json')
        ->set_output($json_estado_tablas);
    }
    
}

/* Fin del archivo Sincro.php */