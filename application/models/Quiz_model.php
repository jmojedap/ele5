<?php
class Quiz_Model extends CI_Model{
    
    function basico($quiz_id)
    {
        
        $elementos = $this->elementos($quiz_id);
        $row_quiz = $this->Db_model->row_id('quiz', $quiz_id);
        
        //Datos adicionales
        $row_quiz->cant_elementos = $elementos->num_rows();
        
        $basico['elementos'] = $elementos;
        $basico['quiz_elementos'] = $elementos;
        $basico['quiz_id'] = $quiz_id;
        $basico['row'] = $row_quiz;
        $basico['row_tema'] = $this->Pcrn->registro_id('tema', $row_quiz->tema_id);
        $basico['head_title'] = $row_quiz->nombre_quiz;
        $basico['view_description'] = 'quices/quiz_v';
        $basico['nav_2'] = 'quices/menu_v';
        $basico['carpeta_imagenes'] = RUTA_UPLOADS . 'quices/';
        
        return $basico;
    }

// EXPLORACIÓN DE QUICES
//-----------------------------------------------------------------------------

    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($filters, $num_page, $per_page = 10)
    {
        //Data inicial, de la tabla
            $data = $this->get($filters, $num_page, $per_page);
        
        //Elemento de exploración
            $data['controller'] = 'quices';                      //Nombre del controlador
            $data['cf'] = 'quices/explorar/';                     //Nombre del controlador
            $data['views_folder'] = 'quices/explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Evidencias';
            $data['head_subtitle'] = $data['search_num_rows'];
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        return $data;
    }

    function get($filters, $num_page, $per_page)
    {
        //Referencia
            $offset = ($num_page - 1) * $per_page;      //Número de la página de datos que se está consultado

        //Búsqueda y Resultados
            $data['filters'] = $filters;
            $data['list'] = $this->list($filters, $per_page, $offset);    //Resultados para página
        
        //Cargar datos
            $data['str_filters'] = $this->Search_model->str_filters($filters);
            $data['search_num_rows'] = $this->search_num_rows($filters);
            $data['max_page'] = ceil($this->pml->if_zero($data['search_num_rows'],1) / $per_page);   //Cantidad de páginas

        return $data;
    }

    /**
     * Segmento Select SQL, con diferentes formatos, consulta de usuarios
     * 2021-05-12
     */
    function select($format = 'general')
    {
        $arr_select['general'] = '*';
        $arr_select['export'] = '*';
        

        return $arr_select[$format];
    }
    
    /**
     * Query usuarios, aplicando filtros, paginado y orden
     * 2020-12-12
     */
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        //Construir consulta
            $this->db->select($this->select());
            //$this->db->join('usuario', 'usuario.id = institucion.id', 'left');
            
            
        //Orden
            if ( $filters['o'] != '' )
            {
                $order_type = $this->pml->if_strlen($filters['ot'], 'ASC');
                $this->db->order_by($filters['o'], $order_type);
            } else {
                $this->db->order_by('editado', 'DESC');
            }
            
        //Filtros
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
        $query = $this->db->get('quiz', $per_page, $offset);
        
        return $query;
    }

    /**
     * Array Listado elemento resultado de la búsqueda (filtros).
     * 2020-01-21
     */
    function list($filters, $per_page = NULL, $offset = NULL)
    {
        $query = $this->search($filters, $per_page, $offset);
        $list = array();

        foreach ($query->result() as $row)
        {
            $row->cant_elementos = $this->Db_model->num_rows('quiz_elemento', "quiz_id = {$row->id}");  //Cantidad de elementos
            $list[] = $row;
        }

        return $list;
    }

    /**
     * String con condición WHERE SQL para filtrar usuario
     * 2020-08-01
     */
    function search_condition($filters)
    {
        $condition = NULL;

        //$condition .= $this->role_filter() . ' AND ';

        //q words condition
        $words_condition = $this->Search_model->words_condition($filters['q'], array('nombre_quiz', 'cod_quiz', 'texto_enunciado'));
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Otros filtros
        if ( $filters['f1'] != '' ) { $condition .= "tema_id = {$filters['f1']} AND "; }
        if ( $filters['a'] != '' ) { $condition .= "area_id = {$filters['a']} AND "; }
        if ( $filters['n'] != '' ) { $condition .= "nivel = {$filters['n']} AND "; }
        if ( $filters['tp'] != '' ) { $condition .= "tipo_quiz_id = {$filters['tp']} AND "; }
        
        //Quitar cadena final de ' AND '
        if ( strlen($condition) > 0 ) { $condition = substr($condition, 0, -5);}
        
        return $condition;
    }
    
    /**
     * Devuelve la cantidad de registros encontrados en la tabla con los filtros
     * establecidos en la búsqueda
     */
    function search_num_rows($filters)
    {
        $query = $this->search($filters); //Para calcular el total de resultados
        return $query->num_rows();
    }
    
    /**
     * Devuelve segmento Where SQL, aplicando filtro de usuarios según el rol del usuario en sesión
     * 2020-12-22
     */
    function role_filter()
    {
        $role = $this->session->userdata('role');
        $condition = 'quiz.id >= 0';  //Valor por defecto, ningún user, se obtendrían cero user.
        
        /*if ( $role <= 2 ) {
            //Desarrollador, todos los usuarios
            $condition = 'quiz.id > 0';
        }*/
        
        return $condition;
    }

    /**
     * Query para exportar
     * 2021-05-12
     */
    function export($filters)
    {
        $this->db->select($this->select('export'));
        $search_condition = $this->search_condition($filters);
        if ( $search_condition ) { $this->db->where($search_condition);}
        $query = $this->db->get('quiz', 5000);  //Hasta 5000 usuarios

        return $query;
    }

// Otras
//-----------------------------------------------------------------------------

    /**
     * Eliminar quiz y datos relacionados
     * 2024-02-29
     */
    function delete($quiz_id)
    {
        //Eliminar elementos
            $this->db->where('quiz_id', $quiz_id);
            $this->db->delete('quiz_elemento');
            
        //Eliminar de recursos
            $this->db->where('referente_id', $quiz_id);
            $this->db->where('tipo_recurso_id', 3); //Tipo quiz
            $this->db->delete('recurso');
        
        //Eliminar quiz
            $this->db->where('id', $quiz_id);
            $this->db->delete('quiz');

        $qty_deleted = $this->db->affected_rows();

        return $qty_deleted;
    }

    /**
     * Array from HTTP:POST, adding edition data
     * 2024-02-29
     */
    function aRow($data_from_post = TRUE)
    {
        $aRow = array();

        if ( $data_from_post ) { $aRow = $this->input->post(); }
        
        $aRow['usuario_id'] = $this->session->userdata('user_id');
        $aRow['editado'] = date('Y-m-d H:i:s');

        return $aRow;
    }

    
    
    /**
     * Devuelve el ayuda_id,
     * id del post de ayuda en el manual de la plataforma
     * según el tipo de quiz.
     * 
     * @param type $tipo_id
     */
    function ayuda_id_tipo($tipo_id)
    {
        $posts = array(
            1 =>  98,   //A
            2 =>  109,  //B
            3 => 113,   //C
            4 => 116,   //D
            5 => 121,   //E
            6 => 123,   //F
            7 => 126,   //G
            9 => 144,   //G
            10 => 129,  //J
            12 => 132,  //L
            13 => 180,   //M
            112 => 180,   //L2
            113 => 180,   //M2
            201 => 0,   //PL1
            202 => 0,   //PL3
            203 => 0,   //PL2
        );
        
        return $posts[$tipo_id];
    }
    
    function crud_elemento($quiz_id)
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('quiz_elemento');
        $crud->set_subject('elemento');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_read();
        
        //$crud->columns('');
            $crud->where('quiz_id', $quiz_id);
            
        //Valores por defecto
            $crud->change_field_type('quiz_id', 'hidden', $quiz_id);
            //$crud->change_field_type('editado', 'hidden', date('Y-m-d H:i:s'));
        
        //Formato
            //$crud->unset_texteditor('descripcion');
        
        $output = $crud->render();
        
        return $output;
        
    }
    
// IMAGES
//-----------------------------------------------------------------------------

    /**
     * Imágenes asociadas al quiz
     * 2024-03-11
     */
    function images($quiz_id)
    {
        $this->db->select('files.id, files.title, url, url_thumbnail, files.integer_1 AS main, position');
        $this->db->where('is_image', 1);
        $this->db->where('table_id', '4370');      //Tabla quiz
        $this->db->where('related_1', $quiz_id);   //Relacionado con el quix
        $this->db->order_by('position', 'ASC');
        $images = $this->db->get('files');

        return $images;
    }

    /**
     * Establecer una imagen asociada a un quiz como la imagen principal (tabla file)
     * 2024-0-11
     */
    function set_main_image($quiz_id, $file_id)
    {
        $data = array('status' => 0);

        $row_file = $this->Db_model->row_id('files', $file_id);
        if ( ! is_null($row_file) )
        {
            //Quitar otro principal
            $this->db->query("UPDATE files SET integer_1 = 0 WHERE table_id = 4370 AND related_1 = {$quiz_id} AND integer_1 = 1");

            //Poner nuevo principal
            $this->db->query("UPDATE files SET integer_1 = 1 WHERE id = {$file_id} AND related_1 = {$quiz_id}");

            //Actualizar registro en tabla post
            $aRow['imagen_id'] = $row_file->id;
            $aRow['url_image'] = $row_file->url;
            $aRow['url_thumbnail'] = $row_file->url_thumbnail;
            $aRow['usuario_id'] = $this->session->userdata('user_id');
            $aRow['editado'] = date('Y-m-d H:i:s');

            $this->db->where('id', $quiz_id);
            $this->db->update('quiz', $aRow);

            $data['status'] = 1;
        }

        return $data;
    }

// DATOS
//---------------------------------------------------------------------------------------------------------
    
    
    /**
     * Elementos incluidos en un Quiz
     */
    function elementos($quiz_id)
    {
        $this->db->where('tipo_id <= 4');    //Imágenes no incluidas
        $this->db->where('quiz_id', $quiz_id);
        $this->db->order_by('orden', 'ASC');
        
        $elementos = $this->db->get('quiz_elemento');
        
        return $elementos;
    }
    
    /**
     * Imágenes incluídas en un quiz, en quiz_elemento
     * 2019-12-02
     */
    function imagenes($quiz_id)
    {
        $this->db->where('quiz_id', $quiz_id);
        $this->db->where('tipo_id', 5);    //Imágenes
        $elementos = $this->db->get('quiz_elemento');
        
        return $elementos;
    }
    
    /**
     * Array, primera imagen de un quiz
     * @param type $quiz_id
     * @return type
     */
    function imagen($quiz_id)
    {
        $imagen = array();
        
        $imagenes = $this->imagenes($quiz_id);
        
        if ( $imagenes->num_rows() > 0 )
        {
            $row_imagen = $imagenes->row();
            $arr_detalle = json_decode($row_imagen->detalle);

            $imagen['id'] = $row_imagen->id;
            $imagen['src'] = base_url() . RUTA_UPLOADS . 'quices/' . $row_imagen->archivo;
            $imagen['id_alfanumerico'] = $row_imagen->id_alfanumerico;
            $imagen['ancho'] = $arr_detalle->image_width;
            $imagen['alto'] = $arr_detalle->image_height;
        }
        
        return $imagen;
    }
    
    function row_tipo_quiz($tipo_quiz_id)
    {
        $this->db->select('item AS tipo, item_largo AS enunciado, descripcion');
        $this->db->where('categoria_id', 9);
        $this->db->where('id_interno', $tipo_quiz_id);
        $items = $this->db->get('item');
        
        $row_tipo_quiz = $items->row();
        
        return $row_tipo_quiz;
    }
    
// GESTIÓN DE TEMAS
//---------------------------------------------------------------------------------------------------------
    
    /**
     * Listado de temas asociados a un quiz
     * 
     * @param type $quiz_id
     * @return type
     */
    function temas($quiz_id)
    {
        $this->db->select('tema.*');
        $this->db->where('recurso.referente_id', $quiz_id);
        $this->db->where('tipo_recurso_id', 3); //Tipo quiz
        $this->db->join('tema', 'recurso.tema_id = tema.id');
        $quices = $this->db->get('recurso');
        
        return $quices;
    }
    
    /**
     * Elimina de la tabla recurso la asignación de un tema a un quiz. No se
     * elimina el tema ni el quiz, solo la asignación.
     * 
     * @param type $quiz_id
     * @param type $tema_id
     * @return type
     */
    function quitar_tema($quiz_id, $tema_id)
    {
        //Resultado por defecto
            $resultado['ejecutado'] = 0;
            $resultado['mensaje'] = 'No se quitó el tema de esta evidencia';
            $resultado['clase'] = 'alert-danger';
            $resultado['icono'] = 'fa-times';
        
        //Eliminar asignación
            $this->db->where('referente_id', $quiz_id);
            $this->db->where('tema_id', $tema_id);
            $this->db->where('tipo_recurso_id', 3); //Tipo quiz
            $this->db->delete('recurso');
        
        $cant_eliminados = $this->db->affected_rows();

        //Según el resultado
        if ( $cant_eliminados > 0 ) 
        {
            $resultado['ejecutado'] = 1;
            $resultado['mensaje'] = "Se quitó el tema de esta evidencia";
            $resultado['clase'] = 'alert-success';
            $resultado['icono'] = 'fa-check';
        }
        
        return $resultado;
    }
    
// PROCESOS QUIZ
//---------------------------------------------------------------------------------------------------------
    
    /**
     * Creación automática de un quiz a un tema
     * de un tipo definido.
     * 
     * @param type $tema_id
     * @param type $tipo_quiz_id
     * @return type
     */
    function crear($tema_id, $tipo_quiz_id)
    {
        //Calcular el consecutivo
            $consecutivo = $this->Pcrn->num_registros('quiz', "tema_id = {$tema_id}") + 1;
            if ( $consecutivo == 1 ) { $consecutivo = ''; }
        
        //Datos del tema
            $row_tema = $this->Pcrn->registro_id('tema', $tema_id);
        
        //Definir registro
            $registro['nombre_quiz'] = "{$row_tema->nombre_tema} - Quiz {$consecutivo}";
            $registro['cod_quiz'] = $row_tema->cod_tema . 'q' . $consecutivo;
            $registro['tema_id'] = $tema_id;
            $registro['area_id'] = $row_tema->area_id;
            $registro['nivel'] = $row_tema->nivel;
            $registro['texto_enunciado'] = '';
            $registro['tipo_quiz_id'] = $tipo_quiz_id;
            $registro['usuario_id'] = $this->session->userdata('usuario_id');
            $registro['editado'] = date('Y-m-d H:i:s');
        
        //Guardar registro
            $this->db->insert('quiz', $registro);
            $quiz_id = $this->db->insert_id();
        
        //Guardar en la tabla recurso
            $this->guardar_recurso($quiz_id, $tema_id);
        
        return $quiz_id;
        
    }
    
    /**
     * Agrega un quiz en la tabla recurso
     * 
     * @param type $quiz_id
     * @param type $tema_id
     * @return type
     */
    function guardar_recurso($quiz_id, $tema_id)
    {
        //Registro
        $registro['tipo_recurso_id'] = 3;   //Quiz
        $registro['tipo_archivo_id'] = 624; //Ver item.id
        $registro['referente_id'] = $quiz_id;
        $registro['tema_id'] = $tema_id;
        $registro['editado'] = date('Y-m-d H:i:s');
            
        $condicion = "referente_id = {$quiz_id} AND tema_id = {$tema_id} AND tipo_recurso_id = 3";    
        $recurso_id = $this->Pcrn->guardar('recurso', $condicion, $registro);
        
        return $recurso_id;
    }
    
    /**
     * Al hacer un cambio en quiz o quiz_elemento se actualizan los datos de edición del quiz
     * Se actualizan los campos editado y usuario_id
     * 
     * @param type $elemento_id
     */
    function actualizar_editado($elemento_id)
    {
        $row_elemento = $this->Pcrn->registro_id('quiz_elemento', $elemento_id);
        
        $registro['usuario_id'] = $this->session->userdata('usuario_id');
        $registro['editado'] = date('Y-m-d H:i:s');
        
        $this->db->where('id', $row_elemento->quiz_id);
        $this->db->update('quiz', $registro);
    }
    
    /**
     * Actualiza el campo quiz.clave, según los elementos que tenga un quiz
     * @param int $quiz_id
     */
    function actualizar_clave($quiz_id)
    {
        $elementos = $this->elementos($quiz_id);
        $claves = array();
        
        foreach ($elementos->result() as $row_elemento) {
            $claves[] = intval($row_elemento->clave);
        }
        
        $registro['clave'] = json_encode($claves);
        
        $this->db->where('id', $quiz_id);
        $this->db->update('quiz', $registro);
    }
    
    /**
     * Valor por defecto al ingresar a resolver un quiz,
     * Abierto, con valor incorrecto
     * 
     * @param type $quiz_id
     * @return type
     */
    function iniciar($quiz_id)
    {
        
        //Construir el registro que se va a insertar
        $registro = array(
            'usuario_id' => $this->session->userdata('usuario_id'),
            'referente_id' => $quiz_id,
            'estado_int' => 0,
            'tipo_asignacion_id' => 3,  
            'editado' => date('Y-m-d H:i:s'),
            'editado_usuario_id' => $this->session->userdata('usuario_id')
        );

        $condicion = "usuario_id = {$registro['usuario_id']} AND referente_id = {$registro['referente_id']} AND tipo_asignacion_id = {$registro['tipo_asignacion_id']}";

        $ua_id = $this->Pcrn->insertar_si('usuario_asignacion', $condicion, $registro);

        //Respuesta
        return $ua_id;
    }
    
    /**
     * El tipo detalle 'Quiz' corresponde al tipo_asignacion_id = 3,
     * tabla: item.categoria_id = 16
     */
    function guardar_resultado()
    {
        //Valor por defecto
        $usuarioAsignacionId = 0;
        
        //Si es una página existente
        if ( $this->input->post('quiz_id') > 0 )
        {
            //Construir el registro que se va a insertar
            $aRow = array(
                'usuario_id' => $this->input->post('usuario_id'),
                'referente_id' => $this->input->post('quiz_id'),
                'estado_int' => $this->input->post('resultado'),
                'tipo_asignacion_id' => 3,  
                'editado' => date('Y-m-d H:i:s'),
                'editado_usuario_id' => $this->input->post('usuario_id')
            );

            $condicion = "usuario_id = {$aRow['usuario_id']} AND referente_id = {$aRow['referente_id']} AND tipo_asignacion_id = {$aRow['tipo_asignacion_id']}";

            $usuarioAsignacionId = $this->Db_model->save('usuario_asignacion', $condicion, $aRow);
        }

        //Respuesta
        return $usuarioAsignacionId;
    }

    /**
     * El tipo detalle 'Quiz' corresponde al tipo_asignacion_id = 3,
     * tabla: item.categoria_id = 16
     */
    function guardar_resultado_anterior()
    {
        //Valor por defecto
        $ua_id = 0;
        
        //Si es una página existente
        if ( $_REQUEST['quiz_id'] > 0 )
        {
            //Construir el registro que se va a insertar
            $registro = array(
                'usuario_id' => $_REQUEST['usuario_id'],
                'referente_id' => $_REQUEST['quiz_id'],
                'estado_int' => $_REQUEST['resultado'],
                'tipo_asignacion_id' => 3,  
                'editado' => date('Y-m-d H:i:s'),
                'editado_usuario_id' => $_REQUEST['usuario_id']
            );

            $condicion = "usuario_id = {$registro['usuario_id']} AND referente_id = {$registro['referente_id']} AND tipo_asignacion_id = {$registro['tipo_asignacion_id']}";

            $ua_id = $this->Pcrn->guardar('usuario_asignacion', $condicion, $registro);
        }

        //Respuesta
        return $ua_id;
    }
    
// FUNCIONES quiz_elemento
//---------------------------------------------------------------------------------------------------------
    
    function arr_elementos($quiz_id, $formato = 'string')
    {
        $elementos = $this->elementos($quiz_id);
        $arr_elementos = array();
        
        foreach ($elementos->result() as $row_elemento)
        {
            $arr_elementos[] = $this->arr_elemento($row_elemento);
        }
        
        if ( $formato == 'string' ){
            return json_encode($arr_elementos); //Devuelve string JSON
        } else {    
            return $arr_elementos;  //Devuelve array
        }
    }
    
    function arr_elemento($row)
    {   
        $arr_detalle = json_decode($row->detalle);
        
        $elemento['id'] = $row->id;
        $elemento['id_alfanumerico'] = $row->id_alfanumerico;
        $elemento['orden'] = $row->orden;
        $elemento['texto'] = $row->texto;
        $elemento['clave'] = $row->clave;
        $elemento['archivo'] = $row->archivo;
        
        $elemento['cant_opciones'] = 0;
        $elemento['x'] = $row->x;
        $elemento['y'] = $row->y;
        $elemento['alto'] = $row->alto;
        $elemento['ancho'] = $row->ancho;
        
        if ( is_array($arr_detalle) )
        {
            $elemento['cant_opciones'] = count($arr_detalle);
            foreach ( $arr_detalle as $key => $texto_opcion ){
                $campo = 'opcion_' . $key;
                $elemento[$campo] = $texto_opcion;
            }
        } else {
            $elemento['detalle'] = $row->detalle;
        }
        
        return $elemento;
    }
    
    /**
     * Función que agrega o edita un registro a la tabla quiz_elemento
     */
    function guardar_elemento($registro)
    {
        $condicion = "id_alfanumerico = '{$registro['id_alfanumerico']}'";
        
        $elemento_id = $this->Pcrn->guardar('quiz_elemento', $condicion, $registro);
        
        //Actualizar quiz
        $this->actualizar_editado($elemento_id);
        $this->actualizar_clave($registro['quiz_id']);
        $this->enumerar_elementos($registro['quiz_id'], $registro['tipo_id']);
        
        return $elemento_id;
    }
    
    /* Función que elimina un registro de la tabla quiz_elemento
     * 2021-05-21
     */
    function eliminar_elemento($id_alfanumerico)
    {
        $qty_deleted = 0;
        $row_elemento = $this->Db_model->row('quiz_elemento', "id_alfanumerico = '{$id_alfanumerico}'");

        if ( ! is_null($row_elemento) )
        {
            $quiz_id = $row_elemento->quiz_id;
            
            //Eliminar archivo si tiene
                $file_path = RUTA_UPLOADS . 'quices/' . $row_elemento->archivo;
                if ( strlen($row_elemento->archivo) > 0 && file_exists($file_path) ) {
                    unlink($file_path);
                }
    
            //Eliminar registro
                $this->db->where('id', $row_elemento->id);
                $this->db->delete('quiz_elemento');
    
                $qty_deleted = $this->db->affected_rows();
    
            //Actalizaciones
                $this->actualizar_clave($quiz_id);
                $this->enumerar_elementos($quiz_id, $row_elemento->tipo_id);
        }

        return $qty_deleted;
    }
    
    /**
     * Reenumerar los elementos de un quiz, se actualiza quiz_elemento.orden
     * La enumeración es por tipo_id, cada tipo_id tiene una numeración independiente en cada quiz
     * 
     * @param type $quiz_id
     * @param type $tipo_id
     */
    function enumerar_elementos($quiz_id, $tipo_id)
    {
        $this->db->where('quiz_id', $quiz_id);
        $this->db->where('tipo_id', $tipo_id);
        $this->db->order_by('orden', 'ASC');
        $this->db->order_by('id', 'ASC');
        $elementos = $this->db->get('quiz_elemento');
        $i = 0;
        
        foreach ( $elementos->result() as $row_elemento )
        {
            $registro['orden'] = $i;
            $this->db->where('id', $row_elemento->id);
            $this->db->update('quiz_elemento', $registro);
            $i += 1;
        }
    }
    
    function cargar_imagen()
    {
        $data = array();
        
        $config = $this->config_upload();
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('archivo')) {
            $data['status'] = 0;
            $data['html'] = $this->upload->display_errors('<div class="alert alert-danger">', '</div>');
        } else {
            $data['status'] = 1;
            $data['html'] = '';
            $data['upload_data'] = $this->upload->data();
        }
        
        return $data;
        
    }
    
    function guardar_imagen($upload_data)
    {
        $detalle['image_width'] = $upload_data['image_width'];
        $detalle['image_height'] = $upload_data['image_height'];

        $registro['id_alfanumerico'] = $this->Pcrn->alfanumerico_random(16, 0, 1);
        $registro['quiz_id'] = $this->input->post('quiz_id');
        $registro['tipo_id'] = 5;   //Fondo de imagen
        $registro['orden'] = 0;
        $registro['archivo'] = $upload_data['file_name'];
        $registro['detalle'] = json_encode($detalle);
        
        $this->guardar_elemento($registro);
    }
    
    function asignar_archivo($elemento_id, $upload_data)
    {   
        $registro['archivo'] = $upload_data['file_name'];
        
        $this->db->where('id', $elemento_id);
        $this->db->update('quiz_elemento', $registro);
    }
    
    function config_upload()
    {
        $config['upload_path'] = RUTA_UPLOADS .  'quices/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']	= '500';
        $config['max_width']  = '1024';
        $config['max_height']  = '768';
        $config['encrypt_name']  = TRUE;
        
        return $config;
    }

// Versiones 2
//-----------------------------------------------------------------------------

    function save_element($arr_row)
    {
        $condition = "id = '{$arr_row['id']}'";
        
        $saved_id = $this->Db_model->save('quiz_elemento', $condition, $arr_row);
        
        //Actualizar quiz
        $this->actualizar_editado($saved_id);
        $this->actualizar_clave($arr_row['quiz_id']);
        $this->enumerar_elementos($arr_row['quiz_id'], $arr_row['tipo_id']);
        
        return $saved_id;
    }

    /* Función que elimina un registro de la tabla quiz_elemento
     * 2021-05-14
     */
    function delete_element($quiz_id, $elemento_id)
    {
        $condition = "id = {$elemento_id} AND quiz_id = {$quiz_id}";
        $row_elemento = $this->Db_model->row('quiz_elemento', $condition);
        
        //Eliminar archivo si tiene
            $file_path = RUTA_UPLOADS . 'quices/' . $row_elemento->archivo;
            if ( strlen($row_elemento->archivo) > 0 && file_exists($file_path) ) {
                unlink($file_path);
            }

        //Eliminar registro
            $this->db->where('id', $row_elemento->id)->delete('quiz_elemento');
            $qty_deleted = $this->db->affected_rows();

        //Actalizaciones
            $this->actualizar_clave($quiz_id);
            $this->enumerar_elementos($quiz_id, $row_elemento->tipo_id);

        return $qty_deleted;
    }

    /**
     * Listado de elementos de un quiz
     * 2021-05-14
     */
    function get_elements($quiz_id)
    {
        $this->db->select('*');
        $this->db->where('quiz_id', $quiz_id);
        $elements = $this->db->get('quiz_elemento');

        return $elements;
    }

// Versiones V3
//-----------------------------------------------------------------------------

    /**
     * Array con quices aleatorios 3G filtrados con ciertos criterios
     * 2023-11-22
     * @param array $filters | Array con filtros o especificaciones para
     * seleccionar quices.
     * @return $arrQuices array con quices 3G, aleatorios
     * 
     */
    function get_random_quices($filters)
    {
        //$this->db->select('campos');
        if ( strlen($filters['tp']) > 0 ) $this->db->where('tipo_quiz_id', $filters['tp']);
        $this->db->order_by('id', 'RANDOM');
        $quices = $this->db->get('quiz');

        $arrQuices = [];
        $num_rows = ( isset($filters['num_rows']) ) ? $filters['num_rows'] : 5 ;
        $i = 0;
        foreach ($quices->result() as $quiz) {
            $quiz->respuesta = '';
            $quiz->respondido = 0;
            $quiz->comprobado = 0;
            $quiz->resultado = 0;
            $arrQuices[] = $quiz;

            $i++;
            if ( $i >= $num_rows ) break;
        }
        return $arrQuices;
    }
}