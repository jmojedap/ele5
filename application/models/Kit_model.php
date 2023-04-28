<?php
class Kit_Model extends CI_Model{
    
    function basico($kit_id)
    {
        
        $row_kit = $this->row_plus($kit_id);
        
        $basico['kit_id'] = $kit_id;
        $basico['row'] = $row_kit;
        $basico['head_title'] = substr($row_kit->nombre_kit,0,50);
        $basico['view_description'] = 'kits/kit_v';
        
        return $basico;
    }
    
    function row_plus($kit_id)
    {
        $row = $this->Pcrn->registro_id('kit', $kit_id);
        
        $flipbooks = $this->flipbooks($kit_id);
        $cuestionarios = $this->cuestionarios($kit_id);
        $instituciones = $this->instituciones($kit_id);
        
        //Datos adicionales
        $row->cant_flipbooks = $flipbooks->num_rows();
        $row->cant_cuestionarios = $cuestionarios->num_rows();
        $row->cant_instituciones = $instituciones->num_rows();
        
        return $row;
    }
    
    function crud_basico()
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('kit');
        $crud->set_subject('kit');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_read();
        $crud->unset_back_to_list();

        //Permisos de edición
            if ( $this->session->userdata('rol_id') > 2 )
            {
                $crud->unset_add();
                $crud->unset_delete();
                $crud->unset_edit();
            }
        
        //Títulos de los campos
            $crud->display_as('descripcion', 'Descripción');

        //Campos
            $crud->edit_fields('nombre_kit', 'descripcion', 'editado');
            $crud->add_fields('nombre_kit','descripcion', 'usuario_id', 'editado');

        //Reglas de validación
            $crud->required_fields('nombre_kit');
            
        //Valores por defecto
            $crud->change_field_type('usuario_id', 'hidden', $this->session->userdata('usuario_id'));
            $crud->change_field_type('editado', 'hidden', date('Y-m-d H:i:s'));
        
        //Formato
            $crud->unset_texteditor('descripcion');
            
            //$crud->callback_after_insert(array($this, 'gc_after_insert'));
        
        $output = $crud->render();
        
        return $output;
        
    }   
    
    function gc_after_insert($post_array,$primary_key)
    {
        redirect("kits/flipbooks/{$primary_key}");
    }
    
    function editable()
    {
        $editable = TRUE;
        if ( $this->session->userdata('usuario_id') > 2 ) { $editable = FALSE; }
        
        return $editable;
    }
    
    function eliminar($kit_id)
    {
        //Tablas relacionadas
            $this->db->where('kit_id', $kit_id);
            $this->db->delete('kit_elemento');
        
        //Tabla
            $this->db->where('id', $kit_id);
            $this->db->delete('kit');
    }
    
    /**
     * Búsqueda de kits
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

                    foreach ($palabras as $palabra_busqueda) {
                        $this->db->like('CONCAT(nombre_kit, IFNULL( (descripcion), "") )', $palabra_busqueda);
                    }
                }
            
            //Otros filtros
                if ( $busqueda['e'] != '' ) { $this->db->where('editado', $busqueda['e']); }  //Editado
                
            //Otros
                $this->db->order_by('editado', 'DESC');
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('kit'); //Resultados totales
        } else {
            $query = $this->db->get('kit', $per_page, $offset); //Resultados por página
        }
        
        return $query;
    }

// DATOS
//---------------------------------------------------------------------------------------------------------
    
    function flipbooks($kit_id)
    {
        $this->db->select('kit_elemento.id AS asignacion_id, flipbook.*');
        $this->db->join('flipbook', 'kit_elemento.elemento_id = flipbook.id');
        $this->db->where('kit_id', $kit_id);
        $this->db->where('tipo_elemento_id', 1);  //1 => Flipbook, ver tabla item.categoria_id = 10
        $this->db->order_by('area_id', 'ASC');
        $this->db->order_by('nivel', 'ASC');

        $flipbooks = $this->db->get('kit_elemento');
        
        return $flipbooks;
    }
    
    function cuestionarios($kit_id)
    {
        $this->db->select('kit_elemento.id AS asignacion_id, cuestionario.*');
        $this->db->join('cuestionario', 'kit_elemento.elemento_id = cuestionario.id');
        $this->db->where('kit_id', $kit_id);
        $this->db->where('tipo_elemento_id', 2);  //2 => Cuestionario, ver tabla item.categoria_id = 10
        $flipbooks = $this->db->get('kit_elemento');
        
        return $flipbooks;
    }
    
    /**
     * Instituciones a los que se les tiene asignado el Kit
     * 
     * @param type $kit_id
     * @return type
     */
    function instituciones($kit_id)
    {
        $this->db->select('kit_elemento.id AS asignacion_id, kit_elemento.editado, institucion.id, nombre_institucion');
        $this->db->join('institucion', 'kit_elemento.elemento_id = institucion.id');
        $this->db->where('kit_id', $kit_id);
        $this->db->where('tipo_elemento_id', 0);  //0 => Institución, ver tabla item.categoria_id = 10
        $this->db->order_by('nombre_institucion', 'ASC');

        $instituciones = $this->db->get('kit_elemento');
        
        return $instituciones;
    }
    
// PROCESOS
//---------------------------------------------------------------------------------------------------------
    
    function actualizar_editado($asignacion_id)
    {
        $row_asignacion = $this->Pcrn->registro_id('kit_elemento', $asignacion_id);
        
        $registro['editado'] = date('Y-m-d H:i:s');
        $registro['usuario_id'] = $this->session->userdata('usuario_id');
        
        $this->db->where('id', $row_asignacion->kit_id);
        $this->db->update('kit', $registro);
    }
    
    function asignar_flipbooks($asignacion_id)
    {
        $qty_flipbooks_asignados = 0;

        $row_asignacion = $this->Pcrn->registro_id('kit_elemento', $asignacion_id);
        
        $sql = 'SELECT kit_elemento.id AS ke_id, flipbook.id AS flipbook_id, usuario_grupo.usuario_id ';
        $sql .= 'FROM flipbook, kit_elemento, grupo, usuario_grupo ';
        $sql .= 'WHERE ';
        $sql .= "kit_elemento.kit_id = {$row_asignacion->kit_id} AND ";         //Filtrar elementos del kit
        $sql .= 'kit_elemento.tipo_elemento_id = 1 ';                           //Es una asignación de flipbook
        $sql .= 'AND flipbook.id = kit_elemento.elemento_id ';                  //Que coincida el flipbook con el elemento
        $sql .= "AND grupo.institucion_id = {$row_asignacion->elemento_id} ";   //Filtro por institución
        $sql .= 'AND grupo.nivel = flipbook.nivel ';            //Que el nivel del flipbook coincida con el nivel del grupo
        $sql .= 'AND grupo.id = usuario_grupo.grupo_id ';       //Identificar grupo con la lista de usuarios de los grupos
        $sql .= 'ORDER BY usuario_grupo.usuario_id ASC; ';
        
        $query = $this->db->query($sql);
        
        //Crear registros
        foreach ( $query->result() as $row )
        {
            $registro['usuario_id'] = $row->usuario_id;
            $registro['flipbook_id'] = $row->flipbook_id;
            $registro['ke_id'] = $row->ke_id;   //ke_id (kit_elemento.id)
            
            $condicion = "usuario_id = {$registro['usuario_id']} AND flipbook_id = {$registro['flipbook_id']}";
            $uf_id = $this->Pcrn->guardar('usuario_flipbook', $condicion, $registro);
            if ( $uf_id > 0 ) $qty_flipbooks_asignados++;
        }
        
        return $qty_flipbooks_asignados;
    }

    
    /**
     * Asignar cuestionarios de un kit a los estudianes de una instutición a la que está asociada
     * dicho kit
     * 2023-03-02
     */
    function asignar_cuestionarios($asignacion_id)
    {
        $qty_cuestionarios_asignados = 0;
        $row_asignacion = $this->Pcrn->registro_id('kit_elemento', $asignacion_id);
        
        $sql = 'SELECT kit_elemento.id AS ke_id, cuestionario.id AS cuestionario_id, usuario_grupo.usuario_id, usuario_grupo.grupo_id, grupo.institucion_id, tiempo_minutos ';
        $sql .= 'FROM cuestionario, kit_elemento, grupo, usuario_grupo ';
        $sql .= 'WHERE ';
        $sql .= "kit_elemento.kit_id = {$row_asignacion->kit_id} AND ";         //Filtrar elementos del kit
        $sql .= 'kit_elemento.tipo_elemento_id = 2 ';                           //Es una asignación de cuestionario
        $sql .= 'AND cuestionario.id = kit_elemento.elemento_id ';              //Que coincida el cuestionario con el elemento
        $sql .= "AND grupo.institucion_id = {$row_asignacion->elemento_id} ";   //Filtro por institución
        $sql .= 'AND grupo.nivel = cuestionario.nivel ';            //Que el nivel del cuestionario coincida con el nivel del grupo
        $sql .= 'AND grupo.id = usuario_grupo.grupo_id ';           //Identificar grupo con la lista de usuarios de los grupos
        $sql .= 'ORDER BY usuario_grupo.usuario_id ASC; ';
        
        $query = $this->db->query($sql);
        
        //Valores comunes registro usuario_cuestionario
            $registro['fecha_inicio'] = date('Y-m-d H:i:s');
            $registro['fecha_fin'] = $this->Pcrn->suma_fecha(date('Y-m-d 23:59:59'), '+1 month');
            $registro['creado'] = date('Y-m-d H:i:s');
            $registro['editado'] = date('Y-m-d H:i:s');
            $registro['editado_usuario_id'] = $this->session->userdata('usuario_id');
            $registro['creado_usuario_id'] = $this->session->userdata('usuario_id');
            
        
        //Crear registros
        foreach ($query->result() as $row)
        {
            $registro['usuario_id'] = $row->usuario_id;
            $registro['cuestionario_id'] = $row->cuestionario_id;
            $registro['grupo_id'] = $row->grupo_id;
            $registro['institucion_id'] = $row->institucion_id;
            $registro['tiempo_minutos'] = $row->tiempo_minutos;
            $registro['ke_id'] = $row->ke_id;   //ke_id (kit_elemento.id)
            
            $condicion = "usuario_id = {$registro['usuario_id']} AND cuestionario_id = {$registro['cuestionario_id']}";
            $uc_id = $this->Pcrn->guardar('usuario_cuestionario', $condicion, $registro);
            if ( $uc_id > 0 ) $qty_cuestionarios_asignados++;
        }
        
        return $qty_cuestionarios_asignados;
    }
    
    /**
     * Actualiza la fecha de la asignación
     */
    function actualizar_asignacion($asignacion_id)
    {
        $registro['editado'] = date('Y-m-d H:i:s');
        
        $this->db->where('id', $asignacion_id);
        $this->db->update('kit_elemento', $registro);
    }
    
    /* Función que agrega un registro a la tabla kit_elemento
     * 
     */
    function agregar_elemento($registro)
    {
        $condicion = "kit_id = {$registro['kit_id']} AND ";
        $condicion .= "elemento_id = {$registro['elemento_id']} AND ";
        $condicion .= "tipo_elemento_id = {$registro['tipo_elemento_id']}";
        
        $asignacion_id = $this->Pcrn->guardar('kit_elemento', $condicion, $registro);
        
        //Actualizar kit
        $this->actualizar_editado($asignacion_id);
        
        
        return $asignacion_id;
        
    }
    
    /* Función que elimina un registro de la tabla kit_elemento
     * 
     */
    function quitar_elemento($asignacion_id)
    {
        //Actualizar kit.editado, antes de eliminar
        $this->actualizar_editado($asignacion_id);
        
        $this->db->where('id', $asignacion_id);
        $this->db->delete('kit_elemento');
        
    }
    
    /* Función que agrega un registro a la tabla kit_elemento
     * 
     */
    function agregar_institucion($registro)
    {
        $condicion = "kit_id = {$registro['kit_id']} AND ";
        $condicion .= "elemento_id = {$registro['elemento_id']} AND ";
        $condicion .= "tipo_elemento_id = 0";
        
        $asignacion_id = $this->Pcrn->guardar('kit_elemento', $condicion, $registro);
        
        
        return $asignacion_id;
        
    }
    
    /* Elimina un registro de la tabla kit_elemento correspondiente de la asignación
     * de una institución a un kit. Elimina también las asignaciones que fueron 
     * hechas a usuarios de la instutución a partir de los elementos del kit.
     * 
     */
    function quitar_institucion($asignacion_id)
    {
        $qty_deleted = 0;

        $row_ke = $this->Pcrn->registro_id('kit_elemento', $asignacion_id);
        
        //Eliminar asignaciones de contenidos
            $this->db->where("ke_id IN (SELECT id FROM kit_elemento WHERE kit_id = {$row_ke->kit_id})");
            $this->db->where("usuario_id IN (SELECT id FROM usuario WHERE institucion_id = {$row_ke->elemento_id})");
            $this->db->delete('usuario_flipbook');

        $qty_deleted += $this->db->affected_rows();
        
        //Eliminar asignaciones de cuestionarios
            $this->db->where("ke_id IN (SELECT id FROM kit_elemento WHERE kit_id = {$row_ke->kit_id})");
            $this->db->where("usuario_id IN (SELECT id FROM usuario WHERE institucion_id = {$row_ke->elemento_id})");
            $this->db->delete('usuario_cuestionario');

        $qty_deleted += $this->db->affected_rows();
        
        //Eliminar asignación de institución de la tabla elemento_kit
            $this->db->where('id', $asignacion_id);
            $this->db->delete('kit_elemento');

        return $qty_deleted;
    }
    
    /**
     * Elimina las asignaciones de cuestionarios y flipbooks que fueron creadas
     * por la asignación del kit pero ya no están incluidos en este. Las asignaciones
     * para eliminar se reconocen por el campo ke_id (kit_elemento.id). Si el dato
     * tabla.ke_id en no existe en la tabla kit_elemento, se procede a eliminar.
     * 
     * @param int $asignacion_id
     */
    function depurar($asignacion_id)
    {
        $row_asignacion = $this->Pcrn->registro_id('kit_elemento', $asignacion_id);
        $institucion_id = $row_asignacion->elemento_id;
        
        //Asignación de flipbooks
            $sql_flipbooks = 'DELETE FROM usuario_flipbook ';
            $sql_flipbooks .= 'WHERE ';
            $sql_flipbooks .= "ke_id NOT IN (SELECT id FROM kit_elemento WHERE kit_id = {$row_asignacion->kit_id}) AND ";
            $sql_flipbooks .= "usuario_flipbook.usuario_id IN (SELECT id FROM usuario WHERE institucion_id = {$institucion_id})";

            $this->db->query($sql_flipbooks);

            $data['qty_deleted_asignaciones_flipbooks'] = $this->db->affected_rows();
        
        //Asignación de cuestionarios
            $sql_cuestionarios = 'DELETE FROM usuario_cuestionario ';
            $sql_cuestionarios .= 'WHERE ';
            $sql_cuestionarios .= "ke_id NOT IN (SELECT id FROM kit_elemento WHERE kit_id = {$row_asignacion->kit_id}) AND ";
            $sql_cuestionarios .= "usuario_cuestionario.institucion_id = {$institucion_id}";

            $this->db->query($sql_cuestionarios);

            $data['qty_deleted_asignaciones_cuestionario'] = $this->db->affected_rows();

        return $data;
    }
    
    function importar_elementos($kit_id, $array_hoja)
    {
        $no_importados = array();
        $fila = 2;  //Inicia en la fila 2 de la hoja de cálculo
        
        $tablas = array(
            'institucion',
            'flipbook',
            'cuestionario'
        );
            
        //Predeterminados registro nuevo
            $registro['kit_id'] = $kit_id;
            $registro['editado'] = date('Y-m-d H:i:s');
        
        foreach ( $array_hoja as $array_fila )
        {
            
            $tipo_elemento_id = $this->Pcrn->si_strlen($array_fila[1], 0);
            $tabla = $tablas[$tipo_elemento_id];
            $elemento_id = $this->Pcrn->campo_id($tabla, $array_fila[0], 'id');
            
            if ( ! is_null($elemento_id) )
            {
                
                //Registro
                $registro['elemento_id'] = $elemento_id;
                $registro['tipo_elemento_id'] = $tipo_elemento_id;
                
                if ( $tipo_elemento_id == 0 ){
                    //Institución
                    $asignacion_id = $this->agregar_institucion($registro);
                } else {
                    //Flipbooks y Cuestionarios
                    $asignacion_id = $this->agregar_elemento($registro);
                }
                
            } else {
                $no_importados[] = $fila;
            }
            
            $fila++;    //Para siguiente fila
        }
        
        return $no_importados;
    }

    /**
     * Opciones de kits para dropdowns
     * 2020-07-13
     */
    function options($condition = 'id > 0')
    {
        $this->db->select("CONCAT('0', id) AS kit_id, nombre_kit AS full_name");
        $this->db->where($condition);
        $this->db->order_by('nombre_kit', 'ASC');
        $kits = $this->db->get('kit', 500);

        $options = array('' => ' [Seleccione el Kit] ');
        $options = array_merge($options, $this->pml->query_to_array($kits, 'full_name', 'kit_id'));

        return $options;
    }
    
    
}