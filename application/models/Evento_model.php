<?php
class Evento_Model extends CI_Model{
    
    function basico($evento_id)
    {
        $row_evento = $this->Pcrn->registro_id('evento', $evento_id);
        
        $basico['evento_id'] = $evento_id;
        $basico['row'] = $row_evento;
        $basico['titulo_pagina'] = $row_evento->nombre_evento;
        $basico['vista_a'] = 'eventos/evento_v';
        
        return $basico;
    }
    
    /**
     * Determina si un usuario tiene el permiso para eliminar un registro de evento
     * 
     * @param type $evento_id
     * @return boolean
     */
    function eliminable($evento_id)
    {   
        $eliminable = FALSE;
        $row_evento = $this->Pcrn->registro_id('evento', $evento_id);
        
        //El usuario creó el evento
        if ( $row_evento->c_usuario_id == $this->session->userdata('usuario_id') ) {
            $eliminable = TRUE;
        }
        
        //El usuario es aministrador
        if ( $this->session->userdata('rol_id') <= 1 ) { $eliminable = TRUE; }
            
        return $eliminable;
    }
    
    /**
     * Elimina un registro de evento y sus registros relacionados en otras tablas
     * 
     * @param type $evento_id
     * @return type
     */
    function eliminar($evento_id)
    {
        $cant_eliminados = 0;
        $eliminable = $this->eliminable($evento_id);
        
        if ( $eliminable ) {
            //Tablas relacionadas
                $this->db->where('tipo_id', 3); //Programación de quices
                $this->db->where('referente_2_id', $evento_id);
                $this->db->delete('evento');
        
            //Tabla
                $this->db->where('id', $evento_id);
                $this->db->delete('evento');
                
            $cant_eliminados = $this->db->affected_rows();
        }
            
        return $cant_eliminados;
    }
    
    /**
     * Modifica el campo evento.estado para un registro específico
     * 
     * @param type $tipo_id
     * @param type $referente_id
     * @param type $estado
     */
    function act_estado($tipo_id, $referente_id, $estado)
    {
        $registro['estado'] = $estado;
        
        $this->db->where('tipo_id', $tipo_id);
        $this->db->where('referente_id', $referente_id);
        $this->db->update('evento', $registro);
    }
    
    /**
     * Guarda un registro en la tabla evento
     * 
     * @param type $registro
     * @return type
     */
    function guardar_evento($registro, $condicion_add = NULL)
    {
        //Condición para identificar el registro del evento
            $condicion = "tipo_id = {$registro['tipo_id']} AND referente_id = {$registro['referente_id']}";
            if ( ! is_null($condicion_add) ){
                $condicion .= " AND " . $condicion_add;
            }
        
            $evento_id = $this->Pcrn->existe('evento', $condicion);
        
        //Datos adicionales del registro
            $registro['editado'] = date('Y-m-d H:i:s');
        
        //Guardar el evento
        if ( $evento_id == 0 ) 
        {
            //No existe, se inserta
            $registro['creado'] = date('Y-m-d H:i:s');
            $registro['c_usuario_id'] = $this->session->userdata('usuario_id');
            
            $this->db->insert('evento', $registro);
            $evento_id = $this->db->insert_id();
        } else {
            //Ya existe, editar
            $this->db->where('id', $evento_id);
            $this->db->update('evento', $registro);
        }
        
        return $evento_id;
    }
    
// DATOS
//---------------------------------------------------------------------------------------------------------
    
    function cant_eventos($filtros)
    {
        if ( $filtros['u'] != '' ) { $this->db->where('usuario_id', $filtros['u']); }    //Usuario
        if ( $filtros['t'] != '' ) { $this->db->where('tipo_id', $filtros['t']); }    //Tipo
        
        $query = $this->db->get('evento');
        
        return $query->num_rows();
    }
    
    function row_evento($claves)
    {
        //Valor por defecto
        $row = NULL;
        
        $this->db->where($claves);
        $query = $this->db->get('evento');
        if ( $query->num_rows() > 0 ){
            $row = $query->row();
        }
        
        return $row;
    }
    
// NOTICIAS
//---------------------------------------------------------------------------------------------------------
    
    /**
     * Query con los eventos para mostrarse en el muro de noticias del usuario
     * 
     * @return type
     */
    function noticias($busqueda, $limit, $offset = 0)
    {
        
        $condicion = $this->condicion_noticias();
        $fecha_limite = $this->Pcrn->suma_fecha(date('Y-m-d'), '+1 day') . ' 23:59:59';
        
        //Filtros
        if ( $busqueda['a'] != '' ) { $this->db->where('area_id', $busqueda['a']); }        //Área
        if ( $busqueda['g'] != '' ) { $this->db->where('grupo_id', $busqueda['g']); }       //Grupo
        if ( $busqueda['tp'] != '' ) { $this->db->where('tipo_id', $busqueda['tp']); }      //Tipo evento
        
        $this->db->limit($limit);
        $this->db->offset($offset);
        $this->db->where('fecha_inicio <= "' . $fecha_limite . '"');
        $this->db->where("($condicion)");
        $this->db->order_by('fecha_inicio', 'DESC');
        $this->db->order_by('hora_inicio', 'DESC');
        
        $query = $this->db->get('evento');
        
        return $query;
    }
    
    /**
     * String con condición SQL para filtrar los eventos que aparecerán
     * en el muro de noticias del usuario. El filtro depende del rol de usuario
     * y del tipo de evento.
     * 
     * @return type
     */
    function condicion_noticias()
    {
        $srol = $this->session->userdata('srol');   //Superrol
        $condicion = 'id = 0';
        
        $str_grupos = implode(',', $this->session->userdata('arr_grupos'));
        
        switch ($srol){
            case 'interno';
                $condicion = 'tipo_id IN (12,11,13,101,50)';
                break;
            case 'institucional';
                $condicion = "(tipo_id = 50 AND entero_1 IN (1, 3) AND institucion_id = {$this->session->userdata('institucion_id')})";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 50 AND entero_1 = 2 AND grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 11 AND grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 4 AND grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 12 AND grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 13 AND grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 21 AND institucion_id = {$this->session->userdata('institucion_id')})";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 101 AND grupo_id IN ({$str_grupos}))";
                break;
            case 'estudiante';
                $condicion = "(tipo_id = 1 AND estado = 0 AND usuario_id = {$this->session->userdata('usuario_id')})";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 2 AND grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 3 AND grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 4 AND grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 50 AND entero_1 = 1 AND institucion_id = {$this->session->userdata('institucion_id')})";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 50 AND entero_1 = 2 AND grupo_id IN ({$str_grupos}))";
                break;
        }
        
        return $condicion;
    }
    
    /**
     * Array con variables para la configuración del formulario
     * de publicaciones del muro de noticias, la configuración depende del rol de usuario
     * 
     * @return string
     */
    function config_form_publicacion()
    {
        //Variables de referencia
            $srol = $this->session->userdata('srol');
            $grupo_id = $this->session->userdata('grupo_id');
        
        //Valores por defecto, srol estudiante
            $config_form['entero_1'] = 2;
            $config_form['grupo_id'] = $grupo_id;
            $config_form['texto_alcance'] = '<i class="fa fa-users"></i> Grupo ' . $this->App_model->nombre_grupo($grupo_id);

        //Config, según súper rol
        switch ($srol) {
            case 'interno':
                $config_form['entero_1'] = 1;
                $config_form['grupo_id'] = 0;
                $config_form['texto_alcance'] = '<i class="fa fa-building"></i> Institución';
                break;
            case 'institucional':
                $config_form['entero_1'] = 1;
                $config_form['grupo_id'] = 0;
                $config_form['texto_alcance'] = '<i class="fa fa-building"></i> Institución';
                break;
        }
        
        return $config_form;
    }
    
// ACTIVIDAD Y NOTICIAS DE USUARIO
//---------------------------------------------------------------------------------------------------------
    
    /**
     * Query con los eventos de la actividad de un usuario específico ($usuario_id)
     * 
     * @return type
     */
    function noticias_usuario($usuario_id, $busqueda, $limit, $offset = 0)
    {
        
        $condicion = $this->condicion_noticias();
        $fecha_limite = $this->Pcrn->suma_fecha(date('Y-m-d'), '+1 day') . ' 23:59:59';
        
        //Filtros
        if ( $busqueda['a'] != '' ) { $this->db->where('area_id', $busqueda['a']); }        //Área
        if ( $busqueda['g'] != '' ) { $this->db->where('grupo_id', $busqueda['g']); }       //Grupo
        if ( $busqueda['tp'] != '' ) { $this->db->where('tipo_id', $busqueda['tp']); }      //Tipo evento
        
        //Usuario
        $this->db->where('usuario_id', $usuario_id);
        
        $this->db->limit($limit);
        $this->db->offset($offset);
        $this->db->where('fecha_inicio <= "' . $fecha_limite . '"');
        $this->db->where("({$condicion})");
        $this->db->order_by('fecha_inicio', 'DESC');
        $this->db->order_by('hora_inicio', 'DESC');
        
        $query = $this->db->get('evento');
        
        return $query;
    }
    
    /**
     * SIN UTILIZAR 2016-04-25
     * 
     * String con condición SQL para filtrar los eventos que aparecerán en la
     * sección actividad del perfil de un usuario. El filtro depende del rol
     * de usuario observador y del tipo de evento.
     * 
     * @return type
     */
    function condicion_noticias_usuario()
    {
        $srol = $this->session->userdata('srol');   //Superrol
        $condicion = 'id = 0';
        
        $str_grupos = implode(',', $this->session->userdata('arr_grupos'));
        
        switch ($srol)
        {
            case 'interno';
                $condicion = "id > 0";
                break;
            case 'institucional';
                $condicion = "(tipo_id = 50 AND entero_1 IN (1, 3) AND institucion_id = {$this->session->userdata('institucion_id')})";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 50 AND entero_1 = 2 AND grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 11 AND grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 12 AND grupo_id IN ({$str_grupos}))";
                break;
            case 'estudiante';
                $condicion = "(tipo_id = 1 AND usuario_id = {$this->session->userdata('usuario_id')})";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 2 AND grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 3 AND grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 22 AND grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 11)";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 12)";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 50 AND entero_1 = 1 AND institucion_id = {$this->session->userdata('institucion_id')})";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 50 AND entero_1 = 2 AND grupo_id IN ({$str_grupos}))";
                break;
        }
        
        return $condicion;
    }
    
    
// GESTIÓN DE EVENTOS DE FLIPBOOKS
//---------------------------------------------------------------------------------------------------------
    
    function guardar_apertura_flipbook($flipbook_id)
    {
        $row_flipbook = $this->Pcrn->registro_id('flipbook', $flipbook_id);
        
        $registro['tipo_id'] = 15;   //Lectura de flipbook, ver item cantegoria_id = 13
        $registro['fecha_inicio'] = date('Y-m-d');
        $registro['hora_inicio'] = date('H:i:s');
        $registro['referente_id'] = $flipbook_id;
        $registro['entero_1'] = $row_flipbook->tipo_flipbook_id;
        $registro['usuario_id'] = $this->session->userdata('usuario_id');
        $registro['institucion_id'] = $this->session->userdata('institucion_id');
        $registro['grupo_id'] = $this->session->userdata('grupo_id');
        $registro['area_id'] = $row_flipbook->area_id;
        $registro['nivel'] = $row_flipbook->nivel;
        
        //Condición adicional WHERE para guardar registro
        $condicion_add = "fecha_inicio = '{$registro['fecha_inicio']}' AND hora_inicio = '{$registro['hora_inicio']}'";
        
        $evento_id = $this->guardar_evento($registro, $condicion_add);
        
        return $evento_id;
    }
    
// GESTIÓN DE EVENTOS DE TEMAS
//---------------------------------------------------------------------------------------------------------
    
    /**
     * Temas programados para un grupo
     * @return type
     */
    function evs_temas($busqueda)
    {
        //Filtros
        if ( $busqueda['a'] != '' ) { $this->db->where('area_id', $busqueda['a']); }    //Área
        if ( $busqueda['g'] != '' ) { $this->db->where('grupo_id', $busqueda['g']); }    //Grupo
        if ( $busqueda['tp'] != '' ) { $this->db->where('tipo_id', $busqueda['tp']); }    //Tipo evento
        
        if ( $this->session->userdata('rol_id') == 6 )
        {
            //Estudiante
            $this->db->where('grupo_id', $this->session->userdata('grupo_id'));
        } else {
            //Los que corresponden a sus grupos
            $str_grupos = implode(',', $this->session->userdata('arr_grupos'));
            $this->db->where("grupo_id IN ($str_grupos)");
        }
        
        $this->db->select('id, nombre_evento, fecha_inicio, referente_2_id, entero_1, referente_id');
        $this->db->where('tipo_id', 2); //Tipo 2 => programacion de temas
        $query = $this->db->get('evento');
        
        return $query;
    }
    
    function programar_tema($datos)
    {
        $row_tema = $this->Pcrn->registro_id('tema', $datos['tema_id']);
        $row_grupo = $this->Pcrn->registro_id('grupo', $datos['grupo_id']);
        
        $registro['nombre_evento'] = $row_tema->nombre_tema;
        $registro['tipo_id'] = 2;                       //Programación de tema
        $registro['referente_id'] = $row_tema->id;      //Id del tema
        $registro['referente_2_id'] = $datos['flipbook_id'];
        $registro['entero_1'] = $datos['num_pagina'];   //Página en la que está el tema dentro del flipbook
        $registro['fecha_inicio'] = $datos['fecha_inicio'];
        $registro['grupo_id'] = $row_grupo->id;
        $registro['institucion_id'] = $row_grupo->institucion_id;
        $registro['area_id'] = $row_tema->area_id;
        $registro['nivel'] = $row_grupo->nivel;
        
        //Condición adicional WHERE para guardar registro
        $condicion_add = "grupo_id = {$registro['grupo_id']}";
        
        $evento_id = $this->guardar_evento($registro, $condicion_add);
        
        //Programar quices del tema
        $this->guardar_ev_quiz($evento_id);
        
        return $evento_id;
    }
    
    function guardar_lectura_tema($flipbook_id, $tema_id)
    {
        $row_flipbook = $this->Pcrn->registro_id('flipbook', $flipbook_id);
        
        $registro['tipo_id'] = 12;   //Lectura de tema, ver item cantegoria_id = 13
        $registro['fecha_inicio'] = date('Y-m-d');
        $registro['hora_inicio'] = date('H:i:s');
        $registro['referente_id'] = $tema_id;
        $registro['referente_2_id'] = $flipbook_id;
        $registro['usuario_id'] = $this->session->userdata('usuario_id');
        $registro['institucion_id'] = $this->session->userdata('institucion_id');
        $registro['grupo_id'] = $this->session->userdata('grupo_id');
        $registro['area_id'] = $row_flipbook->area_id;
        $registro['nivel'] = $row_flipbook->nivel;
        
        //Condición adicional WHERE para guardar registro
        $condicion_add = "fecha_inicio = '{$registro['fecha_inicio']}' AND hora_inicio = '{$registro['hora_inicio']}'";
        
        $evento_id = $this->guardar_evento($registro, $condicion_add);
        
        return $evento_id;
    }
    
// GESTIÓN DE EVENTOS DE QUICES
//---------------------------------------------------------------------------------------------------------
    
    function guardar_ev_quiz($evento_id)
    {
        $this->load->model('Tema_model');
        
        $row_evento = $this->Pcrn->registro_id('evento', $evento_id);
        
        //Registro, valores generales
        $registro['tipo_id'] = 3;   //Programación de quiz, ver item cantegoria_id = 13
        $registro['referente_2_id'] = $row_evento->id;
        $registro['fecha_inicio'] = $row_evento->fecha_inicio;
        $registro['hora_inicio'] = $row_evento->hora_inicio;
        $registro['institucion_id'] = $row_evento->institucion_id;
        $registro['grupo_id'] = $row_evento->grupo_id;
        $registro['area_id'] = $row_evento->area_id;
        $registro['nivel'] = $row_evento->nivel;
        
        $condicion_add = "referente_2_id = {$registro['referente_2_id']}";
        
        $quices = $this->Tema_model->quices($row_evento->referente_id);
        
        foreach ($quices->result() as $row_quiz) {
            $registro['referente_id'] = $row_quiz->id;
            
            $evento_id = $this->guardar_evento($registro, $condicion_add);
        }
        
    }
    
    /**
     * Quices programados para un grupo
     * @return type
     */
    function evs_quices($busqueda)
    {
        //Filtros
        if ( $busqueda['a'] != '' ) { $this->db->where('evento.area_id', $busqueda['a']); }    //Área
        if ( $busqueda['g'] != '' ) { $this->db->where('evento.grupo_id', $busqueda['g']); }    //Grupo
        if ( $busqueda['tp'] != '' ) { $this->db->where('evento.tipo_id', $busqueda['tp']); }    //Tipo evento
        
        if ( $this->session->userdata('srol') == 'estudiante' ) {
            //Estudiante
            $this->db->where('grupo_id', $this->session->userdata('grupo_id'));
        } else {
            //Otros tipos de usuario
            $this->db->where('c_usuario_id', $this->session->userdata('usuario_id'));
        }
        
        $this->db->select('evento.*, quiz.nombre_quiz');
        $this->db->where('tipo_id', 3); //Tipo 3 => programacion de quices
        $this->db->join('quiz', 'quiz.id = evento.referente_id');
        $query = $this->db->get('evento');
        
        return $query;
    }
    
    /**
     * Agrega evento de respuesta de quiz
     * se refiere respuesta de un quiz por parte de un estudiante
     * 
     * @param type $quiz_id
     * @return type
     */
    function guardar_inicia_quiz($quiz_id, $ua_id)
    {
        $evento_id = 0;
        $row_quiz = $this->Pcrn->registro_id('quiz', $quiz_id);
        
        if ( ! is_null($row_quiz) )
        {
            $registro['fecha_inicio'] = date('Y-m-d');
            $registro['hora_inicio'] = date('H:i:s');
            $registro['tipo_id'] = 13;   //Respuesta de quiz
            $registro['referente_id'] = $ua_id;
            $registro['referente_2_id'] = $quiz_id;
            $registro['entero_1'] = 0;  //Cantidad de intentos
            $registro['estado'] = 0;    //Incorrecto
            $registro['usuario_id'] = $this->session->userdata('usuario_id');
            $registro['institucion_id'] = $this->session->userdata('institucion_id');
            $registro['grupo_id'] = $this->session->userdata('grupo_id');
            $registro['area_id'] = $row_quiz->area_id;
            $registro['nivel'] = $row_quiz->nivel;
            
            $condicion_add = "usuario_id = {$this->session->userdata('usuario_id')}";   //Agregado 2018-08-23

            $evento_id = $this->guardar_evento($registro, $condicion_add);
        }
        
        return $evento_id;
    }
    
    /**
     * Edita respuesta de quiz
     * guardar_fin_quiz
     * se refiere a la finalización de respuesta de un quiz por parte de un estudiante
     * 
     * @param type $ua_id
     * @return type
     */
    function guardar_fin_quiz($ua_id)
    {
        $evento_id = 0;
        $row_ua = $this->Pcrn->registro_id('usuario_asignacion', $ua_id);
        
        $condicion = "tipo_id = 13 AND referente_id = {$ua_id} AND usuario_id = {$this->session->userdata('usuario_id')}";
        $cant_intentos = $this->Pcrn->campo('evento', $condicion, 'entero_1');
        //$row_evento = $this->Pcrn->registro('evento', $condicion);
        
        if ( ! is_null($cant_intentos) )
        {
            $registro['tipo_id'] = 13;              //Respuesta de quiz
            $registro['referente_id'] = $ua_id;     //usuario_asignacion.id
            $registro['fecha_fin'] = date('Y-m-d');
            $registro['hora_fin'] = date('H:i:s');
            $registro['estado'] = $row_ua->estado_int;           //Correcto o incorrecto
            $registro['entero_1'] = $cant_intentos + 1;   //Cantidad de intentos
            
            $condicion_add = "usuario_id = {$this->session->userdata('usuario_id')}";

            $evento_id = $this->guardar_evento($registro, $condicion_add);
        }
        
        return $evento_id;
        
    }
    
// GESTIÓN DE EVENTOS DE CUESTIONARIOS
//---------------------------------------------------------------------------------------------------------
    
    /**
     * Cuestionarios programados para el usuario
     * @return type
     */
    function evs_cuestionarios_ant($busqueda)
    {
        
        //Filtros
        if ( $busqueda['a'] != '' ) { $this->db->where('evento.area_id', $busqueda['a']); }    //Área
        if ( $busqueda['g'] != '' ) { $this->db->where('grupo_id', $busqueda['g']); }    //Grupo
        if ( $busqueda['tp'] != '' ) { $this->db->where('evento.tipo_id', $busqueda['tp']); }    //Tipo evento
        
        //Consulta
        $this->db->select('*');
        $this->db->where('usuario_id', $this->session->userdata('usuario_id'));
        $this->db->where('evento.tipo_id', 1); //Tipo 1 => asignación de cuestionario
        $this->db->where('estado', 0); //Estado 0  => sin responder
        $this->db->join('cuestionario', 'cuestionario.id = evento.referente_2_id');
        $query = $this->db->get('evento');
        
        return $query;
    }
    
    /**
     * 2018-09-22
     * Cuestionarios programados para el usuario
     * @return type
     */
    function evs_cuestionarios($busqueda)
    {
        
        //Filtros
        if ( $busqueda['a'] != '' ) { $this->db->where('evento.area_id', $busqueda['a']); }    //Área
        if ( $busqueda['g'] != '' ) { $this->db->where('grupo_id', $busqueda['g']); }    //Grupo
        if ( $busqueda['tp'] != '' ) { $this->db->where('evento.tipo_id', $busqueda['tp']); }    //Tipo evento
        
        //Consulta
        $this->db->select('evento.id, nombre_cuestionario, fecha_inicio, fecha_fin');
        $this->db->where('grupo_id', $this->session->userdata('grupo_id'));
        $this->db->where('evento.tipo_id', 22); //Tipo 1 => asignación de cuestionario a grupo
        $this->db->join('cuestionario', 'cuestionario.id = evento.referente_id');
        $query = $this->db->get('evento');
        
        return $query;
    }
    
    /**
     * Cuestionarios programados por el usuario
     * Profesores
     * 
     * @return type
     */
    function evs_cuestionarios_prf($busqueda)
    {
        //Filtros
        if ( $busqueda['a'] != '' ) { $this->db->where('evento.area_id', $busqueda['a']); }    //Área
        if ( $busqueda['g'] != '' ) { $this->db->where('grupo_id', $busqueda['g']); }    //Grupo
        if ( $busqueda['tp'] != '' ) { $this->db->where('evento.tipo_id', $busqueda['tp']); }    //Tipo evento
        
        $this->db->select('nombre_evento, fecha_inicio, fecha_fin, referente_2_id, institucion_id, grupo_id');
        $this->db->where('c_usuario_id', $this->session->userdata('usuario_id'));
        $this->db->where('tipo_id', 1); //Tipo 1 => asignación de cuestionario
        $this->db->group_by('nombre_evento, fecha_inicio, fecha_fin, referente_2_id, institucion_id, grupo_id');
        $query = $this->db->get('evento');
        
        return $query;
    }
    
    /**
     * Agrega registro en la tabla evento,
     * se refiere a la asignación de cuestionarios a estudiantes
     * 
     * @param type $registro_uc
     * @param type $uc_id
     * @return type
     */
    function guardar_asignar_ctn($row_uc)
    {
        $evento_id = 0;
        $row_cuestionario = $this->Pcrn->registro_id('cuestionario', $row_uc->cuestionario_id);
        
        if ( ! is_null($row_cuestionario) ) 
        {
            $registro['fecha_inicio'] = substr($row_uc->fecha_inicio,0,10);
            $registro['hora_inicio'] = substr($row_uc->fecha_inicio,11, 8);
            $registro['fecha_fin'] = substr($row_uc->fecha_fin,0,10);
            $registro['hora_fin'] = substr($row_uc->fecha_inicio,11, 8);
            $registro['tipo_id'] = 1;   //Asignación de cuestionario
            $registro['referente_id'] = $row_uc->id;
            $registro['referente_2_id'] = $row_uc->cuestionario_id;
            $registro['estado'] = $row_uc->respondido;
            $registro['usuario_id'] = $row_uc->usuario_id;
            $registro['institucion_id'] = $row_uc->institucion_id;
            $registro['grupo_id'] = $row_uc->grupo_id;
            $registro['area_id'] = $row_cuestionario->area_id;
            $registro['nivel'] = $row_cuestionario->nivel;

            $evento_id = $this->guardar_evento($registro);   
        }
        
        return $evento_id;
    }
    
    /**
     * Agrega o edita respuesta de cuestionario
     * se refiere respuesta de un cuestionario por parte de un estudiante
     * 
     * @param type $row_uc
     * @return type
     */
    function guardar_inicia_ctn($row_uc)
    {
        $evento_id = 0;
        $row_cuestionario = $this->Pcrn->registro_id('cuestionario', $row_uc->cuestionario_id);
        
        if ( ! is_null($row_cuestionario) )
        {
            $registro['fecha_inicio'] = date('Y-m-d');
            $registro['hora_inicio'] = date('H:i:s');
            $registro['tipo_id'] = 11;   //Respuesta de cuestionario
            $registro['referente_id'] = $row_uc->id;
            $registro['referente_2_id'] = $row_uc->cuestionario_id;
            $registro['entero_1'] = $row_cuestionario->creado_usuario_id;
            $registro['estado'] = 0;
            $registro['usuario_id'] = $this->session->userdata('usuario_id');
            $registro['institucion_id'] = $row_uc->institucion_id;
            $registro['grupo_id'] = $row_uc->grupo_id;
            $registro['area_id'] = $row_cuestionario->area_id;
            $registro['nivel'] = $row_cuestionario->nivel;

            $evento_id = $this->guardar_evento($registro);   
        }
        
        return $evento_id;
    }
    
    /**
     * Agrega o edita respuesta de cuestionario
     * guardar_fin_cuestionario
     * se refiere a la finalización de respuesta de un cuestionario por parte de un estudiante
     * 
     * @param type $row_uc
     * @return type
     */
    function guardar_fin_ctn($row_uc)
    {

        $registro['tipo_id'] = 11;
        $registro['referente_id'] = $row_uc->id;
        $registro['fecha_fin'] = date('Y-m-d');
        $registro['hora_fin'] = date('H:i:s');
        $registro['tipo_id'] = 11;   //Respuesta de cuestionario
        $registro['estado'] = 1;    //Finalizado

        $evento_id = $this->guardar_evento($registro);
        
        return $evento_id;
        
    }
    
    /**
     * Crear eventos de cuestionarios ya existentes en la tabla usuario_cuestionario
     */
    function crear_ev_ctn_existentes()
    {
        
        $cant_guardados = 0;
        
        $this->db->where('id NOT IN (SELECT referente_id FROM evento WHERE tipo_id = 1)');
        $this->db->where("fecha_inicio >= '2016-03-01'");
        $this->db->where('respondido', 0);  //Sin responder
        $asignaciones = $this->db->get('usuario_cuestionario');
        
        foreach ( $asignaciones->result() as $row_uc ) {
            $evento_id = $this->guardar_asignar_ctn($row_uc);
            if ( $evento_id > 0 ) { $cant_guardados++; }
            if ( $cant_guardados >= 1200 ) { break; }
        }
        
        $resultado['cant_totales'] = $asignaciones->num_rows();
        $resultado['cant_guardados'] = $cant_guardados;
        $resultado['cant_pendientes'] = $asignaciones->num_rows() - $cant_guardados;
        
        return $resultado;
    }
    
    /**
     * Reinicia una asignación de un cuestionario para la tabla evento
     * La asignación queda en estado = 0, sin responder y se elimina
     * el evento de respuesta de cuestionario.
     * 
     * @param type $uc_id
     */
    function reiniciar_ctn($uc_id)
    {
        //Editar asignación, tipo 1
            $reg_asignacion['tipo_id'] = 1;
            $reg_asignacion['referente_id'] = $uc_id;
            $reg_asignacion['estado'] = 0;  //Sin responder
            $this->guardar_evento($reg_asignacion);
            
        //Eliminar respuesta de cuestionario, tipo 11
            $this->db->where('tipo_id', 11);
            $this->db->where('referente_id', $uc_id);
            $this->db->delete('evento');
    }
    
    /**
     * Crea un registro del evento tras la creación de un cuestionario por parte
     * de un usuario
     * 
     * @param type $cuestionario_id
     * @return type
     */
    function guardar_ev_crea_ctn($cuestionario_id)
    {
        $row_cuestionario = $this->Pcrn->registro_id('cuestionario', $cuestionario_id);
        $row_usuario = $this->Pcrn->registro_id('usuario', $row_cuestionario->creado_usuario_id);
        
        $registro['tipo_id'] = 21;  //Creación de cuestionario, item.ite_interno cat 13
        $registro['referente_id'] = $cuestionario_id;
        $registro['fecha_inicio'] = date('Y-m-d');
        $registro['hora_inicio'] = date('H:i:s');
        $registro['usuario_id'] = $row_cuestionario->creado_usuario_id;
        $registro['institucion_id'] = $row_usuario->institucion_id;
        $registro['area_id'] = $row_cuestionario->area_id;
        $registro['nivel'] = $row_cuestionario->nivel;
        
        $evento_id = $this->guardar_evento($registro);
                
        return $evento_id;
    }

    
// EVENTOS PUBLICACIONES
//---------------------------------------------------------------------------------------------------------
    
    function guardar_ev_publicacion($post_id)
    {
        $row_post = $this->Pcrn->registro_id('post', $post_id);
        
        $registro['tipo_id'] = 50;    //Publicación, ver item categoria_id = 13, tipos de evento
        $registro['referente_id'] = $post_id;
        $registro['entero_1'] = $this->input->post('entero_1');    //Tipo publicación, ver item categoria_id = 12, tipo de publicación muro
        $registro['fecha_inicio'] = $this->Pcrn->fecha_formato($row_post->creado, 'Y-m-d');
        $registro['hora_inicio'] = $this->Pcrn->fecha_formato($row_post->creado, 'H:i:s');
        $registro['institucion_id'] = $this->session->userdata('institucion_id');
        $registro['grupo_id'] = $this->input->post('grupo_id');
        $registro['usuario_id'] = $this->session->userdata('usuario_id');

        $evento_id = $this->guardar_evento($registro);
        
        return $evento_id;
        
        
    }
    
// GESTIÓN DE EVENTOS LINKS
//---------------------------------------------------------------------------------------------------------
    
    /**
     * Temas programados para un grupo
     * @return type
     */
    function evs_links($busqueda)
    {
        //Filtros
        if ( $busqueda['a'] != '' ) { $this->db->where('area_id', $busqueda['a']); }    //Área
        if ( $busqueda['g'] != '' ) { $this->db->where('grupo_id', $busqueda['g']); }    //Grupo
        if ( $busqueda['tp'] != '' ) { $this->db->where('tipo_id', $busqueda['tp']); }    //Tipo evento
        
        if ( $this->session->userdata('rol_id') == 6 ) {
            //Estudiante
            $this->db->where('grupo_id', $this->session->userdata('grupo_id'));
        } else {
            //Los que corresponden a sus grupos
            $str_grupos = implode(',', $this->session->userdata('arr_grupos'));
            $this->db->where("grupo_id IN ($str_grupos)");
        }
        
        $this->db->select('*');
        $this->db->where('tipo_id', 4); //Tipo 4 => programacion de links
        $query = $this->db->get('evento');
        
        return $query;
    }
    
// GESTIÓN DE EVENTO LOGIN
//-----------------------------------------------------------------------------
    /**
     * Guarda el evento de login en la tabla evento.
     * Act. 2018-08-18
     * 
     * @return type
     */
    function guardar_ev_login()
    {
        $row_usuario = $this->Pcrn->registro_id('usuario', $this->session->userdata('usuario_id'));
        $nivel = $this->Pcrn->campo_id('grupo', $row_usuario->grupo_id, 'nivel');
        
        //Registro, valores generales
            $registro['tipo_id'] = 101;   //Login de usuario, ver item cantegoria_id = 13
            $registro['fecha_inicio'] = date('Y-m-d');
            $registro['hora_inicio'] = date('H:i:s');
            $registro['referente_id'] = $row_usuario->id;
            $registro['usuario_id'] = $row_usuario->id;
            $registro['institucion_id'] = $this->session->userdata('institucion_id');
            $registro['grupo_id'] = $this->session->userdata('grupo_id');
            $registro['nivel'] = $nivel;

            $condicion_add = 'id = 0';  //Se pone una condición adicional incumplible, para que siempre agregue el registro
            $evento_id = $this->guardar_evento($registro, $condicion_add);
            
        //Agregar evento_id a los datos de sesión
            $this->session->set_userdata('login_id', $evento_id);
        
        return $evento_id;
        
    }
    
}