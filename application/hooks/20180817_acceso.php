<?php

class Acceso {
    
    /**
     * Control de acceso de usuarios basado en el id de los recursos (recurso.id)
     * Actualmente en uso.
     * 
     */
    function identificado()
    {
        //Crea instancia para obtener acceso a las librerías de codeigniter, basado en el id
        $this->CI = &get_instance();
        
        $funciones_publicas = $this->funciones_publicas();
        $funciones_bloqueadas = $this->CI->session->userdata('funciones_bloqueadas');
        
        $current_controller = $this->CI->uri->segment(1);
        $current_function = $this->CI->uri->segment(2);
        $current_cf = "{$current_controller}/{$current_function}";
        
        $funciones = $this->CI->db->get_where('sis_acl_recurso', "recurso = '{$current_cf}'");
        
        $current_cf_id = 0;
        if ( $funciones->num_rows() > 0 ) {
            $current_cf_id = $funciones->row()->id;
        }
        
        $controladores = $this->CI->db->get_where('sis_acl_recurso', "recurso = '{$current_controller}'");
        
        $current_controller_id = 0;
        if ( $controladores->num_rows() > 0 ) {    
            $current_controller_id = $controladores->row()->id;
        }
        
        //No está logueado y el controlador requerido no está entre los controladores públicos
        if ( $this->CI->session->userdata('logged') != TRUE && ! in_array($current_cf, $funciones_publicas) ) { redirect('app/no_permitido'); }
        
        //Sí está logueado y el controlador/funcion requerido está entre los controladores bloqueados
        if ( $this->CI->session->userdata('logged') == TRUE && in_array($current_cf_id, $funciones_bloqueadas) ) { redirect('app/no_permitido'); }
        
        //Sí está logueado y el controlador requerido está entre los controladores bloqueados
        if ( $this->CI->session->userdata('logged') == TRUE && in_array($current_controller_id, $funciones_bloqueadas) ) { redirect('app/no_permitido'); }
        
    }
    
    function funciones_publicas()
    {
        $funciones_publicas[] = '/';
        $funciones_publicas[] = 'app/index';
        $funciones_publicas[] = 'app/no_permitido';
        $funciones_publicas[] = 'app/login';
        $funciones_publicas[] = 'app/test';
        
        $funciones_publicas[] = 'app/registro';
        $funciones_publicas[] = 'app/validar_login';
        $funciones_publicas[] = 'app/logout';
        
        $funciones_publicas[] = 'develop/sl';
        
        $funciones_publicas[] = 'usuarios/registrado';
        $funciones_publicas[] = 'usuarios/activar';
        $funciones_publicas[] = 'usuarios/activar_e';
        $funciones_publicas[] = 'usuarios/restaurar';
        $funciones_publicas[] = 'usuarios/restaurar_e';
        
        $funciones_publicas[] = 'posts/leer';
        
        $funciones_publicas[] = 'sincro/registros_json';
        $funciones_publicas[] = 'sincro/registros_json_id';
        $funciones_publicas[] = 'sincro/json_estado_tablas';
        $funciones_publicas[] = 'sincro/test_ajax';
        $funciones_publicas[] = 'sincro/cant_registros';
        
        return $funciones_publicas;
    }
    
}