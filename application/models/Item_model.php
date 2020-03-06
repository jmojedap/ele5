<?php

class Item_model extends CI_Model{
    
    function __construct(){
        parent::__construct();
        
    }
    
    //Devuelve un array con los datos del item
    function basico($item_id)
    {   
        $basico = array(
            'row' => NULL
        );
        
        $this->db->where('id', $item_id);
        $query = $this->db->get('item');
        
        if( $query->num_rows() > 0 )
        {
            $row = $query->row();
            
            $basico['titulo_pagina'] = 'Ítem :: ' . $row->item;
            $basico['vista_a'] = 'sistema/items/item_v';
            $basico['row'] = $row;   
        }
        
        return $basico;
    }
    
// CRUD ITEM
//---------------------------------------------------------------------------------------------------------
    
    /**
     * Devuelve objeto query con resultados de búsqueda
     * 
     * @param type $busqueda
     * @param type $per_page
     * @param type $offset
     * @return type
     */
    function buscar($busqueda, $per_page = NULL, $offset = NULL)
    {
        
        $this->load->model('Busqueda_model');
        
        //Construir búsqueda
        //Crear array con términos de búsqueda
            if ( strlen($busqueda['q']) > 2 ){
                $palabras = $this->Busqueda_model->palabras($busqueda['q']);
                $concat_campos = $this->Busqueda_model->concat_campos(array('item', 'item_largo', 'abreviatura', 'slug'));

                foreach ($palabras as $palabra) {
                    $this->db->like("CONCAT({$concat_campos})", $palabra);
                }
            }
        
        //Otros filtros
            if ( $busqueda['cat'] != '' ) { $this->db->where('categoria_id', $busqueda['cat']); }    //Categoría
            
        //Especificaciones de consulta
            $this->db->order_by('categoria_id, id_interno', 'ASC');
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('item'); //Resultados totales
        } else {
            $query = $this->db->get('item', $per_page, $offset); //Resultados por página
        }
        
        return $query;
        
    }
    
    /**
     * Devuelve render de formulario grocery crud para la edición
     * de datos de item
     */
    function crud_basico($categoria_id = NULL)
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('item');
        $crud->set_subject('ítem');
        $crud->unset_export();
        $crud->unset_print();
        //$crud->unset_add();
        $crud->unset_back_to_list();
        $crud->unset_delete();
        $crud->columns('item');
            
        //Filtros
            //$crud->where("item.id = {$row_item->id}");
        
        //Títulos de los campos
            //$crud->display_as('nombre', 'Nombre');
            
        //Relaciones
            

        //Tipos de campo
            $this->load->model('Esp');
            $opciones_categoria = $this->Esp->categorias_item();
            $crud->field_type('categoria_id', 'dropdown', $opciones_categoria);
            
        //Reglas de validación
            $crud->required_fields('item', 'categoria_id');
        
        $output = $crud->render();
        
        return $output;
    }
    
    /**
     * Array con los valores para poner en el formulario
     * 
     * @param type $row
     * @return type
     */
    function valores_form($row)
    {
        $campos = $this->campos_editables();
        
        foreach ( $campos as $campo ) 
        {
            $valores_form[$campo] = NULL;
        }
        
        if ( ! is_null($row) ) 
        {
            foreach ( $campos as $campo ) 
            {
                $valores_form[$campo] = $row->$campo;
            }
        }
        
        return $valores_form;
    }
    
    /**
     * Array con nombres de los campos editables en formulario, tabla item
     * 
     * @param type $formato
     * @return string
     */
    function campos_editables()
    {
        $campos = array(
            'item',
            'id_interno',
            'abreviatura',
            'padre_id',
            'filtro',
            'descripcion',
            'slug',
            'item_largo',
            'item_corto',
            'item_grupo',
        );
        
        return $campos;
        
    }
    
    function siguiente_id_interno($categoria_id)
    {
        $id_interno = 1;
        
        $this->db->select('MAX(id_interno) AS max_id_interno');
        $this->db->where('categoria_id', $categoria_id);
        $query = $this->db->get('item');
        
        if ( $query->num_rows() > 0 ) 
        {
            $id_interno = $query->row()->max_id_interno + 1;
        }
        
        return $id_interno;
    }
    
    function eliminar($condiciones)
    {
        $this->db->where($condiciones);
        $this->db->delete('item');
    }
    
    /**
     * Guardar un registro en la tabla item. Insertar o Editar.
     * @param type $registro
     * @return type
     */
    function guardar($registro, $item_id)
    {
        //Establecer condición
            $condicion = "id = {$item_id}";
            if ( $item_id == 0 ) { $condicion = "categoria_id = {$registro['categoria_id']} AND id_interno = {$registro['id_interno']}"; }
        
        //Insertar o Editar
            $item_id_guardado = $this->Pcrn->guardar('item', $condicion, $registro);
        
        return $item_id_guardado;
    }
    
    /**
     * Devuelve el valor del campo item.id_interno para una categoría
     * dado un valor de un campo.
     * 
     * @param type $categoria_id
     * @param type $valor
     * @param type $campo
     * @return type
     */
    function id_interno($categoria_id, $valor, $campo = 'abreviatura')
    {   
        $condicion = "categoria_id = {$categoria_id} AND {$campo} = '{$valor}'";
        $id_interno = $this->Pcrn->campo('item', $condicion, 'id_interno');
        
        return $id_interno;
    }
    
// DATOS
//-----------------------------------------------------------------------------
    
    function categorias()
    {
        $categorias = $this->opciones('categoria_id = 0');
        return $categorias;
    }
    
    function items($categoria_id)
    {
        $this->db->order_by('id_interno', 'ASC');
        $items = $this->db->get_where('item', "categoria_id = {$categoria_id}");
        
        return $items;
    }
    
    /**
     * Devuelve el nombre de un item con el formato correspondiente.
     * 
     * @param type $categoria_id
     * @param type $id_interno
     * @param type $campo
     * @return type
     */
    function nombre($categoria_id, $id_interno, $campo = 'item')
    {
        $nombre = 'ND';
        
        $this->db->select("{$campo} as campo");
        $this->db->where('id_interno', $id_interno);
        $this->db->where('categoria_id', $categoria_id);
        $query = $this->db->get('item');
        
        if ( $query->num_rows() > 0 ) {
            $nombre = $query->row()->campo;
        }
        
        return $nombre;
    }
    
    /**
     * Devuelve el nombre de un item con el formato correspondiente, a partir
     * del item.id
     * 
     * @param type $item_id
     * @return type
     */
    function nombre_id($item_id, $campo = 'item')
    {
        $nombre = 'ND';
        
        $this->db->select("{$campo} as campo");
        $this->db->where('id', $item_id);
        $query = $this->db->get('item');
        
        if ( $query->num_rows() > 0 ) {
            $nombre = $query->row()->campo;
        }
        
        return $nombre;
    }
    
    
// Arrays
//-----------------------------------------------------------------------------
    
    /**
     * Devuelve un array con índice y valor para una categoría específica de items
     * Dadas unas características definidas en el array $config
     * 
     * @param type $condicion
     * @return type
     */
    function arr_interno($condicion = NULL)
    {   
        $this->db->select('id_interno, item');
        $this->db->where($condicion);
        $this->db->order_by('orden', 'ASC');
        $this->db->order_by('id_interno', 'ASC');
        $query = $this->db->get('item');
        
        $arr_item = $this->Pcrn->query_to_array($query, 'item', 'id_interno');
        
        return $arr_item;
    }
    
    /**
     * Array con opciones de item, para elementos select de formularios.
     * La variable $condicion es una condición WHERE de SQL para filtrar los items.
     * En el array el índice corresponde al id_interno y el valor del array al
     * campo item. La variable $texto_vacio se pone al principio del array
     * cuando el campo select está vacío, sin ninguna opción seleccionada.
     * 
     * @param type $condicion
     * @param type $texto_vacio
     * @return type
     */
    function opciones($condicion, $texto_vacio = NULL)
    {
        $select = 'CONCAT("0", (id_interno)) AS campo_indice_str, item AS campo_valor';
        
        $this->db->select($select);
        $this->db->where($condicion);
        $this->db->order_by('orden', 'ASC');
        $this->db->order_by('id_interno', 'ASC');
        $query = $this->db->get('item');
        
        $opciones_pre = $this->Pcrn->query_to_array($query, 'campo_valor', 'campo_indice_str');
        
        if ( ! is_null($texto_vacio) ) {
            $opciones = array_merge(array('' => '[ ' . $texto_vacio . ' ]'), $opciones_pre);
        } else {
            $opciones = $opciones_pre;
        }
        
        return $opciones;
    }
    
    /**
     * Array con opciones de item, para elementos select de formularios.
     * La variable $condicion es una condición WHERE de SQL para filtrar los items.
     * En el array el índice corresponde al id y el valor del array al
     * campo item. La variable $texto_vacio se pone al principio del array
     * cuando el campo select está vacío, sin ninguna opción seleccionada.
     * 
     * @param type $condicion
     * @param type $texto_vacio
     * @return type
     */
    function opciones_id($condicion, $texto_vacio = NULL)
    {
        $select = 'CONCAT("0", (id)) AS campo_indice_str, item AS campo_valor';
        
        $this->db->select($select);
        $this->db->where($condicion);
        $this->db->order_by('orden', 'ASC');
        $this->db->order_by('id_interno', 'ASC');
        $query = $this->db->get('item');
        
        $opciones_pre = $this->Pcrn->query_to_array($query, 'campo_valor', 'campo_indice_str');
        
        if ( ! is_null($texto_vacio) ) {
            $opciones = array_merge(array('' => '[ ' . $texto_vacio . ' ]'), $opciones_pre);
        } else {
            $opciones = $opciones_pre;
        }
        
        return $opciones;
    }
    
    /**
     * Devuelve array con valores predeterminados para utilizar en la función
     * Item_model->arr_item
     * 2020-03-06
     */
    function arr_config_item($estilo = 'id_interno')
    {
        $arr_config['condicion'] = 'id > 0';
        $arr_config['order_type'] = 'ASC';
        $arr_config['campo_valor'] = 'item';
        
        
        switch ($estilo) {
            case 'id':
                //id, ordenado alfabéticamente
                $arr_config['campo_indice'] = 'id';
                $arr_config['order_by'] = 'item';
                $arr_config['str'] = FALSE;
                break;
            case 'id_interno':
                //id_interno, ordenado por id_interno
                $arr_config['campo_indice'] = 'id_interno';
                $arr_config['order_by'] = 'id_interno';
                $arr_config['str'] = TRUE;
                break;
            case 'id_interno_num':
                //id_interno, ordenado por id_interno, numérico
                $arr_config['campo_indice'] = 'id_interno';
                $arr_config['order_by'] = 'id_interno';
                $arr_config['str'] = FALSE;
                break;
            case 'id_nombre_corto':
                //id, ordenado por id_interno, numérico 2020-03-06
                $arr_config['campo_indice'] = 'id';
                $arr_config['campo_valor'] = 'item_corto';
                $arr_config['order_by'] = 'id';
                $arr_config['str'] = FALSE;
                break;
            
        }
        
        return $arr_config;
    }
    
    /**
     * Devuelve un array con índice y valor para una categoría específica de items
     * Dadas unas características definidas en el array $config
     * 
     * @param type $categoria_id
     * @param type $estilo
     * @return type
     */
    function arr_item($categoria_id, $estilo = 'id_interno')
    {
        
        $config = $this->arr_config_item($estilo);
        
        $select = $config['campo_indice'] . ' AS campo_indice, CONCAT("0", (' . $config['campo_indice'] . ')) AS campo_indice_str, ' . $config['campo_valor'] .' AS campo_valor';
        
        $indice = 'campo_indice_str';
        if ( ! $config['str'] ) { $indice = 'campo_indice'; }
        
        $this->db->select($select);
        if ( $categoria_id > 0 ) { $this->db->where('categoria_id', $categoria_id); }
        $this->db->where($config['condicion']);
        $this->db->order_by($config['order_by'], $config['order_type']);
        $query = $this->db->get('item');
        
        $arr_item = $this->Pcrn->query_to_array($query, 'campo_valor', $indice);
        
        return $arr_item;
    }
    
    function arr_campo($categoria_id, $campo)
    {
        $config = $this->arr_config_item();
        
        $select = $config['campo_indice'] . ' AS campo_indice, CONCAT("0", (id_interno)) AS campo_indice_str, ' . $campo .' AS campo_valor';
        
        $indice = 'campo_indice_str';
        if ( ! $config['str'] ) { $indice = 'campo_indice'; }
        
        $this->db->select($select);
        if ( $categoria_id > 0 ) { $this->db->where('categoria_id', $categoria_id); }
        $this->db->where($config['condicion']);
        $this->db->order_by($config['order_by'], $config['order_type']);
        $query = $this->db->get('item');
        
        $arr_item = $this->Pcrn->query_to_array($query, 'campo_valor', $indice);
        
        return $arr_item;
    }
    
}