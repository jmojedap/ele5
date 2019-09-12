<?php
class Post_Model extends CI_Model{
    
    function basico($post_id)
    {
        $row = $this->Pcrn->registro_id('post', $post_id);
        
        $basico['row'] = $row;
        $basico['nombre_post'] = $this->Pcrn->si_strlen($row->nombre_post, 'Post ' . $row->id);
        $basico['head_title'] = $basico['nombre_post'];
        
        return $basico;
    }
    
    /**
     * Nombre de la vista para la edición de un post, dependiendo del tipo:
     * $row_post->tipo_id
     * 
     * @param type $row_post
     * @return string
     */
    function vista_editar($row_post)
    {
        $vista_editar = 'posts/editar_v';
        
        $vistas = array(
            22 => 'posts/listas/editar_v',
            30 => 'posts/bitacora/edit_v',
            4401 => 'posts/enunciados/editar_v',
            4311 => 'posts/contenidos_ap/editar_v'
        );
        
        if ( array_key_exists($row_post->tipo_id, $vistas) ) { $vista_editar = $vistas[$row_post->tipo_id]; }
        
        return $vista_editar;
    }
    
    /**
     * Nombre de la vista para la lectura de un post, dependiendo del tipo:
     * $row_post->tipo_id
     * 
     * @param type $row_post
     * @return string
     */
    function vista_leer($row_post)
    {
        //$vista_leer = 'posts/leer_v';
        $vista_leer = 'posts/contenidos_ap/leer_v';
        
        $vistas = array(
            30 => 'posts/bitacora/leer_v',
            4401 => 'posts/contenidos_ap/leer_v',
        );
        
        if ( array_key_exists($row_post->tipo_id, $vistas) ) { $vista_leer = $vistas[$row_post->tipo_id]; }
        
        return $vista_leer;
    }
    
// EXPLORACIÓN
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     * 
     * @return string
     */
    function data_explorar($num_pagina)
    {
        //Data inicial, de la tabla
            $data = $this->data_tabla_explorar($num_pagina);
        
        //Elemento de exploración
            $data['controlador'] = 'posts';                      //Nombre del controlador
            $data['carpeta_vistas'] = 'posts/explorar/';         //Carpeta donde están las vistas de exploración
            $data['titulo_pagina'] = 'Posts';
                
        //Otros
            $data['cant_resultados'] = $this->Post_model->cant_resultados($data['busqueda']);
            $data['max_pagina'] = ceil($this->Pcrn->si_cero($data['cant_resultados'],1) / $data['per_page']) - 1;   //Cantidad de páginas, menos 1 por iniciar en cero

        //Vistas
            $data['vista_a'] = $data['carpeta_vistas'] . 'explorar_v';
            $data['vista_menu'] = $data['carpeta_vistas'] . 'menu_v';
        
        return $data;
    }
    
    /**
     * Array con los datos para la tabla de la vista de exploración
     * 
     * @param type $num_pagina
     * @return string
     */
    function data_tabla_explorar($num_pagina)
    {
        //Elemento de exploración
            $data['cf'] = 'posts/explorar/';     //CF Controlador Función
        
        //Paginación
            $data['num_pagina'] = $num_pagina;              //Número de la página de datos que se está consultado
            $data['per_page'] = 20;                          //Cantidad de registros por página
            $offset = $num_pagina * $data['per_page'];      //Número de la página de datos que se está consultado
        
        //Búsqueda y Resultados
            $this->load->model('Busqueda_model');
            $data['busqueda'] = $this->Busqueda_model->busqueda_array();
            $data['busqueda_str'] = $this->Busqueda_model->busqueda_str();
            $data['resultados'] = $this->Post_model->buscar($data['busqueda'], $data['per_page'], $offset);    //Resultados para página
            
        //Otros
            $data['seleccionados_todos'] = '-'. $this->Pcrn->query_to_str($data['resultados'], 'id');               //Para selección masiva de todos los elementos de la página
            
        return $data;
    }
    
    /**
     * Búsqueda de posts
     * 
     * @param type $busqueda
     * @param type $per_page
     * @param type $offset
     * @return type
     */
    function buscar($busqueda, $per_page = NULL, $offset = NULL)
    {
        //Construir búsqueda
        //Crear array con términos de búsqueda
            if ( strlen($busqueda['q']) > 2 ){
                
                $campos_posts = array('nombre_post', 'contenido', 'resumen', 'editado', 'creado');
                
                $concat_campos = $this->Busqueda_model->concat_campos($campos_posts);
                $palabras = $this->Busqueda_model->palabras($busqueda['q']);

                foreach ($palabras as $palabra) {
                    $this->db->like("CONCAT({$concat_campos})", $palabra);
                }
            }
        
        //Especificaciones de consulta
            $this->db->select('post.*');
            $this->db->order_by('editado', 'DESC');
            
        //Otros filtros
            if ( $busqueda['e'] != '' ) { $this->db->where('editado', $busqueda['e']); }                //Editado
            if ( $busqueda['tp'] != '' ) { $this->db->where('tipo_id', $busqueda['tp']); }              //Tipo de post
            if ( $busqueda['f1'] != '' ) { $this->db->where('referente_1_id', $busqueda['f1']); }       //Filtro 1
            if ( $busqueda['f2'] != '' ) { $this->db->where('referente_2_id', $busqueda['f2']); }       //Filtro 2
            if ( $busqueda['f3'] != '' ) { $this->db->where('referente_3_id', $busqueda['f3']); }       //Filtro 3
            if ( $busqueda['condicion'] != '' ) { $this->db->where($busqueda['condicion']); }           //Condición especial
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('post'); //Resultados totales
        } else {
            $query = $this->db->get('post', $per_page, $offset); //Resultados por página
        }
        
        return $query;
        
    }
    
    /**
     * Devuelve la cantidad de registros encontrados en la tabla con los filtros
     * establecidos en la búsqueda
     * 
     * @param type $busqueda
     * @return type
     */
    function cant_resultados($busqueda)
    {
        $resultados = $this->buscar($busqueda); //Para calcular el total de resultados
        return $resultados->num_rows();
    }
    
    function crud_basico()
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('post');
        $crud->set_subject('post');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_back_to_list();
        $crud->unset_delete();
        
        //Títulos
            $crud->display_as('nombre_post', 'Título');
            $crud->display_as('tipo_id', 'Tipo');
            
        //Campos
            $crud->add_fields(
                    'nombre_post',
                    'tipo_id',
                    'usuario_id',
                    'editor_id',
                    'creado',
                    'editado'
                );
            
        //Array opciones
            $arr_tipos = $this->App_model->arr_item(33);
            
        //Relaciones
            //$crud->set_relation('estado_post', 'item', 'item', 'categoria_id = 7', 'id_interno ASC');
            
        //Reglas
            //$crud->required_fields('nombre', 'direccion', 'telefono', 'pais_id', 'email');
            //$crud->set_rules('grosor', 'Grosor', 'is_natural');
        
        //Tipos de campo
            $crud->field_type('tipo_id', 'dropdown', $arr_tipos);
            $crud->field_type('usuario_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->field_type('editor_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->field_type('creado', 'hidden', date('Y-m-d H:i:s'));
            $crud->field_type('editado', 'hidden', date('Y-m-d H:i:s'));
                        
        //Formato
            
            $crud->unset_texteditor('notas_admin');
        
        $output = $crud->render();
        
        return $output;
    }
    
    function vista_a($row)
    {
        $vista_a = 'posts/post_v';
        if ( $row->tipo_id == 22 ){ $vista_a = 'posts/listas/lista_v'; }
        
        return $vista_a;
    }
    
    /**
     * Inserta un registro en la tabla grupo. Toma datos de POST, actualiza los 
     * campos del registo dependientes.
     * 
     * @param type $institucion_id
     * @return type
     */
    function insertar() 
    {
        //Resultado del proceso, por defecto
            $resultado = $this->Pcrn->res_inicial();
        
        //Cargar registro e insertarlo
            $registro = $this->input->post();
            $registro['editor_id'] = $this->session->userdata('usuario_id');
            $registro['editado'] = date('Y-m-d H:i:s');
            $registro['usuario_id'] = $this->session->userdata('usuario_id');
            //$registro['creado'] = date('Y-m-d H:i:s');

            $this->db->insert('post', $registro);
            $nuevo_id = $this->db->insert_id();
        
        //Si se creó el post, modificar array de resultado
            if ( $nuevo_id > 0 )
            {
                //$this->act_dependientes($post_id);
                
                $resultado['ejecutado'] = 1;
                $resultado['mensaje'] = 'El post fue creado correctamente.';
                $resultado['clase'] = 'alert-success';
                $resultado['icono'] = 'fa-check';
                $resultado['nuevo_id'] = $nuevo_id;
            }
            
        return $resultado;
    }
    
    function guardar_post($condicion, $registro)
    {
        $post_id = $this->Pcrn->existe('post', $condicion);
        
        //Complementar datos
        $registro['editor_id'] = $this->session->userdata('usuario_id');
        $registro['editado'] = date('Y-m-d H:i:s');
        
        if ( $post_id == 0 ) 
        {
            //No existe, insertar
            $registro['usuario_id'] = $this->session->userdata('usuario_id');
            $registro['creado'] = date('Y-m-d H:i:s');
            
            $this->db->insert('post', $registro);
            $post_id = $this->db->insert_id();
        } else {
            //Ya existe, editar
            $this->db->where('id', $post_id);
            $this->db->update('post', $registro);
        }
        
        return $post_id;
    }
    
    function editable($post_id)
    {
        $editable = 1;
        return $editable;
    }
    
    function metadatos($post_id, $dato_id = NULL)
    {
        $this->db->select('*');
        $this->db->where('relacionado_id', $post_id);
        $this->db->where('dato_id', $dato_id);
        $this->db->order_by('dato_id', 'ASC');
        $this->db->order_by('orden', 'ASC');
        $query = $this->db->get('meta');
        
        return $query;
    }
    
    /**
     * Actualizar un registro en la tabla post
     * 2019-07-02
     */
    function actualizar($post_id, $registro = NULL)
    {
        if ( is_null($registro) ) { $registro = $this->input->post(); }
        
        $registro['editor_id'] = $this->session->userdata('usuario_id');
        $registro['editado'] = date('Y-m-d H:i:s');
        
        $this->db->where('id', $post_id);
        $this->db->update('post', $registro);
        
        $data['status'] = 1;
        $data['message'] = 'Los datos fueron actualizados exitosamente';
        
        return $data;
    }
    
    function eliminable($post_id)
    {
        $eliminable = 1;
        return $eliminable;
    }
    
    function eliminar($post_id)
    {
        if ( $this->eliminable($post_id) ) 
        {
            //Tablas relacionadas, post
                $this->db->where('tabla_id', 2000); //Tabla post
                $this->db->where('elemento_id', $post_id);
                $this->db->delete('meta');
                
            //Tablas relacionadas, post, listas
                $this->db->where('relacionado_id', $post_id);
                $this->db->where('dato_id', 22);
                $this->db->delete('meta');
            
            //Tabla principal
                $this->db->where('id', $post_id);
                $this->db->delete('post');
        }
    }
    
    function reordenar_lista($post_id, $arr_elementos)
    {
        //Actualizar orden en tabla meta
            foreach ( $arr_elementos as $orden => $elemento_id)
            {
                $registro['orden'] = $orden;

                $this->db->where('relacionado_id', $post_id);
                $this->db->where('dato_id', 22);    //Elemento de lista
                $this->db->where('elemento_id', $elemento_id);
                $this->db->update('meta', $registro);
            }
        
        //Actualizar edición, tabla post
            $reg_post['editado'] = date('Y-m-d H:i:s');
            $reg_post['editor_id'] = $this->session->userdata('usuario_id');
            
            $this->db->where('id', $post_id);
            $this->db->update('post', $reg_post);
        
        
        return count($arr_elementos);
    }
    
// CONTENIDOS AP (ACOMPAÑAMIENTO PEDAGÓGICO)
//-----------------------------------------------------------------------------
    
    /**
     * Búsqueda de contenidos de Acompañamiento Pedagógico
     * 
     * @param type $busqueda
     * @param type $per_page
     * @param type $offset
     * @return type
     */
    function ap_buscar($busqueda, $per_page = NULL, $offset = NULL)
    {
        //Filtro por rol de usuarios
            $filtro_usuarios = $this->ap_filtro_usuarios();
        
        //Construir búsqueda
        //Crear array con términos de búsqueda
            if ( strlen($busqueda['q']) > 2 )
            {
                
                $campos_posts = array('nombre_post', 'contenido', 'resumen', 'editado', 'creado');
                
                $concat_campos = $this->Busqueda_model->concat_campos($campos_posts);
                $palabras = $this->Busqueda_model->palabras($busqueda['q']);

                foreach ($palabras as $palabra) {
                    $this->db->like("CONCAT({$concat_campos})", $palabra);
                }
            }
        
        //Especificaciones de consulta
            $this->db->select('post.*');
            $this->db->order_by('editado', 'DESC');
            $this->db->where('tipo_id = 4311'); //Acompañamiento pedagógico
            $this->db->where($filtro_usuarios);
            
        //Otros filtros
            if ( $busqueda['e'] != '' ) { $this->db->where('editado', $busqueda['e']); }                //Editado
            if ( $busqueda['tp'] != '' ) { $this->db->where('tipo_id', $busqueda['tp']); }              //Tipo de post
            if ( $busqueda['f1'] != '' ) { $this->db->where('referente_1_id', $busqueda['f1']); }       //Filtro 1
            if ( $busqueda['f2'] != '' ) { $this->db->where('referente_2_id', $busqueda['f2']); }       //Filtro 2
            if ( $busqueda['f3'] != '' ) { $this->db->where('referente_3_id', $busqueda['f3']); }       //Filtro 3
            if ( $busqueda['condicion'] != '' ) { $this->db->where($busqueda['condicion']); }           //Condición especial
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('post'); //Resultados totales
        } else {
            $query = $this->db->get('post', $per_page, $offset); //Resultados por página
        }
        
        return $query;
        
    }
    
    function ap_filtro_usuarios()
    {
        $rol_id = $this->session->userdata('rol_id');
        $condicion = 'id = 0';  //Valor por defecto
        
        if ( in_array($rol_id, array(0,1,2)) )
        {
            $condicion = 'id > 0';
        } else {
            $condicion_sub = 'dato_id = 400010';
            $condicion_sub .= ' AND elemento_id = ' . $this->session->userdata('institucion_id');
            $condicion_sub .= ' AND fecha_1 >= "' . date('Y-m-d H:i:s') . '"';
            $condicion = "id IN (SELECT relacionado_id FROM meta WHERE {$condicion_sub})";
        }
        
        return $condicion;
    }
    
    /**
     * Array con los datos para la vista de exploración de post acompañamiento
     * pedagógico
     * 
     * @return string
     */
    function ap_data_explorar($num_pagina)
    {
        //Data inicial, de la tabla
            $data = $this->ap_data_tabla_explorar($num_pagina);
        
        //Elemento de exploración
            $data['controlador'] = 'posts';                      //Nombre del controlador
            $data['carpeta_vistas'] = 'posts/contenidos_ap/explorar/';         //Carpeta donde están las vistas de exploración
            $data['titulo_pagina'] = 'Contenidos AP';
                
        //Otros
            $data['cant_resultados'] = $this->Post_model->cant_resultados($data['busqueda']);
            $data['max_pagina'] = ceil($this->Pcrn->si_cero($data['cant_resultados'],1) / $data['per_page']) - 1;   //Cantidad de páginas, menos 1 por iniciar en cero

        //Vistas
            $data['vista_a'] = $data['carpeta_vistas'] . 'explorar_v';
            $data['vista_menu'] = $data['carpeta_vistas'] . 'menu_v';
        
        return $data;
    }
    
    /**
     * Array con los datos para la tabla de la vista de exploración
     * 
     * @param type $num_pagina
     * @return string
     */
    function ap_data_tabla_explorar($num_pagina)
    {
        //Elemento de exploración
            $data['cf'] = 'posts/ap_explorar/';     //CF Controlador Función
        
        //Paginación
            $data['num_pagina'] = $num_pagina;              //Número de la página de datos que se está consultado
            $data['per_page'] = 20;                         //Cantidad de registros por página
            $offset = $num_pagina * $data['per_page'];      //Número de la página de datos que se está consultado
        
        //Búsqueda y Resultados
            $this->load->model('Busqueda_model');
            $data['busqueda'] = $this->Busqueda_model->busqueda_array();
            $data['busqueda_str'] = $this->Busqueda_model->busqueda_str();
            
            
            $data['resultados'] = $this->Post_model->ap_buscar($data['busqueda'], $data['per_page'], $offset);    //Resultados para página
            
        //Otros
            $data['seleccionados_todos'] = '-'. $this->Pcrn->query_to_str($data['resultados'], 'id');               //Para selección masiva de todos los elementos de la página
            
        return $data;
    }
    
    function ap_guardar_asignacion($registro)
    {
        //Resultado previo
        $resultado['ejecutado'] = 0;
        
        //Completar registro
        $registro['tabla_id'] = 4000;
        $registro['dato_id'] = 400010;
        
        //Guardar
        $this->load->model('Meta_model');
        $meta_id = $this->Meta_model->guardar($registro);
        
        //Actualizar resultado
        if ( $meta_id > 0 )    
        {
            $resultado['ejecutado'] = 1;
        }
        
        return $resultado;
    }
    
    /**
     * Elimina la asignación de un post a una institución en la tabla meta
     * dato_id = 400010
     * 
     * @param type $post_id
     * @param type $meta_id
     * @return int
     */
    function ap_eliminar_asignacion($post_id, $meta_id)
    {
        //Valor por defecto
        $resultado['ejecutado'] = 0;
        
        //Eliminación
        $this->db->where('dato_id', 400010);
        $this->db->where('id', $meta_id);
        $this->db->where('relacionado_id', $post_id);
        $this->db->delete('meta');
        
        //Ejecutado
        if ( $this->db->affected_rows() > 0  ) { $resultado['ejecutado'] = 1; }
        
        return $resultado;
    }
    
    /**
     * Asigna masivamente contenidos AP a Instituciones
     * 
     * @param type $array_hoja    Array con los datos de asignaciones
     * @return type
     */
    function ap_importar_asignaciones($array_hoja)
    {       
        $this->load->model('Esp');
        
        $no_importados = array();
        $fila = 2;  //Inicia en la fila 2 de la hoja de cálculo
        
        foreach ( $array_hoja as $array_fila )
        {
            //Datos referencia
                $row_ap = $this->Pcrn->registro('post', "id = '{$array_fila[0]}'");
                $row_institucion = $this->Pcrn->registro('institucion', "id = '{$array_fila[1]}'");
                
            //Validar
                $condiciones = 0;
                if ( ! is_null($row_ap) ) { $condiciones++; }               //Debe tener contenido AP identificado
                if ( ! is_null($row_institucion) ) { $condiciones++; }      //Debe tener institución identificada
                if ( strlen($array_fila[2]) > 0 ) { $condiciones++; }       //Debe tener fecha escrita
                
            //Si cumple las condiciones
            if ( $condiciones == 3 )
            {
                $mktime = $this->Pcrn->fexcel_unix($array_fila[2]);
                
                $registro['elemento_id'] = $row_institucion->id;
                $registro['relacionado_id'] = $row_ap->id;
                $registro['fecha_1'] = date('Y-m-d', $mktime) . ' 23:59:59'; //Día completo
                $this->ap_guardar_asignacion($registro);
            } else {
                $no_importados[] = $fila;
            }
            
            $fila++;    //Para siguiente fila
        }
        
        $res_importacion['no_importados'] = $no_importados;
        
        return $res_importacion;
    }
    
    /**
     * Query de Instituciones relacionadas con un POST
     * @return type
     */
    function instituciones($post_id)
    {
        $this->db->select('meta.id AS meta_id, elemento_id AS institucion_id, nombre_institucion, fecha_1, meta.usuario_id');
        $this->db->join('institucion', 'meta.elemento_id = institucion.id');
        $this->db->where('relacionado_id', $post_id);
        $this->db->order_by('elemento_id', 'ASC');
        $query = $this->db->get('meta');

        return $query;
    }
    
}