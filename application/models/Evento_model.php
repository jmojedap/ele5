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

// EXPLORE FUNCTIONS - users/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($filters, $num_page)
    {
        //Data inicial, de la tabla
            $data = $this->get($filters, $num_page);
        
        //Elemento de exploración
            $data['controller'] = 'eventos';                      //Nombre del controlador
            $data['cf'] = 'eventos/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'eventos/explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Eventos';
            $data['head_subtitle'] = $data['search_num_rows'];
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        return $data;
    }

    /**
     * Array con listado de eventos, filtrados por búsqueda y num página, más datos adicionales sobre
     * la búsqueda, filtros aplicados, total resultados, página máxima.
     * 2020-08-07
     */
    function get($filters, $num_page, $per_page = 12)
    {
        //Referencia
            $offset = ($num_page - 1) * $per_page;      //Número de la página de datos que se está consultado

        //Búsqueda y Resultados
            $elements = $this->search($filters, $per_page, $offset);    //Resultados para página
        
        //Cargar datos
            $data['filters'] = $filters;
            //$data['list'] = $this->list($filters, $per_page, $offset);    //Resultados para página
            $data['list'] = $elements->result();
            $data['str_filters'] = $this->Search_model->str_filters();      //String con filtros en formato GET de URL
            $data['search_num_rows'] = $this->search_num_rows($data['filters']);
            $data['max_page'] = ceil($this->pml->if_zero($data['search_num_rows'],1) / $per_page);   //Cantidad de páginas

        return $data;
    }
    
    /**
     * Query de eventos, filtrados según búsqueda, limitados por página
     * 2020-08-01
     */
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        //Construir consulta
            $this->db->select('evento.*, CONCAT((fecha_inicio), (" "), (hora_inicio)) AS inicio, usuario.username, institucion.nombre_institucion');
            $this->db->join('usuario', 'usuario.id = evento.creador_id');
            $this->db->join('institucion', 'evento.institucion_id = institucion.id', 'left');
            
            
        //Orden
            if ( $filters['o'] != '' )
            {
                $order_type = $this->pml->if_strlen($filters['ot'], 'ASC');
                $this->db->order_by($filters['o'], $order_type);
            } else {
                $this->db->order_by('evento.id', 'DESC');
            }
            
        //Filtros
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
            $query = $this->db->get('evento', $per_page, $offset); //Resultados por página
        
        return $query;
    }

    /**
     * String con condición WHERE SQL para filtrar users
     * 2020-08-01
     */
    function search_condition($filters)
    {
        $condition = NULL;

        $condition .= $this->role_filter() . ' AND ';

        //q words condition
        $words_condition = $this->Search_model->words_condition($filters['q'], array('nombre_evento', 'descripcion', 'url'));
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Otros filtros
        if ( $filters['tp'] != '' ) { $condition .= "tipo_id = {$filters['tp']} AND "; }
        if ( $filters['fi'] != '' ) { $condition .= "creado >= '{$filters['fi']} 00:00:00' AND "; }
        if ( $filters['ff'] != '' ) { $condition .= "creado <= '{$filters['ff']} 23:59:59' AND "; }
        if ( $filters['i'] != '' ) { $condition .= "evento.institucion_id = '{$filters['i']}' AND "; }
        if ( $filters['g'] != '' ) { $condition .= "evento.grupo_id = '{$filters['g']}' AND "; }
        
        //Quitar cadena final de ' AND '
        if ( strlen($condition) > 0 ) { $condition = substr($condition, 0, -5);}
        
        return $condition;
    }

    /**
     * Array Listado elemento resultado de la búsqueda (filtros).
     * 2020-06-19
     */
    /*function list($filters, $per_page = NULL, $offset = NULL)
    {
        $query = $this->search($filters, $per_page, $offset);
        $list = array();

        foreach ($query->result() as $row)
        {
            
            $list[] = $row;
        }

        return $list;
    }*/
    
    /**
     * Devuelve la cantidad de registros encontrados en la tabla con los filtros
     * establecidos en la búsqueda
     */
    function search_num_rows($filters)
    {
        /*$this->db->select('id');
        $search_condition = $this->search_condition($filters);
        if ( $search_condition ) { $this->db->where($search_condition);}
        $query = $this->db->get('evento'); //Para calcular el total de resultados

        return $query->num_rows();*/
        return 5000000;
    }
    
    /**
     * Devuelve segmento SQL, para filtrar listado de usuarios según el rol del usuario en sesión
     * 2020-08-01
     */
    function role_filter()
    {
        $role = $this->session->userdata('role');
        $condition = 'evento.id = 0';  //Valor por defecto, ningún user, se obtendrían cero user.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los user
            $condition = 'evento.id > 0';
        }
        
        return $condition;
    }
    
    /**
     * Array con options para ordenar el listado de user en la vista de
     * exploración
     * 
     */
    function order_options()
    {
        $order_options = array(
            '' => '[ Ordenar por ]',
            'id' => 'ID Evento',
            'nombre_evento' => 'Nombre',
            'fecha_inicio' => 'Fecha inicio',
        );
        
        return $order_options;
    }

// Separador
//-----------------------------------------------------------------------------
    
    /**
     * Determina si un usuario tiene el permiso para eliminar un registro de evento
     */
    function eliminable($evento_id)
    {   
        $eliminable = FALSE;
        $row_evento = $this->Db_model->row_id('evento', $evento_id);
        
        //El usuario creó el evento
        if ( $row_evento->creador_id == $this->session->userdata('user_id') ) { $eliminable = TRUE; }
        
        //El usuario es aministrador
        if ( $this->session->userdata('rol_id') <= 1 ) { $eliminable = TRUE; }
            
        return $eliminable;
    }
    
    /**
     * Elimina un registro de evento y sus registros relacionados en otras tablas
     */
    function eliminar($evento_id)
    {
        $qty_deleted = 0;
        $eliminable = $this->eliminable($evento_id);
        
        if ( $eliminable )
        {
            //Tablas relacionadas
                $this->db->where('tipo_id', 3); //Programación de quices
                $this->db->where('referente_2_id', $evento_id);
                $this->db->delete('evento');
        
            //Tabla
                $this->db->where('id', $evento_id);
                $this->db->delete('evento');
                
            $qty_deleted = $this->db->affected_rows();
        }
            
        return $qty_deleted;
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
        $arr_row['estado'] = $estado;
        
        $this->db->where('tipo_id', $tipo_id);
        $this->db->where('referente_id', $referente_id);
        $this->db->update('evento', $arr_row);
    }
    
    /**
     * Guarda un registro en la tabla evento
     * 2020-10-21
     */
    function guardar_evento($arr_row, $condicion_add = NULL)
    {
        //Condición para identificar el registro del evento
            $condicion = "tipo_id = {$arr_row['tipo_id']} AND referente_id = {$arr_row['referente_id']}";
            if ( ! is_null($condicion_add) ) $condicion .= " AND " . $condicion_add;
        
            $evento_id = $this->Pcrn->existe('evento', $condicion);
        
        //Datos adicionales del registro
            $arr_row['editado'] = date('Y-m-d H:i:s');
        
        //Guardar el evento
        if ( $evento_id == 0 ) 
        {
            //No existe, se inserta
            $arr_row['periodo_id'] = intval(date('Ym'));
            $arr_row['creado'] = date('Y-m-d H:i:s');
            $arr_row['creador_id'] = $this->Pcrn->si_nulo($this->session->userdata('user_id'), 1001);   //1001, ELE Automático
            $arr_row['ip_address'] = $this->input->ip_address();
            
            $this->db->insert('evento', $arr_row);
            $evento_id = $this->db->insert_id();
        } else {
            //Ya existe, editar
            $this->db->where('id', $evento_id);
            $this->db->update('evento', $arr_row);
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
        //$this->db->where('fecha_inicio <= "' . $fecha_limite . '"');
        $this->db->where($condicion);
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
                $condicion = 'tipo_id IN (12,11,13,101,50,107)';
                break;
            case 'institucional';
                $condicion = "institucion_id = {$this->session->userdata('institucion_id')} AND ";
                $condicion .= '(';
                $condicion .= "(tipo_id = 50 AND entero_1 IN (1, 3) AND institucion_id = {$this->session->userdata('institucion_id')})";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 50 AND entero_1 = 2 AND grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id IN (4,11,12,13,101,107) AND grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 21 AND institucion_id = {$this->session->userdata('institucion_id')})";
                $condicion .= ')';
                break;
            case 'estudiante';
                $condicion = "institucion_id = {$this->session->userdata('institucion_id')} AND ";
                $condicion .= '(';
                $condicion .= "(tipo_id = 1 AND estado = 0 AND usuario_id = {$this->session->userdata('user_id')})";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id IN (2,3,4) AND grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';                
                $condicion .= "(tipo_id = 50 AND entero_1 = 1)";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 50 AND entero_1 = 2 AND grupo_id IN ({$str_grupos}))";
                $condicion .= ')';
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
     * 2020-12-30
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
                $condicion = "(tipo_id = 1 AND usuario_id = {$this->session->userdata('user_id')})";
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
                $condicion .= "(tipo_id = 107)";
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
        
        $arr_row['tipo_id'] = 15;   //Lectura de flipbook, ver item cantegoria_id = 13
        $arr_row['fecha_inicio'] = date('Y-m-d');
        $arr_row['hora_inicio'] = date('H:i:s');
        $arr_row['referente_id'] = $flipbook_id;
        $arr_row['entero_1'] = $row_flipbook->tipo_flipbook_id;
        $arr_row['usuario_id'] = $this->session->userdata('user_id');
        $arr_row['institucion_id'] = $this->session->userdata('institucion_id');
        $arr_row['grupo_id'] = $this->session->userdata('grupo_id');
        $arr_row['area_id'] = $row_flipbook->area_id;
        $arr_row['nivel'] = $row_flipbook->nivel;
        
        //Condición adicional WHERE para guardar registro
        $condicion_add = "fecha_inicio = '{$arr_row['fecha_inicio']}' AND hora_inicio = '{$arr_row['hora_inicio']}'";
        
        $evento_id = $this->guardar_evento($arr_row, $condicion_add);
        
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
        
        $arr_row['nombre_evento'] = $row_tema->nombre_tema;
        $arr_row['tipo_id'] = 2;                       //Programación de tema
        $arr_row['referente_id'] = $row_tema->id;      //Id del tema
        $arr_row['referente_2_id'] = $datos['flipbook_id'];
        $arr_row['entero_1'] = $datos['num_pagina'];   //Página en la que está el tema dentro del flipbook
        $arr_row['fecha_inicio'] = $datos['fecha_inicio'];
        $arr_row['grupo_id'] = $row_grupo->id;
        $arr_row['institucion_id'] = $row_grupo->institucion_id;
        $arr_row['area_id'] = $row_tema->area_id;
        $arr_row['nivel'] = $row_grupo->nivel;
        
        //Condición adicional WHERE para guardar registro
        $condicion_add = "grupo_id = {$arr_row['grupo_id']}";
        
        $evento_id = $this->guardar_evento($arr_row, $condicion_add);
        
        //Programar quices del tema
        $this->guardar_ev_quiz($evento_id);
        
        return $evento_id;
    }
    
    function guardar_lectura_tema($flipbook_id, $tema_id)
    {
        $row_flipbook = $this->Pcrn->registro_id('flipbook', $flipbook_id);
        
        $arr_row['tipo_id'] = 12;   //Lectura de tema, ver item cantegoria_id = 13
        $arr_row['fecha_inicio'] = date('Y-m-d');
        $arr_row['hora_inicio'] = date('H:i:s');
        $arr_row['referente_id'] = $tema_id;
        $arr_row['referente_2_id'] = $flipbook_id;
        $arr_row['usuario_id'] = $this->session->userdata('user_id');
        $arr_row['institucion_id'] = $this->session->userdata('institucion_id');
        $arr_row['grupo_id'] = $this->session->userdata('grupo_id');
        $arr_row['area_id'] = $row_flipbook->area_id;
        $arr_row['nivel'] = $row_flipbook->nivel;
        
        //Condición adicional WHERE para guardar registro
        $condicion_add = "fecha_inicio = '{$arr_row['fecha_inicio']}' AND hora_inicio = '{$arr_row['hora_inicio']}'";
        
        $evento_id = $this->guardar_evento($arr_row, $condicion_add);
        
        return $evento_id;
    }
    
// GESTIÓN DE EVENTOS DE QUICES
//---------------------------------------------------------------------------------------------------------
    
    function guardar_ev_quiz($evento_id)
    {
        $this->load->model('Tema_model');
        
        $row_evento = $this->Pcrn->registro_id('evento', $evento_id);
        
        //Registro, valores generales
        $arr_row['tipo_id'] = 3;   //Programación de quiz, ver item cantegoria_id = 13
        $arr_row['referente_2_id'] = $row_evento->id;
        $arr_row['fecha_inicio'] = $row_evento->fecha_inicio;
        $arr_row['hora_inicio'] = $row_evento->hora_inicio;
        $arr_row['institucion_id'] = $row_evento->institucion_id;
        $arr_row['grupo_id'] = $row_evento->grupo_id;
        $arr_row['area_id'] = $row_evento->area_id;
        $arr_row['nivel'] = $row_evento->nivel;
        
        $condicion_add = "referente_2_id = {$arr_row['referente_2_id']}";
        
        $quices = $this->Tema_model->quices($row_evento->referente_id);
        
        foreach ($quices->result() as $row_quiz) {
            $arr_row['referente_id'] = $row_quiz->id;
            
            $evento_id = $this->guardar_evento($arr_row, $condicion_add);
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
            $this->db->where('creador_id', $this->session->userdata('user_id'));
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
            $arr_row['fecha_inicio'] = date('Y-m-d');
            $arr_row['hora_inicio'] = date('H:i:s');
            $arr_row['tipo_id'] = 13;   //Respuesta de quiz
            $arr_row['referente_id'] = $ua_id;
            $arr_row['referente_2_id'] = $quiz_id;
            $arr_row['entero_1'] = 0;  //Cantidad de intentos
            $arr_row['estado'] = 0;    //Incorrecto
            $arr_row['usuario_id'] = $this->session->userdata('user_id');
            $arr_row['institucion_id'] = $this->session->userdata('institucion_id');
            $arr_row['grupo_id'] = $this->session->userdata('grupo_id');
            $arr_row['area_id'] = $row_quiz->area_id;
            $arr_row['nivel'] = $row_quiz->nivel;
            
            $condicion_add = "usuario_id = {$this->session->userdata('user_id')}";   //Agregado 2018-08-23

            $evento_id = $this->guardar_evento($arr_row, $condicion_add);
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
        
        $condicion = "tipo_id = 13 AND referente_id = {$ua_id} AND usuario_id = {$this->session->userdata('user_id')}";
        $cant_intentos = $this->Pcrn->campo('evento', $condicion, 'entero_1');
        //$row_evento = $this->Pcrn->registro('evento', $condicion);
        
        if ( ! is_null($cant_intentos) )
        {
            $arr_row['tipo_id'] = 13;              //Respuesta de quiz
            $arr_row['referente_id'] = $ua_id;     //usuario_asignacion.id
            $arr_row['fecha_fin'] = date('Y-m-d');
            $arr_row['hora_fin'] = date('H:i:s');
            $arr_row['estado'] = $row_ua->estado_int;           //Correcto o incorrecto
            $arr_row['entero_1'] = $cant_intentos + 1;   //Cantidad de intentos
            
            $condicion_add = "usuario_id = {$this->session->userdata('user_id')}";

            $evento_id = $this->guardar_evento($arr_row, $condicion_add);
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
        //$this->db->select('evento.id, nombre_cuestionario, fecha_inicio, fecha_fin');
        $this->db->where('usuario_id', $this->session->userdata('user_id'));
        $this->db->where('evento.tipo_id', 1);  //Tipo 1 => asignación de cuestionario
        $this->db->where('estado', 1);          //Estado 1  => sin responder
        $this->db->join('cuestionario', 'cuestionario.id = evento.referente_2_id');
        $query = $this->db->get('evento');
        
        return $query;
    }
    
    /**
     * 2018-09-22 (EN DESARROLLO)
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
     */
    function evs_cuestionarios_prf($busqueda)
    {
        //Filtros
        if ( $busqueda['a'] != '' ) { $this->db->where('evento.area_id', $busqueda['a']); }    //Área
        if ( $busqueda['g'] != '' ) { $this->db->where('grupo_id', $busqueda['g']); }    //Grupo
        if ( $busqueda['tp'] != '' ) { $this->db->where('evento.tipo_id', $busqueda['tp']); }    //Tipo evento
        
        $this->db->select('MAX(id) AS max_evento_id, nombre_evento, fecha_inicio, fecha_fin, referente_id, institucion_id, grupo_id');
        $this->db->where('creador_id', $this->session->userdata('user_id'));
        $this->db->where('tipo_id', 22); //Tipo 2 => asignación de cuestionario a grupo
        $this->db->group_by('nombre_evento, fecha_inicio, fecha_fin, referente_2_id, institucion_id, grupo_id');
        $query = $this->db->get('evento');
        
        return $query;
    }
    
    /**
     * Agrega registro en la tabla evento,
     * se refiere a la asignación de cuestionarios a estudiantes
     * 
     * @param type $arr_row_uc
     * @param type $uc_id
     * @return type
     */
    function guardar_asignar_ctn($row_uc)
    {
        $evento_id = 0;
        $row_cuestionario = $this->Pcrn->registro_id('cuestionario', $row_uc->cuestionario_id);
        
        if ( ! is_null($row_cuestionario) ) 
        {
            $arr_row['fecha_inicio'] = substr($row_uc->fecha_inicio,0,10);
            $arr_row['hora_inicio'] = substr($row_uc->fecha_inicio,11, 8);
            $arr_row['fecha_fin'] = substr($row_uc->fecha_fin,0,10);
            $arr_row['hora_fin'] = substr($row_uc->fecha_inicio,11, 8);
            $arr_row['tipo_id'] = 1;   //Asignación de cuestionario
            $arr_row['referente_id'] = $row_uc->id;
            $arr_row['referente_2_id'] = $row_uc->cuestionario_id;
            $arr_row['estado'] = $row_uc->estado;
            $arr_row['usuario_id'] = $row_uc->usuario_id;
            $arr_row['institucion_id'] = $row_uc->institucion_id;
            $arr_row['grupo_id'] = $row_uc->grupo_id;
            $arr_row['area_id'] = $row_cuestionario->area_id;
            $arr_row['nivel'] = $row_cuestionario->nivel;

            $evento_id = $this->guardar_evento($arr_row);   
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
            $arr_row['fecha_inicio'] = date('Y-m-d');
            $arr_row['hora_inicio'] = date('H:i:s');
            $arr_row['tipo_id'] = 11;   //Respuesta de cuestionario
            $arr_row['referente_id'] = $row_uc->id;
            $arr_row['referente_2_id'] = $row_uc->cuestionario_id;
            $arr_row['entero_1'] = $row_cuestionario->creado_usuario_id;
            $arr_row['estado'] = 0;
            $arr_row['usuario_id'] = $this->session->userdata('user_id');
            $arr_row['institucion_id'] = $row_uc->institucion_id;
            $arr_row['grupo_id'] = $row_uc->grupo_id;
            $arr_row['area_id'] = $row_cuestionario->area_id;
            $arr_row['nivel'] = $row_cuestionario->nivel;

            $evento_id = $this->guardar_evento($arr_row);   
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

        $arr_row['tipo_id'] = 11;
        $arr_row['referente_id'] = $row_uc->id;
        $arr_row['fecha_fin'] = date('Y-m-d');
        $arr_row['hora_fin'] = date('H:i:s');
        $arr_row['tipo_id'] = 11;   //Respuesta de cuestionario
        $arr_row['estado'] = 1;    //Finalizado

        $evento_id = $this->guardar_evento($arr_row);
        
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
        
        $arr_row['tipo_id'] = 21;  //Creación de cuestionario, item.ite_interno cat 13
        $arr_row['referente_id'] = $cuestionario_id;
        $arr_row['fecha_inicio'] = date('Y-m-d');
        $arr_row['hora_inicio'] = date('H:i:s');
        $arr_row['usuario_id'] = $row_cuestionario->creado_usuario_id;
        $arr_row['institucion_id'] = $row_usuario->institucion_id;
        $arr_row['area_id'] = $row_cuestionario->area_id;
        $arr_row['nivel'] = $row_cuestionario->nivel;
        
        $evento_id = $this->guardar_evento($arr_row);
                
        return $evento_id;
    }

    
// EVENTOS PUBLICACIONES
//---------------------------------------------------------------------------------------------------------
    
    function guardar_ev_publicacion($post_id)
    {
        $row_post = $this->Pcrn->registro_id('post', $post_id);
        
        $arr_row['tipo_id'] = 50;    //Publicación, ver item categoria_id = 13, tipos de evento
        $arr_row['referente_id'] = $post_id;
        $arr_row['entero_1'] = $this->input->post('entero_1');    //Tipo publicación, ver item categoria_id = 12, tipo de publicación muro
        $arr_row['fecha_inicio'] = $this->Pcrn->fecha_formato($row_post->creado, 'Y-m-d');
        $arr_row['hora_inicio'] = $this->Pcrn->fecha_formato($row_post->creado, 'H:i:s');
        $arr_row['institucion_id'] = $this->session->userdata('institucion_id');
        $arr_row['grupo_id'] = $this->input->post('grupo_id');
        $arr_row['usuario_id'] = $this->session->userdata('user_id');

        $evento_id = $this->guardar_evento($arr_row);
        
        return $evento_id;
        
        
    }
    
// GESTIÓN DE EVENTOS LINKS
//---------------------------------------------------------------------------------------------------------
    
    /**
     * Links personalizados (4) programados a un grupo
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


    /**
     * Links internos asignados (5) programados a un grupo
     * 2020-03-25
     */
    function evs_links_internos($busqueda)
    {
        //Filtros
        if ( $busqueda['a'] != '' ) { $this->db->where('area_id', $busqueda['a']); }    //Área
        if ( $busqueda['g'] != '' ) { $this->db->where('grupo_id', $busqueda['g']); }    //Grupo
        
        if ( $this->session->userdata('rol_id') == 6 ) {
            //Estudiante
            $this->db->where('grupo_id', $this->session->userdata('grupo_id'));
        } else {
            //Los que corresponden a sus grupos
            $str_grupos = implode(',', $this->session->userdata('arr_grupos'));
            $this->db->where("grupo_id IN ($str_grupos)");
        }
        
        $this->db->where('tipo_id', 5); //Tipo 5 => programacion de links internos
        $query = $this->db->get('evento');
        
        return $query;
    }

    /**
     * Sesiones virtuales programadas (6)
     * 2020-04-21
     */
    function evs_sesionv($busqueda)
    {
        //Filtros
        if ( $busqueda['a'] != '' ) { $this->db->where('area_id', $busqueda['a']); }    //Área
        if ( $busqueda['g'] != '' ) { $this->db->where('grupo_id', $busqueda['g']); }    //Grupo
        if ( $busqueda['tp'] != '' ) { $this->db->where('tipo_id', $busqueda['tp']); }    //Tipo, filtro adicional
        
        if ( $this->session->userdata('rol_id') == 6 ) {
            //Estudiante
            $this->db->where('grupo_id', $this->session->userdata('grupo_id'));
        } else {
            //Los que corresponden a sus grupos
            $str_grupos = implode(',', $this->session->userdata('arr_grupos'));
            $this->db->where("grupo_id IN ($str_grupos)");
        }
        
        $this->db->where('tipo_id', 6); //Tipo 6 => sesión virtual programada
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
        $row_usuario = $this->Pcrn->registro_id('usuario', $this->session->userdata('user_id'));
        $nivel = $this->Pcrn->campo_id('grupo', $row_usuario->grupo_id, 'nivel');
        
        //Registro, valores generales
            $arr_row['tipo_id'] = 101;   //Login de usuario, ver item cantegoria_id = 13
            $arr_row['fecha_inicio'] = date('Y-m-d');
            $arr_row['hora_inicio'] = date('H:i:s');
            $arr_row['referente_id'] = $row_usuario->id;
            $arr_row['usuario_id'] = $row_usuario->id;
            $arr_row['institucion_id'] = $this->session->userdata('institucion_id');
            $arr_row['grupo_id'] = $this->session->userdata('grupo_id');
            $arr_row['nivel'] = $nivel;

            $condicion_add = 'id = 0';  //Se pone una condición adicional incumplible, para que siempre agregue el registro
            $evento_id = $this->guardar_evento($arr_row, $condicion_add);
            
        //Agregar evento_id a los datos de sesión
            $this->session->set_userdata('login_id', $evento_id);
        
        return $evento_id;
        
    }

// AYUDAS
//-----------------------------------------------------------------------------

    /**
     * Array con opciones para select, de horas del día
     * 2020-04-23
     */
    function opciones_hora()
    {
        $opciones_hora = array();
        for ($i=0; $i < 24; $i++) { 
            $value = substr('0' . $i,-2) . ' am';
            if ( $i > 12 ) { $value = substr('0' . ($i-12),-2) . ' pm'; }
            $opciones_hora[$i] = $value;
        }

        return $opciones_hora;
    }

    /**
     * Array con opciones para select, de minutos de una hora
     * 2020-04-23
     */
    function opciones_minuto($lapse = 5)
    {
        $opciones_minuto = array();
        for ($i=0; $i < 60; $i += $lapse) { 
            $opciones_minuto[$i] = substr('0' . $i,-2);
        }

        return $opciones_minuto;
    }
    
}