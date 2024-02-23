<?php
class Programa_Model extends CI_Model{
    
    function basic($programa_id)
    {
        
        //programas
            $this->db->where('programa_id', $programa_id);
            $programas = $this->db->get('programa_tema');
        
        $row_programa = $this->Pcrn->registro_id('programa', $programa_id);
        
        //Datos adicionales
        $row_programa->cant_programas = $programas->num_rows();
        
        $basic['programas'] = $programas;
        $basic['programa_id'] = $programa_id;
        $basic['row'] = $row_programa;
        $basic['head_title'] = $row_programa->nombre_programa;
        $basic['view_a'] = 'programas/programa_v';
        
        return $basic;
    }

// EXPLORE FUNCTIONS - programas/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($filters, $num_page, $per_page = 10)
    {
        //Data inicial, de la tabla
            $data = $this->get($filters, $num_page, $per_page);
        
        //Elemento de exploración
            $data['controller'] = 'programas';                       //Nombre del controlador
            $data['cf'] = 'programas/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'admin/programas/explore/';      //Carpeta donde están las vistas de exploración
            $data['numPage'] = $num_page;                       //Número de la página
            
        //Vistas
            $data['head_title'] = 'Programas';
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = 'admin/programas/menus/explore_v';
        
        return $data;
    }

    function get($filters, $num_page, $per_page = 10)
    {
        //Load
            $this->load->model('Search_model');

        //Búsqueda y Resultados
            $data['filters'] = $filters;
            $offset = ($num_page - 1) * $per_page;      //Número de la página de datos que se está consultado
            $elements = $this->search($filters, $per_page, $offset);    //Resultados para página
        
        //Cargar datos
            $data['list'] = $elements->result();
            $data['strFilters'] = $this->Search_model->str_filters($filters, TRUE);
            $data['qtyResults'] = $this->qty_results($filters);
            $data['maxPage'] = ceil($this->pml->if_zero($data['qtyResults'],1) / $per_page);   //Cantidad de páginas

        return $data;
    }

    /**
     * Segmento Select SQL, con diferentes formatos, consulta de programas
     * 2022-08-23
     */
    function select($format = 'general')
    {
        $arr_select['general'] = 'programa.*, institucion.nombre_institucion, usuario.username';
        $arr_select['export'] = '*';

        return $arr_select[$format];
    }
    
    /**
     * Query con resultados de programas filtrados, por página y offset
     * 2020-07-15
     */
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        //Segmento SELECT
            $select_format = 'general';
            if ( $filters['sf'] != '' ) { $select_format = $filters['sf']; }
            $this->db->select($this->select($select_format));
            $this->db->join('institucion', 'programa.institucion_id = institucion.id','left');
            $this->db->join('usuario', 'programa.usuario_id = usuario.id', 'left');
        
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
            $query = $this->db->get('programa', $per_page, $offset); //Resultados por página
        
        return $query;
        
    }

    /**
     * String con condición WHERE SQL para filtrar post
     * 2022-05-02
     */
    function search_condition($filters)
    {
        $condition = NULL;

        $condition .= $this->role_filter() . ' AND ';

        //q words condition
        $search_fields = ['nombre_programa', 'descripcion'];
        $words_condition = $this->Search_model->words_condition($filters['q'], $search_fields);
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Otros filtros
        if ( $filters['i'] != '' ) { $condition .= "programa.institucion_id = ({$filters['i']}) AND "; }
        if ( $filters['n'] != '' ) { $condition .= "nivel = ({$filters['n']}) AND "; }
        if ( $filters['a'] != '' ) { $condition .= "area_id = {$filters['a']} AND "; }
        
        //Quitar cadena final de ' AND '
        if ( strlen($condition) > 0 ) { $condition = substr($condition, 0, -5);}
        
        return $condition;
    }
    
    /**
     * Devuelve la cantidad de registros encontrados en la tabla con los filtros
     * establecidos en la búsqueda
     */
    function qty_results($filters)
    {
        $this->db->select('id');
        $search_condition = $this->search_condition($filters);
        if ( $search_condition ) { $this->db->where($search_condition);}
        $query = $this->db->get('programa'); //Para calcular el total de resultados

        return $query->num_rows();
    }

    /**
     * Query para exportar
     * 2022-08-17
     */
    function query_export($filters)
    {
        //Select
        $select = $this->select('export');
        if ( $filters['sf'] != '' ) { $select = $this->select($filters['sf']); }
        $this->db->select($select);

        //Condición Where
        $search_condition = $this->search_condition($filters);
        if ( $search_condition ) { $this->db->where($search_condition);}

        //Get
        $query = $this->db->get('programa', 10000);  //Hasta 10.000 registros

        return $query;
    }
    
    /**
     * Devuelve segmento SQL
     */
    function role_filter()
    {
        $role = $this->session->userdata('role');
        $condition = 'programa.id > 0';  //Valor por defecto, ningún post, se obtendrían cero programas.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los post
            $condition = 'programa.id > 0';
        } elseif ( $role == 3 ) {
            $condition = 'type_id IN (311,312)';
        }
        
        return $condition;
    }
    
    /**
     * Array con options para ordenar el listado de post en la vista de
     * exploración
     */
    function order_options()
    {
        $order_options = array(
            '' => '[ Ordenar por ]',
            'id' => 'ID Post',
            'post_name' => 'Nombre'
        );
        
        return $order_options;
    }

// CRUD
//-----------------------------------------------------------------------------

    /**
     * Eliminar registro de la tabla programa y tablas relacionadas
     * 2023-08-01
     */
    function delete($programa_id)
    {
        $qty_deleted = 0;
        //De la tabla programa
            $this->db->where('id', $programa_id);
            $this->db->delete('programa');
            $qty_deleted = $this->db->affected_rows();
            
        //Tablas relacionadas
            $this->db->where('programa_id', $programa_id);
            $this->db->delete('programa_tema');

        return $qty_deleted;
    }

// Datos
//-----------------------------------------------------------------------------
    
    function reciente()
    {
        $programa_id = 0;
        
        $this->db->where('usuario_id', $this->session->userdata('usuario_id'));
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('programa');
        
        if ( $query->num_rows() > 0 ) {
            $programa_id = $query->row()->id;
        }
        
        return $programa_id;
    }
    
    function opciones_tema($programa_id)
    {
        $this->db->select("CONCAT('cod_', (orden + 1)) as tema_id, nombre_tema"); 
        $this->db->join('programa_tema', 'tema.id = programa_tema.tema_id');
        $this->db->where('programa_id', $programa_id);
        $this->db->order_by('orden', 'ASC');
        $query = $this->db->get('tema');
        
        $campo_indice = "tema_id";
        $campo_valor = "nombre_tema";
        
        $opciones_defecto = array(
            "cod_0" => "(Inicio)"
        );
        
        $opciones_tema = array_merge($opciones_defecto, $this->Pcrn->query_to_array($query, $campo_valor, $campo_indice));
        
        return $opciones_tema;
    }
    
    /**
     * Grocery Crud Config Programa
     * 2023-08-06
     */
    function crud_basico()
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('programa');
        $crud->set_subject('programa');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_back_to_list();
        $crud->unset_delete();
        $crud->unset_read();
        
        //Filtro
            $crud->where('programa.id', 0);
        
        //Títulos de los campos
            $crud->display_as('area_id', 'Área');
            $crud->display_as('descripcion', 'Descripción');
            $crud->display_as('anio_generacion', 'Año generación');
        
        //Relaciones
            $crud->set_relation('area_id', 'item', 'item', 'categoria_id = 1');
            
        //Redirigir después de crear el programa
            $controller = 'admin/programas';
            $function = 'reciente';
            $crud->set_lang_string(
                'insert_success_message',
                'El programa ha sido actualizado<br/>Por favor espere mientras se abre el editor de programas.
                    <script type="text/javascript">
                        window.location = "'.site_url($controller.'/'.$function).'";
                    </script>
                <div style="display:none">
                '
            );

        //Formulario Edit
            $crud->edit_fields(
                'nombre_programa',
                'area_id',
                'nivel',
                'cantidad_unidades',
                'institucion_id',
                'descripcion',
                'editado'
            );
            
            $crud->add_fields(
                'nombre_programa',
                'area_id',
                'nivel',
                'anio_generacion',
                'institucion_id',
                'cantidad_unidades',
                'descripcion',
                'usuario_id',
                'creado',
                'editado'
            );
            
        //Formato nivel
            $opciones_nivel = $this->Item_model->opciones('categoria_id = 3');
            $crud->field_type('nivel', 'dropdown', $opciones_nivel);
            
        //Formato
            $crud->field_type('usuario_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->field_type('creado', 'hidden', date('Y-m-d H:i:s'));
            $crud->field_type('editado', 'hidden', date('Y-m-d H:i:s'));
            $crud->field_type('anio_generacion', 'dropdown', range(date('Y')-1, date('Y')+5));
            
        //Si es usuario institucional
            if ( $this->session->userdata('institucion_id') != 0 ) {
                //Es usuario institucional
                $crud->field_type('institucion_id', 'hidden', $this->session->userdata('institucion_id'));
            } else {
                //Es usuario interno
                $crud->display_as('institucion_id', 'Institución');
                $crud->set_relation('institucion_id', 'institucion', 'nombre_institucion');
            }
            
        //Reglas de validación
            $crud->required_fields('nombre_programa', 'area_id', 'nivel', 'cantidad_unidades');
        
        //Formato
            $crud->unset_texteditor('descripcion');
        
        $output = $crud->render();
        
        return $output;
        
    }
    
    function gc_after_update($post_array,$primary_key)
    {
        $this->act_campo_temas($primary_key);
    }
    
    /**
     * Link para Grocery Crud de los programas
     * 
     * @param type $value
     * @param type $row
     * @return type
     */
    function gc_link_programa($value, $row)
    {
        $texto = substr($row->nombre_programa, 0, 50);
        $att = 'title="Ir al programa ' . $value. '"';
        return anchor("admin/programas/temas/{$row->id}", $texto, $att);
    }
    
    function gc_after_insert($post_array,$primary_key)
    {
        redirect("admin/programas/temas/{$primary_key}");
    }
    
// GESTIÓN DE TEMAS
//---------------------------------------------------------------------------------------------------------
    
    /**
     * Objeto query con temas de un programa
     * 2023-08-07
     */
    function temas($programa_id)
    {
        $this->db->select('tema.*, programa_tema.id AS pt_id, programa_tema.orden, programa_tema.unidad');
        $this->db->where('programa_id', $programa_id);
        $this->db->join('programa_tema', 'tema.id = programa_tema.tema_id');
        $this->db->order_by('unidad', 'ASC');
        $this->db->order_by('orden', 'ASC');
        $query = $this->db->get('tema');
        
        return $query;
    }

    /**
     * Guardar un registro en la tabla programa_tema
     * 2023-08-07
     */
    function save_programa_tema($aRow)
    {
        $condition = "programa_id = {$aRow['programa_id']} AND tema_id = {$aRow['tema_id']}";
        $saved_id = $this->Db_model->save('programa_tema', $condition, $aRow);

        $this->enumerar_temas($aRow['programa_id']);

        return $saved_id;
    }

    /**
     * Eliminar un registro de la tabla programa_tema
     * 2023-08-07
     */
    function remove_tema($programa_id, $tema_id, $pt_id)
    {
        $this->db->where('id', $pt_id);
        $this->db->where('tema_id', $tema_id);
        $this->db->delete('programa_tema');
        
        $qty_deleted = $this->db->affected_rows();
        if ( $qty_deleted > 0 ) $this->enumerar_temas($programa_id);

        return $qty_deleted;
    }
    
    /**
     * Páginas flipbook asociadas al programa a través del tema_id
     * 2023-12-29
     * 
     * @param int $programa_id
     * @return object $paginas
     */
    function paginas($programa_id)
    {
        $this->db->select('pagina_flipbook.id, pagina_flipbook.tema_id');
        $this->db->where('programa_id', $programa_id);
        $this->db->where('pagina_origen_id IS NULL');   //Sólo páginas originales
        $this->db->join('programa_tema', 'programa_tema.tema_id = pagina_flipbook.tema_id');
        $this->db->order_by('programa_tema.orden', 'ASC');
        $this->db->order_by('pagina_flipbook.orden', 'ASC');
        $paginas = $this->db->get('pagina_flipbook');

        return $paginas;
    }
    
    /**
     * Preguntas asociadas al programaa a través del tema_id
     * 
     * @param type $programa_id
     * @return type query
     */
    function preguntas($programa_id)
    {
        $this->db->select('pregunta.id');
        $this->db->where('programa_id', $programa_id);
        $this->db->join('programa_tema', 'programa_tema.tema_id = pregunta.tema_id');
        $this->db->order_by('programa_tema.orden', 'ASC');
        $this->db->order_by('pregunta.orden', 'ASC');
        $preguntas = $this->db->get('pregunta');
        
        return $preguntas;
    }
    
    /**
     * Query con los flipbooks que han sido generados a partir del programa
     * @param type $programa_id
     * @return type
     */
    function flipbooks($programa_id)
    {
        $this->db->where('programa_id', $programa_id);
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get('flipbook');
        
        return $query;
    }

    /**
     * Artículos HTML asociados al programa a través del tema_id
     * 2023-08-21
     * @param int $programa_id
     * @return object $articulos
     */
    function articulos($programa_id)
    {
        $this->db->select('post.id, programa_tema.unidad, post.referente_1_id AS tema_id');
        $this->db->where('programa_id', $programa_id);
        $this->db->where('post.tipo_id', '126');   //Artículos de temas
        $this->db->join('programa_tema', 'programa_tema.tema_id = post.referente_1_id');
        $this->db->order_by('programa_tema.unidad', 'ASC');
        $this->db->order_by('programa_tema.orden', 'ASC');
        $articulos = $this->db->get('post');

        return $articulos;
    }
    
// PROCESOS
//---------------------------------------------------------------------------------------------------------

    /**
     * Enumerar los temas de un programa, campo tema.orden
     * 2023-08-07
     * @param int $programa_id
     */
    function enumerar_temas($programa_id)
    {
        $orden = 0;
        $temas = $this->temas($programa_id);
        
        foreach ($temas->result() as $tema)
        {
            $aRow['orden'] = $orden;
            $this->db->where('id', $tema->pt_id);
            $this->db->update('programa_tema', $aRow);
            $orden += 1;
        }
    }
    
    /**
     * Cambia el valor del campo tema.orden para un tema
     * Modifica los valores de ese campo para las preguntas sigientes
     * cambiar_pos_tema: Cambiar posición de tema
     * 2023-08-07
     * 
     * @param int $programa_id
     * @param int $tema_id
     * @param int $pos_final
     * @return int $affected_rows
     */
    function cambiar_pos_tema($programa_id, $tema_id, $pos_final)
    {
        //Definición de variables
            $affectedRows = 0;
            $sql = '';
        
        //Fila de la pregunta que se va a mover
            $row_tema = $this->Db_model->row('programa_tema', "programa_id = {$programa_id} AND tema_id = {$tema_id}");
            
        //Condición que selecciona el conjunto de registros a modificar
            $condicion_1 = "programa_id = {$programa_id}";
        
        //Variables proceso
            $pos_inicial = $row_tema->orden;  //Posición actual del objeto
            $cant_registros = $this->Pcrn->num_registros('programa_tema', $condicion_1);
            
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
                $sql = 'UPDATE programa_tema';
                $sql .= " SET {$operacion}";
                $sql .= " WHERE {$condicion_1}";
                $sql .= " AND {$condicion_2}";

                $this->db->query($sql);
                $affectedRows = $this->db->affected_rows();
        
            //Cambiar la posición a la pregunta específica
                $aRow['orden'] = $pos_final;
                $this->db->where('tema_id', $tema_id);
                $this->db->update('programa_tema', $aRow);

                $affectedRows += $this->db->affected_rows();
        }
        
        return $affectedRows;
    }

// PROCESOS DE IMPORTACIÓN MASIVA DE DATOS
//-----------------------------------------------------------------------------

    /**
     * Importa masivamente programas
     * 2023-08-07
     */
    function import($arr_sheet)
    {
        $data = array('qty_imported' => 0, 'results' => []);
        
        foreach ( $arr_sheet as $key => $row_data )
        {
            $data_import = $this->import_programa($row_data);
            $data['qty_imported'] += $data_import['status'];
            $data['results'][$key + 2] = $data_import;
        }
        
        return $data;
    }

    /**
     * Importación, crea un registro en la tabla programa
     * 2023-08-07
     */
    function import_programa($row_data)
    {
        //Referencia
            $this->load->model('Esp');
            $areas = $this->Esp->arr_cod_area();
            $area_id = 0;
            if ( array_key_exists($row_data[1], $areas) ) { $area_id = $areas[$row_data[1]]; }

        //Contruir registro
            $aRow['nombre_programa'] = $row_data[0];
            $aRow['area_id'] = $area_id;
            $aRow['nivel'] = $row_data[2];
            $aRow['anio_generacion'] = $row_data[3];
            $aRow['institucion_id'] = $this->Pcrn->existe('institucion', "id = {$row_data[4]}");
            $aRow['cantidad_unidades'] = intval($row_data[5]);
            $aRow['usuario_id'] = $this->session->userdata('user_id');
            $aRow['creado'] = date('Y-m-d H:i:s');
            $aRow['editado'] = date('Y-m-d H:i:s');

        //Validar
            $error_text = '';
            if ( strlen($row_data[0]) == 0 ) { $error_text .= "La columna A (Tema) está vacía. "; }
            if ( $area_id == 0 ) { $error_text .= 'El código "' . $row_data[1] . '" no corresponde a ninguna área. '; }   //Identificación de área
            if ( $aRow['institucion_id'] == 0 ) { $error_text .= 'ID institución ' . $row_data[4] . ' no existe. '; }   //Tiene institución identificada            
            if ( $aRow['anio_generacion'] < date('Y') ) { $error_text .= 'El año "' . $aRow['anio_generacion'] . '" debe ser igual o posterior al actual. '; }   //Año actual o posterior
            if ( strlen($aRow['cantidad_unidades']) == 0 ) { $error_text .= 'La cantidad de unidades no puede estar vacía. '; }   //
            if ( $aRow['cantidad_unidades'] < 1 ) { $error_text .= 'La cantidad de unidades debe ser un número mayor a cero. '; }   //

        //Si no hay error
            if ( $error_text == '' )
            {
                //Guardar en tabla programa
                $saved_id = $this->Db_model->save('programa', 'id = 0', $aRow);                

                $data = array('status' => 1, 'text' => 'Programa creado con ID: ' . $saved_id, 'imported_id' => $saved_id);
            } else {
                $data = array('status' => 0, 'text' => $error_text, 'imported_id' => 0);
            }

        return $data;
    }

// GENERAR FLIPBOOKS MULTI DESDE ARCHIVO EXCEL
//-----------------------------------------------------------------------------

    /**
     * Generar flipbooks a partir de programas, con archivo excel
     * 2023-08-16
     */
    function generar_flipbooks_multi($arr_sheet)
    {
        $data = array('qty_imported' => 0, 'results' => []);
        
        foreach ( $arr_sheet as $key => $row_data )
        {
            $data_import = $this->generar_flipbook($row_data);
            $data['qty_imported'] += $data_import['status'];
            $data['results'][$key + 2] = $data_import;
        }
        
        return $data;
    }

    /**
     * Importación, generar un flipbook a partir de una fila del archivo de importación
     * 2023-08-16
     */
    function generar_flipbook($row_data)
    {
        //Referencia
            $arrTipos = [0,1,3,4,5,6];  //Tipos de flipbook existentes
            $programa = $this->Db_model->row_id('programa', is_null($row_data[0]) ? 0 : $row_data[0]);
            $flipbook = $this->Db_model->row_id('flipbook', is_null($row_data[1]) ? 0 : $row_data[1]);

        //Validar
            $error_text = '';
            if ( strlen($row_data[0]) == 0 ) { $error_text .= "La columna A (Programa) está vacía. "; }
            if ( strlen($row_data[2]) == 0 ) { $error_text .= "La columna C (Tipo) está vacía. "; }
            if ( is_null($programa) ) { $error_text .= "No existe un programa con valor el ID '{$row_data[0]}'. "; }
            if ( !in_array($row_data[2], $arrTipos) ) { $error_text .= "No existe el tipo de contenido '{$row_data[2]}'. "; }

        //Si no hay error
            if ( $error_text == '' )
            {
                //Contruir registro
                $aRow['nombre_flipbook'] = $programa->nombre_programa;
                $aRow['nivel'] = $programa->nivel;
                $aRow['area_id'] = $programa->area_id;
                $aRow['tipo_flipbook_id'] = $row_data[2];
                $aRow['anio_generacion'] = $programa->anio_generacion;
                $aRow['descripcion'] = $programa->descripcion;
                $aRow['programa_id'] = $programa->id;
                $aRow['editor_id'] = $this->session->userdata('user_id');
                $aRow['editado'] = date('Y-m-d H:i:s');
                if ( $row_data[1] == 0 || is_null($flipbook) ) {
                    $aRow['creador_id'] = $this->session->userdata('user_id');
                    $aRow['creado'] = date('Y-m-d H:i:s');
                }

                //Guardar en tabla flipbook
                $saved_id = $this->Db_model->save('flipbook', "id = {$row_data[1]}", $aRow);                

                $qtyPages = 0;
                $tipoDetalle = 'páginas';
                if ( $saved_id > 0 ) {
                    if ( in_array($aRow['tipo_flipbook_id'], [0,1,3,4,5]) ) {
                        $qtyPages = $this->asignar_paginas_fb($programa->id, $saved_id);
                    } elseif (in_array($aRow['tipo_flipbook_id'], [6])) {
                        $tipoDetalle = 'artículos HTML';
                        $qtyPages = $this->asignar_articulos_fb($programa->id, $saved_id);
                    }
                }

                $data = [
                    'status' => 1,
                    'text' => "Flipbook generado con ID: {$saved_id} con {$qtyPages} {$tipoDetalle}",
                    'imported_id' => $saved_id
                ];
            } else {
                $data = array('status' => 0, 'text' => $error_text, 'imported_id' => 0);
            }

        return $data;
    }
    
    /**
     * Elimina las páginas, asigna las páginas de los temas de un programa a un
     * flipbook, tabla flipbook_contenido
     * 2023-08-16
     * 
     * @param int $programa_id
     * @param int $flipbook_id
     * @return int $num_pages número de páginas asignadas
     */
    function asignar_paginas_fb($programa_id, $flipbook_id)
    {
        //Eliminar páginas actuales
            $this->db->where('flipbook_id', $flipbook_id);
            $this->db->delete('flipbook_contenido');
            
        $this->load->model('Flipbook_model');
        
        $num_pagina = 0;
        $paginas = $this->paginas($programa_id);
        
        foreach ($paginas->result() as $row_pagina) {
            $registro_fc['flipbook_id'] = $flipbook_id;
            $registro_fc['pagina_id'] = $row_pagina->id;
            $registro_fc['tema_id'] = $row_pagina->tema_id; //Agregado 2023-09-05
            $registro_fc['num_pagina'] = $num_pagina;

            $this->Flipbook_model->insertar_flipbook_contenido($registro_fc);

            //Para siguiente página
            $num_pagina += 1;
        }

        return $num_pagina;
    }

    /**
     * Elimina las páginas, asigna las páginas de los temas de un programa a un
     * flipbook, tabla flipbook_contenido
     * 2023-08-16
     * 
     * @param int $programa_id
     * @param int $flipbook_id
     * @return int $num_pages número de páginas asignadas
     */
    function asignar_articulos_fb($programa_id, $flipbook_id)
    {
        //Eliminar articulos actuales
            $this->db->where('flipbook_id', $flipbook_id);
            $this->db->delete('flipbook_contenido');
            
        $this->load->model('Flipbook_model');
        
        $num_pagina = 0;
        $articulos = $this->articulos($programa_id);
        
        foreach ($articulos->result() as $rowArticulo) {
            $aRow['flipbook_id'] = $flipbook_id;
            $aRow['pagina_id'] = $rowArticulo->id;
            $aRow['tabla_contenido'] = 2000;    //Pagina ID corresponde a post.id
            $aRow['unidad'] = $rowArticulo->unidad;
            $aRow['tema_id'] = $rowArticulo->tema_id;
            $aRow['num_pagina'] = $num_pagina;

            $this->Flipbook_model->insertar_flipbook_contenido($aRow);

            //Para siguiente página
            $num_pagina += 1;
        }

        return $num_pagina;
    }
    
    /**
     * Crea un cuestionario a partir de un programa de temas
     * @param type $programa_id
     * @param type $registro
     * @return type
     */
    function generar_cuestionario($programa_id, $registro)
    {
        $this->db->insert('cuestionario', $registro);
        $cuestionario_id = $this->db->insert_id();
        
        
        //Crear páginas en el cuestionario
            $num_pregunta = 0;
            $this->load->model('Cuestionario_model');
        
        //Seleccionar preguntas
            $preguntas = $this->preguntas($programa_id);
            
        //Crear cada página en el cuestionario creado, cp = cuestionario_pregunta
            foreach ($preguntas->result() as $row_pregunta) {
                $registro_cp['cuestionario_id'] = $cuestionario_id;
                $registro_cp['pregunta_id'] = $row_pregunta->id;
                $registro_cp['orden'] = $num_pregunta;
                
                $this->Cuestionario_model->insertar_cp($registro_cp);
                
                //Para siguiente pregunta
                $num_pregunta += 1;
            }
        
        return $cuestionario_id;
    }
    
    /**
     * Crea una copia de un programa, incluyendo las temas que lo componen
     * 
     * 
     * @param type $datos
     * @return type 
     */
    function copiar($datos)
    {
        
        $row_programa = $this->Pcrn->registro('programa', "id = {$datos['programa_id']}");  //Tema original
        
        //Crear nuevo registro en la tabla programa
            $registro = array(
                'nombre_programa' => $datos['nombre_programa_nuevo'],
                'anio_generacion' =>  $row_programa->anio_generacion,
                'institucion_id' =>  $this->session->userdata('institucion_id'),
                'nivel' =>  $row_programa->nivel,
                'area_id' =>  $row_programa->area_id,
                'descripcion' =>  $datos['descripcion'],
                'creado' =>  date('Y-m-d H:i:s'),
                'editado' =>  date('Y-m-d H:i:s'),
                'usuario_id' => $this->session->userdata('usuario_id')
            );
        
            $this->db->insert('programa', $registro);
            $programa_id_nuevo = $this->db->insert_id();
            
        //Crear registros de temas incluidos. Tabla programa_tema
            $this->copiar_temas($datos['programa_id'], $programa_id_nuevo);
            
        return $programa_id_nuevo;  //Se devuelve el id del nuevo programa
        
    }
    
    /**
     * Asignar los temas de un programa a otro
     * 
     * @param type $programa_id
     * @param type $programa_id_nuevo
     */
    function copiar_temas($programa_id, $programa_id_nuevo)
    {
        $registro_pt['programa_id'] = $programa_id_nuevo;

        $this->db->where('programa_id', $programa_id);
        $this->db->order_by('orden', 'ASC');
        $temas = $this->db->get('programa_tema');

        foreach ($temas->result() as $row_pt) {
            $registro_pt['orden'] = $row_pt->orden;
            $registro_pt['tema_id'] = $row_pt->tema_id;

            $this->db->insert('programa_tema', $registro_pt);
        }
        
        $this->act_campo_temas($programa_id_nuevo);
    }

    /**
     * Asignación masiva de temas a programas con archivo excel
     * 2023-10-23
     */
    function asignar_temas_multi($arr_sheet)
    {
        $data = ['qty_imported' => 0, 'results' => []];
        
        foreach ( $arr_sheet as $key => $row_data )
        {
            //Contruir registro
                $aRow['programa_id'] = $this->Pcrn->existe('programa', "id = '{$row_data[0]}'");
                $aRow['tema_id'] = $this->Pcrn->existe('tema', "cod_tema = '{$row_data[1]}'");
                $aRow['orden'] = $row_data[3];  //El orden se recalcula al guardar (enumeración)
                $aRow['unidad'] = intval($row_data[2]);

            //Validar
                $error_text = '';
                if ( $aRow['programa_id'] == 0 ) { $error_text .= 'El programa "' . $row_data[0] . '"  no fue identificado. '; }    //Programa no existente
                if ( $aRow['tema_id'] == 0 ) { $error_text .= 'El tema "' . $row_data[1] . '" no fue identificado. '; }        //Tema no existente
                if ( $aRow['unidad'] <= 0 ) { $error_text .= 'El número de unidad "' . $row_data[2] .  '" no es válido. '; }    //Unidad no válida

            //Resultado por defecto
                $data_import = array('status' => 0, 'text' => $error_text, 'imported_id' => 0);
                if ( $error_text == '' )
                {
                    //Guardar en tabla programa_tema
                    $saved_id = $this->save_programa_tema($aRow);                

                    $data_import = array('status' => 1, 'text' => 'Tema asignado al programa creado con ID: ' . $saved_id, 'imported_id' => $saved_id);
                }

            $data['qty_imported'] += $data_import['status'];
            $data['results'][$key + 2] = $data_import;
        }
        
        return $data;
    }
    
    /**
     * Actualiza el campo programa.temas a un listado de programas en un array
     * @param type $arr_programas
     */
    function act_campo_temas_arr($arr_programas)
    {
        foreach ( $arr_programas as $programa_id => $num_temas ) {
            $this->act_campo_temas($programa_id);
        }
    }
    
    /**
     * Actualiza el campo: programa.temas
     * 
     * @param type $programa_id
     */
    function act_campo_temas($programa_id)
    {
        //Calcular valor de programa.temas
            $temas_query = $this->temas($programa_id);
            $temas_arr = $this->Pcrn->query_to_array($temas_query, 'tema_id');
            $temas_str = implode('-', $temas_arr);
        
        //Actualizar campo
            $registro['temas'] = $temas_str;
            
            $this->db->where('id', $programa_id);
            $this->db->update('programa', $registro);   
    }
    
    /**
     * Elimina masivamente los temas de un listado de programas
     * 
     * @param type $array_hoja    Array con los datos de los programas
     */
    function vaciar($array_hoja)
    {   
        $this->load->model('Esp');
        
        $no_importados = array();
        $fila = 2;  //Inicia en la fila 2 de la hoja de cálculo
        
        foreach ( $array_hoja as $array_fila )
        {
            //Identificar valores
                $programa_id = $array_fila[0];
                
            //Validar
                $condiciones = 0;
                if ( strlen($programa_id) > 0 ) { $condiciones++; }   //Debe tener algo escrito
                
            //Si cumple las condiciones
            if ( $condiciones == 1 )
            {   
                //Eliminación
                    $this->db->where('programa_id', $programa_id);
                    $this->db->delete('programa_tema');
                    
                //Vaciar campo programa.temas
                    $reg_programa['temas'] = '';
                    $this->db->where('id', $programa_id);
                    $this->db->update('programa', $reg_programa);
            } else {
                $no_importados[] = $fila;
            }
            
            $fila++;    //Para siguiente fila
        }
        
        return $no_importados;
    }
}