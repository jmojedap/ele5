<?php
class Desarrollo_Model extends CI_Model{
    
    function basico($tarea_id)
    {
        $row = $this->Pcrn->registro_id('tarea', $tarea_id);
        
        $basico['row'] = $row;
        $basico['nombre_tarea'] = $this->Pcrn->si_strlen($row->nombre_tarea, 'Post ' . $row->id);
        $basico['titulo_pagina'] = $basico['nombre_tarea'];
        $basico['vista_a'] = $this->vista_a($row);
        
        return $basico;
    }
    
    
    
// EXPLORACIÓN
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     * 
     * @return string
     */
    function data_explorar()
    {
        //Elemento de exploración
            $data['cf'] = 'desarrollo/explorar/';                       //CF Controlador Función
            $data['controlador'] = 'desarrollo';                        //Nombre del controlador
            $data['carpeta_vistas'] = 'sistema/desarrollo/explorar/';   //Carpeta donde están las vistas de exploración
            $data['titulo_pagina'] = 'Desarrollo';

        //Vistas
            $data['menu_a'] = $data['carpeta_vistas'] . 'menu_v';
            $data['vista_a'] = $data['carpeta_vistas'] . 'explorar_v';
        
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
            $data['cf'] = 'tareas/explorar/';     //CF Controlador Función
        
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
     * Búsqueda de tareas
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
                
                $campos_tareas = array('nombre_tarea', 'contenido', 'resumen', 'editado', 'creado');
                
                $concat_campos = $this->Busqueda_model->concat_campos($campos_tareas);
                $palabras = $this->Busqueda_model->palabras($busqueda['q']);

                foreach ($palabras as $palabra) {
                    $this->db->like("CONCAT({$concat_campos})", $palabra);
                }
            }
        
        //Especificaciones de consulta
            $this->db->select('tarea.*');
            $this->db->order_by('editado', 'DESC');
            
        //Otros filtros
            if ( $busqueda['e'] != '' ) { $this->db->where('editado', $busqueda['e']); }                //Editado
            if ( $busqueda['tp'] != '' ) { $this->db->where('tipo_id', $busqueda['tp']); }              //Tipo de tarea
            if ( $busqueda['f1'] != '' ) { $this->db->where('referente_1_id', $busqueda['f1']); }       //Filtro 1
            if ( $busqueda['f2'] != '' ) { $this->db->where('referente_2_id', $busqueda['f2']); }       //Filtro 2
            if ( $busqueda['f3'] != '' ) { $this->db->where('referente_3_id', $busqueda['f3']); }       //Filtro 3
            if ( $busqueda['condicion'] != '' ) { $this->db->where($busqueda['condicion']); }           //Condición especial
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('tarea'); //Resultados totales
        } else {
            $query = $this->db->get('tarea', $per_page, $offset); //Resultados por página
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
            $registro = $this->input->tarea();
            $registro['editor_id'] = $this->session->userdata('usuario_id');
            $registro['editado'] = date('Y-m-d H:i:s');
            $registro['usuario_id'] = $this->session->userdata('usuario_id');
            //$registro['creado'] = date('Y-m-d H:i:s');

            $this->db->insert('tarea', $registro);
            $nuevo_id = $this->db->insert_id();
        
        //Si se creó el tarea, modificar array de resultado
            if ( $nuevo_id > 0 )
            {
                //$this->act_dependientes($tarea_id);
                
                $resultado['ejecutado'] = 1;
                $resultado['mensaje'] = 'El tarea fue creado correctamente.';
                $resultado['clase'] = 'alert-success';
                $resultado['icono'] = 'fa-check';
                $resultado['nuevo_id'] = $nuevo_id;
            }
            
        return $resultado;
    }
    
    function guardar($condicion, $registro)
    {
        $tarea_id = $this->Pcrn->existe('tarea', $condicion);
        
        $registro['editor_id'] = $this->session->userdata('usuario_id');
        $registro['editado'] = $this->session->userdata('usuario_id');
        
        if ( $tarea_id == 0 ) {
            //No existe, insertar
            
            $registro['usuario_id'] = $this->session->userdata('usuario_id');
            $registro['creado'] = date('Y-m-d H:i:s');
            
            $this->db->insert('tarea', $registro);
            $tarea_id = $this->db->insert_id();
        } else {
            //Ya existe, editar
            $this->db->where('id', $tarea_id);
            $this->db->update('tarea', $registro);
        }
        
        return $tarea_id;
    }
    
    function editable($tarea_id)
    {
        $editable = 1;
        return $editable;
    }
    
    function metadatos($tarea_id, $dato_id = NULL)
    {
        $this->db->select('*');
        $this->db->where('relacionado_id', $tarea_id);
        $this->db->where('dato_id', $dato_id);
        $this->db->order_by('dato_id', 'ASC');
        $this->db->order_by('orden', 'ASC');
        $query = $this->db->get('meta');
        
        return $query;
    }
    
    function actualizar($tarea_id, $registro = NULL)
    {
        if ( is_null($registro) ) { $registro = $this->input->tarea(); }
        
        $registro['editor_id'] = $this->session->userdata('usuario_id');
        $registro['editado'] = date('Y-m-d H:i:s');
        
        $this->db->where('id', $tarea_id);
        $this->db->update('tarea', $registro);
        
        $resultado['ejecutado'] = 1;
        $resultado['mensaje'] = 'Los datos fueron actualizados exitosamente';
        $resultado['type'] = 'success';
        $resultado['icono'] = 'fa-check';
        
        return $resultado;
    }
    
    function eliminar($tarea_id)
    {
        if ( $this->eliminable($tarea_id) ) 
        {
            //Tablas relacionadas, tarea
                $this->db->where('tabla_id', 2000); //Tabla tarea
                $this->db->where('elemento_id', $tarea_id);
                $this->db->delete('meta');
                
            //Tablas relacionadas, tarea, listas
                $this->db->where('relacionado_id', $tarea_id);
                $this->db->where('dato_id', 22);
                $this->db->delete('meta');
            
            //Tabla principal
                $this->db->where('id', $tarea_id);
                $this->db->delete('tarea');
        }
    }
    
    
    

    
}