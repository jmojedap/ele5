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
        
        $controladores_publicos = array('app', 'cgr', 'sincro', '');
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
        if ( $this->CI->session->userdata('logged') != TRUE && !in_array($current_controller, $controladores_publicos) ) { redirect('app/no_permitido'); }
        
        //Sí está logueado y el controlador/funcion requerido está entre los controladores bloqueados
        if ( $this->CI->session->userdata('logged') == TRUE && in_array($current_cf_id, $funciones_bloqueadas) ) { redirect('app/no_permitido'); }
        
        //Sí está logueado y el controlador requerido está entre los controladores bloqueados
        if ( $this->CI->session->userdata('logged') == TRUE && in_array($current_controller_id, $funciones_bloqueadas) ) { redirect('app/no_permitido'); }
        
    }
    
    /**
     * Control de acceso de usuarios a las páginas, no está en uso. 
     */
    function identificado_ant()
    {
        //Crea instancia para obtener acceso a las librerías de codeigniter
        $this->CI = &get_instance();
        
        $controladores_publicos = array('app', '');
        $funciones_bloqueadas = $this->CI->session->userdata('funciones_bloqueadas');
        
        $current_controller = $this->CI->uri->segment(1);
        $current_function = $this->CI->uri->segment(2);
        $current_cf = "{$current_controller}/{$current_function}";
        
        //No está logueado y el controlador requerido no está entre los controladores públicos
        if ( $this->CI->session->userdata('logged') != TRUE && !in_array($current_controller, $controladores_publicos) ) { redirect('app/no_permitido'); }
        
        //El usuario Sí está logueado y el controlador/funcion requerido está entre los controladores bloqueados para su rol
        if ( $this->CI->session->userdata('logged') == TRUE && in_array($current_cf, $funciones_bloqueadas) ) { redirect('app/no_permitido'); }
        
        //El usuario Sí está logueado y el controlador requerido está entre los controladores bloqueados para su rol
        if ( $this->CI->session->userdata('logged') == TRUE && in_array($current_controller, $funciones_bloqueadas) ) { redirect('app/no_permitido'); }
    }
    
    
    
    /**
     * No utilizado, desarrollo pendiente para evitar consulta a la tabla sis_acl_recurso
     * 
     * @param type $current_cf
     * @return int
     */
    function id_recurso($current_cf)
    {
        $recursos['datos/niveles'] = 13;
        
        return $recursos[$current_cf];
    }
    
    /**
     * No utilizado, desarrollo pendiente para evitar consulta a la tabla sis_acl_recurso
     * @param type $recurso_id
     * @return string
     */
    function datos_recurso($recurso_id)
    {
        $datos[13] = array(
            'menu' => ''
        );
        
        return $datos[$recurso_id];
    }
    
}