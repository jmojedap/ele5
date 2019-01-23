<?php
class Quiz_Model extends CI_Model{
    
    function basico($quiz_id)
    {
        
        $elementos = $this->elementos($quiz_id);
        
        $row_quiz = $this->Pcrn->registro_id('quiz', $quiz_id);
        
        //Datos adicionales
        $row_quiz->cant_elementos = $elementos->num_rows();
        
        $basico['elementos'] = $elementos;
        $basico['quiz_elementos'] = $elementos;
        $basico['quiz_id'] = $quiz_id;
        $basico['row'] = $row_quiz;
        $basico['row_tema'] = $this->Pcrn->registro_id('tema', $row_quiz->tema_id);
        $basico['titulo_pagina'] = $row_quiz->nombre_quiz;
        $basico['vista_a'] = 'quices/quiz_v';
        $basico['carpeta_imagenes'] = RUTA_UPLOADS . 'quices/';
        
        return $basico;
    }
    
    /**
     * Búsqueda de quices
     * 
     * @param type $busqueda
     * @param type $per_page
     * @param type $offset
     * @return type
     */
    function buscar($busqueda, $per_page = NULL, $offset = NULL)
    {

        //Construir búsqueda
        
            //Texto búsqueda
                //Crear array con términos de búsqueda
                if ( strlen($busqueda['q']) > 2 ){
                    $palabras = $this->Busqueda_model->palabras($busqueda['q']);

                    foreach ($palabras as $palabra) {
                        $this->db->like('CONCAT(cod_quiz, nombre_quiz, IF(ISNULL(texto_enunciado), 0, texto_enunciado))', $palabra);
                    }
                }
            
            //Otros filtros
                if ( $busqueda['a'] != '' ) { $this->db->where('area_id', $busqueda['a']); }    //Área
                if ( $busqueda['n'] != '' ) { $this->db->where('nivel', $busqueda['n']); }  //Nivel
                if ( $busqueda['tp'] != '' ) { $this->db->where('tipo_quiz_id', $busqueda['tp']); }  //Tipo quiz
                if ( $busqueda['e'] != '' ) { $this->db->where('editado', $busqueda['e']); }  //Editado
                
                
            //Otros
                $this->db->order_by('cod_quiz', 'ASC');
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('quiz'); //Resultados totales
        } else {
            $query = $this->db->get('quiz', $per_page, $offset); //Resultados por página
        }
        
        return $query;
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
            13 => 180   //M
        );
        
        return $posts[$tipo_id];
    }
    
    /**
     * Objeto Grocery Crud para la edición de quices
     * 2018-11-06
     *  
     * @param type $quiz_id
     * @return type
     */
    function crud_editar($quiz_id)
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        $this->load->model('Esp');
        
        $crud = new grocery_CRUD();
        $crud->set_table('quiz');
        $crud->set_subject('quiz');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_add();
        $crud->unset_back_to_list();
        $crud->unset_delete();
        $crud->unset_read();
        $crud->columns('nombre_quiz', 'descripcion', 'editado');

        //Permisos de edición
        
        //Permisos de adición
        
        //Relaciones
            $crud->set_relation('area_id', 'item', 'item', 'categoria_id = 1');
        
        //Filtro
            $crud->where('quiz.id', 0);
        
        //Títulos de los campos
            $crud->display_as('nombre_quiz', 'Nombre');
            $crud->display_as('area_id', 'Área');
            $crud->display_as('tipo_quiz_id', 'Tipo');
            $crud->display_as('cod_quiz', 'Cód quiz');
            $crud->display_as('texto_enunciado', 'Enunciado especial');

        //Formulario Edit
            $crud->edit_fields(
                'nombre_quiz',
                'tipo_quiz_id',
                'clave',
                'cod_quiz',
                'texto_enunciado',
                'usuario_id',
                'editado'
            );
            
        //Opciones
            $arr_tipo_quiz = $this->Item_model->arr_item(9);

        //Reglas de validación
            $crud->required_fields('nombre_quiz', 'cod_quiz', 'nivel', 'area_id');
            
        //Valores por defecto
            $crud->field_type('editado', 'hidden', date('Y-m-d H:i:s'));
            $crud->field_type('usuario_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->field_type('tipo_quiz_id', 'dropdown', $arr_tipo_quiz);
            $crud->unset_texteditor('texto_enunciado');
        
        $output = $crud->render();
        
        return $output;
        
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
        
        //Títulos de los campos
            //$crud->display_as('usuario_id', 'Creado por');
        
        //Relaciones
            //$crud->set_relation('usuario_id', 'usuario', '{nombre} {apellidos} ({username})');
        

        //Formulario Edit
            /*$crud->edit_fields(
                'nombre_quiz',
                'descripcion',
                'editado'
            );*/

        //Formulario Add
            /*$crud->add_fields(
                'nombre_quiz',
                'descripcion',
                'usuario_id'
            );*/

        //Reglas de validación
            //$crud->required_fields('nombre_quiz');
            
        //Valores por defecto
            $crud->change_field_type('quiz_id', 'hidden', $quiz_id);
            //$crud->change_field_type('editado', 'hidden', date('Y-m-d H:i:s'));
        
        //Formato
            //$crud->unset_texteditor('descripcion');
        
        $output = $crud->render();
        
        return $output;
        
    }
    
    /**
     * Link para Grocery Crud de los quices
     * 
     * @param type $value
     * @param type $row
     * @return type
     */
    function gc_link_quiz($value, $row)
    {
        $texto = substr($row->nombre_quiz, 0, 50);
        $att = 'title="Ir al quiz ' . $value. '"';
        return anchor("quices/detalle/{$row->id}", $texto, $att);
    }
    
    function gc_after_insert($post_array, $primary_key)
    {
        redirect("quices/flipbooks/{$primary_key}");
    }

// DATOS
//---------------------------------------------------------------------------------------------------------
    
    
    
    function elementos($quiz_id)
    {
        $this->db->where('tipo_id <= 4');    //Imágenes no incluidas
        $this->db->where('quiz_id', $quiz_id);
        $this->db->order_by('orden', 'ASC');
        
        $elementos = $this->db->get('quiz_elemento');
        
        return $elementos;
    }
    
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
        
        if ( $imagenes->num_rows() > 0 ){
            $row_imagen = $imagenes->row();
            $arr_detalle = json_decode($row_imagen->detalle);

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
     * @param type $quiz_id
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
    
    function eliminar($quiz_id)
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
        
        foreach ($elementos->result() as $row_elemento) {
            $arr_elementos[] = $this->arr_elemento($row_elemento);
        }
        
        if ( $formato == 'string' ){
            return json_encode($arr_elementos); //Devuelve string
        } else {    
            return $arr_elementos;  //Devuelve array
        }
    }
    
    function arr_elemento($row)
    {   
        $arr_detalle = json_decode($row->detalle);
        
        $elemento['id_alfanumerico'] = $row->id_alfanumerico;
        $elemento['orden'] = $row->orden;
        $elemento['texto'] = $row->texto;
        $elemento['clave'] = $row->clave;
        $elemento['archivo'] = $row->archivo;
        
        $elemento['cant_opciones'] = count($arr_detalle);
        $elemento['x'] = $row->x;
        $elemento['y'] = $row->y;
        $elemento['alto'] = $row->alto;
        $elemento['ancho'] = $row->ancho;
        
        if ( is_array($arr_detalle) ){
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
     * 
     */
    function eliminar_elemento($id_alfanumerico)
    {
        $row_elemento = $this->Pcrn->registro('quiz_elemento', "id_alfanumerico = '{$id_alfanumerico}'");
        $quiz_id = $row_elemento->quiz_id;
        
        $this->db->where('id', $row_elemento->id);
        $this->db->delete('quiz_elemento');
        
        $this->actualizar_clave($quiz_id);
        $this->enumerar_elementos($quiz_id, $row_elemento->tipo_id);
        
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
        
        foreach ( $elementos->result() as $row_elemento ){
            $registro['orden'] = $i;
            $this->db->where('id', $row_elemento->id);
            $this->db->update('quiz_elemento', $registro);
            $i += 1;
        }
    }
    
    function cargar_imagen()
    {
        $results = array();
        
        $config = $this->config_upload();
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('archivo')) {
            $results['result'] = 0;
            $results['message'] = $this->upload->display_errors('<h4 class="alert_error">', '<h4>');
        } else {
            $results['result'] = 1;
            $results['message'] = '';
            $results['upload_data'] = $this->upload->data();
        }
        
        return $results;
        
    }
    
    function guardar_imagen($upload_data)
    {
        $detalle['image_width'] = $upload_data['image_width'];
        $detalle['image_height'] = $upload_data['image_height'];

        $registro['id_alfanumerico'] = $this->Pcrn->alfanumerico_random(16, 0, 1);
        $registro['quiz_id'] = $this->input->post('quiz_id');
        $registro['tipo_id'] = 5;
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
    
}