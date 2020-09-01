<?php
class Mensaje_model extends CI_Model{
    
    function basico($conversacion_id)
    {
        $data['row'] = $this->Pcrn->registro_id('conversacion', $conversacion_id);
        $data['usuarios'] = $this->usuarios($conversacion_id);
        $data['cant_destinatarios'] = 0;
        
        if ( $data['usuarios']->num_rows() > 0 ) {
            $data['cant_destinatarios'] = $data['usuarios']->num_rows() - 1;
        }
        
        $head_title = 'Nuevo mensaje';
        if ( $conversacion_id > 0 ) {
            if ( strlen($data['row']->asunto) > 0 ) { $head_title = substr($data['row']->asunto, 0, 50); }
        }
        
        $data['head_title'] = $head_title;
        $data['view_a'] = 'mensajes/conversacion/conversacion_v';

        return $data;
    }
    
    /**
     * Buscar conversaciones que cumplen unos criterios
     * 
     * @param type $busqueda
     * @param type $per_page
     * @param type $offset
     * @return type
     */
    function buscar($busqueda, $per_page = NULL, $offset = NULL)
    {
        
        $filtro_rol = $this->filtro_usuarios($this->session->userdata('usuario_id'));

        //Construir búsqueda
        //Crear array con términos de búsqueda
            if ( strlen($busqueda['q']) > 2 )
            {
                $palabras = $this->Busqueda_model->palabras($busqueda['q']);

                foreach ($palabras as $palabra) {
                    $concat_campos = $this->Busqueda_model->concat_campos(array('asunto'));
                    $this->db->like("CONCAT({$concat_campos})", $palabra);
                }
            }
        
        //Especificaciones de consulta
            $this->db->where($filtro_rol); //Filtro según el rol de usuario que se tenga
            $this->db->order_by('editado', 'DESC');
            
        //Otros filtros
            //if ( $busqueda['i'] != '' ) { $this->db->where('institucion_id', $busqueda['i']); }    //Institución
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('conversacion'); //Resultados totales
        } else {
            $query = $this->db->get('conversacion', $per_page, $offset); //Resultados por página
        }
        
        return $query;
    }
    
    /**
     * Condición SQL para filtrar las conversaciones que puede ver el usuario
     * en sesión.
     * 
     * @param type $usuario_id
     * @return type
     */
    function filtro_usuarios($usuario_id)
    {
        $filtro_usuarios = 'id > 0';
        if ( $this->session->userdata('rol_id') > 1 ) {
            $filtro_usuarios = "id IN (SELECT referente_id FROM usuario_asignacion WHERE tipo_asignacion_id = 5 AND usuario_id = {$usuario_id})";
        }
        
        return $filtro_usuarios;
    }
    
    /**
     * Devuelve ID de la conversación más reciente en la que participa el usuario
     * @return type
     */
    function conversacion_id()
    {
        $conversacion_id = 0;
        
        $this->db->where("id IN (SELECT referente_id FROM usuario_asignacion WHERE usuario_id = {$this->session->userdata('usuario_id')} AND tipo_asignacion_id = 5)");
        $this->db->order_by('editado', 'DESC');
        $conversaciones = $this->db->get('conversacion');
        
        if ( $conversaciones->num_rows() ) { $conversacion_id = $conversaciones->row()->id; }
        
        return $conversacion_id;
    }
    
    function data_explorar()
    {
      $data['vista_a'] = 'mensajes/conversaciones_v';
      $data['titulo_pagina'] = 'Mensajes';
      
      return $data;
    }
    
    /**
     * Listado de conversaciones en las que participa el usuario
     * 
     * @param type $busqueda
     * @param type $per_page
     * @param type $offset
     * @return type
     */
    function conversaciones($busqueda, $per_page = NULL, $offset = NULL)
    {
        $usuario_id = $this->session->userdata('usuario_id');
        
        //Array con id de conversaciones con coincidencias en  $busqueda['q']
            if ( strlen($busqueda['q']) > 0 )
            {
                $cnvs_texto = $this->conversaciones_q($busqueda);
                $arr_conversaciones = $cnvs_texto;
                $str_conversaciones = implode(', ', $arr_conversaciones);
                $this->db->where("conversacion.id IN ({$str_conversaciones})");
            }
        
        //Especificaciones de consulta
            $this->db->where('usuario_asignacion.usuario_id', $usuario_id);
            $this->db->where('tipo_asignacion_id', 5);  //Asignación a conversación
            $this->db->join('conversacion', 'usuario_asignacion.referente_id = conversacion.id');
            $this->db->order_by('conversacion.editado', 'DESC');
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('usuario_asignacion'); //Resultados totales
        } else {
            $query = $this->db->get('usuario_asignacion', $per_page, $offset); //Resultados por página
        }
        
        return $query;
    }
    
    /**
     * Devuelve array con valores de conversacion.id que coinciden con el texto buscado en $busqueda['q']
     * La coincidencia se busca en los campos: mensaje.texto_mensaje, conversacion.asunto: y 
     * usuario.(username, nombre, apellidos). Se limita a las conversaciones del usuario de la
     * sesión.
     * 
     * @param type $busqueda
     * @return type
     */
    function conversaciones_q($busqueda)
    {
        $conversaciones = array(0);
        
        $usuario_id = $this->session->userdata('usuario_id');
        
        if ( strlen($busqueda['q']) > 2 ){
            $concat_campos = $this->Busqueda_model->concat_campos(array('texto_mensaje', 'asunto', 'username', 'usuario.nombre', 'usuario.apellidos'));
            $palabras = $this->Busqueda_model->palabras($busqueda['q']);

            foreach ($palabras as $palabra) {
                $this->db->like("CONCAT({$concat_campos})", $palabra);
            }
        }
        
        $this->db->select('mensaje.conversacion_id');
        $this->db->where('mensaje_usuario.usuario_id', $usuario_id);
        $this->db->join('mensaje_usuario', 'mensaje.id = mensaje_usuario.mensaje_id');
        $this->db->join('conversacion', 'conversacion.id = mensaje.conversacion_id');
        $this->db->join('usuario', 'usuario.id = mensaje.usuario_id');
        $this->db->group_by('conversacion_id');
        $query = $this->db->get('mensaje');
        
        if ( $query->num_rows() > 0 ) {
            $conversaciones = $this->Pcrn->query_to_array($query, 'conversacion_id');
        }
        
        return $conversaciones;
    }
    
    /**
     * Devuelve query con usuarios que están en una conversación
     * 
     * @param type $conversacion_id
     * @return type
     */
    function usuarios($conversacion_id)
    {
        $this->db->where('referente_id', $conversacion_id);
        $this->db->where('tipo_asignacion_id', 5);
        $this->db->join('usuario', 'usuario_asignacion.usuario_id = usuario.id');
        $usuarios = $this->db->get('usuario_asignacion');
        
        return $usuarios;
    }
    
    /**
     * Devuelve query con usuarios destinatarios en una conversación
     * 
     * @param type $conversacion_id
     */
    function destinatarios($conversacion_id)
    {
        $row_conversacion = $this->Pcrn->registro_id('conversacion', $conversacion_id);
        
        $destinatarios = $this->db->get_where('usuario', 'id=0');
        
        if ( ! is_null($row_conversacion) ) {
            if ( $row_conversacion->usuario_id == $this->session->userdata('usuario_id') ) {
                //Si es el creador, son todos los usuarios de la conversación
                $destinatarios = $this->usuarios($conversacion_id);
            } else {
                $usuario_id = $this->Pcrn->si_nulo($row_conversacion->usuario_id, 0);

                //Si no es el creador de la conversación, el destinatario solo es el creador
                $this->db->select('id AS usuario_id');
                $this->db->where("id IN ({$usuario_id}, {$this->session->userdata('usuario_id')})");
                $destinatarios = $this->db->get('usuario');
            }
        }
        
        return $destinatarios;
    }
    
    /**
     * Query con mensajes de una conversación
     * 
     * @param type $row_conversacion
     * @param type $per_page
     * @param type $offset
     * @return type
     */
    function mensajes($row_conversacion, $per_page = NULL, $offset = NULL)
    {
        
        $this->db->select('*, mensaje.usuario_id AS remitente_id');
        
        if ( ! is_null($row_conversacion)  ) {
            $this->db->where('conversacion_id', $row_conversacion->id);
        } else {
            $this->db->where('mensaje.id', 0);
        }
        
        $this->db->join('mensaje_usuario', 'mensaje.id = mensaje_usuario.mensaje_id');
        $this->db->where('mensaje_usuario.usuario_id', $this->session->userdata('usuario_id'));
        $this->db->where('estado < 2');
        $this->db->order_by('enviado', 'ASC');
        
        //Obtener resultados
        if ( is_null($per_page) ){
            $mensajes = $this->db->get('mensaje'); //Resultados totales
        } else {
            $mensajes = $this->db->get('mensaje', $per_page, $offset); //Resultados por página
        }
        
        return $mensajes;
    }
    
    function nuevo($registro = array())
    {
        $registro['usuario_id'] = $this->session->userdata('usuario_id');
        $registro['creado'] = date('Y-m-d H:i:s');
        $registro['editado'] = date('Y-m-d H:i:s');
        
        $this->db->insert('conversacion', $registro);
        
        $conversacion_id = $this->db->insert_id();
        
        $this->agregar_usuario($conversacion_id, $registro['usuario_id']);  //Agrega al usuario creador de la conversación
        
        return $conversacion_id;   
    }
    
    function nuevo_grupal($grupo_id, $arr_row = null)
    {       
        $arr_row['tipo_id'] = 2;   //Grupal
        $arr_row['referente_id'] = $grupo_id;
                
        $conversacion_id = $this->nuevo($arr_row);
        
        $this->db->where('grupo_id', $grupo_id);
        $usuarios = $this->db->get('usuario_grupo');
        
        foreach ( $usuarios->result() as $row_usuario ){
            $this->agregar_usuario($conversacion_id, $row_usuario->usuario_id);
        }
        
        return $conversacion_id;
    }
    
    function nuevo_institucional($institucion_id, $tipo_id)
    {       
        $registro['tipo_id'] = $tipo_id;
        $registro['referente_id'] = $institucion_id;
        
        $conversacion_id = $this->nuevo($registro);
        
        if ( $tipo_id == 3 ) {
            //3 => Profesores
            $this->db->where('rol_id IN (3,4,5)');
        } elseif ( $tipo_id == 4 ) {
            //4 => Estudiantes
            $this->db->where('rol_id', 6);
        }
        
        $this->db->where('institucion_id', $institucion_id);
        $usuarios = $this->db->get('usuario');
        
        foreach ( $usuarios->result() as $row_usuario ){
            $this->agregar_usuario($conversacion_id, $row_usuario->id);
        }
        
        return $conversacion_id;
    }
    
    function eliminable_conversacion()
    {
        $eliminable = FALSE;
        
        //Si es administrador
        if ( $this->session->userdata('rol_id') <= 1 ) { $eliminable = TRUE; }
        
        return $eliminable;
    }
    
    /**
     * Elimina la conversación y sus datos relacionados
     * 
     * @param type $conversacion_id
     */
    function eliminar($conversacion_id)
    {
        //Tabla mensaje
            $this->db->where('conversacion_id', $conversacion_id);
            $this->db->delete('mensaje');
            
        //Tabla usuario_asignacion
            $this->db->where('referente_id', $conversacion_id);
            $this->db->where('tipo_asignacion_id', 5);  //Asignación de mensaje, ver item.categoria_id = 16
            $this->db->delete('usuario_asignacion');
        
        //Tabla conversacion
            $this->db->where('id', $conversacion_id);
            $this->db->delete('conversacion');
            
        //Eliminación en cascada, datos residuales
            $this->eliminacion_cascada();
    }
    
    /**
     * Elimina los datos de un usuario en una conversación, la conversación
     * sigue existiendo, pero sin la participación del usuario en sesión
     * 
     * @param type $conversacion_id
     */
    function abandonar($conversacion_id)
    {
        //Eliminar de usuario_asignación
            $this->db->where('usuario_id', $this->session->userdata('usuario_id'));
            $this->db->where('referente_id', $conversacion_id);
            $this->db->where('tipo_asignacion_id', 5);  //Conversación
            $this->db->delete('usuario_asignacion');
            
        //Eliminar de mensaje_usuario
            $this->db->where('usuario_id', $this->session->userdata('usuario_id'));
            $this->db->where("mensaje_id IN (SELECT id FROM mensaje WHERE conversacion_id = {$conversacion_id})");
            $this->db->delete('mensaje_usuario');
    }
    
    /**
     * Eliminación en cascada de datos relacionados de mensajes y conversación
     */
    function eliminacion_cascada()
    {
        $consultas_sql[] = "DELETE FROM conversacion WHERE asunto IS NULL";
        $consultas_sql[] = "DELETE FROM usuario_asignacion WHERE tipo_asignacion_id = 5 AND referente_id NOT IN (SELECT id FROM conversacion)";
        $consultas_sql[] = "DELETE FROM mensaje WHERE conversacion_id NOT IN (SELECT id FROM conversacion)";
        $consultas_sql[] = "DELETE FROM mensaje_usuario WHERE mensaje_id NOT IN (SELECT id FROM mensaje)";   
        
        foreach ($consultas_sql as $sql) { $this->db->query($sql); }
    }
    
    /**
     * Agrega a un usuario a una conversación
     * 
     * @param type $conversacion_id
     * @param type $usuario_id
     */
    function agregar_usuario($conversacion_id, $usuario_id)
    {
        $registro['usuario_id'] = $usuario_id;
        $registro['referente_id'] = $conversacion_id;
        $registro['tipo_asignacion_id'] = 5;    //Asignación de usuario a una conversación
        $registro['editado'] = date('Y-m-d H:i:s');
        $registro['editado_usuario_id'] = $this->session->userdata('usuario_id');
        
        $condicion = "usuario_id = {$usuario_id} AND referente_id = {$conversacion_id} AND tipo_asignacion_id = 5"; //Para verificar si no existe
        
        //Se asigna el usuario a la conversación
        $ua_id = $this->Pcrn->guardar('usuario_asignacion', $condicion, $registro);
        
        return $ua_id;
        
    }
    
    /**
     * Quitar a un usuario de una conversación, función solo permitida antes 
     * de enviar el primer mensaje de la conversación
     * 
     * @param type $conversacion_id
     * @param type $usuario_id
     */
    function quitar_usuario($conversacion_id, $usuario_id)
    {
        $this->db->where('usuario_id', $usuario_id);
        $this->db->where('referente_id', $conversacion_id);
        $this->db->where('tipo_asignacion_id', 5);
        $this->db->delete('usuario_asignacion');
    }
    
    /**
     * 
     */
    function actualizar_conversacion()
    {
        $registro['editado'] = date('Y-m-d H:i:s');
        
        //Para conversación nueva
        if ( $this->input->post('asunto') ){
            $registro['asunto'] = $this->input->post('asunto');
        }
        
        $this->db->where('id', $this->input->post('conversacion_id'));
            $this->db->update('conversacion', $registro);
    }
    
    /**
     * Guardar registro en la tabla mensaje, devuelve ID del mensaje guardado
     * 
     * @return type
     */
    function guardar($arr_row = null)
    {
        if ( is_null($arr_row) )
        {
            $arr_row['conversacion_id'] = $this->input->post('conversacion_id');
            $arr_row['texto_mensaje'] = $this->input->post('texto_mensaje');
            $arr_row['url'] = $this->input->post('url');
        }
        $arr_row['usuario_id'] = $this->session->userdata('usuario_id');
        $arr_row['enviado'] = date('Y-m-d H:i:s');
        
        $this->db->insert('mensaje', $arr_row);
        
        return $this->db->insert_id();
    }
    
    /**
     * Enviar el mensaje a todos los usuarios asociados a la conversación
     * 2020-04-22
     */
    function enviar($mensaje_id, $conversacion_id = 0)
    {
        if ( $conversacion_id == 0 ) 
        {
            $conversacion_id = $this->input->post('conversacion_id');
        }
        
        $registro['mensaje_id'] = $mensaje_id;
        $registro['estado']= 0; //Se envía con estado 0, no leído
        
        $usuarios = $this->destinatarios($conversacion_id);
        
        foreach ($usuarios->result() as $row_usuario)
        {
            $registro['usuario_id'] = $row_usuario->usuario_id;
            $this->db->insert('mensaje_usuario', $registro);
        }
    }
    
    /**
     * Devuelve cantidad de mensajes no leídos de una conversación
     * 
     * @param type $conversacion_id
     * @return type
     */
    function no_leidos($conversacion_id = NULL, $usuario_id = NULL)
    {
        
        if ( is_null($usuario_id) ) { $usuario_id = $this->session->userdata('usuario_id'); }
        
        $row_conversacion = $this->Pcrn->registro_id('conversacion', $conversacion_id);
        $condicion_lectores = $this->condicion_lectores($row_conversacion);
        
        $this->db->where('estado', 0);
        $this->db->where('mensaje_usuario.usuario_id', $usuario_id);
        
        if ( ! is_null($conversacion_id) ) { $this->db->where("mensaje_id IN (SELECT id FROM mensaje WHERE conversacion_id = {$conversacion_id} AND {$condicion_lectores})"); }
        $this->db->select('id');
        $query = $this->db->get('mensaje_usuario');

        $no_leidos = $query->num_rows();
        
        return $no_leidos;
    }
    
    /**
     * Condición SQL para filtrar los posibles usuarios destinatarios de mensajes
     * al que le pueda enviar el usuario en sesión actual
     * 
     * @param type $row_conversacion
     * @return string
     */
    function condicion_lectores($row_conversacion)
    {
        $remitente_id = 0;
        
        $condicion = 'mensaje.id > 0';  //Todos
        
        if ( ! is_null($row_conversacion) ) {
            //Condición especial 2015-03-02
            if ( $row_conversacion->usuario_id != $this->session->userdata('usuario_id') && ! is_null($row_conversacion) ){
                //Si no es el creador de la conversación, solo sus mensajes y los del remitente inicial
                $remitente_id = $row_conversacion->usuario_id;
                $usuarios = array(
                    $this->session->userdata('usuario_id'),
                    $remitente_id
                );

                $condicion = "mensaje.usuario_id IN (" . implode(', ', $usuarios) . ")";
            }
        }
        
        return $condicion;
        
    }
    
    /**
     * Actualizar el campo mensaje_usuario.estado = 1
     * 
     * Se marca un mensaje como leído por el usuario
     * 
     * @param type $mensaje_id
     * @param type $usuario_id 
     */
    function marcar_leido($conversacion_id)
    {
        $conversacion_id = $this->Pcrn->si_nulo($conversacion_id, 0);
        
        //Actualizar valor en la tabla mensaje_usuario
            $this->db->where("mensaje_id IN (SELECT id FROM mensaje WHERE conversacion_id = {$conversacion_id})");
            $this->db->where('estado', 0);  //No leídos
            $this->db->where('usuario_id', $this->session->userdata('usuario_id'));
            $this->db->update('mensaje_usuario', array('estado' => 1));
        
        //Actualizar la variable de sesión
            $condicion = "usuario_id = {$this->session->userdata('usuario_id')} AND estado = 0";
            $no_leidos = $this->Pcrn->num_registros('mensaje_usuario', $condicion);
            $this->session->set_userdata('no_leidos', $no_leidos);
    }
    
    /**
     * No se elimina el registro. Actualiza el estado de un mensaje recibido 
     * por un usuario al estado 2 => eliminado.
     * 
     * @param type $mensaje_id
     * @param type $usuario_id
     */
    function eliminar_mensaje($mensaje_id, $usuario_id)
    {
        $this->db->where('mensaje_id', $mensaje_id);
        $this->db->where('usuario_id', $usuario_id);
        $this->db->update('mensaje_usuario', array('estado' => 2)); //Estado 2 = eliminado
    }
    
    /**
     * Elimina los mensajes de conversaciones inexistentes o sin asignación
     * 
     * @param type $usuario_id
     */
    function depurar($usuario_id)
    {
        $cant_registros = 0;
        $conversacion_id = 0;
        
        $this->db->select('id, mensaje_id');
        $this->db->where('usuario_id', $usuario_id);
        $mensajes = $this->db->get('mensaje_usuario');
        
        foreach ( $mensajes->result() AS $row_mensaje )
        {
            $conversacion_id_pre = $this->Pcrn->campo_id('mensaje', $row_mensaje->mensaje_id, 'conversacion_id');
            $conversacion_id = $this->Pcrn->si_nulo($conversacion_id_pre, 0);
            $condicion = "usuario_id = {$usuario_id} AND referente_id = {$conversacion_id} AND tipo_asignacion_id = 5";
            $cant_registros = $this->Pcrn->num_registros('usuario_asignacion', $condicion);
            
            if ( $cant_registros == 0 ) 
            {
                $this->db->where('id', $row_mensaje->id);
                $this->db->delete('mensaje_usuario');
            }
        }
    }

    /**
     * Enviar mensaje automático sobre la programación de un evento tipo sesión virtual (6)
     * Se envia mensaje a los estudiantes de todo el grupo.
     * 2020-04-22
     */
    function automatico_sesionv($evento_id, $arr_evento)
    {
        //Crear conversación
        $arr_conversacion['asunto'] = 'Tienes una sesión virtual: ' . $this->pml->date_format($arr_evento['fecha_inicio'], 'd/M') . ' ' . $arr_evento['hora_inicio'];
        $conversacion_id = $this->nuevo_grupal($arr_evento['grupo_id'], $arr_conversacion);

        //Crear y enviar mensaje
        $arr_mensaje['conversacion_id'] = $conversacion_id;
        $arr_mensaje['texto_mensaje'] = 'Programé una sesión virtual para tu grupo, se realizará el ';
        $arr_mensaje['texto_mensaje'] .= $this->pml->date_format($arr_evento['fecha_inicio'], 'd/M') . ' a las ';
        $arr_mensaje['texto_mensaje'] .= $arr_evento['hora_inicio'] . '. La sesión virtual tratará: ';
        $arr_mensaje['texto_mensaje'] .= $arr_evento['descripcion'] . '. ';
        $arr_mensaje['texto_mensaje'] .= 'Para acceder a la sesión virtual haz clic en siguiente enlace: ';
        $arr_mensaje['url'] = $arr_evento['url'];
        $mensaje_id = $this->guardar($arr_mensaje);
        $this->enviar($mensaje_id, $conversacion_id);

        return $conversacion_id;
    }
}