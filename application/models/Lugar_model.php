<?php

class Lugar_model extends CI_Model{

    function __construct(){
        parent::__construct();
        
    }
    
    function basico($lugar_id)
    {
        
        $this->db->where('id', $lugar_id);
        $query = $this->db->get('lugar');
        
        $row = $query->row();
        
        //Imagen principal
        $basico['row'] = $row;
        $basico['titulo_pagina'] = $row->nombre_lugar;
        $basico['vista_a'] = 'sistema/lugares/lugar_v';
        
        return $basico;
    }

//LUGARES - CIUDADES
//---------------------------------------------------------------------------------------------------
    
    function buscar($busqueda, $per_page = NULL, $offset = NULL)
    {

        //Construir búsqueda
        //Crear array con términos de búsqueda
            if ( strlen($busqueda['q']) > 2 ){
                $palabras = $this->Busqueda_model->palabras($busqueda['q']);

                foreach ($palabras as $palabra) {
                    $this->db->like('CONCAT(IFNULL(nombre_lugar, ""))', $palabra);
                }
            }
        
        //Especificaciones de consulta
            $this->db->select('*, CONCAT((nombre_lugar), ", ", (region)) AS name');
            $this->db->order_by('nombre_lugar', 'ASC');
            
        //Otros filtros
            if ( $busqueda['tp'] != '' ) { $this->db->where('tipo_id', $busqueda['tp']); }  //Tipo de lugar
            if ( $busqueda['condicion'] != '' ) { $this->db->where($busqueda['condicion']); }  //Condición especial
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('lugar'); //Resultados totales
        } else {
            $query = $this->db->get('lugar', $per_page, $offset); //Resultados por página
        }
        
        return $query;
        
    }
    
    function crud_basico()
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('lugar');
        $crud->set_subject('lugar');
        $crud->unset_print();
        $crud->unset_read();
        $crud->unset_delete();
        $crud->unset_back_to_list();

        //Títulos de los campos
            $crud->display_as('nombre_lugar', 'Nombre');
            $crud->display_as('palabras_clave', 'Nombres similares');
            
            
        //Campos
            $crud->add_fields(
                'nombre_lugar',
                'palabras_clave',
                'activo'
            );
            
            $crud->edit_fields(
                'nombre_lugar',
                'palabras_clave',
                'activo'
            );

        //Reglas de validación
            $crud->required_fields('nombre_lugar');
            
        //Formato
            $crud->field_type('activo', 'dropdown', array(0 => 'No', 1 => 'Sí'));
            
        //Procesos
            $crud->callback_after_insert(array($this, 'after_save_lugar'));
            $crud->callback_after_update(array($this, 'after_save_lugar'));
            
        
        $output = $crud->render();
        
        return $output;
    }
    
    function eliminar($lugar_id)
    {
        //Tabla principal
            $this->db->where('id', $lugar_id);
            $this->db->delete('lugar');
    }
    
    function after_save_lugar($post_array, $primary_key)
    {
        $this->act_campos_calculados($primary_key);
        $this->act_nombres_dependientes($primary_key);
    }
    
    function arr_tipo_lugar()
    {
        $arr_tipo_lugar = array(
            1 => 'Continente',
            2 => 'País',
            3 => 'Departamento/Estado',
            4 => 'Ciudad',
        );
        
        return $arr_tipo_lugar;
    }
    
    function opciones_tipo_lugar($texto_vacio = NULL)
    {
        $opciones = array();
        
        if ( ! is_null($texto_vacio) ) {
            $opciones[''] = "[ {$texto_vacio} ]";
        }
        
        $opciones['01'] = 'Continente';
        $opciones['02'] = 'País';
        $opciones['03'] = 'Departamento/Estado';
        $opciones['04'] = 'Ciudad';
        
        return $opciones;
    }
    
    /**
     * Sub-lugares que contiene un lugar
     * @param type $lugar_id
     * @return type
     */
    function condicion_sublugares($lugar_id)
    {
        $row_lugar = $this->Pcrn->registro_id('lugar', $lugar_id);
        
        $campos_ref = array(
            1 => 'continente_id',
            2 => 'pais_id',
            3 => 'region_id',
            4 => 'region_id'    //Experimental
        );
        
        $tipo_id = $row_lugar->tipo_id + 1;   //Aumentar nivel para filtrar sublugares
        
        $condicion = "{$campos_ref[$row_lugar->tipo_id]} = {$lugar_id} AND tipo_id = {$tipo_id}";
        
        return $condicion;
    }
    
    function guardar($lugar_id)
    {
        $row_lugar = $this->Pcrn->registro_id('lugar', $lugar_id);
        
        //Construir registro
            $registro['nombre_lugar'] = $this->input->post('nombre_lugar');
            $registro['palabras_clave'] = $this->input->post('palabras_clave');
            $registro['slug'] = $this->Pcrn->slug_unico($this->input->post('nombre_lugar'), 'lugar');
            $registro['tipo_id'] = $row_lugar->tipo_id + 1;
            $registro['continente_id'] = $row_lugar->continente_id;
            $registro['pais_id'] = $row_lugar->pais_id;
            $registro['region_id'] = $row_lugar->region_id;
            $registro['ciudad_id'] = $row_lugar->ciudad_id;
            $registro['pais'] = $row_lugar->pais;
            $registro['region'] = $row_lugar->region;
            
        //Condición
            $condicion = "nombre_lugar = '{$registro['nombre_lugar']}' AND tipo_id = {$registro['tipo_id']}";
            
        //Guardar
            $nuevo_lugar_id = $this->Pcrn->insertar_si('lugar', $condicion, $registro);
            
        //Actualizar campos adicionales
            $this->act_campo_id($nuevo_lugar_id);
            $this->act_campos_calculados($nuevo_lugar_id);
            
        return $nuevo_lugar_id;
        
    }
    
    /**
     * Actualiza un campo de la tabla, según el tipo de lugar
     * 
     * Si es ciudad actualiza cuidad_id = id
     * Si es pais pais_id = id
     * Si es region: region_id = id
     * 
     * @param type $lugar_id
     */
    function act_campo_id($lugar_id)
    {
        $row_lugar = $this->Pcrn->registro_id('lugar', $lugar_id);
        $nombre_campo = $this->campo_id($row_lugar->tipo_id);
        
        $registro[$nombre_campo] = $row_lugar->id;
        
        $this->db->where('id', $row_lugar->id);
        $this->db->update('lugar', $registro);
    }
    
    /**
     * Actualiza los campos dependientes
     * pais y region, a partir de pais_id y region_id
     * 
     * @param type $lugar_id
     */
    function act_campos_calculados($lugar_id)
    {
        $row_lugar = $this->Pcrn->registro_id('lugar', $lugar_id);
        
        $registro['slug'] = $this->Pcrn->slug_unico($row_lugar->nombre_lugar, 'lugar');
        $registro['pais'] = $this->Pcrn->campo_id('lugar', $row_lugar->pais_id, 'nombre_lugar');
        $registro['region'] = $this->Pcrn->campo_id('lugar', $row_lugar->region_id, 'nombre_lugar');
        
        $this->db->where('id', $lugar_id);
        $this->db->update('lugar', $registro);
    }
    
    function act_nombres_dependientes($lugar_id)
    {
        $row_lugar = $this->Pcrn->registro_id('lugar', $lugar_id);
        
        $tipos_dependientes = array(2,3);
        
        if ( in_array($row_lugar->tipo_id, $tipos_dependientes) ) 
        {
            $nombre_campo = $this->campo_nombre_dependiente($row_lugar->tipo_id);
            $campo_id = $this->campo_id($row_lugar->tipo_id);
            
            $registro[$nombre_campo] = $this->Pcrn->campo_id('lugar', $lugar_id, 'nombre_lugar');
            
            
            $this->db->where($campo_id, $lugar_id);
            $this->db->update('lugar', $registro);
        }
    }
    
    function campo_id($tipo_id)
    {
        $campos_ref = array(
            1 => 'continente_id',
            2 => 'pais_id',
            3 => 'region_id',
            4 => 'ciudad_id'    //Experimental
        );
        
        return $campos_ref[$tipo_id];
    }
    
    function campo_nombre_dependiente($tipo_id)
    {
        $campos_ref = array(
            2 => 'pais',
            3 => 'region',
        );
        
        return $campos_ref[$tipo_id];
    }
    
    function sublugares($lugar_id)
    {
        $this->load->model('Busqueda_model');
        
        $busqueda = $this->Busqueda_model->busqueda_array();
        $busqueda['condicion'] = $this->condicion_sublugares($lugar_id);
            
        $resultados = $this->buscar($busqueda);
        
        return $resultados;
    }
    
    function titulo_sublugares($tipo_id)
    {
        $titulos = array(
            1 => 'Países',
            2 => 'Dtos/Estados',
            3 => 'Ciudades',
            4 => 'Sector'
        );
        
        return $titulos[$tipo_id];
    }
}