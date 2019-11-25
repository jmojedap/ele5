<?php
class Tema_Model extends CI_Model{
    
    function basico($tema_id)
    {
        
        //preguntas
            $this->db->where('tema_id', $tema_id);
            $preguntas = $this->db->get('pregunta');
            
        //preguntas
            $this->db->where('tema_id', $tema_id);
            $programas = $this->db->get('programa_tema');
            
        //páginas flipbook
            //$this->db->where('en_tema', 1);
            $this->db->where('tema_id', $tema_id);
            $pf = $this->db->get('pagina_flipbook');
        
        $row_tema = $this->Pcrn->registro_id('tema', $tema_id);
        
        //Datos adicionales
        $row_tema->cant_preguntas = $preguntas->num_rows();
        $row_tema->cant_pf = $pf->num_rows();
        $row_tema->cant_programas = $programas->num_rows();
        
        $basico['preguntas'] = $preguntas;
        $basico['programas'] = $programas;
        $basico['pf'] = $pf;
        
        $basico['tema_id'] = $tema_id;
        $basico['row'] = $row_tema;
        $basico['head_title'] = $row_tema->nombre_tema;
        $basico['view_description'] = 'temas/tema_v';
        $basico['nav_2'] = 'temas/menu_v';
        
        return $basico;
    }

// Exploraración
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     * 2019-08-05
     * 
     * @return string
     */
    function data_explorar($num_pagina)
    {
        //Data inicial, de la tabla
            $data = $this->data_tabla_explorar($num_pagina);
        
        //Elemento de exploración
            $data['carpeta_vistas'] = 'temas/explorar/';         //Carpeta donde están las vistas de exploración
            $data['head_title'] = 'Temas';
            $data['el_plural'] = 'temas';
            $data['el_singular'] = 'tema';
                
        //Otros
            $data['arr_filtros'] = array('a', 'n', 'tp');
            
        //Vistas
            $data['head_subtitle'] = $data['cant_resultados'];
            $data['view_a'] = $data['carpeta_vistas'] . 'explorar_v';
            $data['nav_2'] = $data['carpeta_vistas'] . 'menu_v';
        
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
            $data['controlador'] = 'temas';         //Nombre del controlador
            $data['cf'] = 'temas/explorar/';        //CF Controlador Función
        
        //Paginación
            $data['num_pagina'] = $num_pagina;                  //Número de la página de datos que se está consultado
            $data['per_page'] = 15;                             //Cantidad de registros por página
            $offset = ($num_pagina - 1) * $data['per_page'];    //Número de la página de datos que se está consultado
        
        //Búsqueda y Resultados
            $this->load->model('Busqueda_model');
            $data['busqueda'] = $this->Busqueda_model->busqueda_array();
            $data['busqueda_str'] = $this->Busqueda_model->busqueda_str();
            $data['resultados'] = $this->buscar($data['busqueda'], $data['per_page'], $offset);    //Resultados para página
            
        //Otros
            $data['cant_resultados'] = $this->cant_resultados($data['busqueda']);
            $data['max_pagina'] = ceil($this->Pcrn->si_cero($data['cant_resultados'],1) / $data['per_page']);   //Cantidad de páginas
            $data['seleccionados_todos'] = '-'. $this->Pcrn->query_to_str($data['resultados'], 'id');           //Para selección masiva de todos los elementos de la página
            
        return $data;
    }


    function buscar($busqueda, $per_page = NULL, $offset = NULL)
    {
        
        $filtro_rol = $this->filtro_rol();

        //Construir búsqueda
        //Crear array con términos de búsqueda
            if ( strlen($busqueda['q']) > 2 )
            {
                $palabras = $this->Busqueda_model->palabras($busqueda['q']);

                foreach ($palabras as $palabra_busqueda)
                {
                    $concat_campos = $this->Busqueda_model->concat_campos(array('cod_tema', 'nombre_tema', 'titulo_tema'));
                    $this->db->like("CONCAT({$concat_campos})", $palabra_busqueda);
                }
            }
        
        //Especificaciones de consulta
            $this->db->where($filtro_rol); //Filtro según el rol de usuario que se tenga
            $this->db->order_by('editado', 'DESC');
            
        //Otros filtros
            if ( $busqueda['a'] != '' ) { $this->db->where('area_id', $busqueda['a']); }    //Área
            if ( $busqueda['n'] != '' ) { $this->db->where('nivel', $busqueda['n']); }      //Nivel
            if ( $busqueda['tp'] != '' ) { $this->db->where('tipo_id', $busqueda['tp']); }  //Tipo tema
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('tema'); //Resultados totales
        } else {
            $query = $this->db->get('tema', $per_page, $offset); //Resultados por página
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

    /**
     * Condición tipo WHERE SQL, para filtrar el resultado de las búsquedas
     * según el rol de usuario de sesión.
     * 
     * @param type $usuario_id
     * @return type 
     */
    function filtro_rol()
    {
        $usuario_id = $this->session->userdata('usuario_id');
        $row_usuario = $this->Pcrn->registro_id('usuario', $usuario_id);

        $condicion = 'id = 0';  //Valor por defecto, ningún usuario, se obtendrían cero temas.
        
        if ( $row_usuario->rol_id == 0 ) {
            //Desarrollador, todos los temas
            $condicion = 'id > 0';
        } elseif ( $row_usuario->rol_id == 1 ) {
            //Administrador, todos los temas
            $condicion = 'id > 0';
        } elseif ( $row_usuario->rol_id == 2 ) {
            $condicion = 'id > 0';
        } elseif ( $row_usuario->rol_id == 3 ) {
            //Administrador institucional, todos los temas de su institución
            $condicion = "institucion_id = {$row_usuario->institucion_id} ";
            //$condicion .= " OR rol_id < 3 ORDER BY rol_id, apellidos";
        } elseif ( $row_usuario->rol_id == 4 ) {
            //Directivo, todos los temas de su institución
            $condicion = "institucion_id = {$row_usuario->institucion_id} ";
            //$condicion .= " OR rol_id < 3 ORDER BY rol_id, apellidos";
        } elseif ( $row_usuario->rol_id == 5 ) {
            //Profesor, todos los estudiantes de sus grupos asignados
            $sql = "SELECT grupo_id FROM grupo_profesor WHERE (profesor_id) = {$usuario_id}";
            $condicion = "grupo_id IN ({$sql})";
        } elseif ( $row_usuario->rol_id == 6 ) {
            //Estudiante, todos los estudianes de su grupo
            $condicion = "( grupo_id = ({$row_usuario->grupo_id})";
            $condicion .= " OR id IN (SELECT profesor_id FROM grupo_profesor WHERE (grupo_id) = ({$row_usuario->grupo_id})) )";
        } elseif ( $row_usuario->rol_id == 8 ) {
            //Comercial
            $condicion = "institucion_id IN (SELECT id FROM institucion WHERE ejecutivo_id = {$this->session->userdata('usuario_id')})";
        }
        
        return $condicion;
        
    }
    
// GENERAL
//-----------------------------------------------------------------------------
    
    function eliminar($tema_id)
    {
        //Tabla tema
            $this->db->where('id', $tema_id);
            $this->db->delete('tema');
            
        //Tablas relacionadas
            $tablas = array('recurso', 'programa_tema', 'pagina_flipbook');
            
            foreach( $tablas as $tabla )
            {
                $this->db->where('tema_id', $tema_id);
                $this->db->delete($tabla);
            }
            
        //Tabla meta
            $this->db->where('tabla_id', 4540); //Tabla tema
            $this->db->where('elemento_id', $tema_id);
            $this->db->delete('meta');
    }
    
    function autocompletar($busqueda, $limit = 15)
    {
        //$filtro_rol = $this->filtro_temas($this->session->userdata('usuario_id'));

        //Construir búsqueda
        //Crear array con términos de búsqueda
            if ( strlen($busqueda['q']) > 2 )
            {
                $palabras = $this->Busqueda_model->palabras($busqueda['q']);

                foreach ($palabras as $palabra) {
                    $this->db->like('CONCAT(cod_tema, nombre_tema)', $palabra);
                }
            }
        
        //Especificaciones de consulta
            $this->db->select('id, CONCAT((id), " | ", (nombre_tema)) AS name');
            //$this->db->where($filtro_rol); //Filtro según el rol de usuario que se tenga
            $this->db->order_by('nombre_tema', 'ASC');
            
        //Otros filtros
            if ( $busqueda['condicion'] != '' ) { $this->db->where($busqueda['condicion']); }    //Condición adicional
            
        $query = $this->db->get('tema', $limit); //Resultados por página
        
        return $query;
    }
    
// Grocery Crud
//-----------------------------------------------------------------------------
    
    function crud_basico()
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('tema');
        $crud->set_subject('tema');
        $crud->unset_export();
        $crud->unset_print();
        //$crud->unset_add();
        $crud->unset_back_to_list();
        $crud->unset_delete();
        $crud->unset_read();
        $crud->columns('nombre_tema');
        
        //Títulos de los campos
            $crud->display_as('area_id', 'Área');
            $crud->display_as('descripcion', 'Descripción');
            $crud->display_as('tipo_id', 'Tipo tema');
            $crud->display_as('componente_id', 'Componente');
            $crud->display_as('componente', 'Componente descripción');
            $crud->display_as('cod_tema', 'Cód. tema');
            
        //Opciones
            $arr_tipos = $this->Item_model->arr_interno('categoria_id = 17');
            
        //Opciones nivel
            $opciones_nivel = $this->App_model->opciones_nivel('item_largo');
            $crud->field_type('nivel', 'dropdown', $opciones_nivel);
        
        //Relaciones
            $crud->set_relation('area_id', 'item', 'item', 'categoria_id = 1');
            $crud->set_relation('componente_id', 'item', 'item', 'categoria_id = 8');

        //Formulario Edit
            $crud->edit_fields(
                'cod_tema',    
                'nombre_tema',
                'area_id',
                'nivel',
                'componente_id',
                'componente',
                'tipo_id',
                'descripcion'
            );
            
            $crud->add_fields(
                'cod_tema',    
                'nombre_tema',
                'area_id',
                'nivel',
                'componente_id',
                'componente',
                'tipo_id',
                'descripcion'
            );
            
        //Tipo
            $crud->field_type('tipo_id', 'dropdown', $arr_tipos);

        //Funciones
            //$crud->callback_after_update(array($this, 'gc_after_insert'));

        //Reglas de validación
            $crud->required_fields('nombre_tema', 'area_id', 'nivel', 'tipo_id');
            
        //Valores por defecto
            
        //Formato
            
            $crud->unset_texteditor('descripcion');
        
        $output = $crud->render();
        
        return $output;
        
    }
    
    /**
     * Link para Grocery Crud de los temas
     * 
     * @param type $value
     * @param type $row
     * @return type
     */
    function gc_link_tema($value, $row)
    {
        $texto = substr($row->nombre_tema, 0, 50);
        $att = 'title="Ir al tema ' . $value. '"';
        return anchor("temas/archivos/{$row->id}", $texto, $att);
    }
    
    function gc_after_insert($post_array,$primary_key)
    {
        redirect("temas/preguntas/{$primary_key}");
    }
    
    function crud_archivos($tema_id)
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('recurso');
        $crud->set_subject('archivo');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_read();
        $crud->columns('nombre_archivo', 'tipo_archivo_id', 'fecha_subida', 'disponible');
        
        //Filtro
            $crud->where('tema_id', $tema_id);
            $crud->where('tipo_recurso_id', 1);

        //Callback, vista
            //$crud->callback_column('nombre_tema',array($this,'gc_link_tema'));
        
        //Títulos de los campos
            $crud->display_as('tipo_archivo_id', 'Tipo');
            $crud->display_as('usuario_id', 'Editado por');
            $crud->display_as('fecha_subida', 'Editado');
        
        //Relaciones
            $crud->set_relation('tipo_archivo_id', 'item', 'item', 'categoria_id = 20 AND item_grupo = 1');
            
        //Opciones disponible
            $opciones_disponible = $this->Item_model->opciones('categoria_id = 55 AND id_interno <=1');
            $crud->field_type('disponible', 'dropdown', $opciones_disponible);
        

        //Formulario Edit
            $crud->edit_fields(
                'nombre_archivo',
                'tema_id',
                'tipo_recurso_id',
                'tipo_archivo_id',
                'fecha_subida',
                'disponible',
                'usuario_id'
            );

        //Formulario Add
            $crud->add_fields(
                'nombre_archivo',
                'tema_id',
                'tipo_recurso_id',
                'tipo_archivo_id',
                'fecha_subida',
                'disponible',
                'usuario_id'
            );

        //Funciones
            //$crud->callback_after_update(array($this, 'gc_after_insert'));

        //Reglas de validación
            $crud->required_fields('nombre_archivo', 'tipo_archivo_id');
            
        //Valores por defecto
            $crud->field_type('usuario_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->field_type('fecha_subida', 'hidden', date('Y-m-d H:i:s') );
            $crud->field_type('tema_id', 'hidden', $tema_id );
            $crud->field_type('tipo_recurso_id', 'hidden', 1 ); //1 => Archivos
        
        //Cargue de archivo
            //$this->config->set_item('grocery_crud_file_upload_allow_file_types', 'mp3|pdf|mp4|jpg|png');
            //$crud->set_field_upload('nombre_archivo', 'assets/uploads/audios');
        
        $output = $crud->render();
        
        return $output;
        
    }
    
    function crud_links($tema_id)
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('recurso');
        $crud->set_subject('link');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_read();
        $crud->columns('url', 'tipo_archivo_id', 'fecha_subida', 'usuario_id');
        
        //Filtro
            $crud->where('tema_id', $tema_id);
            $crud->where('tipo_recurso_id', 2);

        //Callback, vista
            $crud->callback_column('url',array($this,'gc_link_recurso'));
        
        //Títulos de los campos
            $crud->display_as('tipo_archivo_id', 'Tipo');
            $crud->display_as('usuario_id', 'Editado por');
            $crud->display_as('fecha_subida', 'Editado');
        
        //Relaciones
            $crud->set_relation('tipo_archivo_id', 'item', 'item', 'categoria_id = 20 AND item_grupo = 2');
        

        //Formulario Edit
            $crud->edit_fields(
                'url',
                'tema_id',
                'tipo_recurso_id',
                'tipo_archivo_id',
                'fecha_subida',
                'usuario_id'
            );

        //Formulario Add
            $crud->add_fields(
                'url',
                'tema_id',
                'tipo_recurso_id',
                'tipo_archivo_id',
                'fecha_subida',
                'usuario_id'
            );

        //Funciones
            //$crud->callback_after_update(array($this, 'gc_after_insert'));

        //Reglas de validación
            $crud->required_fields('url', 'tipo_archivo_id');
            
        //Valores por defecto
            $crud->field_type('usuario_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->field_type('fecha_subida', 'hidden', date('Y-m-d H:i:s') );
            $crud->field_type('tema_id', 'hidden', $tema_id );
            $crud->field_type('tipo_recurso_id', 'hidden', 2 ); //1 => Links
        
        //Cargue de archivo
            //$this->config->set_item('grocery_crud_file_upload_allow_file_types', 'mp3|pdf|mp4|jpg|png');
            //$crud->set_field_upload('nombre_archivo', 'assets/uploads/audios');
        
        $output = $crud->render();
        
        return $output;
        
    }
    
    /**
     * Link para Grocery Crud de los temas
     * 
     * @param type $value
     * @param type $row
     * @return type
     */
    function gc_link_recurso($value, $row)
    {
        $texto = substr($value, 0, 50);
        $att = 'title="Ir al link ' . $value. '" target="_blank"';
        return anchor($value, $texto, $att);
    }

    function crud_relacionados($row_tema)
    {

        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('meta');
        $crud->set_subject('tema relacionado');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_read();
        $crud->columns('relacionado_id', 'categoria_1');
        
        //Filtro
            
            $crud->where('tabla_id', 4540);         //Tabla tema
            $crud->where('elemento_id', $row_tema->id);
            $crud->where('dato_id', 4541);          //Metadatos, temas relacionados

        //Callback, vista
            //$crud->callback_column('url',array($this,'gc_link_recurso'));
        
        //Títulos de los campos
            $crud->display_as('relacionado_id', 'Tema');
            $crud->display_as('categoria_1', 'Tipo relación');
            
        //Arrays
            $arr_relaciones = $this->Item_model->arr_interno('categoria_id = 18');
        
        //Relaciones
            $crud->set_relation('relacionado_id', 'tema', '{cod_tema} :: {nombre_tema}', "tipo_id = 0 AND area_id ={$row_tema->area_id}");

        //Formulario Edit
            
            $crud->edit_fields(
                'tabla_id',
                'elemento_id',
                'relacionado_id',
                'dato_id',
                'categoria_1',
                'fecha',
                'usuario_id'
            );

        //Formulario Add
            $crud->add_fields(
                'tabla_id',
                'elemento_id',
                'relacionado_id',
                'dato_id',
                'categoria_1',
                'fecha',
                'usuario_id'
            );

        //Funciones
            //$crud->callback_after_update(array($this, 'gc_after_insert'));

        //Reglas de validación
            $crud->required_fields('relacionado_id', 'categoria_1');
            
        //Valores por defecto
            $crud->field_type('tabla_id', 'hidden', 4540);
            $crud->field_type('elemento_id', 'hidden', $row_tema->id);
            $crud->field_type('dato_id', 'hidden', 4541);
            $crud->field_type('categoria_1', 'dropdown', $arr_relaciones);
            $crud->field_type('fecha', 'hidden', date('Y-m-d H:i:s') );
            $crud->field_type('usuario_id', 'hidden', $this->session->userdata('usuario_id'));
        
        $output = $crud->render();
        
        return $output;
        
    }
    
// DATOS
//---------------------------------------------------------------------------------------------------------
    
    function paginas($tema_id)
    {
        $this->db->select('pagina_flipbook.id AS pagina_id, orden AS num_pagina, tema_id, archivo_imagen, nombre_tema, titulo_tema');
        $this->db->join('tema', 'pagina_flipbook.tema_id = tema.id', 'LEFT');
        $this->db->where('tema_id', $tema_id);
        $this->db->order_by('orden', 'ASC');
        $paginas = $this->db->get('pagina_flipbook');
        
        return $paginas;
    }
    
    function archivos($tema_id)
    {
        $this->db->where('tema_id', $tema_id);
        $this->db->where('tipo_recurso_id', 1);
        $archivos = $this->db->get('recurso');
        
        return $archivos;
    }
    
    function archivos_leer($tema_id)
    {
        $this->db->select('recurso.*, recurso.id AS archivo_id, nombre_archivo, slug AS tipo_archivo, CONCAT( (slug), (".png")) AS icono, CONCAT( (slug), ("/"),(nombre_archivo)) AS ubicacion');
        $this->db->where('tema_id', $tema_id);
        $this->db->join('item', 'recurso.tipo_archivo_id = item.id');
        $this->db->where('tipo_recurso_id', 1);
        $this->db->where('disponible', 1);
        $archivos = $this->db->get('recurso');
        
        return $archivos;
    }
    
    function preguntas($tema_id)
    {
        $this->db->where('tema_id', $tema_id);
        $this->db->order_by('orden', 'ASC');
        $preguntas = $this->db->get('pregunta');
        
        return $preguntas;
    }
    
    function anotaciones($tema_id, $usuario_id = NULL)
    {
        if ( is_null($usuario_id) ){ $usuario_id = $this->session->userdata('usuario_id'); }
        
        $this->db->select('pagina_id, anotacion, 0 AS num_pagina, pagina_flipbook_detalle.editado');
        $this->db->where('tipo_detalle_id', 3);
        $this->db->where('pagina_flipbook.tema_id', $tema_id);
        $this->db->where('usuario_id', $usuario_id);
        $this->db->join('pagina_flipbook', 'pagina_flipbook.id = pagina_flipbook_detalle.pagina_id');
        $this->db->order_by('num_pagina', 'ASC');
        $anotaciones = $this->db->get('pagina_flipbook_detalle');
        
        return $anotaciones;
    }
    
    /**
     * Array con los temas relacionados a un tema ut
     * 
     * @param type $tema_id
     * @param type $tipo_id
     * @return type
     */
    function arr_relacionados($tema_id, $tipo_id = NULL)
    {
        $this->db->select('relacionado_id AS tema_id');
        $this->db->where('tabla_id', 4540);         //Tabla tema
        $this->db->where('elemento_id', $tema_id);
        $this->db->where('dato_id', 4541);          //Metadatos, temas relacionados
        if ( ! is_null($tipo_id) ) { $this->db->where('categoria_1', $tipo_id); }
        
        $query = $this->db->get('meta');
        
        $arr_relacionados = $this->Pcrn->query_to_array($query, 'tema_id');
        
        return $arr_relacionados;
    }
    
    /**
     * String con los temas relacionados a un tema UT
     * 
     * @param type $tema_id
     * @param type $tipo_id
     * @return type
     */
    function str_relacionados($tema_id, $tipo_id = NULL)
    {
        $arr_relacionados = $this->arr_relacionados($tema_id, $tipo_id);
        $str_relacionados = implode('-', $arr_relacionados);
        
        return $str_relacionados;
    }
    
    /**
     * Array con elementos que deben mostrarse en el menú de la función temas/leer
     * 
     * @param type $row_tema
     * @return boolean
     */
    function elementos_leer($row_tema)
    {
        //Valores por defecto
            $elementos = array(
                'recursos' => 1
            );
            
        //Control herramientas_adicionales
            if ( $elementos['crear_cuestionario'] OR $elementos['temas_relacionados'] ) {
                $elementos['herramientas_adicionales'] = 1;
            }
            
        
        return $elementos;
    }
    
// GESTIÓN DE QUICES (EVIDENCIAS DE APRENDIZAJE) Y TEMAS
//---------------------------------------------------------------------------------------------------------
    
    /**
     * Query con quices (Evidencias de aprendizaje) asociadas a un tema.
     * 
     * @param type $tema_id
     * @return type
     */
    function quices($tema_id)
    {
        $this->db->select('quiz.*');
        $this->db->join('quiz', 'recurso.referente_id = quiz.id');
        $this->db->where('recurso.tema_id', $tema_id);
        $this->db->where('tipo_recurso_id', 3); //Tipo quiz
        $quices = $this->db->get('recurso');
        
        return $quices;
    }
    
    /**
     * Asigna masivamente quices de un tema a otro
     * 
     * @param type $array_hoja    Array con los datos de los temas origen y destino
     * @return type
     */
    function asignar_quices_masivo($array_hoja)
    {
        $no_importados = array();
        $fila = 2;  //Inicia en la fila 2 de la hoja de cálculo
        $this->load->model('Pregunta_model');
        
        foreach ( $array_hoja as $array_fila )
        {
            //Datos referencia
                $tema_id = $this->Pcrn->campo('tema', "cod_tema = '{$array_fila[0]}'", 'id');
                $tema_destino_id = $this->Pcrn->campo('tema', "cod_tema = '{$array_fila[1]}'", 'id');
                
            //Validar
                $condiciones = 0;
                if ( ! is_null($tema_id) ) { $condiciones++; }    //Debe tener tema origen identificado
                if ( ! is_null($tema_destino_id) ) { $condiciones++; }    //Debe tener tema origen identificado
                
            //Si cumple las condiciones
            if ( $condiciones == 2 )
            {   
                //echo "copiar de {$tema_id} a {$tema_destino_id} <br/>";
                $this->asignar_quices($tema_id, $tema_destino_id);
            } else {
                $no_importados[] = $fila;
            }
            
            $fila++;    //Para siguiente fila
        }
        
        $res_importacion['no_importados'] = $no_importados;
        
        return $res_importacion;
    }
    
    /**
     * Asigna todos los quices de un tema a otro.
     * 
     * @param type $tema_id
     * @param type $tema_destino_id
     */
    function asignar_quices($tema_id, $tema_destino_id)
    {
        $quices = $this->quices($tema_id);  //Quices del tema original
        $cant_asignados = 0;
        
        foreach( $quices->result() as $row_quiz )
        {
            $recurso_id =  $this->asignar_quiz($tema_destino_id, $row_quiz->id);
            if ( $recurso_id > 0 ) { $cant_asignados++; }
        }
        
        return $cant_asignados;
    }
    
    /**
     * Asigna un quiz a un tema, registro en la tabla recurso
     * 
     * @param type $tema_id
     * @param type $quiz_id
     * @return type
     */
    function asignar_quiz($tema_id, $quiz_id)
    {
        $registro['tema_id'] = $tema_id;
        $registro['referente_id'] = $quiz_id;
        $registro['tipo_recurso_id'] = 3;
        
        $condicion = "tema_id = {$registro['tema_id']} AND referente_id = {$registro['referente_id']} AND tipo_recurso_id = 3";
        
        $recurso_id = $this->Pcrn->guardar('recurso', $condicion, $registro);
        
        return $recurso_id;
    }
    
    /**
     * Elimina la asignación de un quiz a un tema, de la tabla recurso
     * 
     * @param type $tema_id
     * @param type $quiz_id
     */
    function quitar_quiz($tema_id, $quiz_id)
    {
        $this->db->where('tema_id', $tema_id);
        $this->db->where('referente_id', $quiz_id);
        $this->db->where('tipo_recurso_id', 3);
        $this->db->delete('recurso');
        
        return $this->db->affected_rows();
    }
    
// PROCESOS
//---------------------------------------------------------------------------------------------------------

    /**
     * Crea una copia de un tema, incluyendo los recursos relacionados
     * 
     * @param type $datos
     * @return type 
     */
    function generar_copia($datos)
    {
        
        $row_tema = $this->Pcrn->registro('tema', "id = {$datos['tema_id']}");  //Tema original
        
        //Crear nuevo registro en la tabla tema
            $registro = array(
                'cod_tema' => $datos['cod_tema'],
                'nombre_tema' =>  $datos['nombre_tema'],
                'nivel' =>  $row_tema->nivel,
                'area_id' =>  $row_tema->area_id,
                'componente_id' =>  $row_tema->componente_id,
                'componente_id' =>  $row_tema->componente,
                'editado' =>  date('Y-m-d H:i:s'),
                'usuario_id' => $this->session->userdata('usuario_id'),
                'descripcion' =>  $datos['descripcion'],
                'tipo_id' =>  $row_tema->tipo_id,
            );
        
            $this->db->insert('tema', $registro);
            $tema_id_nuevo = $this->db->insert_id();
            
        //Copiar elementos
            $this->copiar_recursos($datos['tema_id'], $tema_id_nuevo);
            $this->copiar_paginas($datos['tema_id'], $tema_id_nuevo);
            
        return $tema_id_nuevo;  //Se devuelve el id del nuevo tema
    }
    
    /**
     * Asignar los recursos de un tema a otro
     * 
     * @param type $tema_id
     * @param type $tema_id_nuevo
     */
    function copiar_recursos($tema_id, $tema_id_nuevo)
    {
        
        $this->db->where('tema_id', $tema_id);
        $this->db->order_by('id', 'ASC');
        $recursos = $this->db->get('recurso');

        $registro['tema_id'] = $tema_id_nuevo;
        foreach ($recursos->result() as $row_recurso) {
            $registro['nombre_archivo'] = $row_recurso->nombre_archivo;
            $registro['url'] = $row_recurso->url;
            $registro['referente_id'] = $row_recurso->referente_id;
            $registro['tipo_recurso_id'] = $row_recurso->tipo_recurso_id;
            $registro['tipo_archivo_id'] = $row_recurso->tipo_archivo_id;
            $registro['disponible'] = $row_recurso->disponible;
            $registro['fecha_subida'] = $row_recurso->fecha_subida;
            $registro['editado'] = date('Y-m-d H:i:s');
            $registro['usuario_id'] = $this->session->userdata('usuario_id');

            $this->db->insert('recurso', $registro);
        }
    }
    
    /**
     * Crear copias de las páginas, se asignan al nuevo tema
     * Se reutilizan los archivos
     * 
     * @param type $tema_id
     * @param type $tema_id_nuevo
     */
    function copiar_paginas($tema_id, $tema_id_nuevo)
    {
        
        $this->db->where('tema_id', $tema_id);
        $this->db->order_by('id', 'ASC');
        $paginas = $this->db->get('pagina_flipbook');

        $registro['tema_id'] = $tema_id_nuevo;
        foreach ($paginas->result() as $row_pagina) {
            $registro['titulo_pagina'] = $row_pagina->titulo_pagina;
            $registro['orden'] = $row_pagina->orden;
            $registro['en_tema'] = $row_pagina->en_tema;
            $registro['archivo_imagen'] = $row_pagina->archivo_imagen;
            $registro['pagina_origen_id'] = $row_pagina->pagina_origen_id;
            $registro['editado'] = date('Y-m-d H:i:s');

            $this->db->insert('pagina_flipbook', $registro);
        }
    }
    
// IMPORTACIÓN DE DATOS MS-EXCEL
//-----------------------------------------------------------------------------
    
    /**
     * Inserta masivamente temas
     * tabla tema
     * 
     * @param type $array_hoja    Array con los datos de los temas
     */
    function importar($array_hoja)
    {   
        $this->load->model('Esp');
        
        $no_importados = array();
        $fila = 2;  //Inicia en la fila 2 de la hoja de cálculo
        
        //$areas = $this->Esp->arr_cod_area();
        $componentes = $this->Esp->arr_componentes();
            
        //Predeterminados registro nuevo
            $registro['editado'] = date('Y-m-d H:i:s');
            $registro['usuario_id'] = $this->session->userdata('usuario_id');
        
        foreach ( $array_hoja as $array_fila )
        {
            //Identificar valores
                $componente_id = 0;
                if ( array_key_exists(intval($array_fila[4]), $componentes) ) { $componente_id = $componentes[$array_fila[4]]; }
            
            //Complementar registro
                $registro['cod_tema'] = $array_fila[0];
                $registro['nombre_tema'] = $array_fila[1];
                $registro['area_id'] = $array_fila[2];           //Columna C
                $registro['nivel'] = $array_fila[3];            //Columna D
                $registro['componente_id'] = $componente_id;
                $registro['componente'] = $array_fila[5];       //Columna F
                $registro['descripcion'] = $array_fila[6];      //Columna G
                $registro['tipo_id'] = $array_fila[7];          //Columna H
                
            //Validar
                $condiciones = 0;
                if ( strlen($array_fila[1]) > 0 ) { $condiciones++; }   //Debe tener nombre escrito
                if ( strlen($array_fila[2]) > 0 ) { $condiciones++; }    //Tiene área diligenciada
                
            //Si cumple las condiciones
            if ( $condiciones == 2 )
            {
                $this->Pcrn->guardar('tema', "cod_tema = '{$registro['cod_tema']}'", $registro);
            } else {
                $no_importados[] = $fila;
            }
            
            $fila++;    //Para siguiente fila
        }
        
        return $no_importados;
    }
    
    /**
     * Asigna masivamente temas a las unidades temáticas
     * tabla meta
     * 
     * @param type $array_hoja    Array con los datos de asignación
     */
    function importar_ut($array_hoja)
    {   
        $this->load->model('Meta_model');
        
        $no_importados = array();
        $fila = 2;  //Inicia en la fila 2 de la hoja de cálculo
        
        $arr_relaciones = $this->Item_model->arr_interno('categoria_id = 18');
        
        //Predeterminados registro nuevo
        
        foreach ( $array_hoja as $array_fila )
        {
            //Identificar valores
                $tipo_relacion_id = 0;
                if ( array_key_exists(intval($array_fila[2]), $arr_relaciones) ) { $tipo_relacion_id = $array_fila[2]; }
                
                $ut_id = $this->Pcrn->campo('tema', "cod_tema = '{$array_fila[0]}'", 'id');
                $tema_id = $this->Pcrn->campo('tema', "cod_tema = '{$array_fila[1]}'", 'id');
            
            //Complementar registro
                $registro['tabla_id'] = 4540;   //Tabla tema
                $registro['dato_id'] = 4541;   //Metadato, asignación de tema
                $registro['elemento_id'] = $this->Pcrn->si_nulo($ut_id, 0);             //ID de unidad temática
                $registro['relacionado_id'] = $this->Pcrn->si_nulo($tema_id, 0);    //ID del tema asignado
                $registro['categoria_1'] = $tipo_relacion_id;                           //Tipo de relación del tema con la unidad temática
                
            //Validar
                $condiciones = 0;
                if ( ! is_null($ut_id) ) { $condiciones++; }                //Tiene unidad temática identificada
                if ( ! is_null($tema_id) ) { $condiciones++; }              //Tiene tema identificado
                if ( $tipo_relacion_id != 0 ) { $condiciones++; }           //Tiene tipo de relación identificada
                
            //Si cumple las condiciones
            if ( $condiciones == 3 )
            {   
                $this->Meta_model->guardar($registro);
            } else {
                $no_importados[] = $fila;
            }
            
            $fila++;    //Para siguiente fila
        }
        
        return $no_importados;
    }
    
    /**
     * Copia masivamente preguntas de un tema y se las asigna a otro
     * 
     * @param type $array_hoja    Array con los datos de los temas origen y destino
     * @return type
     */
    function copiar_preguntas_masivo($array_hoja)
    {
        $no_importados = array();
        $fila = 2;  //Inicia en la fila 2 de la hoja de cálculo
        $this->load->model('Pregunta_model');
        
        foreach ( $array_hoja as $array_fila )
        {
            //Datos referencia
                $tema_id = $this->Pcrn->campo('tema', "cod_tema = '{$array_fila[0]}'", 'id');
                $tema_destino_id = $this->Pcrn->campo('tema', "cod_tema = '{$array_fila[1]}'", 'id');
                
            //Validar
                $condiciones = 0;
                if ( ! is_null($tema_id) ) { $condiciones++; }    //Debe tener tema origen identificado
                if ( ! is_null($tema_destino_id) ) { $condiciones++; }    //Debe tener tema origen identificado
                
            //Si cumple las condiciones
            if ( $condiciones == 2 )
            {   
                //echo "copiar de {$tema_id} a {$tema_destino_id} <br/>";
                $this->copiar_preguntas($tema_id, $tema_destino_id);
            } else {
                $no_importados[] = $fila;
            }
            
            $fila++;    //Para siguiente fila
        }
        
        $res_importacion['no_importados'] = $no_importados;
        
        return $res_importacion;
    }
    
    
    /**
     * Crea copia de la preguntas de un tema y se las asigna a otro
     * 
     * @param type $tema_id
     * @param type $tema_destino_id
     */
    function copiar_preguntas($tema_id, $tema_destino_id)
    {
        $preguntas = $this->preguntas($tema_id);
        $preguntas_destino = $this->preguntas($tema_destino_id);
        
        //Solo se copian si el tema destino no tiene preguntas
        if ( $preguntas_destino->num_rows() == 0 )
        {
            foreach( $preguntas->result() as $row_pregunta ) 
            {
                $this->Pregunta_model->clonar($row_pregunta->id, $tema_destino_id);
            }
        }
    }
    
    
// GESTIÓN DE PÁGINAS DE TEMAS
//-----------------------------------------------------------------------------
    
    
    /**
     * Enumerar las páginas de un tema, campo pagina_flipbook.orden
     * 
     * @param type $tema_id
     */
    function enumerar_pf($tema_id)
    {
        
        $orden = 0;
        
        $this->db->where('tema_id', $tema_id);
        $this->db->where('en_tema', 1);
        $this->db->order_by('orden', 'ASC');
        $paginas = $this->db->get('pagina_flipbook');
        
        foreach ($paginas->result() as $row_pf){
            
            $registro['orden'] = $orden;
            $this->db->where('id', $row_pf->id);
            $this->db->update('pagina_flipbook', $registro);
            
            $orden += 1;
        }
    }
    
    /**
     * Cambia el valor del campo pagina_flipbook.orden para una página
     * Modifica los valores de ese campo para las páginas contiguas
     * cambiar_pos_pag: Cambiar posición de página
     * 
     * @param type $tema_id
     * @param type $pf_id
     * @param type $pos_final
     * @return type
     */
    function cambiar_pos_pag($tema_id, $pf_id, $pos_final)
    {
        //Fila de la página que se va a mover
            $row_pagina = $this->Pcrn->registro_id('pagina_flipbook', $pf_id);
            
        //Condición que selecciona el conjunto de registros a modificar
            $condicion_1 = "tema_id = {$tema_id} AND en_tema = 1";    
        
        //Variables proceso
            $pos_inicial = $row_pagina->orden;  //Posición actual del objeto
            $cant_registros = $this->Pcrn->num_registros('pagina_flipbook', $condicion_1);
            
            //Control: Limitar la posición final en la que se ubicará la página
            $pos_final = $this->Pcrn->limitar_entre($pos_final, 0, $cant_registros - 1);    //Menos uno porque el conteo inicia en 0
        
        //Hacer cambios si los valores de posición son diferentes
        if ( $pos_final != $pos_inicial ){
            
            if ( $pos_final > $pos_inicial ){
                $operacion = 'orden = orden - 1';
                $condicion_2 = "orden > {$pos_inicial} AND orden <= {$pos_final}";
            } elseif ( $pos_final < $pos_inicial ) {
                $operacion = 'orden = orden + 1';
                $condicion_2 = "orden >= {$pos_final} AND orden < {$pos_inicial}";
            }
            
            //Cambiar el valor de las páginas contiguas
                $sql = 'UPDATE pagina_flipbook';
                $sql .= " SET {$operacion}";
                $sql .= " WHERE {$condicion_1}";
                $sql .= " AND {$condicion_2}";

                $this->db->query($sql);
        
            //Cambiar la posición a la página específica
                $registro['orden'] = $pos_final;
                $this->db->where('id', $pf_id);
                $this->db->update('pagina_flipbook', $registro);
        }
        
        return $sql;
        
    }
    
    /**
     * Cambia el valor del campo pregunta.orden para una pregunta
     * Modifica los valores de ese campo para las preguntas contiguas
     * cambiar_pos_pregunta: Cambiar posición de pregunta
     * 
     * @param type $tema_id
     * @param type $pregunta_id
     * @param type $pos_final
     * @return type
     */
    function cambiar_pos_pregunta($tema_id, $pregunta_id, $pos_final)
    {
        //Definición de variables
            $sql = '';
        
        //Fila de la pregunta que se va a mover
            $row_pregunta = $this->Pcrn->registro_id('pregunta', $pregunta_id);
            
        //Condición que selecciona el conjunto de registros a modificar
            $condicion_1 = "tema_id = {$tema_id}";
        
        //Variables proceso
            $pos_inicial = $row_pregunta->orden;  //Posición actual del objeto
            $cant_registros = $this->Pcrn->num_registros('pregunta', $condicion_1);
            
            //Control: Limitar la posición final en la que se ubicará la pregunta
            $pos_final = $this->Pcrn->limitar_entre($pos_final, 0, $cant_registros - 1);    //Menos uno porque el conteo inicia en 0
        
        //Hacer cambios si los valores de posición son diferentes
        if ( $pos_final != $pos_inicial ){
            
            if ( $pos_final > $pos_inicial ){
                $operacion = 'orden = orden - 1';
                $condicion_2 = "orden > {$pos_inicial} AND orden <= {$pos_final}";
            } elseif ( $pos_final < $pos_inicial ) {
                $operacion = 'orden = orden + 1';
                $condicion_2 = "orden >= {$pos_final} AND orden < {$pos_inicial}";
            }
            
            //Cambiar el valor de las preguntas contiguas
                $sql = 'UPDATE pregunta';
                $sql .= " SET {$operacion}";
                $sql .= " WHERE {$condicion_1}";
                $sql .= " AND {$condicion_2}";

                $this->db->query($sql);
        
            //Cambiar la posición a la pregunta específica
                $registro['orden'] = $pos_final;
                $this->db->where('id', $pregunta_id);
                $this->db->update('pregunta', $registro);
        }
        
        return $sql;
        
    }
    
    /**
     * Actualiza el campo pregunta.orden, según el orden de las preguntas
     * @param type $tema_id
     */
    function numerar_preguntas($tema_id)
    {
        $this->db->where('tema_id', $tema_id);
        $this->db->order_by('orden', 'ASC');
        $preguntas = $this->db->get('pregunta');
        
        $num_pregunta = 0;
        
        foreach ($preguntas->result() as $row_pregunta)
        {
            $registro['orden'] = $num_pregunta;
            $this->db->where('id', $row_pregunta->id);
            $this->db->update('pregunta', $registro);
            
            $num_pregunta += 1;
        }
    }
    
    function quitar_pregunta($tema_id, $pregunta_id)
    {
        //Editar registro
            $registro['tema_id'] = NULL;
            $registro['orden'] = 0;
            
            $this->db->where('id', $pregunta_id);
            $this->db->update('pregunta', $registro);
            
        //Reenumerar el tema
            $this->numerar_preguntas($tema_id);
    }
    
    function actualizar_paginas_total($temas)
    {
        $cant_paginas = 0;
        foreach ( $temas->result() as $row_tema ) {
            $this->eliminar_paginas($row_tema->id);
            $cant_paginas += $this->actualizar_paginas_tema($row_tema);
        }
        
        return $cant_paginas;
    }
    
    function eliminar_paginas($tema_id){
        $this->db->where('tema_id', $tema_id);
        $paginas = $this->db->get('pagina_flipbook');
        
        foreach ( $paginas->result() as $row_pagina ) {
            $this->Pagina_model->eliminar($row_pagina->id);
        }
        
        return $paginas->num_rows();
    }
    
    function actualizar_paginas($row_tema)
    {
        $cargar = 0;
        $carpeta = RUTA_UPLOADS . 'pf_zoom/';
        for ($i = 0; $i <= 10; $i++) {
            $nombre_archivo = $row_tema->cod_tema . '-' . $i . '.jpg';
            $ruta_archivo = $carpeta . $nombre_archivo;
            
            if ( file_exists($ruta_archivo) ) { 
                $cargar += 1; 
                $orden = $i;
                $this->agregar_pagina($row_tema, $nombre_archivo, $orden);
            }
        }
        
        return $cargar;
    }
    
    function agregar_pagina($row_tema, $nombre_archivo, $orden)
    {
        //Crear página
            $registro['titulo_pagina'] = $row_tema->nombre_tema . ' - ' . $orden;
            $registro['tema_id'] = $row_tema->id;
            $registro['en_tema'] = 1;
            $registro['archivo_imagen'] = $nombre_archivo;
            $registro['editado'] = date('Y-m-d H:i:s');
            $registro['orden'] = $orden;

            $this->db->insert('pagina_flipbook', $registro);

            $pagina_id = $this->db->insert_id();
        
        //Asignar el tema, con el campo orden correcto
            $this->Pagina_model->asignar_tema($pagina_id, $registro);
        
        return $pagina_id;
    }

// GESTIÓN DE LINKS
//-----------------------------------------------------------------------------

    /**
     * Listado de links asociados a un tema
     * 2019-09-02
     */
    function links($tema_id)
    {
        $this->db->select('id, titulo, url');
        $this->db->where('tema_id', $tema_id);
        $this->db->where('tipo_recurso_id', 2);
        $links = $this->db->get('recurso');
        
        return $links;
    }

    /**
     * Guardar link asociado a un tema en la tabla recurso
     * 2019-09-03
     */
    function save_link($tema_id, $link_id)
    {
        //Resultado inicial por defecto
            $data = array('status' => 0, 'message' => 'El link no fue guardado');

        //Construir registro
            $arr_row['titulo'] = $this->input->post('titulo');
            $arr_row['url'] = $this->input->post('url');
            $arr_row['tema_id'] = $tema_id;
            $arr_row['tipo_recurso_id'] = 2;    //Link
            $arr_row['editado'] = date('Y-m-d H:i:s');
            $arr_row['usuario_id'] = $this->session->userdata('user_id');

        //Guardar
            $condition = "tema_id = {$tema_id} AND id = {$link_id}";
            $saved_id = $this->Pcrn->guardar('recurso', $condition, $arr_row);
        
            if ( $saved_id > 0 )
            {
                $data = array('status' => 1, 'message' => 'Los datos del link fueron guardados', 'link_id' => $saved_id);
            }
    
        return $data;
    }

    /**
     * Elimina un link de la tabla recurso, asociado a un $tema_id.
     * 2019-09-03
     */
    function delete_link($tema_id, $link_id)
    {
        $data = array('status' => 0, 'message' => 'No se pudo eliminar el link');
    
        $this->db->where('id', $link_id);
        $this->db->where('tema_id', $tema_id);
        $this->db->delete('recurso');
        
        $quan_deleted = $this->db->affected_rows();
    
        if ( $quan_deleted > 0 )
        {
            $data = array('status' => 1, 'message' => 'Link eliminado');
        }
    
        return $data;
    }

// GESTIÓN DE PREGUNTAS ABIERTAS (pa)
//-----------------------------------------------------------------------------

    /**
     * Listado de preguntas abiertas asociadas a un tema
     * 2019-09-02
     */
    function preguntas_abiertas($tema_id)
    {
        $this->db->select('id, contenido');
        $this->db->where('referente_1_id', $tema_id);
        $this->db->where('referente_2_id', 1);  //Pública, creadas por ELE
        $this->db->where('tipo_id', 121);   //Post tipo 121
        $preguntas_abiertas = $this->db->get('post');
        
        return $preguntas_abiertas;
    }

    /**
     * Guardar link asociado a un tema en la tabla recurso
     * 2019-09-03
     */
    function save_pa($tema_id, $pa_id)
    {
        //Resultado inicial por defecto
            $data = array('status' => 0, 'message' => 'La pregunta no fue guardada');

        //Construir registro
            $arr_row['tipo_id'] = 121;  //Pregunta abierta
            $arr_row['referente_1_id'] = $tema_id;  //Tema asociado
            $arr_row['referente_2_id'] = $this->input->post('referente_2_id');
            $arr_row['contenido'] = $this->input->post('contenido');

        //Guardar
            $this->load->model('Post_model');
            $condition = "referente_1_id = {$tema_id} AND id = {$pa_id}";
            $saved_id = $this->Post_model->guardar_post($condition, $arr_row);
        
            if ( $saved_id > 0 )
            {
                $data = array('status' => 1, 'message' => 'La pregunta fue guardada correctamente', 'saved_id' => $saved_id);
            }
    
        return $data;
    }

    /**
     * Elimina una pregunta abierta (pa) de la tabla post, asociada a un $tema_id.
     * 2019-09-03
     */
    function delete_pa($tema_id, $pa_id)
    {
        $data = array('status' => 0, 'message' => 'No se pudo eliminar la pregunta');
    
        $this->db->where('id', $pa_id);
        $this->db->where('referente_1_id', $tema_id);
        $this->db->where('tipo_id', 121);   //Post tipo Pregunta abierta 121
        $this->db->delete('post');
        
        $quan_deleted = $this->db->affected_rows();
    
        if ( $quan_deleted > 0 )
        {
            $data = array('status' => 1, 'message' => 'Pregunta eliminada');
        }
    
        return $data;
    }

    /**
     * Importar masivamente preguntas abiertas a temas
     * tabla post, tipo_id = 121
     * 2019-09-06
     * 
     * @param type $array_hoja    Array con los datos de preguntas
     */
    function importar_pa($array_hoja)
    {
        $no_importados = array();
        $fila = 2;  //Inicia en la fila 2 de la hoja de cálculo
        
        //Predeterminados registro nuevo
        $arr_row['tipo_id'] = 121;
        $arr_row['referente_2_id'] = 1; //Pública, agregada por ELE

        $this->load->model('Post_model');
        
        foreach ( $array_hoja as $array_fila )
        {
            //Identificar valores
                $tema_id = $this->Pcrn->campo('tema', "cod_tema = '{$array_fila[0]}'", 'id');
            
            //Complementar registro
                $arr_row['referente_1_id'] = $tema_id;
                $arr_row['contenido'] = $array_fila[1];
                
            //Validar
                $condiciones = 0;
                if ( ! is_null($tema_id) ) { $condiciones++; }                  //Tiene tema identificado
                if ( strlen($arr_row['contenido']) > 0 ) { $condiciones++; }    //Tiene contenido texto escrito
                
            //Si cumple las condiciones
            if ( $condiciones == 2 )
            {   
                $this->Post_model->guardar_post('id = 0', $arr_row);    //Condición imposible, siempre agrega
            } else {
                $no_importados[] = $fila;
            }
            
            $fila++;    //Para siguiente fila
        }
        
        return $no_importados;
    }

// LECTURAS DINAMICAS (ledin)
//-----------------------------------------------------------------------------

    /**
     * Query lecturas dinámicas asociadas a un tema
     * 2019-11-21
     */
    function ledins($tema_id)
    {
        //$this->db->select('id, nombre');
        $this->db->where('tipo_id', 125);
        $this->db->where('referente_1_id', $tema_id);
        $ledins = $this->db->get('post');

        return $ledins;
    }

    /**
     * Registro lectura dinámica (ledin_id)
     */
    function ledin($ledin_id)
    {
        $ledin = $this->Pcrn->registro_id('post', $ledin_id);
        return $ledin;
    }

    /**
     * Importar masivamente ledins a temas
     * tabla post, tipo_id = 125
     * 2019-10-17
     * 
     * @param type $array_hoja    Array con los datos de preguntas
     */
    function importar_ledins($array_hoja)
    {
        $no_importados = array();
        $fila = 2;  //Inicia en la fila 2 de la hoja de cálculo
        
        $this->load->model('Post_model');
        
        foreach ( $array_hoja as $array_fila )
        {
            //Identificar valores
                $tema_id = $this->Pcrn->campo('tema', "cod_tema = '{$array_fila[0]}'", 'id');
                
            //Validar
                $condiciones = 0;
                if ( ! is_null($tema_id) ) { $condiciones++; }                  //Tiene tema identificado
                if ( strlen($array_fila[2]) > 0 ) { $condiciones++; }           //Tiene nombre de archivo
                
            //Si cumple las condiciones
            if ( $condiciones == 2 )
            {
                $post_id = $this->importar_ledin($tema_id, $array_fila);
            } else {
                $no_importados[] = $fila;
            }
            
            $fila++;    //Para siguiente fila
        }
        
        return $no_importados;
    }

    /**
     * Realiza la importación de lectura ledin de cada fila en el archivo excel
     * 2019-10-17
     */
    function importar_ledin($tema_id, $array_fila)
    {
        //Construir registro
            $arr_row['nombre_post'] = $array_fila[1];
            $arr_row['tipo_id'] = 125;
            $arr_row['contenido_json'] = $this->ledin_json($array_fila);
            $arr_row['contenido'] = $this->ledin_lectura($array_fila[0]);
            $arr_row['texto_1'] = $array_fila[0] . '.txt';   //Nombre archivo de texto lectura
            $arr_row['texto_2'] = $array_fila[2];            //Nombre archivo imagen asociada
            $arr_row['referente_1_id'] = $tema_id;

        //Guardar
            $post_id = $this->Post_model->guardar_post('id = 0', $arr_row);    //Condición imposible, siempre agrega

        return $post_id;
    }

    /**
     * A partir de la fila del archivo de excel, se construye un el contenido json de la lectura ledin,
     * que incluye la lectura original, y el conjunto de palabras y definiciones.
     */
    function ledin_json($array_fila)
    {
        //$contenido = array('lectura' => '', 'palabras' => NULL);

        $contenido['lectura'] = $this->ledin_lectura($array_fila[0]);
        $contenido['palabras'] = $this->ledin_palabras($array_fila);
        $contenido['lectura_dinamica'] = $this->ledin_lectura_dinamica($contenido);
        $contenido['diccionario'] = $this->ledin_diccionario($contenido);

        $contenido_json = json_encode($contenido);

        return $contenido_json;
    }

    function ledin_lectura($file_name)
    {
        $lectura = 'NO HAY LECTURA DISPONIBLE';

        $file_path = RUTA_UPLOADS . 'lecturas_dinamicas/' . $file_name . '.txt';

        if ( file_exists($file_path) )
        {
            $lectura = file_get_contents($file_path);
        }

        return $lectura;
    }

    /**
     * Array palabras y su definición
     */
    function ledin_palabras($array_fila)
    {
        $palabras = array();

        for ($i=3; $i < 22; $i+=2)
        {
            $palabra = array(
                'titulo' => $array_fila[$i],
                'definicion' => $array_fila[$i+1]
            );

            if ( strlen($palabra['titulo']) > 0 ) { $palabras[] = $palabra; }
        }

        return $palabras;
    }

    function ledin_contenido_total($contenido_json)
    {
        $contenido = $this->ledin_contenido($contenido_json);
        $contenido .= $this->ledin_lectura_dinamica($contenido_json);

        return $contenido;
    }

    function ledin_diccionario($contenido)
    {
        $diccionario = $contenido['lectura'];
        
        foreach ($contenido['palabras'] as $palabra)
        {
            $modificado = '<span class="con_definicion" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="' . $palabra['definicion'] . '">';
            $modificado .= $palabra['titulo'];
            $modificado .= '</span>';
            $diccionario = str_replace($palabra['titulo'], $modificado, $diccionario);
        }

        $diccionario = str_replace('<br>', '<br><br>', $diccionario);   //Doble salto de renglón

        return $diccionario;
    }

    function ledin_lectura_dinamica($contenido)
    {
        $ledin = '';
        $palabras = explode(' ', $contenido['lectura']);

        foreach ($palabras as $palabra)
        {
            $ledin .= '<span>' . $palabra .  '</span> ';
        }

        $ledin = str_replace('<br>', '<br><br>', $ledin);   //Doble salto de renglón

        return $ledin;
    }

}