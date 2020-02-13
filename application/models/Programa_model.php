<?php
class Programa_Model extends CI_Model{
    
    function basico($programa_id)
    {
        
        //temas
            $this->db->where('programa_id', $programa_id);
            $temas = $this->db->get('programa_tema');
        
        $row_programa = $this->Pcrn->registro_id('programa', $programa_id);
        
        //Datos adicionales
        $row_programa->cant_temas = $temas->num_rows();
        
        $basico['temas'] = $temas;
        $basico['programa_id'] = $programa_id;
        $basico['row'] = $row_programa;
        $basico['titulo_pagina'] = $row_programa->nombre_programa;
        $basico['vista_a'] = 'programas/programa_v';
        
        return $basico;
    }
    
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
            $controller = 'programas';
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
            $crud->required_fields('nombre_programa', 'area_id', 'nivel');
        
        //Formato
            $crud->unset_texteditor('descripcion');
        
        $output = $crud->render();
        
        return $output;
        
    }
    
    function crud_editar_temas($programa_id, $filtros)
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('programa');
        $crud->set_subject('programa');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_add();
        $crud->unset_back_to_list();
        $crud->unset_delete();
        $crud->unset_read();
        $crud->columns('nombre_programa', 'area_id', 'nivel', 'taller_id','descripcion');
        
        //Filtro
            $crud->where('programa.id', 0);

        //Callback, vista
            $crud->callback_column('nombre_programa',array($this,'gc_link_programa'));
        
        //Títulos de los campos
            $crud->display_as('area_id', 'Área');
            $crud->display_as('descripcion', 'Descripción');
            $crud->display_as('taller_id', 'Taller asociado');
            $crud->display_as('institucion_id', 'Institución');
            
        //Filtro para opciones de temas
            $row_programa = $this->Pcrn->registro_id('programa', $programa_id);
            $condicion = "area_id = {$row_programa->area_id} AND ";
            $condicion .= "nivel = {$filtros['nivel']} AND ";
            $condicion .= "tipo_id = {$filtros['tipo_id']}";
        
        //Relaciones
            $crud->set_relation('area_id', 'item', 'item', 'categoria_id = 1');
            $crud->set_relation('institucion_id', 'institucion', 'nombre_institucion');
            
            $crud->set_relation_n_n('temas', 'programa_tema', 'tema', 'programa_id', 'tema_id', 'nombre_tema', 'orden', $condicion);

        //Formulario Edit
            $crud->edit_fields('nombre_programa', 'temas');

        //Funciones
            $crud->callback_after_update(array($this, 'gc_after_update'));

        //Reglas de validación
            $crud->required_fields('nombre_programa', 'area_id', 'nivel');
            
        //Opciones nivel
            $opciones_nivel = $this->App_model->opciones_nivel('item_largo');
            $crud->field_type('nivel', 'dropdown', $opciones_nivel);
        
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
        return anchor("programas/temas/{$row->id}", $texto, $att);
    }
    
    function gc_after_insert($post_array,$primary_key)
    {
        redirect("programas/temas/{$primary_key}");
    }

// Búsquedas
//-----------------------------------------------------------------------------
    
    /**
     * Búsqueda de programas
     * 
     * @param type $busqueda
     * @param type $per_page
     * @param type $offset
     * @return type
     */
    function buscar($busqueda, $per_page = NULL, $offset = NULL)
    {

        //Construir búsqueda
        
            $filtro_rol = $this->filtro_rol();
        
            //Texto búsqueda
                //Crear array con términos de búsqueda
                if ( strlen($busqueda['q']) > 2 ){
                    $palabras = $this->Busqueda_model->palabras($busqueda['q']);

                    foreach ($palabras as $palabra_busqueda) {
                        $this->db->like('CONCAT(nombre_programa, descripcion, anio_generacion)', $palabra_busqueda);
                    }
                }
            
            //Otros filtros
                if ( $busqueda['a'] != '' ) { $this->db->where('area_id', $busqueda['a']); }    //Área
                if ( $busqueda['n'] != '' ) { $this->db->where('nivel', $busqueda['n']); }  //Nivel
                if ( $busqueda['i'] != '' ) { $this->db->where('institucion_id', $busqueda['i']); }  //Institución
                if ( $busqueda['e'] != '' ) { $this->db->where('editado', $busqueda['e']); }  //Editado
                
            //Otros
                $this->db->where($filtro_rol);
                $this->db->order_by('editado', 'DESC');
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('programa'); //Resultados totales
        } else {
            $query = $this->db->get('programa', $per_page, $offset); //Resultados por página
        }
        
        return $query;
    }
    
    function filtro_rol()
    {
        $filtro_rol = 'id > 0';
        
        if ( $this->session->userdata('srol') == 'institucional' )
        {
            //Usuarios asociados a la institución y creado por usuarios no internos
            $filtro_rol = "institucion_id = {$this->session->userdata('institucion_id')}";
            $filtro_rol .= " AND usuario_id IN (SELECT id FROM usuario WHERE rol_id > 2)";
        }
        
        return $filtro_rol;
    }
    
// DATOS
//---------------------------------------------------------------------------------------------------------
    
    function temas($programa_id)
    {
        $this->db->select('*');
        $this->db->where('programa_id', $programa_id);
        $this->db->join('programa_tema', 'tema.id = programa_tema.tema_id');
        $this->db->order_by('orden', 'ASC');
        $query = $this->db->get('tema');
        
        return $query;
    }
    
    /**
     * Páginas flipbook asociadas al programa a través del tema_id
     * 
     * @param type $programa_id
     * @return type query
     */
    function paginas($programa_id)
    {
        
        $this->db->select('pagina_flipbook.id');
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
    
// PROCESOS
//---------------------------------------------------------------------------------------------------------
    
    function eliminar($programa_id)
    {
        //De la tabla programa
            $this->db->where('id', $programa_id);
            $this->db->delete('programa');
            
        //Tablas relacionadas
            $this->db->where('programa_id', $programa_id);
            $this->db->delete('programa_tema');
    }

    /**
     * Enumerar los temas de un programa, campo tema.orden
     * 
     * @param type $programa_id
     */
    function enumerar_tema($programa_id)
    {
        
        $orden = 0;
        
        $this->db->where('programa_id', $programa_id);
        $this->db->where('en_programa', 1);
        $this->db->order_by('orden', 'ASC');
        $paginas = $this->db->get('tema');
        
        foreach ($paginas->result() as $row_tema){
            
            $registro['orden'] = $orden;
            $this->db->where('id', $row_tema->id);
            $this->db->update('tema', $registro);
            
            $orden += 1;
        }
    }
    
    /**
     * Cambia el valor del campo tema.orden para una página
     * Modifica los valores de ese campo para las páginas contiguas
     * cambiar_pos_pag: Cambiar posición de página
     * 
     * @param type $programa_id
     * @param type $tema_id
     * @param type $pos_final
     * @return type
     */
    function cambiar_pos_pag($programa_id, $tema_id, $pos_final)
    {
        //Fila de la página que se va a mover
            $row_pagina = $this->Pcrn->registro_id('tema', $tema_id);
            
        //Condición que selecciona el conjunto de registros a modificar
            $condicion_1 = "programa_id = {$programa_id} AND en_programa = 1";    
        
        //Variables proceso
            $pos_inicial = $row_pagina->orden;  //Posición actual del objeto
            $cant_registros = $this->Pcrn->num_registros('tema', $condicion_1);
            
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
                $sql = 'UPDATE tema';
                $sql .= " SET {$operacion}";
                $sql .= " WHERE {$condicion_1}";
                $sql .= " AND {$condicion_2}";

                $this->db->query($sql);
        
            //Cambiar la posición a la página específica
                $registro['orden'] = $pos_final;
                $this->db->where('id', $tema_id);
                $this->db->update('tema', $registro);
        }
        
        return $sql;
        
    }
    
    /**
     * Genera multiples flipbooks desde múltiples programas
     * 2020-02-13, se agregó  los tipos de contenidos permitidos
     */
    function generar_flipbooks_multi($array_hoja)
    {
        $no_importados = array();
        $fila = 2;  //Inicia en la fila 2 de la hoja de cálculo
        $arr_tipos = array(0,1,3,4,5);  //Tipos de flipbook existentes

        //Valores comunes
            $registro['creado'] = date('Y-m-d H:i:s');
            $registro['editado'] = date('Y-m-d H:i:s');
            $registro['creador_id'] = $this->session->userdata('usuario_id');
            $registro['editor_id'] = $this->session->userdata('usuario_id');
        
        foreach ( $array_hoja as $array_fila )
        {
            //Identificar programa
                $row_programa = $this->Pcrn->registro_id('programa', $array_fila[0]);
                //echo $array_fila[0] . '::' . $row_programa->nombre_programa . '<br>';
                
            //Identificar el flipbook
                $flipbook_id = 0;
                if ( strlen($array_fila[1]) > 0 ) {
                    $flipbook_id = $this->Pcrn->existe('flipbook', "id = $array_fila[1]");
                }
            
            //Complementar registro
            if ( ! is_null($row_programa) )
            {
                $registro['nombre_flipbook'] = $row_programa->nombre_programa;
                $registro['nivel'] = $row_programa->nivel;
                $registro['area_id'] = $row_programa->area_id;
                $registro['tipo_flipbook_id'] = $array_fila[2];
                $registro['anio_generacion'] = $row_programa->anio_generacion;
                $registro['descripcion'] = $row_programa->descripcion;
                $registro['programa_id'] = $row_programa->id;
            }
                
            //Validar
                $condiciones = 0;
                if ( ! is_null($row_programa) ) { $condiciones++; }                 //Tiene programa identificado
                if ( in_array($array_fila[2], $arr_tipos) ) { $condiciones++; }     //Es alguna de las opciones posibles
                
            //Si cumple las condiciones
            if ( $condiciones == 2 )
            {   
                if ( $flipbook_id == 0 )
                {
                    //Crear nuevo flipbook
                    $this->generar_flipbook($row_programa->id, $registro);
                } else {
                    //Sobreescribir
                    $registro['descripcion'] = $row_programa->descripcion;
                    $this->sobreescribir_fb($row_programa->id, $flipbook_id, $registro);
                }
            } else {
                $no_importados[] = $fila;
            }
            
            $fila++;    //Para siguiente fila
        }
        
        return $no_importados;
    }
    
    /**
     * Crea un flipbook a partir de un programa de temas
     * @param type $programa_id
     * @param type $registro
     * @return type
     */
    function generar_flipbook($programa_id, $registro)
    {
        $this->db->insert('flipbook', $registro);
        $flipbook_id = $this->db->insert_id();
        
        //Asignar las páginas
        $this->asignar_paginas_fb($programa_id, $flipbook_id);
        
        return $flipbook_id;
    }
    
    /**
     * Recrea un flipbook existente a partir de un programa de temas
     * @param type $programa_id
     * @param type $flipbook_id
     * @param type $registro
     * @return type
     */
    function sobreescribir_fb($programa_id, $flipbook_id, $registro)
    {
        //Actualizar flipbook
            $registro['programa_id'] = $programa_id;
            $registro['editor_id'] = $this->session->userdata('usuario_id');
            $registro['editado'] = date('Y-m-d H:i:s');

            $this->db->where('id', $flipbook_id);
            $this->db->update('flipbook', $registro);
            
        //Asignar las páginas
            $this->asignar_paginas_fb($programa_id, $flipbook_id);
    }
    
    /**
     * Elimina las páginas
     * Asignar las páginas de los temas de un programa a un flipbook, tabla flipbook_contenido
     * 
     * 
     * @param type $programa_id
     * @param type $flipbook_id
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
            $registro_fc['num_pagina'] = $num_pagina;

            $this->Flipbook_model->insertar_flipbook_contenido($registro_fc);

            //Para siguiente página
            $num_pagina += 1;
        }
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
    
    /* Función que agrega un registro a la tabla programa_tema (pt)
     * 
     */
    function agregar_tema($registro)
    {
        
        $condicion = "programa_id = {$registro['programa_id']} AND tema_id = {$registro['tema_id']}";
        $existe = $this->Pcrn->existe('programa_tema', $condicion);
        
        $resultado = 0;
        
        if ( $existe == 0 )
        {
            //El registro no existe, se inserta
            
            //Establecer campo orden, si no está definido
                if ( is_null($registro['orden']) ) {
                    $condicion_orden = "programa_id = {$registro['programa_id']}";
                    $registro['orden'] = $this->Pcrn->num_registros('programa_tema', $condicion_orden);
                }
            
            //Cambiar el orden de los temas siguientes, con orden mayor
                $sql = "UPDATE programa_tema SET orden = orden + 1 WHERE programa_id = {$registro['programa_id']} AND orden >= {$registro['orden']}";
                $this->db->query($sql);
            
            //Insertando
                $this->db->insert('programa_tema', $registro);
                $resultado = 1;
                
            //Actualizar el campo programa.temas
                $this->act_campo_temas($registro['programa_id']);
        }
        
        return $resultado;
    }
    
    /**
     * Inserta masivamente temas a múltiples programas
     * tabla programa_tema
     * 
     * @param type $array_hoja    Array con los datos de los temas
     * @return type
     */
    function asignar_temas_multi($array_hoja)
    {       
        
        $no_importados = array();
        $fila = 2;  //Inicia en la fila 2 de la hoja de cálculo
        
        foreach ( $array_hoja as $array_fila )
        {
            
            //Complementar registro
                $registro['programa_id'] = $this->Pcrn->existe('programa', "id = {$array_fila[0]}");
                $registro['tema_id'] = $this->Pcrn->existe('tema', "cod_tema = '{$array_fila[1]}'");
                $registro['orden'] = NULL;  //El orden se calcula al guardar (agregar_tema)
                
            //Validar
                $condiciones = 0;
                if ( $registro['programa_id'] != 0 ) { $condiciones++; }    //Tiene programa identificado
                if ( $registro['tema_id'] != 0 ) { $condiciones++; }        //Tiene tema identificado
                
            //Si cumple las condiciones
            if ( $condiciones == 2 )
            {   
                $this->agregar_tema($registro);
            } else {
                $no_importados[] = $fila;
            }
            
            $fila++;    //Para siguiente fila
        }
        
        return $no_importados;
    }
    
    /**
     * Inserta masivamente programas
     * tabla programa
     * 
     * @param type $array_hoja    Array con los datos de los programas
     */
    function importar($array_hoja)
    {   
        $this->load->model('Esp');
        
        $no_importados = array();
        $fila = 2;  //Inicia en la fila 2 de la hoja de cálculo
        
        $areas = $this->Esp->arr_cod_area();
            
        //Predeterminados registro nuevo
            $registro['usuario_id'] = $this->session->userdata('usuario_id');
            $registro['creado'] = date('Y-m-d H:i:s');
            $registro['editado'] = date('Y-m-d H:i:s');
        
        foreach ( $array_hoja as $array_fila )
        {
            //Identificar valores
                $area_id = 0;
                if ( array_key_exists($array_fila[1], $areas) ) { $area_id = $areas[$array_fila[1]]; }
            
            //Complementar registro
                $registro['nombre_programa'] = $array_fila[0];
                $registro['area_id'] = $area_id;  //Columna B
                $registro['nivel'] = $array_fila[2];
                $registro['anio_generacion'] = $array_fila[3];
                $registro['institucion_id'] = $this->Pcrn->existe('institucion', "id = {$array_fila[4]}");
                
            //Validar
                $condiciones = 0;
                if ( strlen($array_fila[0]) > 0 ) { $condiciones++; }   //Debe tener nombre escrito
                if ( $area_id != 0 ) { $condiciones++; }   //Tiene área identificada
                if ( $registro['institucion_id'] != 0 ) { $condiciones++; }   //Tiene institución identificada
                
            //Si cumple las condiciones
            if ( $condiciones == 3 )
            {   
                $this->Pcrn->guardar('programa', "nombre_programa = '{$registro['nombre_programa']}'", $registro);
            } else {
                $no_importados[] = $fila;
            }
            
            $fila++;    //Para siguiente fila
        }
        
        return $no_importados;
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