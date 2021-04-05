<?php
class Recurso_Model extends CI_Model{
    
    /**
     * Definir el id de recurso por defecto si ese parámetro no está definido
     * @return int
     */
    function recurso_id()
    {
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('recurso');
        $recurso_id = $query->row()->id;
        
        return $recurso_id;
    }
    
    function basico($recurso_id)
    {
        
        //preguntas
            $this->db->where('recurso_id', $recurso_id);
            $preguntas = $this->db->get('pregunta');
            
        //preguntas
            $this->db->where('recurso_id', $recurso_id);
            $programas = $this->db->get('programa_recurso');
            
        //páginas flipbook
            $this->db->where('en_recurso', 1);
            $this->db->where('recurso_id', $recurso_id);
            $pf = $this->db->get('pagina_flipbook');
        
        $row_recurso = $this->Pcrn->registro_id('recurso', $recurso_id);
        
        //Datos adicionales
        $row_recurso->cant_preguntas = $preguntas->num_rows();
        $row_recurso->cant_pf = $pf->num_rows();
        $row_recurso->cant_programas = $programas->num_rows();
        
        $basico['preguntas'] = $preguntas;
        $basico['programas'] = $programas;
        $basico['pf'] = $pf;
        $basico['recurso_id'] = $recurso_id;
        $basico['row'] = $row_recurso;
        $basico['titulo_pagina'] = $row_recurso->nombre_recurso;
        $basico['contenido_a'] = 'recursos/recurso_v';
        
        return $basico;
    }
    
    /**
     * Búsqueda de archivos, tabla recurso
     * 
     * @param type $busqueda
     * @param type $per_page
     * @param type $offset
     * @return type
     */
    function buscar_archivos($busqueda, $per_page = NULL, $offset = NULL)
    {
        //Construir búsqueda
        
            //Texto búsqueda
                //Crear array con términos de búsqueda
                if ( strlen($busqueda['q']) > 2 ){
                    $palabras = $this->Busqueda_model->palabras($busqueda['q']);

                    foreach ($palabras as $palabra_busqueda) {
                        $this->db->like('CONCAT(nombre_archivo, nombre_tema)', $palabra_busqueda);
                    }
                }
            
            //Otros filtros
                if ( $busqueda['a'] != '' ) { $this->db->where('area_id', $busqueda['a']); }    //Área
                if ( $busqueda['n'] != '' ) { $this->db->where('nivel', $busqueda['n']); }  //Nivel
                if ( $busqueda['tp'] != '' ) { $this->db->where('tipo_archivo_id', $busqueda['tp']); }  //Tipo archivo
                if ( $busqueda['e'] != '' ) { $this->db->where('recurso.editado', $busqueda['e']); }  //Editado
                
            //Otros
                $this->db->select('*, recurso.id AS recurso_id');
                $this->db->join('tema', 'recurso.tema_id = tema.id');
                $this->db->where('tipo_recurso_id', 1); //Archivo
                $this->db->order_by('nombre_archivo', 'ASC');
            
        //Obtener resultados
            if ( is_null($per_page) ){
                $query = $this->db->get('recurso'); //Resultados totales
            } else {
                $query = $this->db->get('recurso', $per_page, $offset); //Resultados por página
            }
        
        return $query;
    }    
    
    
    /**
     * Link para Grocery Crud de los recursos
     * 
     * @param type $value
     * @param type $row
     * @return type
     */
    function gc_link_recurso($value, $row)
    {
        $texto = substr($row->nombre_recurso, 0, 50);
        $att = 'title="Ir al recurso ' . $value. '"';
        return anchor("recursos/preguntas/{$row->id}", $texto, $att);
    }
    
    function gc_after_save($post_array,$primary_key)
    {
        //Agregar caracteres que separan links al final
        $texto_final = substr($post_array['link'], -5);
        
        if ( $texto_final != '<=:=>' ){
            $registro['link'] = $post_array['link'] . '<=:=>';
            $this->db->where('id', $primary_key);
            $this->db->update('recurso', $registro);
        }
        
    }

// DATOS
//---------------------------------------------------------------------------------------------------------
    
    function archivos($filtros, $per_page = NULL, $offset = NULL)
    {
        $carpeta_uploads = base_url() . RUTA_UPLOADS;
        $campo_ubicacion = "CONCAT(('{$carpeta_uploads}'), (slug), ('/'), (nombre_archivo)) AS ubicacion";
        
        $this->db->select("recurso.id AS recurso_id, nombre_archivo, tema_id, nombre_tema, nivel, area_id, tipo_archivo_id, item AS tipo_archivo, slug AS carpeta, {$campo_ubicacion}, disponible, recurso.editado, fecha_subida, recurso.usuario_id");
        $this->db->where('tipo_recurso_id', 1);
        $this->db->join('tema', 'recurso.tema_id = tema.id');
        $this->db->join('item', 'recurso.tipo_archivo_id = item.id');
        
        //Aplicación de filtros
            if ( ! is_null($filtros['tipo_archivo_id']) ) { $this->db->where('tipo_archivo_id', $filtros['tipo_archivo_id']); }
            if ( ! is_null($filtros['area_id']) ) { $this->db->where('area_id', $filtros['area_id']); }
            if ( ! is_null($filtros['nivel']) ) { $this->db->where('nivel', $filtros['nivel']); }
            if ( ! is_null($filtros['editado']) ) { $this->db->where('recurso.editado', $filtros['editado']); }
            if ( $filtros['condicion'] != '' ) { $this->db->where($filtros['condicion']); }
        
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('recurso'); //Resultados totales
        } else {
            $query = $this->db->get('recurso', $per_page, $offset); //Resultados por página
        }
        
        return $query;
    }
    
    function links($busqueda, $per_page = NULL, $offset = NULL)
    {
        
        $this->db->select("recurso.id, recurso.titulo, url, tema_id, nombre_tema, nivel, area_id, tipo_archivo_id, disponible, recurso.editado, recurso.usuario_id");
        $this->db->where('tipo_recurso_id', 2); //Tipo link
        $this->db->join('tema', 'recurso.tema_id = tema.id');
        
        //Aplicación de filtros
            if ( ! is_null($busqueda['a']) ) { $this->db->where('area_id', $busqueda['a']); }
            if ( ! is_null($busqueda['n']) ) { $this->db->where('nivel', $busqueda['n']); }
            if ( ! is_null($busqueda['e']) ) { $this->db->where('recurso.editado', $busqueda['e']); }
            if ( $busqueda['condicion'] != '' ) { $this->db->where($busqueda['condicion']); }
        
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('recurso'); //Resultados totales
        } else {
            $query = $this->db->get('recurso', $per_page, $offset); //Resultados por página
        }
        
        return $query;
    }
    
    function carpeta($tipo_archivo_id)
    {
        $carpeta = RUTA_UPLOADS . $this->Pcrn->campo_id('item', $tipo_archivo_id, 'slug') . '/';
        return $carpeta;
    }

// GESTIÓN DE RECURSOS LINKS
//-----------------------------------------------------------------------------

    /**
     * Array con los datos para la vista de exploración
     */
    function links_explore_data($num_page)
    {
        //Data inicial, de la tabla
            $data = $this->links_get($num_page);
        
        //Elemento de exploración
            $data['controller'] = 'recursos';                      //Nombre del controlador
            $data['cf'] = 'recursos/links/';                      //Nombre del controlador
            $data['views_folder'] = 'recursos/links/explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Links';
            $data['head_subtitle'] = $data['search_num_rows'];
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        return $data;
    }

    function links_get($num_page)
    {
        //Referencia
            $per_page = 10;                             //Cantidad de registros por página
            $offset = ($num_page - 1) * $per_page;      //Número de la página de datos que se está consultado

        //Búsqueda y Resultados
            $this->load->model('Search_model');
            $data['filters'] = $this->Search_model->filters();
            $elements = $this->links_search($data['filters'], $per_page, $offset);    //Resultados para página
        
        //Cargar datos
            $data['list'] = $elements->result();
            $data['str_filters'] = $this->Search_model->str_filters();
            $data['search_num_rows'] = $this->links_search_num_rows($data['filters']);
            $data['max_page'] = ceil($this->pml->if_zero($data['search_num_rows'],1) / $per_page);   //Cantidad de páginas

        return $data;
    }
    
    /**
     * String con condición WHERE SQL para filtrar post
     */
    function links_search_condition($filters)
    {
        $condition = NULL;
        
        //Revisar cada filtro
        if ( $filters['a'] != '' ) { $condition .= "area_id = {$filters['a']} AND "; }
        if ( $filters['n'] != '' ) { $condition .= "nivel = {$filters['n']} AND "; }
        if ( $filters['cpnt'] != '' ) { $condition .= "recurso.componente_id = {$filters['cpnt']} AND "; }
        
        //Quitar AND final
        if ( strlen($condition) > 0 ) { $condition = substr($condition, 0, -5); }
        
        return $condition;
    }
    
    function links_search($filters, $per_page = NULL, $offset = NULL)
    {
        //Construir consulta
            $select = 'recurso.id, titulo, url, tema_id, tema.nombre_tema, area_id, nivel, palabras_clave, recurso.descripcion, recurso.componente_id';
            $this->db->select($select);
            $this->db->join('tema', 'tema.id = recurso.tema_id');
        
        //Crear array con términos de búsqueda
            $words_condition = $this->Search_model->words_condition($filters['q'], array('titulo', 'url'));
            if ( $words_condition )
            {
                $this->db->where($words_condition);
            }
            
        //Orden
            if ( $filters['o'] != '' )
            {
                $order_type = $this->pml->if_strlen($filters['ot'], 'DESC');
                $this->db->order_by($filters['o'], $order_type);
            } else {
                $this->db->order_by('recurso.editado', 'DESC');
            }
            
        //Filtros
            $this->db->where('tipo_recurso_id', 2); //Recurso Tipo link
            $search_condition = $this->links_search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
        if ( is_null($per_page) )
        {
            $query = $this->db->get('recurso'); //Resultados totales
        } else {
            $query = $this->db->get('recurso', $per_page, $offset); //Resultados por página
        }
        
        return $query;
        
    }
    
    /**
     * Devuelve la cantidad de registros encontrados en la tabla con los filtros
     * establecidos en la búsqueda
     * 
     * @param type $filters
     * @return type
     */
    function links_search_num_rows($filters)
    {
        $query = $this->links_search($filters); //Para calcular el total de resultados
        return $query->num_rows();
    }
    
    /**
     * Array con options para ordenar el listado de post en la vista de
     * exploración
     * 
     * @return string
     */
    function links_options_order()
    {
        $options_order = array(
            '' => '[ Ordenar por ]',
            'editado' => 'Fecha de edición',
            'area_id' => 'Área',
            'nivel' => 'Nivel'
        );
        
        return $options_order;
    }

    /**
     * Programar en el calendario de un grupo, un link en una fecha determinada
     * 2020-03-25
     */
    function links_programar()
    {
        //Datos referencia
            $row_link = $this->Db_model->row_id('recurso', $this->input->post('referente_id'));
            $row_tema = $this->Db_model->row_id('tema', $row_link->tema_id);

        //Construir registro
            $arr_row = $this->input->post();
            $arr_row['tipo_id'] = 5;    //Link interno asignado
            $arr_row['url'] = $row_link->url;
            $arr_row['referente_2_id'] = $row_link->tema_id;
            $arr_row['institucion_id'] = $this->session->userdata('institucion_id');
            $arr_row['area_id'] = $row_tema->area_id;
            $arr_row['nivel'] = $row_tema->nivel;
            $arr_row['creador_id'] = $this->session->userdata('usuario_id');

        //Guardar
            $condition = "tipo_id = {$arr_row['tipo_id']} AND referente_id = {$arr_row['referente_id']} AND grupo_id = {$arr_row['grupo_id']}";
            $data['saved_id'] = $this->Db_model->save('evento', $condition, $arr_row);
            
        return $data;
    }

    /**
     * Query links programados por el usuario en sesión
     * 2020-03-27
     */
    function links_programados()
    {
        $this->db->select('evento.id, recurso.titulo, fecha_inicio, grupo_id, evento.url, nivel, area_id, tema_id');
        $this->db->where('tipo_id', 5);
        $this->db->where('creador_id', $this->session->userdata('usuario_id'));
        $this->db->join('recurso', 'evento.referente_id = recurso.id');
        $this->db->order_by('fecha_inicio', 'ASC');
        $links = $this->db->get('evento', 500);

        return $links;
    }

    /**
     * Actualiza el campo pregunta.palabras_clave, que está vacío, con el nombre del tema asociado
     * 2020-03-16
     */
    function links_update_palabras_clave_auto()
    {
        $this->db->select('recurso.id, nombre_tema');
        $this->db->join('tema', 'recurso.tema_id = tema.id');
        $this->db->where('recurso.palabras_clave = ""');
        $this->db->where('tipo_recurso_id', 2); //Recurso tipo link
        $preguntas = $this->db->get('recurso');

        $data = array('status' => 1, 'message' => 'Se actualizaron 0 registros', 'qty_affected' => 0);

        foreach ( $preguntas->result() as $row )
        {
            $arr_row['palabras_clave'] = $row->nombre_tema;
            $this->db->where('id', $row->id);
            $this->db->update('recurso', $arr_row);

            $data['qty_affected'] += 1;
        }

        $data['qty_affected'] = $preguntas->num_rows();
        if ( $data['qty_affected'] > 0 )
        {
            $data['status'] = 1;
            $data['message'] = 'Registros modificados: ' . $data['qty_affected'];
        }

        return $data;
    }

    /**
     * Importa recursos links a la base de datos
     * 2020-04-02
     */
    function links_importar($arr_sheet)
    {
        $data = array('qty_imported' => 0, 'results' => array());
        
        foreach ( $arr_sheet as $key => $row_data )
        {
            $data_import = $this->importar_link($row_data);
            $data['qty_imported'] += $data_import['status'];
            $data['results'][$key + 2] = $data_import;
        }
        
        return $data;
    }

    /**
     * Realiza la importación de una fila del archivo excel. Valida los campos, crea registro
     * en la tabla recurso.
     * 2020-04-02
     */
    function importar_link($row_data)
    {
        //Validar
            $error_text = '';
                            
            if ( strlen($row_data[0]) == 0 ) { $error_text .= 'La casilla <b>Cód. tema</b> está vacía. '; }
            if ( strlen($row_data[1]) == 0 ) { $error_text .= 'La casilla <b>título</b> está vacía. '; }
            if ( strlen($row_data[2]) == 0 ) { $error_text .= 'La casilla <b>URL</b> está vacía. '; }

        //Identificar tema
            $row_tema = $this->Db_model->row('tema', "cod_tema = '{$row_data[0]}'");
            if ( is_null($row_tema) ) { $error_text .= 'El código de tema (' . $row_data[0] . ') es incorrecto o no existe. '; }

        //Si no hay error
            if ( $error_text == '' )
            {
                $arr_row['tema_id'] = $row_tema->id;
                $arr_row['titulo'] = $row_data[1];
                $arr_row['url'] = $row_data[2];
                $arr_row['descripcion'] = $row_data[3];
                $arr_row['palabras_clave'] = $row_data[4];
                $arr_row['componente_id'] = ( strlen($row_data[5]) > 0 ) ? $row_data[5] : 0 ;
                $arr_row['tipo_recurso_id'] = 2;    //Recurso tipo link
                $arr_row['fecha_subida'] = date('Y-m-d H:i:s');
                $arr_row['editado'] = date('Y-m-d H:i:s');
                $arr_row['usuario_id'] = $this->session->userdata('usuario_id');

                //Guardar en tabla recurso
                $condition = "tema_id = {$arr_row['tema_id']} AND tipo_recurso_id = {$arr_row['tipo_recurso_id']} AND url = '{$arr_row['url']}'";
                $saved_id = $this->Db_model->save('recurso', $condition, $arr_row);

                $data = array('status' => 1, 'text' => '', 'imported_id' => $saved_id);
            } else {
                $data = array('status' => 0, 'text' => $error_text, 'imported_id' => 0);
            }

        return $data;
    }

// Eliminar links de temas en archivo Excel
//-----------------------------------------------------------------------------

    /**
     * Eliminar links de temas en archivo excel
     * 2021-04-05
     */
    function links_eliminar($arr_sheet)
    {
        $data = array('qty_imported' => 0, 'results' => array());
        
        foreach ( $arr_sheet as $key => $row_data )
        {
            $data_import = $this->link_eliminar($row_data);
            $data['qty_imported'] += $data_import['status'];
            $data['results'][$key + 2] = $data_import;
        }
        
        return $data;
    }

    /**
     * Eliminar links de un tema específico, row de tabla Excel
     * 2021-04-05
     */
    function link_eliminar($row_data)
    {
        //Validar
            $error_text = '';
            $tema = $this->Db_model->row('tema', "cod_tema = '{$row_data[0]}'");
                            
            if ( is_null($tema) ) { $error_text = "El tema (Columna A) con el código '{$row_data[0]}' no fue encontrado. "; }

        //Si no hay error
            if ( $error_text == '' )
            {
                //Guardar en tabla item
                $this->db->where('tipo_recurso_id', 2)->where('tema_id', $tema->id)->delete('recurso');
                
                $qty_deleted = $this->db->affected_rows();

                $data = array('status' => 1, 'text' => "{$qty_deleted} links eliminados para el tema con código '{$tema->cod_tema}'", 'imported_id' => $tema->id);
            } else {
                $data = array('status' => 0, 'text' => $error_text, 'imported_id' => 0);
            }

        return $data;
    }
    
// PROCESOS
//---------------------------------------------------------------------------------------------------------
    
    function eliminar($recurso_id)
    {
        $this->db->where('id', $recurso_id);
        $this->db->delete('recurso');
        
        echo $this->db->affected_rows();
    }
    
    /**
     * Inserta masivamente la asignación de temas
     * tabla recurso
     * 
     * @param type $array_hoja    Array con los datos de los programas
     */
    function asignar($array_hoja)
    {   
        
        $no_importados = array();
        $fila = 2;  //Inicia en la fila 2 de la hoja de cálculo
        
        $this->load->model('Esp');
        $tipos_archivo = $this->Esp->arr_tipos_archivo();
            
        //Predeterminados registro nuevo
            $registro['tipo_recurso_id'] = 1;   //Archivo
            $registro['usuario_id'] = $this->session->userdata('usuario_id');
            $registro['editado'] = date('Y-m-d H:i:s');
            $registro['disponible'] = 1;    //2016-11-28, por defecto disponible 1 (Sí)
        
        foreach ( $array_hoja as $array_fila )
        {
            //Identificar valores
                $tema_id = $this->Pcrn->campo('tema', "cod_tema = '{$array_fila[0]}'", 'id');  //Columna A
                $tipo_archivo_id = $tipos_archivo[$array_fila[2]]; //Columna C
            
            //Complementar registro
                $registro['tema_id'] = $tema_id;                //Columna A
                $registro['nombre_archivo'] = $array_fila[1];   //Columna B
                $registro['tipo_archivo_id'] = $tipo_archivo_id;
                
            //Validar
                $condiciones = 0;
                if ( ! is_null($tema_id) ) { $condiciones += 1;}             //Si el tema no fue identificado
                if ( $tipo_archivo_id > 0) { $condiciones += 1; }            //Si el tipo de archivo no fue identificado
                if ( strlen($array_fila[1]) > 0 ) { $condiciones += 1; }    //Si el nombre de archivo está vacío
                
            //Si cumple las condiciones
            if ( $condiciones == 3 )
            {   
                //Condición de comprobación
                $condicion = "nombre_archivo = '{$registro['nombre_archivo']}' AND tema_id = {$registro['tema_id']}";
                $this->Pcrn->guardar('recurso', $condicion, $registro );
            } else {
                $no_importados[] = $fila;
            }
            
            $fila++;    //Para siguiente fila
        }
        
        return $no_importados;
    }
    
    function insert_links($links)
    {
        //Valores iniciales
            $links_cargados = array();
            
        //Predeterminados registro nuevo
            $registro['tipo_recurso_id'] = 2;   //Link
            $registro['tipo_archivo_id'] = 625;   //item.id, link
            $registro['usuario_id'] = $this->session->userdata('usuario_id');
            $registro['editado'] = date('Y-m-d H:i:s');
        
        foreach ( $links as $row_link ) {
            
            //Definiendo valores
                $tema_id = $this->Pcrn->campo('tema', "cod_tema = '{$row_link[0]}'", 'id');  //Columna A
                
            //Comprobando valores
                $cargar = 0;
                if ( ! is_null($tema_id) ) { $cargar += 1;}             //Si el tema fue identificado
                if ( strlen($row_link[1]) > 0 ) { $cargar += 1; }    //Si la url no está vacía
            
            //Se asigna si cumple las 2 condiciones
                if ( $cargar == 2 ){
                    $registro['tema_id'] = $tema_id;  //Columna A
                    $registro['url'] = $row_link[1];  //Columna B
                    
                    //Condición de comprobación
                    $condicion = "url = '{$registro['url']}' AND tema_id = {$registro['tema_id']}";
                    $links_cargados[] = $this->Pcrn->guardar('recurso', $condicion, $registro );
                }
            
        }
        
        return $links_cargados;    
    }
    
    /**
     * Inserta masivamente links
     * tabla recurso
     * 
     * @param type $array_hoja    Array con los datos de los links
     */
    function importar_links($array_hoja)
    {   
        $this->load->model('Esp');
        
        $no_importados = array();
        $fila = 2;  //Inicia en la fila 2 de la hoja de cálculo
            
        //Predeterminados registro nuevo
            $registro['tipo_recurso_id'] = 2;   //Link
            $registro['tipo_archivo_id'] = 625;   //Link
            $registro['usuario_id'] = $this->session->userdata('usuario_id');
            $registro['editado'] = date('Y-m-d H:i:s');
        
        foreach ( $array_hoja as $array_fila )
        {
            //Identificar valores
                $tema_id = 0;
                if ( ! is_null($array_fila[0]) ) { $tema_id = $this->Pcrn->campo('tema', "cod_tema = '{$array_fila[0]}'", 'id'); }
            
            //Complementar registro
                $registro['tema_id'] = $tema_id;
                $registro['titulo'] = $array_fila[1];
                $registro['url'] = $array_fila[2];
                
            //Validar
                $condiciones = 0;
                if ( $tema_id != 0 ) { $condiciones++; }   //Tiene tema identificado
                if ( strlen($registro['url']) > 0 ) { $condiciones++; }   //Tiene url escrita
                
            //Si cumple las condiciones
            if ( $condiciones == 2 )
            {   
                $this->Pcrn->guardar('recurso', "tema_id = {$registro['tema_id']} AND url = '{$registro['url']}'", $registro);
            } else {
                $no_importados[] = $fila;
            }
            
            $fila++;    //Para siguiente fila
        }
        
        return $no_importados;
    }
    
    /**
     * Asociar automáticamente los archivos de una carpeta a los temas
     * 
     * @param type $tipo_archivo_id
     * @return int
     */
    function asociar_archivos($tipo_archivo_id)
    {
        $cant_asociados = 0;
        
        $this->load->helper('file');
        $carpeta = $this->Pcrn->campo_id('item', $tipo_archivo_id, 'slug');
        $ruta = RUTA_UPLOADS . $carpeta . '/';
        $archivos = get_filenames($ruta, TRUE);
        
        foreach($archivos as $ruta_archivo) {
            $info = pathinfo($ruta_archivo);
            $cod_tema = substr($info['filename'], 0, 6);
            $recurso_id = $this->asociar_archivo($ruta_archivo, $tipo_archivo_id);
            if ( $recurso_id > 0 ) { $cant_asociados += 1; }
        }
        
        return $cant_asociados;
    }
    
    /**
     * Asociar automáticamente un archivo a un tema
     * 
     * @param type $ruta_archivo
     * @param type $tipo_archivo_id
     * @return type
     */
    function asociar_archivo($ruta_archivo, $tipo_archivo_id)
    {
        $recurso_id = 0;
        $info = pathinfo($ruta_archivo);
        $cod_tema = substr($info['filename'], 0, 6);
        $tema_id = $this->Pcrn->campo('tema', "cod_tema = '{$cod_tema}'", 'id');
        
        if ( $tema_id  > 0 ) {
            
            $registro['nombre_archivo'] = $info['basename'];
            $registro['tema_id'] = $tema_id;
            $registro['tipo_recurso_id'] = 1;   //Archivo
            $registro['tipo_archivo_id'] = $tipo_archivo_id;
            $registro['disponible'] = 1;
            $registro['editado'] = date('Y-m-d H:i:s');
            $registro['usuario_id'] = $this->session->userdata('usuario_id');
            
            $condicion = "nombre_archivo = '{$registro['nombre_archivo']}' AND tema_id = {$registro['tema_id']}";
            $recurso_id = $this->Pcrn->guardar('recurso', $condicion, $registro);
        }
        
        return $recurso_id;
    }
    
    /**
     * Función de transición V2 a V3
     * Asociar automáticamente los archivos de una carpeta a los temas
     * 
     * @param type $tipo_archivo_id
     * @return int
     */
    function cambiar_nombres($tipo_archivo_id)
    {
        $cant_archivos = 0;
        
        $this->load->helper('file');
        $carpeta = $this->Pcrn->campo_id('item', $tipo_archivo_id, 'slug');
        $ruta = RUTA_UPLOADS . $carpeta . '/';
        $archivos = get_filenames($ruta, TRUE);
        
        foreach($archivos as $ruta_archivo) {
            $info = pathinfo($ruta_archivo);
            $cambiar = $this->cambiar_nombre($tipo_archivo_id, $info);
            $cant_archivos += $cambiar;
        }
        
        return $cant_archivos;
    }
    
    function cambiar_nombre($tipo_archivo_id, $file_info)
    {
        $cambiar_nombre = 0;
        
        $row_item = $this->Pcrn->registro_id('item', $tipo_archivo_id);
        
        $campo = 'archivo_' .  $row_item->slug;
        
        $carpeta = RUTA_UPLOADS . $row_item->slug . '/';
        $nombre_actual = $file_info['filename'] . '.' . $file_info['extension'];
        
        $row_tema = $this->Pcrn->registro('tema', "{$campo} = '{$nombre_actual}'");
        
        if ( $row_tema->id > 0 ) {
            $cambiar_nombre = 1;
            $nombre_nuevo = $row_tema->cod_tema . $row_item->abreviatura . '.' .  $file_info['extension'];
            
            rename($carpeta . $nombre_actual, $carpeta . $nombre_nuevo);
        }
        
        
        
        return $cambiar_nombre;
    }
    
    function act_archivos_disponibles($tipo_archivo_id)
    {
        $carpeta = $this->carpeta($tipo_archivo_id);
        $cant_no_disponibles = 0;
        
        $this->db->where('tipo_archivo_id', $tipo_archivo_id);
        $archivos = $this->db->get('recurso');
        
        $registro['editado'] = date('Y-m-d H:i:s');
        
        foreach ( $archivos->result() as $row_archivo ) {
            
            $ruta = $carpeta .  $row_archivo->nombre_archivo;
            
            if ( file_exists( $ruta ) ) { 
                $registro['disponible'] = 1; 
            } else {
                $registro['disponible'] = 0;
                $cant_no_disponibles += 1;
            }
            
            $this->db->where('id', $row_archivo->id);
            $this->db->update('recurso', $registro);
        }
        
        return $cant_no_disponibles;
    }
    
    function archivos_no_asignados($tipo_archivo_id, $cantidad)
    {
        $archivos_no_asignados = array();
        
        $this->load->helper('file');
        $carpeta = $this->Pcrn->campo_id('item', $tipo_archivo_id, 'slug');
        $ruta = RUTA_UPLOADS . $carpeta . '/';
        $archivos = get_filenames($ruta, TRUE);
        
        foreach($archivos as $ruta_archivo) {
            $info = pathinfo($ruta_archivo);
            $existe = $this->Pcrn->existe('recurso', "nombre_archivo = '{$info['basename']}'");
            
            if ( $existe == 0 ) {
                $archivos_no_asignados[] = $ruta_archivo;
            }
        }
        
        return array_slice($archivos_no_asignados, 0, $cantidad);;
    }
    
}