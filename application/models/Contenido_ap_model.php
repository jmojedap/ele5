<?php

require APPPATH.'/models/Post_model.php';//just add this line and keep rest 

class Contenido_ap_model extends Post_model{

    public function __construct()
    {
        parent::__construct();
        // Aquí puedes agregar tu lógica personalizada del constructor, si es necesario
    }
    
    function basic($post_id)
    {
        $row = $this->Db_model->row_id('post', $post_id);
        
        $data['row'] = $row;
        $data['nombre_post'] = $this->pml->if_strlen($row->nombre_post, 'Post ' . $row->id);
        $data['head_title'] = $data['nombre_post'];
        $data['nav_2'] = 'posts/menu_v';
        
        return $data;
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
        $words_condition = $this->Search_model->words_condition($filters['q'], array('nombre_post', 'contenido', 'resumen', 'keywords'));
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Otros filtros
        if ( $filters['type'] != '' ) { $condition .= "tipo_id = {$filters['type']} AND "; }
        if ( $filters['status'] != '' ) { $condition .= "status = {$filters['status']} AND "; }
        if ( $filters['cat_1'] != '' ) { $condition .= "cat_1 = {$filters['cat_1']} AND "; }
        if ( $filters['cat_2'] != '' ) { $condition .= "cat_2 = {$filters['cat_2']} AND "; }
        if ( $filters['u'] != '' ) { $condition .= "usuario_id = {$filters['u']} AND "; }
        if ( $filters['condition'] != '' ) { $condition .= "{$filters['condition']} AND "; }
        
        //Quitar cadena final de ' AND '
        if ( strlen($condition) > 0 ) { $condition = substr($condition, 0, -5);}
        
        return $condition;
    } 

    /**
     * Cantidad total registros encontrados en la tabla con los filtros
     * establecidos en la búsqueda
     */
    function search_num_rows($filters)
    {
        $this->db->select('id');
        $search_condition = $this->search_condition($filters);
        if ( $search_condition ) { $this->db->where($search_condition);}
        $query = $this->db->get('post'); //Para calcular el total de resultados

        return $query->num_rows();
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
    function buscar($busqueda, $per_page = NULL, $offset = NULL)
    {
        //Filtro por rol de usuarios
            $filtro_usuarios = $this->filtro_usuarios();
        
        //Construir búsqueda
        //Crear array con términos de búsqueda
            if ( strlen($busqueda['q']) > 2 )
            {
                
                $campos_posts = array('nombre_post', 'contenido', 'resumen', 'editado', 'creado');
                
                $concat_campos = $this->Search_model->concat_fields($campos_posts);
                $palabras = $this->Search_model->words($busqueda['q']);

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
            if ( $busqueda['n'] != '' ) { 
                $nivel_formato = strval(intval($busqueda['n']));
                $this->db->where("texto_2 LIKE '%{$nivel_formato}%'");
             }       //Niveles
            if ( $busqueda['condicion'] != '' ) { $this->db->where($busqueda['condicion']); }           //Condición especial
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('post'); //Resultados totales
        } else {
            $query = $this->db->get('post', $per_page, $offset); //Resultados por página
        }
        
        return $query;
        
    }
    
    function filtro_usuarios()
    {
        $role = $this->session->userdata('role');
        $condicion = 'id > 0';  //Valor por defecto
        
        /*if ( in_array($role, array(0,1,2)) )
        {
            $condicion = 'id > 0';
        } else {
            $condicion_sub = 'dato_id = 400010';
            $condicion_sub .= ' AND elemento_id = ' . $this->session->userdata('institucion_id');
            $condicion_sub .= ' AND fecha_1 >= "' . date('Y-m-d H:i:s') . '"';
            $condicion = "id IN (SELECT relacionado_id FROM meta WHERE {$condicion_sub})";
        }*/
        
        return $condicion;
    }
    
    /**
     * Array con los datos para la vista de exploración de post acompañamiento
     * pedagógico
     * 
     * @return string
     */
    function data_explorar($num_pagina)
    {
        //Data inicial, de la tabla
            $data = $this->data_tabla_explorar($num_pagina);
        
        //Elemento de exploración
            $data['controlador'] = 'contenidos_ap';                      //Nombre del controlador
            $data['carpeta_vistas'] = 'posts/contenidos_ap/explorar/';         //Carpeta donde están las vistas de exploración
            $data['head_title'] = 'Contenidos AP';
                
        //Otros
            $data['cant_resultados'] = $this->Contenido_ap_model->search_num_rows($data['filters']);
            $data['max_pagina'] = ceil($this->Pcrn->si_cero($data['cant_resultados'],1) / $data['per_page']) - 1;   //Cantidad de páginas, menos 1 por iniciar en cero

        //Vistas
            $data['view_a'] = $data['carpeta_vistas'] . 'explorar_v';
            //$data['head_subtitle'] = $data['cant_resultados'];
            //$data['nav_2'] = $data['carpeta_vistas'] . 'menu_v';
        
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
            $data['cf'] = 'posts/ap_explorar/';     //CF Controlador Función
        
        //Paginación
            $data['num_pagina'] = $num_pagina;              //Número de la página de datos que se está consultado
            $data['per_page'] = 20;                         //Cantidad de registros por página
            $offset = ($num_pagina - 1) * $data['per_page'];      //Número de la página de datos que se está consultado
        
        //Búsqueda y Resultados
            $this->load->model('Search_model');
            $data['filters'] = $this->Search_model->filters();
            $data['str_filters'] = $this->Search_model->str_filters();
            
            $data['resultados'] = $this->Contenido_ap_model->buscar($data['filters'], $data['per_page'], $offset);    //Resultados para página
            
        //Otros
            $data['seleccionados_todos'] = '-'. $this->Pcrn->query_to_str($data['resultados'], 'id');               //Para selección masiva de todos los elementos de la página
            
        return $data;
    }
    
    function guardar_asignacion($registro)
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
    function eliminar_asignacion($post_id, $meta_id)
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
    function importar_asignaciones($array_hoja)
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
                $this->guardar_asignacion($registro);
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
}